<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\PosTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockMovement;
use App\Services\PosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('prices')->paginate(20);
        $customers = User::where('role', 'customer')->get(['id', 'name', 'email', 'customer_group', 'total_spent']);
        return view('admin.pos.index', compact('products', 'customers'));
    }

    public function searchProduct(Request $request)
    {
        $query = $request->input('q');
        $products = Product::with('prices')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('item_code', 'LIKE', "%{$query}%")
                  ->orWhere('brand', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function loadProducts(Request $request)
    {
        $search = $request->input('search');
        
        $query = Product::with('prices');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('item_code', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $query->paginate(12);
        
        return response()->json([
            'products' => $products->items(),
            'hasMore' => $products->hasMorePages(),
            'nextPage' => $products->nextPageUrl(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,midtrans',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $user = $validated['user_id'] ? User::find($validated['user_id']) : null;
        $total = 0;

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                $product = Product::with('prices')->find($item['id']);
                
                $level = 1;
                if ($user) {
                    $level = PosService::mapGroupToLevel($user->customer_group);
                }
                
                $price = $product->prices->where('price_level', $level)->first()?->price ?? $product->base_price;
                
                $total += ($price * $item['qty']);
                $product->decrement('current_stock', $item['qty']);
            }

            $invoiceNumber = 'POS-' . strtoupper(Str::random(8));
            
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $validated['user_id'],
                'total_amount' => $total,
                'status' => $validated['payment_method'] === 'cash' ? 'processing' : 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['customer_name'] ?? 'Walk-in Customer',
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::with('prices')->find($item['id']);
                $level = $user ? PosService::mapGroupToLevel($user->customer_group) : 1;
                $price = $product->prices->where('price_level', $level)->first()?->price ?? $product->base_price;
                
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $price,
                ]);
            }

            if ($user) {
                $user->increment('total_spent', $total);
                PosService::checkLevelUpgrade($user);
            }

            DB::commit();

            if ($validated['payment_method'] === 'midtrans') {
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production', false);
                Config::$isSanitized = config('midtrans.is_sanitized', true);
                Config::$is3ds = config('midtrans.is_3ds', true);

                $params = [
                    'transaction_details' => [
                        'order_id' => $invoiceNumber,
                        'gross_amount' => (int) $total,
                    ],
                    'customer_details' => [
                        'name' => $user->name ?? $validated['customer_name'] ?? 'Walk-in Customer',
                        'phone' => $user->phone ?? $validated['customer_phone'] ?? '',
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil.',
                    'transaction' => $transaction,
                    'snap_token' => $snapToken,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil.', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function showStockForm()
    {
        $products = Product::limit(20)->get();
        return view('admin.pos.stock_in', compact('products'));
    }

    public function addStock(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($validated['product_id']);
            $product->increment('current_stock', $validated['quantity']);

            StockMovement::create([
                'product_id' => $validated['product_id'],
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'supplier' => $validated['supplier'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Stok berhasil ditambahkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
}