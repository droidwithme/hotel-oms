<?php

namespace App\Http\Controllers\Api\Admin\ProtectedPages;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Methods\FireBase\FireBaseHelper;
use App\Models\ApiUser;
use App\Models\Hotel;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AdminOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api-admin');
    }

    /**
     * @return ResponseFactory|Response
     */
    public function index()
    {
        $receivedOrders = Order::where('order_status', 'received')->orderBy('id','DESC')->get();
        $receivedOrders = $this->orderDetailsCollectionModifier($receivedOrders);

        $processingOrders = Order::where('order_status', 'proceeded')->orderBy('id','DESC')->get();
        $processingOrders = $this->orderDetailsCollectionModifier($processingOrders);

        $completedOrders = Order::where('order_status', 'completed')->orderBy('id','DESC')->get();
        $completedOrders = $this->orderDetailsCollectionModifier($completedOrders);


        $responseBody = [
            'message' => 'Order list successfully retrieved',
            'data' => [
                'received' => $receivedOrders,
                'processing' => $processingOrders,
                'completed' => $completedOrders,
            ]
        ];
        return ApiMethods::apiResponse('success', $responseBody);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function changeOrderStatus(Request $request){
        $orderId = $request->input('order-id');
        $status = $request->input('status');

        $validate = $request->validate([
            'order-id' => 'required|numeric',
            'status' => 'required|in:proceeded,completed',
        ], [
            'order-id.required' => 'The order id is required',
            'order-id.numeric' => 'The order id must be numeric',
            'status.required' => 'The order status is required',
            'status.in' => 'The order status must be either proceeded or completed',
        ]);

        if ($validate) {
            $order = Order::find($orderId);
            if(isset($order)){

                $order->order_status = $status;
                if($order->save()) {
                    // Send Notification
                    if ($status == 'proceeded') {
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

                        FireBaseHelper::orderNotification('order', $status, $order, $fcmTokens);
                    } else if ($status == 'completed') {
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

                        FireBaseHelper::orderNotification('order', $status, $order, $fcmTokens);
                    }

                    $order = $this->orderDetailsModifier($order);

                    $responseBody = [
                        'message' => 'Order successfully updated',
                        'data' => $order
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);
                }

            } else {
                $responseBody = [
                    'statusCode' => 500,
                    'error' => 'order.invalid_id',
                    'message' => 'Order id is invalid.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        }
    }

    /**
     * @param $collection
     * @return mixed
     */
    private function orderDetailsCollectionModifier($collection)
    {
        $orderHistoryList = $collection;
        foreach ($orderHistoryList as $key => $orderHistory) {
            $totalOrderAmount = 0;
            $orderHistoryId = $orderHistory->id;
            $apiUserId = $orderHistory->api_user_id;
            $hotelId = $orderHistory->hotel_id;

            //get store
            $hotel = Hotel::withTrashed()->find($hotelId);
            if ($hotel) {
                $hotel->email = ($hotel->email != null || $hotel->email != '') ? $hotel->email : "";
                $orderHistory['hotel_details'] = $hotel;
            } else {
                unset($orderHistoryList[$key]);
            }

            //get user
            $apiUser = ApiUser::withTrashed()->find($apiUserId);
            if ($apiUser) {
                $apiUser->email = ($apiUser->email != null || $apiUser->email != '') ? $apiUser->email : "";
                $orderHistory['user_details'] = $apiUser;
            } else {
                unset($orderHistoryList[$key]);
            }

            // get receipt details
            $orderHistoryDetailList = OrderDetail::where('order_id', $orderHistoryId)->get();
            foreach ($orderHistoryDetailList as $key2 => $orderHistoryDetail) {
                $menuItemId = $orderHistoryDetail->menu_item_id;

                $menuItem = MenuItem::withTrashed()->find($menuItemId);
                if ($menuItem) {
                    $orderHistoryDetail['menu_item_details'] = $menuItem;
                } else {
                    unset($orderHistoryDetailList[$key2]);
                }

                $totalOrderAmount += $orderHistoryDetail->price;

                if ($totalOrderAmount > 0) {
                    $orderHistory['total_amount'] = $totalOrderAmount;
                } else {
                    $orderHistory['total_amount'] = "N/A";
                }
            }
            $orderHistory['order_detail'] = $orderHistoryDetailList;
        }

        return $orderHistoryList->values();
    }

    /**
     * @param $collection
     * @return mixed
     */
    private function orderDetailsModifier($collection)
    {
        $orderHistory = $collection;

        $totalOrderAmount = 0;
        $orderHistoryId = $orderHistory->id;
        $apiUserId = $orderHistory->api_user_id;
        $hotelId = $orderHistory->hotel_id;

        //get store
        $hotel = Hotel::withTrashed()->find($hotelId);
        if ($hotel) {
            $hotel->email = ($hotel->email != null || $hotel->email != '') ? $hotel->email : "";
            $orderHistory['hotel_details'] = $hotel;
        }

        //get user
        $apiUser = ApiUser::withTrashed()->find($apiUserId);
        if ($apiUser) {
            $apiUser->email = ($apiUser->email != null || $apiUser->email != '') ? $apiUser->email : "";
            $orderHistory['user_details'] = $apiUser;
        }

        // get receipt details
        $orderHistoryDetailList = OrderDetail::where('order_id', $orderHistoryId)->get();
        foreach ($orderHistoryDetailList as $key2 => $orderHistoryDetail) {
            $menuItemId = $orderHistoryDetail->menu_item_id;

            $menuItem = MenuItem::withTrashed()->find($menuItemId);
            if ($menuItem) {
                $orderHistoryDetail['menu_item_details'] = $menuItem;
            } else {
                unset($orderHistoryDetailList[$key2]);
            }

            $totalOrderAmount += $orderHistoryDetail->price;

            if ($totalOrderAmount > 0) {
                $orderHistory['total_amount'] = $totalOrderAmount;
            } else {
                $orderHistory['total_amount'] = "N/A";
            }
        }
        $orderHistory['order_detail'] = $orderHistoryDetailList;

        return $orderHistory;
    }

    public function guard(){
        return Auth::guard('api-hotel');
    }
}
