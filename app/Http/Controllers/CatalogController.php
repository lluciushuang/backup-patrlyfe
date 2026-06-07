<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['prices', 'productPriceTiers', 'images']);
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('brand', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }
        
        $products = $query->paginate(9);
        $categories = Category::withCount('products')->get();
        
        $selectedCategory = $request->category;
        $search = $request->search;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        
        $tierPriceLevel = 1; // Default to level 1 (eceran)
        if (auth()->check()) {
            $activeTier = auth()->user()->activeTier;
            $tierPriceLevel = $activeTier ? $activeTier->price_level : 1;
        }

        $priceStats = (object) [
            'max_price' => Product::max('base_price') ?? 1000000,
            'min_price' => Product::min('base_price') ?? 0
        ];
        
        return view('catalog', compact('products', 'categories', 'selectedCategory', 'search', 'minPrice', 'maxPrice', 'priceStats', 'tierPriceLevel'));
    }

    public function show($id)
    {
        $product = Product::with(['prices', 'productPriceTiers', 'images', 'category'])->findOrFail($id);

        $recommendations = Product::with('prices', 'images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('current_stock', '>', 0)
            ->inRandomOrder()
            ->take(5)
            ->get();

        $isInWishlist = false;
        $tierPriceLevel = 1; // Default to level 1 (eceran)
        if (auth()->check()) {
            $isInWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
            
            $activeTier = auth()->user()->activeTier;
            $tierPriceLevel = $activeTier ? $activeTier->price_level : 1;
        }

        $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('qty') : 0;
        $wishlistCount = auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0;

        return view('show', compact('product', 'recommendations', 'isInWishlist', 'cartCount', 'wishlistCount', 'tierPriceLevel'));
    }

    public function byCategory($slug)
    {
        $category = Category::where('name', 'like', '%' . str_replace('-', ' ', $slug) . '%')
            ->orWhere('name', 'like', '%' . ucfirst($slug) . '%')
            ->firstOrFail();
        
        $products = Product::with(['prices', 'images'])
            ->where('category_id', $category->id)
            ->paginate(9);
        
        $categories = Category::withCount('products')->get();
        $priceStats = (object) [
            'max_price' => Product::max('base_price') ?? 1000000,
            'min_price' => Product::min('base_price') ?? 0
        ];
        $selectedCategory = $category->id;
        
        $tierPriceLevel = 1; // Default to level 1 (eceran)
        if (auth()->check()) {
            $activeTier = auth()->user()->activeTier;
            $tierPriceLevel = $activeTier ? $activeTier->price_level : 1;
        }

        return view('catalog', compact('products', 'categories', 'priceStats', 'selectedCategory', 'tierPriceLevel'));
    }

public function searchAjax(Request $request)
    {
        $query = Product::query()->with(['images']);
        
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('brand', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $products = $query->limit(8)->get();
        
        return response()->json($products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'slug' => route('produk.show', $product->id),
                'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null,
                'price' => $product->prices->first()?->price ?? $product->base_price,
            ];
        }));
    }
}