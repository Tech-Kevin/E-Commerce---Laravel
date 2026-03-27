<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . Auth::id(),
            'number'  => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        Auth::user()->update($request->only('name', 'email', 'number', 'address'));

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
    }

    public function ShowWishlist()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('customer.wishlist', compact('wishlistItems'));
    }
}
