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