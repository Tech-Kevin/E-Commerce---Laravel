<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use Illuminate\Support\Facades\Auth;

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
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('loginForm')
                    ->withInput($request->only('email'))
                    ->with('error', 'Admin has blocked you contact to admin');
            }

            if ($user->role == 'customer') {
                return redirect()->intended(route('home'));
            } elseif (in_array($user->role, ['vendor', 'admin'], true)) {
                return redirect()->intended(route('vendor.dashboard'));
            } elseif ($user->role == 'delivery') {
                return redirect()->intended(route('delivery.dashboard'));
            }
        }

        return redirect()->route('loginForm')
            ->withInput($request->only('email'))
            ->with('error', 'Invalid credentials');
    }
}
