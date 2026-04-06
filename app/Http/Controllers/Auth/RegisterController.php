<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registeration;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view("auth.register");
    }
    public function register(Registeration $req)
    {
        $data = $req->validated();
        
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->address = $data['address'];
        $user->number = $data['number'];
        $user->password = bcrypt($data['password']);
       
        if ( $user->save()) {

            return redirect()->route('loginForm')->with('success', 'User Registered Successfully');
        } else {
            return redirect()->route('registerForm')->with('error', 'Validation Failed');
        }
    }

}