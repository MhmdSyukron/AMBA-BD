<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $selectedCategorySlug = $request->query('category');
        $searchQuery = $request->query('q');
        $sortBy = $request->query('sort', 'latest');

        $productsQuery = Product::with('category');

        if ($selectedCategorySlug) {
            $productsQuery->whereHas('category', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        if ($searchQuery) {
            $productsQuery->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%")
                    ->orWhere('store_name', 'like', "%{$searchQuery}%");
            });
        }

        switch ($sortBy) {
            case 'price_low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'rating':
                $productsQuery->orderBy('rating', 'desc');
                break;
            case 'top_seller':
                $productsQuery->orderBy('sold_count', 'desc');
                break;
            default:
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery->get();

        $flashSaleProducts = Product::where('is_flash_sale', true)->take(6)->get();
        $officialStoreProducts = Product::where('is_official_store', true)->take(6)->get();

        // Cart items for current session
        $sessionId = session()->getId();
        $cartItems = CartItem::with('product')->where('session_id', $sessionId)->get();
        $cartCount = $cartItems->sum('quantity');

        return view('home', compact(
            'categories',
            'products',
            'flashSaleProducts',
            'officialStoreProducts',
            'selectedCategorySlug',
            'searchQuery',
            'sortBy',
            'cartItems',
            'cartCount'
        ));
    }
}
