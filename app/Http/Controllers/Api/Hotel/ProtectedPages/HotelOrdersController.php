<?php

namespace App\Http\Controllers\Api\Hotel\ProtectedPages;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Tymon\JWTAuth\Facades\JWTAuth;

class HotelOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api-hotel');
    }

    public function __invoke()
    {
        $hotel = JWTAuth::toUser();

        $receivedOrders = Order::where(['hotel_id' => $hotel->id, 'order_status'=> 'received'])->orderBy('id','DESC')->get();
        $receivedOrders = $this->orderDetailsCollectionModifier($receivedOrders);

        $processingOrders = Order::where(['hotel_id' => $hotel->id, 'order_status'=> 'proceeded'])->orderBy('id','DESC')->get();
        $processingOrders = $this->orderDetailsCollectionModifier($processingOrders);

        $completedOrders = Order::where(['hotel_id' => $hotel->id, 'order_status'=> 'completed'])->orderBy('id','DESC')->get();
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

    private function orderDetailsCollectionModifier($collection)
    {
        $orderHistoryList = $collection;
        foreach ($orderHistoryList as $key => $orderHistory) {
            $totalOrderAmount = 0;
            $orderHistoryId = $orderHistory->id;
            $apiUserId = $orderHistory->api_user_id;

            //get api user
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
}
