<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
class loginController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }
    public function login(Login $request)
    {
        $credentials = $request->validated();
         
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role == 'customer') {
                return redirect()->route('customer.index');
            } elseif ($user->role == 'vendor') {
                return redirect()->route('vendor.home');
            }
        } else {
            return redirect()->route('loginForm')->with('error', 'Invalid credentials');
        }
    }
}
