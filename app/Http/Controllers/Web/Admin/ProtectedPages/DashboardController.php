<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Hotel;
use App\Models\HotelCategory;
use App\Models\Order;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $hotelCount = Hotel::all()->count();
        $hotelCategoryCount = HotelCategory::all()->count();
//        $productCategoryCount = ProductCategory::all()->count();
        $orderCount = Order::all()->count();
        $appUsers = ApiUser::all()->count();
        return view('pages.admin.dashboard.index', compact('hotelCount', 'hotelCategoryCount', 'orderCount', 'appUsers'));
    }
}
