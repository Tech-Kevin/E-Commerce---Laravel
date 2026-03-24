<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.home');
    }
    public function ShowProfile()
    {
        return view('customer.profile');
    }

    public function ShowWishlist()
    {
        return view('customer.wishlist');
    }

}
