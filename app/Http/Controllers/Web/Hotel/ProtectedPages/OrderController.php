<?php

namespace App\Http\Controllers\Web\Hotel\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Hotel;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * @return Factory|View
     */
    public function allOrders()
    {
        $orderList = Order::whereIn('order_status', ['proceeded', 'completed'])->where('hotel_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($orderList as $item) {
            $orderId = $item->id;
            $hotelId = $item->hotel_id;
            $userId = $item->api_user_id;

            $hotel = Hotel::find($hotelId);
            if ($hotel) {
                $item['hotel_name'] = $hotel->name;
                $item['hotel_mobile'] = $hotel->mobile;
            }

            $appUser = ApiUser::find($userId);
            if ($appUser) {
                $item['api_user_name'] = $appUser->name;
                $item['api_user_mobile'] = $appUser->mobile;
            }

            $orderDetails = OrderDetail::where('order_id', $orderId)->get();
            $orderTotal = 0;
            foreach ($orderDetails as $orderDetail) {
                $orderTotal += $orderDetail->price;
            }

            if ($orderTotal > 0) {
                $item['order_total'] = $orderTotal;
            } else {
                $item['order_total'] = 'N/A';
            }
        }
        return view('pages.hotel.orders.all', compact('orderList'));
    }

    /**
     * @return Factory|View
     */
    public function proceededOrders()
    {
        $orderList = Order::where(['order_status' => 'proceeded', 'hotel_id' => Auth::user()->id])->orderBy('id', 'DESC')->get();
        foreach ($orderList as $item) {
            $orderId = $item->id;
            $hotelId = $item->hotel_id;
            $userId = $item->api_user_id;

            $hotel = Hotel::find($hotelId);
            if ($hotel) {
                $item['hotel_name'] = $hotel->name;
                $item['hotel_mobile'] = $hotel->mobile;
            }

            $appUser = ApiUser::find($userId);
            if ($appUser) {
                $item['api_user_name'] = $appUser->name;
                $item['api_user_mobile'] = $appUser->mobile;
            }

            $orderDetails = OrderDetail::where('order_id', $orderId)->get();
            $orderTotal = 0;
            foreach ($orderDetails as $orderDetail) {
                $orderTotal += $orderDetail->price;
            }

            if ($orderTotal > 0) {
                $item['order_total'] = $orderTotal;
            } else {
                $item['order_total'] = 'N/A';
            }
        }
        return view('pages.hotel.orders.proceeded', compact('orderList'));
    }

    /**
     * @return Factory|View
     */
    public function completedOrders()
    {
        $orderList = Order::where(['order_status' => 'completed', 'hotel_id' => Auth::user()->id])->orderBy('id', 'DESC')->get();
        foreach ($orderList as $item) {
            $orderId = $item->id;
            $hotelId = $item->hotel_id;
            $userId = $item->api_user_id;

            $hotel = Hotel::find($hotelId);
            if ($hotel) {
                $item['hotel_name'] = $hotel->name;
                $item['hotel_mobile'] = $hotel->mobile;
            }

            $appUser = ApiUser::find($userId);
            if ($appUser) {
                $item['api_user_name'] = $appUser->name;
                $item['api_user_mobile'] = $appUser->mobile;
            }

            $orderDetails = OrderDetail::where('order_id', $orderId)->get();
            $orderTotal = 0;
            foreach ($orderDetails as $orderDetail) {
                $orderTotal += $orderDetail->price;
            }

            if ($orderTotal > 0) {
                $item['order_total'] = $orderTotal;
            } else {
                $item['order_total'] = 'N/A';
            }
        }
        return view('pages.hotel.orders.completed', compact('orderList'));
    }

    /**
     * @param $orderId
     * @return Factory|View
     */
    public function orderDetail($orderId)
    {

        // the order
        $order = Order::find($orderId);
        $hotelId = $order->hotel_id;
        $userId = $order->api_user_id;

        $hotel = Hotel::find($hotelId);
        if ($hotel) {
            $order['hotel_name'] = $hotel->name;
            $order['hotel_mobile'] = $hotel->mobile;
        }

        $appUser = ApiUser::find($userId);
        if ($appUser) {
            $order['api_user_name'] = $appUser->name;
            $order['api_user_mobile'] = $appUser->mobile;
        }

        $orderDetails = OrderDetail::where('order_id', $orderId)->get();
        $orderTotal = 0;
        foreach ($orderDetails as $orderDetail) {
            $orderTotal += $orderDetail->price;
        }

        if ($orderTotal > 0) {
            $order['order_total'] = $orderTotal;
        } else {
            $order['order_total'] = 'N/A';
        }

        // The order Details
        $orderDetails = OrderDetail::where('order_id', $orderId)->get();
        foreach ($orderDetails as $key => $orderDetail) {
            $menuItemId = $orderDetail->menu_item_id;

            $menuItem = MenuItem::withTrashed()->find($menuItemId);
            if ($menuItem) {
                $orderDetail['menu_item_name'] = $menuItem->item_title;
            } else {
                unset($orderDetails[$key]);
            }
        }

        return view('pages.hotel.orders.order-details', compact('order', 'orderDetails'));
    }
}
