<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    //The auth middleware
    public function __construct()
    {
        $this->middleware(['auth:hotel,admin']);
    }

    public function index()
    {

        //current role
        $isAdminLoggedIn = Auth::guard('admin')->check();
        $isHotelLoggedIn = Auth::guard('hotel')->check();

        if ($isAdminLoggedIn || $isHotelLoggedIn) {
            if ($isAdminLoggedIn) {
                return redirect()->route('admin.dashboard.index');
            }
            return redirect()->route('hotel.order.proceeded');
        } else {
            return redirect('/login');
        }
    }
}
