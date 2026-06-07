<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->take(4)->get();
        $featuredProducts = Product::with(['prices', 'images'])->where('current_stock', '>', 0)->inRandomOrder()->take(3)->get();
        
        return view('home', compact('categories', 'featuredProducts'));
    }
}