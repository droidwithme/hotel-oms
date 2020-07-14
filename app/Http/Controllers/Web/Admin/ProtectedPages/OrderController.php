<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Methods\FireBase\FireBaseHelper;
use App\Models\ApiUser;
use App\Models\Hotel;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * @return Factory|View
     */
    public function allOrders()
    {
        $orderList = Order::orderBy('id', 'DESC')->get();
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
        return view('pages.admin.orders.all', compact('orderList'));
    }

    /**
     * @return Factory|View
     */
    public function receivedOrders()
    {
        $orderList = Order::where('order_status', 'received')->orderBy('id', 'DESC')->get();
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
        return view('pages.admin.orders.received', compact('orderList'));
    }

    /**
     * @return Factory|View
     */
    public function proceededOrders()
    {
        $orderList = Order::where('order_status', 'proceeded')->orderBy('id', 'DESC')->get();
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
        return view('pages.admin.orders.proceeded', compact('orderList'));
    }

    /**
     * @return Factory|View
     */
    public function completedOrders()
    {
        $orderList = Order::where('order_status', 'completed')->orderBy('id', 'DESC')->get();
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
        return view('pages.admin.orders.completed', compact('orderList'));
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

        return view('pages.admin.orders.order-details', compact('order', 'orderDetails'));
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function updateOrderStatus(Request $request)
    {

        $validate = $request->validate([
            'order-id' => 'required|numeric',
            'order-status' => 'required|in:proceeded,completed'
        ], [
            'order-id.required' => 'An order id is required',
            'order-id.numeric' => 'The order id must be numeric',
            'order-status.required' => 'The order status is required',
            'order-status.in' => 'The order status must be either proceeded or completed',
        ]);

        if ($validate) {
            $orderId = $request->input('order-id');
            $orderStatus = $request->input('order-status');

            $order = Order::find($orderId);
            if ($order) {
                $order->order_status = $orderStatus;

                if ($order->save()) {
                    // Send Notification
                    if ($orderStatus == 'proceeded') {
                        $fcmTokens = [];

                        // Get Fcm Token
                        $hotel = Hotel::find($order->hotel_id);
                        if(isset($hotel->fcm_token)){
                            array_push($fcmTokens, $hotel->fcm_token);
                        }

                        $user = ApiUser::find($order->api_user_id);
                        if(isset($user->fcm_token)){
                            array_push($fcmTokens, $user->fcm_token);
                        }

                        FireBaseHelper::orderNotification('order', $orderStatus, $order, $fcmTokens);
                    }
                    else if ($orderStatus == 'completed') {
                        $fcmTokens = [];

                        // Get Fcm Token
                        $hotel = Hotel::find($order->hotel_id);
                        if(isset($hotel->fcm_token)){
                            array_push($fcmTokens, $hotel->fcm_token);
                        }

                        $user = ApiUser::find($order->api_user_id);
                        if(isset($user->fcm_token)){
                            array_push($fcmTokens, $user->fcm_token);
                        }

                        FireBaseHelper::orderNotification('order', $orderStatus, $order, $fcmTokens);
                    }

                    return response()->json(['status' => true]);
                }
            }
        }
    }
}
