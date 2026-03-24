<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // return $products;
        return view('customer.home', compact('products'));
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