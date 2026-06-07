<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\CustomerTier;
use App\Models\CustomerTierAssignment;
use App\Models\Broadcast;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Akses ditolak.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $totalUsers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalOrders = Transaction::where('status', '!=', 'cancelled')->count();

        $todayRevenue = Transaction::whereDate('created_at', today())
            ->whereIn('status', ['processing', 'shipped', 'delivered', 'done'])
            ->sum('total_amount');

        $pendingOrders = Transaction::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('current_stock', '<=', 5)
            ->where('current_stock', '>', 0)
            ->get();

        $outOfStockProducts = Product::where('current_stock', 0)->get();

        $newCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', now()->subWeek())
            ->latest()
            ->take(5)
            ->get();

        // Data untuk chart
        $monthlyRevenue = Transaction::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                SUM(total_amount) as total
            ')
            ->whereIn('status', ['processing', 'shipped', 'delivered', 'done'])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        $salesByCategory = Transaction::join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(transaction_details.qty * transaction_details.price) as total')
            ->whereIn('transactions.status', ['processing', 'shipped', 'delivered'])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $monthlyRevenueLabels = $monthlyRevenue->map(function($r) {
            return date('M', mktime(0, 0, 0, $r->month, 1));
        })->toArray();
        $monthlyRevenueData = $monthlyRevenue->map(function($r) {
            return $r->total;
        })->toArray();
        
        $salesByCategoryLabels = $salesByCategory->map(function($c) {
            return $c->name;
        })->toArray();
        $salesByCategoryData = $salesByCategory->map(function($c) {
            return $c->total;
        })->toArray();

        $bestSellers = TransactionDetail::join('products', 'transaction_details.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, SUM(transaction_details.qty) as total_sold')
            ->whereHas('transaction', function($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

$topCustomers = Transaction::selectRaw('user_id, SUM(total_amount) as total_spent, COUNT(*) as order_count')
            ->whereIn('status', ['processing', 'shipped', 'delivered', 'done'])
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->with('user:id,name,email')
            ->get();

        $reorderAlerts = Product::where('current_stock', '<=', 3)
            ->where('current_stock', '>', 0)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'todayRevenue',
            'pendingOrders',
            'lowStockProducts',
            'outOfStockProducts',
            'newCustomers',
            'monthlyRevenue',
            'salesByCategory',
            'monthlyRevenueLabels',
            'monthlyRevenueData',
            'salesByCategoryLabels',
            'salesByCategoryData',
            'bestSellers',
            'topCustomers',
            'reorderAlerts'
        ));
    }

    public function products()
    {
        $products = Product::with(['category', 'prices', 'images'])->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function getProduct($id)
    {
        $product = Product::with(['category', 'prices', 'productPriceTiers', 'images'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'item_code' => $product->item_code,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'brand' => $product->brand,
                'unit' => $product->unit,
                'base_price' => $product->base_price,
                'current_stock' => $product->current_stock,
                'prices' => $product->prices->map(function($p) {
                    return ['price_level' => $p->price_level, 'price' => $p->price];
                }),
            ],
        ]);
    }

    public function storeProduct(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'item_code' => 'nullable|string|unique:products,item_code',
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'unit' => 'required|string|max:50',
                'base_price' => 'required|numeric|min:0',
                'current_stock' => 'required|integer|min:0',
                'prices' => 'nullable|array',
                'prices.*' => 'nullable|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_values(array_merge(...array_values($e->errors())))),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'item_type' => 'barang',
                'item_code' => $validated['item_code'] ?? 'PRD-' . strtoupper(Str::random(8)),
                'barcode' => $validated['item_code'] ?? 'PRD-' . strtoupper(Str::random(8)),
                'name' => $validated['name'],
                'brand' => $validated['brand'],
                'unit' => $validated['unit'],
                'base_price' => $validated['base_price'],
                'current_stock' => $validated['current_stock'],
            ]);

            // Simpan harga dengan level yang sesuai
            $prices = $validated['prices'] ?? [];
            foreach ($prices as $level => $price) {
                if ($price !== null && $price > 0) {
                    ProductPrice::create([
                        'product_id' => $product->id,
                        'price_level' => $level,
                        'price' => $price,
                    ]);
                }
            }

            // Simpan gambar
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // Broadcast notifikasi
            Broadcast::create([
                'user_id' => null,
                'title' => 'Produk Baru Ditambahkan',
                'message' => "Produk baru '{$product->name}' telah ditambahkan ke katalog.",
                'type' => 'system',
                'is_read' => false,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.',
                'product' => $product->load(['category', 'prices', 'images']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product creation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'item_code' => 'nullable|string|unique:products,item_code,' . $product->id,
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'unit' => 'required|string|max:50',
                'base_price' => 'required|numeric|min:0',
                'current_stock' => 'required|integer|min:0',
                'prices' => 'nullable|array',
                'prices.*' => 'nullable|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_values(array_merge(...array_values($e->errors())))),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product->update([
                'category_id' => $validated['category_id'],
                'item_code' => $validated['item_code'] ?? $product->item_code,
                'barcode' => $validated['item_code'] ?? $product->barcode,
                'name' => $validated['name'],
                'brand' => $validated['brand'],
                'unit' => $validated['unit'],
                'base_price' => $validated['base_price'],
                'current_stock' => $validated['current_stock'],
            ]);

            // Update harga dengan level yang sesuai
            $prices = $validated['prices'] ?? [];
            foreach ($prices as $level => $price) {
                if ($price !== null && $price > 0) {
                    ProductPrice::updateOrCreate(
                        ['product_id' => $product->id, 'price_level' => $level],
                        ['price' => $price]
                    );
                } else {
                    // Hapus harga jika kosong
                    ProductPrice::where('product_id', $product->id)
                        ->where('price_level', $level)
                        ->delete();
                }
            }

            // Simpan gambar baru (tidak menghapus yang lama)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
                'product' => $product->load(['category', 'prices', 'images']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'stock' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $previousStock = $product->current_stock;
        $product->increment('current_stock', $validated['stock']);
        $currentStock = $previousStock + $validated['stock'];

        Broadcast::create([
            'user_id' => null,
            'title' => 'Stok Ditambahkan',
            'message' => "Stok produk '{$product->name}' ditambah {$validated['stock']} pcs (dari {$previousStock} menjadi {$currentStock}).",
            'type' => 'system',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Stok berhasil ditambahkan.",
            'previous_stock' => $previousStock,
            'current_stock' => $currentStock,
        ]);
    }

    public function reduceStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'stock' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        if ($validated['stock'] > $product->current_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah stok yang dikurangi melebihi stok yang tersedia.',
            ], 400);
        }

        $previousStock = $product->current_stock;
        $product->decrement('current_stock', $validated['stock']);
        $currentStock = $previousStock - $validated['stock'];

        Broadcast::create([
            'user_id' => null,
            'title' => 'Stok Dikurangi',
            'message' => "Stok produk '{$product->name}' dikurangi {$validated['stock']} pcs (dari {$previousStock} menjadi {$currentStock}).",
            'type' => 'system',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Stok berhasil dikurangi.",
            'previous_stock' => $previousStock,
            'current_stock' => $currentStock,
        ]);
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->images()->exists() || $product->prices()->exists() || $product->productPriceTiers()->exists()) {
            $product->images()->delete();
            $product->prices()->delete();
            $product->productPriceTiers()->delete();
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus.',
        ]);
    }

    public function orders()
    {
        $orders = Transaction::with(['user', 'details.product'])->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Transaction::with(['user', 'details.product.images', 'details.product.prices'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Transaction::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,done,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        Broadcast::create([
            'user_id' => $order->user_id,
            'title' => 'Status Pesanan Diperbarui',
            'message' => "Status pesanan #{$order->invoice_number} diubah menjadi " . ucfirst($validated['status']) . ".",
            'type' => 'system',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
        ]);
    }

    public function users()
    {
        $users = User::where('role', 'customer')
            ->with(['tierAssignments' => function ($q) {
                $q->where('expires_at', '>', now())->orWhereNull('expires_at');
            }, 'tierAssignments.tier'])
            ->latest()
            ->paginate(10);

        $tiers = CustomerTier::all();

        return view('admin.users.index', compact('users', 'tiers'));
    }

    public function assignTier(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'tier_id' => 'required|exists:customer_tiers,id',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $tier = CustomerTier::findOrFail($validated['tier_id']);
        $now = now();
        $expiresAt = $validated['expires_at'] ? \Carbon\Carbon::parse($validated['expires_at']) : null;

        // Check if user already has this tier assigned (active or expired)
        $existingAssignment = CustomerTierAssignment::where('user_id', $user->id)
            ->where('customer_tier_id', $tier->id)
            ->first();

        DB::beginTransaction();
        try {
            // Expire any other active tier assignments (except this one if exists)
            $user->tierAssignments()
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->when($existingAssignment, function ($q) use ($existingAssignment) {
                    $q->where('id', '!=', $existingAssignment->id);
                })
                ->update(['expires_at' => $now]);

            if ($existingAssignment) {
                // Reactivate existing assignment
                $existingAssignment->update([
                    'expires_at' => $expiresAt,
                ]);
            } else {
                // Create new assignment
                CustomerTierAssignment::create([
                    'user_id' => $user->id,
                    'customer_tier_id' => $tier->id,
                    'assigned_at' => $now,
                    'expires_at' => $expiresAt,
                ]);
            }

            $expiryText = $expiresAt ? " (berlaku sampai {$expiresAt->format('d/m/Y')})" : ' (permanen)';

            Broadcast::create([
                'user_id' => $user->id,
                'title' => 'Level Harga Diperbarui',
                'message' => "Anda mendapatkan level harga {$tier->name}{$expiryText}.",
                'type' => 'promo',
                'is_read' => false,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Level harga {$tier->name} berhasil diberikan kepada {$user->name}.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memberikan level harga: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function broadcasts()
    {
        $broadcasts = Broadcast::with('user')->latest()->paginate(10);

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function sendBroadcast(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:promo,system,info',
            'target_type' => 'required|in:all,specific_tier,specific_user',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'tier_ids' => 'nullable|array',
            'tier_ids.*' => 'exists:customer_tiers,id',
        ]);

        $users = collect();

        if ($validated['target_type'] === 'all') {
            $users = User::where('role', 'customer')->get();
        } elseif ($validated['target_type'] === 'specific_user' && !empty($validated['user_ids'])) {
            $users = User::whereIn('id', $validated['user_ids'])->get();
        } elseif ($validated['target_type'] === 'specific_tier' && !empty($validated['tier_ids'])) {
            $userIds = CustomerTierAssignment::whereIn('customer_tier_id', $validated['tier_ids'])
                ->where('expires_at', '>', now())
                ->orWhereNull('expires_at')
                ->pluck('user_id')
                ->unique();
            $users = User::whereIn('id', $userIds)->get();
        }

        foreach ($users as $user) {
            Broadcast::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'],
                'is_read' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim ke ' . $users->count() . ' customer.',
        ]);
    }

    public function reports()
    {
        $monthlyRevenue = Transaction::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                SUM(total_amount) as total
            ')
            ->whereIn('status', ['processing', 'shipped', 'delivered', 'done'])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $salesByCategory = Transaction::join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(transaction_details.qty * transaction_details.price) as total')
            ->whereIn('transactions.status', ['processing', 'shipped', 'delivered'])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact('monthlyRevenue', 'salesByCategory'));
    }

    public function customerTiers()
    {
        $tiers = CustomerTier::withCount('assignments')->get();

        return view('admin.tiers.index', compact('tiers'));
    }

    public function getCustomerTier($id)
    {
        $tier = CustomerTier::findOrFail($id);

        return response()->json([
            'success' => true,
            'tier' => $tier,
        ]);
    }

    public function storeCustomerTier(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_level' => 'required|integer|in:1,2,3,4',
            'min_purchase' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $tier = CustomerTier::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price_level' => $validated['price_level'],
            'discount_percent' => 0, // Deprecated, tapi kept for backward compatibility
            'min_purchase' => $validated['min_purchase'],
            'description' => $validated['description'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Level harga customer berhasil ditambahkan.',
            'tier' => $tier,
        ]);
    }

    public function updateCustomerTier(Request $request, $id)
    {
        $tier = CustomerTier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_level' => 'required|integer|in:1,2,3,4',
            'min_purchase' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $tier->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price_level' => $validated['price_level'],
            'discount_percent' => 0, // Deprecated
            'min_purchase' => $validated['min_purchase'],
            'description' => $validated['description'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Level harga customer berhasil diperbarui.',
            'tier' => $tier,
        ]);
    }

    public function destroyCustomerTier($id)
    {
        $tier = CustomerTier::findOrFail($id);

        if ($tier->assignments()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Level harga tidak dapat dihapus karena masih digunakan oleh customer.',
            ], 400);
        }

        $tier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Level harga customer berhasil dihapus.',
        ]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('q');

        $users = User::where('role', 'customer')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json(['users' => $users]);
    }

    public function dashboardStats()
    {
        $data = [
            'totalUsers' => User::where('role', 'customer')->count(),
            'totalProducts' => Product::count(),
            'totalOrders' => Transaction::where('status', '!=', 'cancelled')->count(),
            'todayRevenue' => Transaction::whereDate('created_at', today())
                ->whereIn('status', ['processing', 'shipped', 'delivered', 'done'])
                ->sum('total_amount'),
            'pendingOrdersCount' => Transaction::where('status', 'pending')->count(),
            'lowStockCount' => Product::where('current_stock', '<=', 5)->count(),
            'newCustomersCount' => User::where('role', 'customer')
                ->where('created_at', '>=', now()->subWeek())->count(),
'bestSellers' => TransactionDetail::join('products', 'transaction_details.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, SUM(transaction_details.qty) as total_sold')
            ->whereHas('transaction', function($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')->limit(3)->get(),
            'reorderAlerts' => Product::where('current_stock', '<=', 3)->where('current_stock', '>', 0)->count(),
        ];

        return response()->json($data);
    }
}