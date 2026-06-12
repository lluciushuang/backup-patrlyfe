<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;

class CustomerController extends Controller
{
    public function dashboard(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $selectedCategory = $request->input('category');

        $productsQuery = Product::with(['prices', 'images', 'category']);

        if ($search) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('brand', 'LIKE', "%{$search}%")
                    ->orWhere('item_code', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($selectedCategory) {
            $productsQuery->where('category_id', $selectedCategory);
        }

        if ($request->filled('min_price')) {
            $productsQuery->whereHas('prices', function ($q) use ($minPrice) {
                $q->where('price_level', 1)->where('price', '>=', (float) $minPrice);
            });
        }

        if ($request->filled('max_price')) {
            $productsQuery->whereHas('prices', function ($q) use ($maxPrice) {
                $q->where('price_level', 1)->where('price', '<=', (float) $maxPrice);
            });
        }

        $priceStats = Product::join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->where('product_prices.price_level', 1)
            ->selectRaw('MIN(product_prices.price) as min_price, MAX(product_prices.price) as max_price')
            ->first();

        $products = $productsQuery->latest()->paginate(12)->withQueryString();

        $hasNotification = Broadcast::where('user_id', Auth::id())
            ->where('is_read', false)
            ->exists();

        $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('catalog', compact(
            'categories',
            'products',
            'search',
            'hasNotification',
            'cartCount',
            'wishlistCount',
            'minPrice',
            'maxPrice',
            'selectedCategory',
            'priceStats'
        ));
    }

    public function cart()
    {
        $cartItems = Cart::with(['product.prices', 'product.images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $tierPriceLevel = 1; // Default to level 1
        $activeTier = Auth::user()->activeTier;
        if ($activeTier) {
            $tierPriceLevel = $activeTier->price_level;
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->product->prices->where('price_level', $tierPriceLevel)->first()?->price 
                  ?? $item->product->base_price ?? 0;
            $subtotal += $price * $item->qty;
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('cart.index', compact('cartItems', 'subtotal', 'cartCount', 'wishlistCount', 'tierPriceLevel'));
    }

    public function addToCart(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $requestedQty = (int) $request->input('qty', 1);

        if ($product->current_stock <= 0) {
            return back()->with('error', "Stok {$product->name} sedang habis.");
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        $existingQty = $cartItem ? $cartItem->qty : 0;
        $newTotalQty = $existingQty + $requestedQty;

        if ($newTotalQty > $product->current_stock) {
            return back()->with('error', "Stok tidak cukup. Sisa stok: {$product->current_stock} pcs.");
        }

        if ($cartItem) {
            $cartItem->qty += $requestedQty;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product_id,
                'qty' => $requestedQty,
            ]);
        }

        Broadcast::create([
            'user_id' => Auth::id(),
            'title' => 'Item Ditambahkan ke Keranjang',
            'message' => "{$product->name} berhasil ditambahkan ke keranjang Anda.",
            'type' => 'cart',
            'is_read' => false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil ditambahkan ke keranjang!',
                'cart_count' => Cart::where('user_id', Auth::id())->sum('qty'),
            ]);
        }

        return back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function updateCartQty(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $product = $cartItem->product;
        $newQty = (int) $request->input('qty', 1);

        if ($newQty < 1) {
            $cartItem->delete();
            return response()->json([
                'success' => true,
                'removed' => true,
                'cart_count' => Cart::where('user_id', Auth::id())->sum('qty'),
                'subtotal' => 0,
            ]);
        }

        if ($newQty > $product->current_stock) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak cukup. Sisa stok: {$product->current_stock} pcs.",
            ], 400);
        }

        $cartItem->qty = $newQty;
        $cartItem->save();

        $price = $product->prices->where('price_level', 1)->first()->price ?? 0;
        $subtotal = $price * $newQty;

        return response()->json([
            'success' => true,
            'qty' => $newQty,
            'subtotal' => $subtotal,
            'cart_count' => Cart::where('user_id', Auth::id())->sum('qty'),
        ]);
    }

    public function removeFromCart($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $productName = $cartItem->product->name;
        $cartItem->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus.',
                'cart_count' => Cart::where('user_id', Auth::id())->sum('qty'),
            ]);
        }

        return back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    public function wishlist()
    {
        $wishlists = Wishlist::with(['product.prices', 'product.images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $tierPriceLevel = 1; 
        $activeTier = Auth::user()->activeTier;
        if ($activeTier) {
            $tierPriceLevel = $activeTier->price_level;
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('wishlist.index', compact('wishlists', 'cartCount', 'wishlistCount', 'tierPriceLevel'));
    }

    public function toggleWishlist(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Dihapus dari Wishlist!';
            $type = 'info';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product_id,
            ]);
            $message = 'Ditambahkan ke Wishlist!';
            $type = 'wishlist';
        }

        Broadcast::create([
            'user_id' => Auth::id(),
            'title' => $wishlist ? 'Dihapus dari Wishlist' : 'Ditambahkan ke Wishlist',
            'message' => "{$product->name} {$message}",
            'type' => $type,
            'is_read' => false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'added' => !$wishlist,
                'wishlist_count' => Wishlist::where('user_id', Auth::id())->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    public function removeFromWishlist($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari wishlist.',
        ]);
    }

    public function broadcasts(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $broadcasts = Broadcast::where('user_id', Auth::id())
            ->latest()
            ->paginate($perPage);

        Broadcast::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('broadcast.index', compact('broadcasts'));
    }

    public function unreadNotifications()
    {
        $notifications = Broadcast::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Broadcast::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        }

        return view('broadcast.index', compact('notifications', 'unreadCount'));
    }

    public function markNotificationRead($id)
    {
        $broadcast = Broadcast::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $broadcast->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function markAllNotificationsRead()
    {
        Broadcast::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }

    public function profile()
    {
        $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('profile', compact('cartCount', 'wishlistCount'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'phone', 'address'));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function checkout(Request $request)
    {
        $productId = $request->query('product_id');
        $qty = (int) $request->query('qty', 1);

        $checkoutItems = [];
        $subtotal = 0;
        $tierPriceLevel = 1;
        $activeTier = Auth::user()->activeTier;
        if ($activeTier) {
            $tierPriceLevel = $activeTier->price_level;
        }

        if ($productId) {
            $product = Product::with(['prices', 'images'])->findOrFail($productId);
            $price = $product->prices->where('price_level', $tierPriceLevel)->first()?->price
                ?? $product->prices->where('price_level', 1)->first()?->price
                ?? $product->base_price ?? 0;
            $originalPrice = $product->prices->where('price_level', 1)->first()?->price ?? $product->base_price ?? 0;

            if ($qty > $product->current_stock) {
                return redirect()->route('produk.show', $productId)
                    ->with('error', "Stok tidak cukup. Sisa stok: {$product->current_stock} pcs.");
            }

            $checkoutItems[] = (object) [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null,
                'qty' => $qty,
                'price' => $price,
                'original_price' => $originalPrice,
                'subtotal' => $price * $qty,
                'stock' => $product->current_stock,
            ];
            $subtotal = $price * $qty;
        } else {
            $cartItems = Cart::with(['product.prices', 'product.images'])
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
            }

            foreach ($cartItems as $item) {
                $price = $item->product->prices->where('price_level', $tierPriceLevel)->first()?->price
                    ?? $item->product->prices->where('price_level', 1)->first()?->price
                    ?? $item->product->base_price ?? 0;
                $originalPrice = $item->product->prices->where('price_level', 1)->first()?->price ?? $item->product->base_price ?? 0;

                if ($item->qty > $item->product->current_stock) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok {$item->product->name} tidak cukup. Sisa: {$item->product->current_stock} pcs.");
                }

                $checkoutItems[] = (object) [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'brand' => $item->product->brand,
                    'image' => $item->product->images->first() ? asset('storage/' . $item->product->images->first()->image_path) : null,
                    'qty' => $item->qty,
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'subtotal' => $price * $item->qty,
                    'stock' => $item->product->current_stock,
                ];
                $subtotal += $price * $item->qty;
            }
        }

        $ongkosKirim = 0;
        $total = $subtotal + $ongkosKirim;

        $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('checkout', compact(
            'checkoutItems',
            'subtotal',
            'total',
            'productId',
            'qty',
            'cartCount',
            'wishlistCount',
            'tierPriceLevel'
        ));
    }

    public function initiatePayment(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        $orderId = 'TRX-' . time() . '-' . Auth::id();
        $grossAmount = 0;
        $itemDetails = [];
        $dbDetails = [];
        
        $tierPriceLevel = 1;
        $activeTier = Auth::user()->activeTier;
        if ($activeTier) {
            $tierPriceLevel = $activeTier->price_level;
        }

        if ($request->has('product_id')) {
            $product = Product::with('prices')->findOrFail($request->product_id);
            $qty = (int) $request->input('qty', 1);

            if ($product->current_stock < $qty) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Stok {$product->name} tidak cukup. Sisa: {$product->current_stock} pcs."
                ], 400);
            }

            $price = $product->prices->where('price_level', $tierPriceLevel)->first()?->price
                ?? $product->prices->where('price_level', 1)->first()?->price
                ?? $product->base_price ?? 0;

            $itemDetails[] = [
                'id' => $product->id,
                'price' => (int) $price,
                'quantity' => $qty,
                'name' => substr($product->name, 0, 50),
            ];

            $dbDetails[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $price,
            ];

            $grossAmount += $price * $qty;
        } else {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with(['product', 'product.prices'])
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Keranjang kosong.'], 400);
            }

            foreach ($cartItems as $item) {
                if ($item->product->current_stock < $item->qty) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok {$item->product->name} tidak cukup. Sisa: {$item->product->current_stock} pcs."
                    ], 400);
                }

                $price = $item->product->prices->where('price_level', $tierPriceLevel)->first()?->price
                    ?? $item->product->prices->where('price_level', 1)->first()?->price
                    ?? $item->product->base_price ?? 0;

                if ($price > 0) {
                    $itemDetails[] = [
                        'id' => $item->product->id,
                        'price' => (int) $price,
                        'quantity' => $item->qty,
                        'name' => substr($item->product->name, 0, 50),
                    ];

                    $dbDetails[] = [
                        'product_id' => $item->product->id,
                        'qty' => $item->qty,
                        'price' => $price,
                    ];

                    $grossAmount += $price * $item->qty;
                }
            }
        }

        $ongkosKirim = 0;
        $grossAmount += $ongkosKirim;

        if ($grossAmount <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Total tagihan Rp 0.'], 400);
        }

        if ($ongkosKirim > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $ongkosKirim,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '08123456789',
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 1440,
            ],
        ];

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => $orderId,
                'total_amount' => $grossAmount,
                'status' => 'pending',
            ]);

            foreach ($dbDetails as $detail) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $detail['product_id'],
                    'qty' => $detail['qty'],
                    'price' => $detail['price'],
                ]);
            }

            if (!$request->has('product_id')) {
                Cart::where('user_id', Auth::id())->delete();
            }

            $snapToken = Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'invoice_number' => $orderId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentCallback(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = $request->input('transaction_status');
        $fraud = $request->input('fraud_status');

        $transaction = Transaction::where('invoice_number', $orderId)
            ->with('details.product')
            ->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan.'], 404);
        }

        if (in_array($status, ['settlement', 'capture'])) {
            if (in_array($transaction->status, ['pending', 'expire', 'cancel'])) {
                DB::beginTransaction();
                try {
                    $transaction->status = 'processing';
                    $transaction->save();

                    foreach ($transaction->details as $detail) {
                        $product = $detail->product;
                        if ($product) {
                            $product->current_stock = max(0, $product->current_stock - $detail->qty);
                            $product->save();
                        }
                    }

                    Broadcast::create([
                        'user_id' => Auth::id(),
                        'title' => 'Pembayaran Berhasil',
                        'message' => "Pembayaran untuk invoice {$orderId} telah dikonfirmasi.",
                        'type' => 'system',
                        'is_read' => false,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
                }
            }
        } elseif (in_array($status, ['cancel', 'expire', 'deny'])) {
            $transaction->status = 'cancelled';
            $transaction->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Callback diproses.']);
    }

    public function transactions(Request $request)
    {
        $statusFilter = $request->query('status');

        $query = Transaction::with('details.product')
            ->where('user_id', Auth::id());

        if ($statusFilter) {
            if ($statusFilter == 'menunggu') {
                $query->whereIn('status', ['pending', 'unpaid', 'menunggu']);
            } elseif ($statusFilter == 'diproses') {
                $query->where('status', 'processing');
            } elseif ($statusFilter == 'gagal') {
                $query->whereIn('status', ['expire', 'cancel', 'cancelled', 'gagal']);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        $transactions = $query->latest()->paginate(10);

        $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('transactions.index', compact('transactions', 'statusFilter', 'cartCount', 'wishlistCount'));
    }

    public function invoice($invoice_number)
    {
        $transaction = Transaction::where('invoice_number', $invoice_number)
            ->where('user_id', Auth::id())
            ->with(['details.product'])
            ->firstOrFail();

        return view('invoice', compact('transaction'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['prices', 'category', 'images'])->findOrFail($id);

        $recommendations = Product::with('prices', 'images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('current_stock', '>', 0)
            ->inRandomOrder()
            ->take(5)
            ->get();

        $isInWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        $cartCount = Cart::where('user_id',Auth::id())->sum('qty');
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return view('show', compact('product', 'recommendations', 'isInWishlist', 'cartCount', 'wishlistCount'));
    }

    public function buyNow(Request $request, $product_id)
    {
        $product = Product::with(['prices', 'images'])->findOrFail($product_id);
        $qty = (int) $request->input('qty', 1);

        if ($product->current_stock < $qty) {
            return back()->with('error', "Stok tidak cukup. Sisa stok: {$product->current_stock} pcs.");
        }

        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if ($existingCart) {
            if ($existingCart->qty + $qty > $product->current_stock) {
                return back()->with('error', "Gagal. Stok tersedia tidak cukup untuk jumlah ini.");
            }
        }

        Cart::where('user_id', Auth::id())
            ->where('product_id', '!=', $product_id)
            ->delete();

        if ($existingCart) {
            $existingCart->qty = $qty;
            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product_id,
                'qty' => $qty,
            ]);
        }

        Broadcast::create([
            'user_id' => Auth::id(),
            'title' => 'Beli Langsung',
            'message' => "Anda memulai pembelian langsung: {$product->name} (x{$qty}).",
            'type' => 'system',
            'is_read' => false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil! Akan dilanjutkan ke checkout.',
                'redirect' => route('checkout'),
            ]);
        }

        return redirect()->route('checkout');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $products = Product::with('prices', 'images')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('brand', 'LIKE', "%{$query}%")
                    ->orWhere('item_code', 'LIKE', "%{$query}%")
                    ->orWhereHas('category', function ($q2) use ($query) {
                        $q2->where('name', 'LIKE', "%{$query}%");
                    });
            })
            ->take(8)
            ->get();

        $results = $products->map(function ($prod) {
            $price = $prod->prices->where('price_level', 1)->first();
            $image = $prod->images->first();

            return [
                'id' => $prod->id,
                'name' => $prod->name,
                'brand' => $prod->brand,
                'price' => $price ? number_format($price->price, 0, ',', '.') : '0',
                'image' => $image ? asset('storage/' . $image->image_path) : null,
                'url' => route('produk.show', $prod->id),
                'stock' => $prod->current_stock,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }

    public function aiChat()
    {
        return view('components.ai-chat');
    }

    public function sendAiMessage(Request $request)
    {
        $userMessage = $request->input('message');
        if (!$userMessage) {
            return response()->json(['status' => 'error', 'reply' => 'Pesan kosong.']);
        }

        $apiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'reply' => 'Sistem AI Offline: API Key belum terbaca di file .env!'
            ]);
        }

        try {
            $availableProducts = Product::where('current_stock', '>', 0)->get(['id', 'name', 'brand']);
            
            $catalogContext = "";
            foreach ($availableProducts as $product) {
                $catalogContext .= "- Nama Barang: {$product->name} | Brand: {$product->brand} | Link Order: " . route('produk.show', $product->id) . "\n";
            }

            $systemPrompt = "Kamu adalah seorang asisten mekanik virtual yang pintar, ramah, akrab, dan gaul dari toko Sinar Jaya Motor (aplikasi PartLyfe).

Aturan ketat yang WAJIB kamu ikuti:
1. Jika pelanggan mengeluh motornya rusak atau bermasalah (misal: mogok, tidak bisa distarter, brebet, oli bocor, bunyi kasar), kamu WAJIB memberikan DIAGNOSA LOGIS terlebih dahulu. Jelaskan kemungkinan kerusakannya (misal: cek kondisi aki, busi, dinamo starter, atau pengapian).
2. JANGAN PERNAH merekomendasikan sparepart yang tidak relevan dengan keluhan mereka (misal: motor mati tidak bisa distarter tapi kamu merekomendasikan lampu atau shockbreaker). Rekomendasi harus tepat sasaran!
3. Berikut adalah daftar katalog sparepart asli yang SAAT INI TERSEDIA (Ready Stock) di Sinar Jaya Motor:\n" . $catalogContext . "\n
4. Berdasarkan hasil diagnosamu, cari apakah ada barang yang cocok dari daftar ready stock di atas. Jika ADA, sebutkan nama produknya dan WAJIB sertakan Link Order aslinya secara utuh agar pelanggan bisa langsung klik untuk membeli. Jika barang tidak ada di daftar, katakan bahwa stok barang tersebut sedang habis dan sarankan alternatif lain.
5. Gunakan bahasa Indonesia yang santai, gaul, akrab (gunakan sapaan seperti 'Bos' atau 'Bro') namun tetap profesional layaknya mekanik berpengalaman.
6. Kamu HANYA boleh menjawab pertanyaan seputar otomotif roda dua/sepeda motor saja. Jika ditanya hal lain di luar topik motor, tolak dengan sopan dan katakan: 'Maaf Bos, mekanik PartLyfe cuma paham seputar dunia motor aja nih. Ada kendala motor lain yang bisa ane bantu?'

Pertanyaan pelanggan: ";

            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . $userMessage]
                        ]
                    ]
                ]
            ]);

            $data = $response->json();

            if (isset($data['error'])) {
                return response()->json([
                    'status' => 'error',
                    'reply' => 'ERROR DARI GOOGLE: ' . $data['error']['message']
                ]);
            }

            $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Waduh, mekanik AI lagi bengong nih.";
            $aiReplyHtml = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $aiReply);

            return response()->json([
                'status' => 'success',
                'reply' => nl2br($aiReplyHtml)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'reply' => 'ERROR SISTEM: ' . $e->getMessage()
            ]);
        }
    }


    public function receiveSyncData(Request $request)
    {
        // 1. Validasi Token Keamanan
       $token = $request->header('X-Sync-Token');
        if ($token !== 'PartLyfeSyncSecure999') { 
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Token!'], 401);
        }

        $table = $request->input('table');
        $data = $request->input('data');

        if (!$table || !is_array($data)) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak valid.'], 400);
        }

        // 2. Eksekusi Upsert (Update jika ada, Create jika belum ada)
        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                // Hapus kolom is_synced sebelum disimpan ke database cloud
                unset($row['is_synced']);
                
                DB::table($table)->updateOrInsert(
                    ['id' => $row['id']], // Kunci patokan (primary key)
                    $row
                );
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => "Berhasil sinkronisasi tabel {$table}"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function sendDataToLocal(Request $request)
    {
        // Validasi Token
        $token = $request->header('X-Sync-Token');
        if ($token !== 'PartLyfeSyncSecure999') { // Sesuaikan tokenmu
            return response()->json(['status' => 'error', 'message' => 'Unauthorized!'], 401);
        }

        $table = $request->query('table');
        if (!$table) {
            return response()->json(['status' => 'error', 'message' => 'Tabel tidak ditentukan'], 400);
        }

        // Ambil semua data dari cloud untuk dikirim ke lokal
        $data = DB::table($table)->get();
        $dataArray = json_decode(json_encode($data), true);

        return response()->json(['status' => 'success', 'data' => $dataArray]);
    }
}