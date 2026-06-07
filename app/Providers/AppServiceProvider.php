<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Broadcast;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $view->with('categories', Category::orderBy('name')->get());
                $view->with('cartCount', Cart::where('user_id', Auth::id())->sum('qty'));
                $view->with('wishlistCount', Wishlist::where('user_id', Auth::id())->count());
                $view->with('notifications', Broadcast::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->latest()
                    ->take(5)
                    ->get());
                $view->with('unreadCount', Broadcast::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count());
            } else {
                $view->with('categories', Category::orderBy('name')->get());
                $view->with('cartCount', 0);
                $view->with('wishlistCount', 0);
                $view->with('notifications', []);
                $view->with('unreadCount', 0);
            }
        });
    }
}
