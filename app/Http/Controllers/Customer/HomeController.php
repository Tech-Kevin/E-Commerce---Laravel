<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        //$products = Product::all();
        $query = Product::query();

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->input('category'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $products   = $query->latest()->get();
        $categories = Category::all();

        return view('customer.home', compact('products', 'categories'));
    }
    public function categoryProducts(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        $categories = Category::where('status', true)->get();

        $query = Product::where('category_id', $category->id);

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderByRaw('COALESCE(sale_price, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(sale_price, price) DESC');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('sale_price')->where('price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('sale_price')->where('price', '<=', $request->max_price);
                  });
            });
        }

        $products = $query->get();

        return view('customer.category-products', compact('category', 'categories', 'products'));
    }

     public function productDetails(Request $request){
        $data = Product::findOrFail($request->id);
        return view('customer.product-details', compact('data'));
    }

    public function addToCart(Request $request){
        $data = Product::findOrFail($request->id);
        return view('customer.cart', compact('data'));

    }

    public function ShowCheckout(){
        return view('customer.checkout');
    }
}