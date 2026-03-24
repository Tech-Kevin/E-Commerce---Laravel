<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(){
        return view("vendor.dashboard");
    }

    public function ShowOrders(){
        return view("vendor.order");
    }
    
    public function ShowCustomers(){
        return view("vendor.customer");
    }

    public function ShowAnalytics(){
        return view("vendor.analytics");
    }

    public function ShowEarnings(){
        return view("vendor.earnings");
    }

    public function ShowSettings(){
        return view("vendor.settings");
    }
}
