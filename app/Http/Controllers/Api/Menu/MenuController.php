<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Methods\FireBase\FireBaseHelper;
use App\Models\Advertisement;
use App\Models\ApiUser;
use App\Models\Hotel;
use App\Models\HotelCategory;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Models\MenuItemImage;
use App\Models\MenuItemOffer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api-user');
    }

    /* Get store category list */
    public function getHotelCategoryList()
    {
        $hotelCategoryList = HotelCategory::orderBy('id', 'DESC')->get();
        foreach ($hotelCategoryList as $hotelCategory) {
            $hotelCategory->category_image = ($hotelCategory->category_image != null || $hotelCategory->category_image != '') ? $hotelCategory->category_image : "";
        }

        // Advertisements
        $advertisements = Advertisement::all();

        $responseBody = [
            'message' => 'Store category list successfully retrieved.',
            'data' => ['advertisements' => $advertisements, 'hotel_categories' => $hotelCategoryList]
        ];
        return ApiMethods::apiResponse('success', $responseBody);
    }

    /* Het store list */
    public function getHotelList(Request $request)
    {
        $validate = $request->validate([
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'hotel-category-id' => 'sometimes|required|numeric',
            'distance' => 'sometimes|required|numeric',
        ], [
            'lat.required' => 'A latitude is required',
            'lat.regex' => 'A valid latitude is required',
            'long.required' => 'A longitude is required',
            'long.regex' => 'A valid longitude is required',
            'hotel-category-id.required' => 'A store category id is required',
            'hotel-category-id.numeric' => 'A valid store category id is required',
            'distance.required' => 'A distance is required',
            'distance.numeric' => 'A valid distance is required',
        ]);

        if ($validate) {
            $lat = $request->input('lat');
            $long = $request->input('long');
            $distance = $request->input('distance');
            $hotelCategoryId = $request->input('hotel-category-id');

            $hotelsInRange = [];

            // Store List
            $hotelList = new Hotel;
            if ($hotelCategoryId) {
                $hotelList = $hotelList->where('hotel_category', $hotelCategoryId);
            }

            $hotelList = $hotelList->orderBy('id', 'DESC')->get();
            foreach ($hotelList as $key => $hotel) {
                if ($distance != null && $distance != "") {
                    $isHotelInRange = ApiMethods::isHotelInRange($hotel->lat, $hotel->long, $lat, $long, $distance);
                } else {
                    $isHotelInRange = ApiMethods::isHotelInRange($hotel->lat, $hotel->long, $lat, $long, 25);
                }

                if ($isHotelInRange) {
                    array_push($hotelsInRange, $hotel->id);
                }

                $hotelCategoryId = $hotel->hotel_category;
                $hotel->hotel_image = ($hotel->hotel_image != null || $hotel->hotel_image != '') ? $hotel->hotel_image : "";
                $hotel->email = ($hotel->email != null || $hotel->email != '') ? $hotel->email : "";

                $hotelCategory = HotelCategory::find($hotelCategoryId);
                if ($hotelCategory) {
                    $hotel['hotel_category_name'] = $hotelCategory->category_name;
                } else {
                    unset($hotelList[$key]);
                }

                $hotel['distance'] = round(ApiMethods::hotelDistanceToUser($hotel->lat, $hotel->long, $lat, $long), 2) . 'Km';
            }
            $hotelList = $hotelList->values();

            //clean hotels array
            $hotelsInRange = array_values(array_unique($hotelsInRange));
            $nearbyHotelList = Hotel::whereIn('id', $hotelsInRange)->get();
            foreach ($nearbyHotelList as $key => $hotel) {
                $hotelCategoryId = $hotel->hotel_category;

                $hotel->hotel_image = ($hotel->hotel_image != null || $hotel->hotel_image != '') ? $hotel->hotel_image : "";
                $hotel->email = ($hotel->email != null || $hotel->email != '') ? $hotel->email : "";

                $hotelCategory = HotelCategory::find($hotelCategoryId);
                if ($hotelCategory) {
                    $hotel['hotel_category_name'] = $hotelCategory->category_name;
                } else {
                    unset($hotelList[$key]);
                }

                $hotel['distance'] = round(ApiMethods::hotelDistanceToUser($hotel->lat, $hotel->long, $lat, $long), 2) . 'Km';
            }
            $nearbyHotelList = $nearbyHotelList->values();

            // Advertisements
            $advertisements = Advertisement::all();

            $responseBody = [
                'message' => 'Store list successfully retrieved.',
                'data' => ['advertisements' => $advertisements, 'nearby_hotels' => $nearbyHotelList, 'all_hotels' => $hotelList]
            ];
            return ApiMethods::apiResponse('success', $responseBody);
        } else {
            $responseBody = [
                'statusCode' => 422,
                'error' => 'store-list.validation_failed',
                'message' => 'Something went wrong, please try again.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);
        }
    }

    /* Get menu items */
    public function getMenuItemList(Request $request)
    {
        $validate = $request->validate([
            'hotel-id' => 'required|numeric',
        ], [
            'hotel-id.required' => 'A store id is required',
            'hotel-id.numeric' => 'A valid store id is required',
        ]);

        if ($validate) {
            $hotelId = $request->input('hotel-id');

            $menuItemList = MenuItem::where('hotel_id', $hotelId)->orderBy('id', 'DESC')->get();
            foreach ($menuItemList as $key => $menuItem) {
                $categoryId = $menuItem->item_category;
                $menuItemId = $menuItem->id;

                $menuItemCategory = MenuItemCategory::find($categoryId);
                if (isset($menuItemCategory)) {
                    $menuItem['item_category_name'] = $menuItemCategory->category_name;
                } else {
                    unset($menuItemList[$key]);
                }

                $menuItem['offers'] = MenuItemOffer::where('product_id', $menuItemId)->get(['offer_name', 'offer_description', 'offer_type', 'discount_amount', 'products_free_quantity']);
                $menuItemImages = MenuItemImage::where('item_id', $menuItemId)->get()->pluck('item_photo');
                $menuItem['item_photos'] = $menuItemImages;
                $menuItem['gst'] = env('STORE_GST_AMOUNT', '0');
            }
            $responseBody = [
                'message' => 'Menu item list successfully retrieved.',
                'data' => array_values($menuItemList->toArray())
            ];
            return ApiMethods::apiResponse('success', $responseBody);
        } else {
            $responseBody = [
                'statusCode' => 422,
                'error' => 'menu-item-list.validation_failed',
                'message' => 'Something went wrong, please try again.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);
        }
    }

    /* Create a sales order receipt */
    public function createOrder(Request $request)
    {
        $request = $request->all();

        $validate = Validator::make($request, [
            'hotel-id' => 'required|numeric',
            'address' => 'required',
            'gst' => 'required|numeric',
            'customer-instructions' => 'sometimes|nullable',
            'alternate-mobile' => 'sometimes|nullable|numeric|digits_between:10,13',
            'items-ordered' => 'required',
            'items-ordered.*.menu-item-id' => 'required|numeric',
            'items-ordered.*.amount-ordered' => 'required|numeric',
            'items-ordered.*.price' => 'required|numeric',
        ], [
            'hotel-id.required' => 'A hotel id is required',
            'hotel-id.numeric' => 'A valid hotel id is required',

            'address.required' => 'An address is required',
            'customer-instructions.present' => 'Customer instructions is required',
            'gst.required' => 'The gst is required',
            'gst.numeric' => 'The gst must be numeric',


            'alternate-mobile.required' => 'An alternate mobile number is required',
            'alternate-mobile.digits_between' => 'Alternate mobile must be of at least 10 - 13 digits',
            'alternate-mobile.numeric' => 'The alternate mobile number entered must be of a numeric format ( 0-9 )',
            'items-ordered.required' => 'The products bought are required',
            'items-ordered.*.menu-item-id.required' => 'The menu item id is required',
            'items-ordered.*.menu-item-id.numeric' => 'The menu item id must be numeric',
            'items-ordered.*.amount-ordered.required' => 'The amount ordered is required',
            'items-ordered.*.amount-ordered.numeric' => 'The amount ordered must be numeric',

            'items-ordered.*.price.required' => 'The price is required',
            'items-ordered.*.price.numeric' => 'The price must be numeric',


        ]);

        if (!$validate->fails()) {
            $hotelId = $request['hotel-id'];
            $address = $request['address'];

            $alternateMobile = isset($request['alternate-mobile']) ? $request['alternate-mobile'] : null;
            $customerInstructions = isset($request['customer-instructions']) ? $request['customer-instructions'] : null;
            $itemsOrdered = $request['items-ordered'];
            $gst = $request['gst'];

            $newOrder = new Order();
            $newOrder->api_user_id = JWTAuth::toUser()->id;
            $newOrder->hotel_id = $hotelId;
            $newOrder->order_id = time();
            $newOrder->order_date = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
            $newOrder->address = $address;
            $newOrder->alternate_mobile = $alternateMobile;
            $newOrder->customer_instructions = $customerInstructions;
            $newOrder->gst = $gst;
            $operationStatus = $newOrder->save();
            $orderId = $newOrder->id;

            if ($operationStatus) {
                $operationStatus2 = false;
                foreach ($itemsOrdered as $item) {

                    $menuItemId = $item['menu-item-id'];
                    $amountOrdered = $item['amount-ordered'];
                    $price = $item['price'];

                    $newOrderDetail = new OrderDetail();
                    $newOrderDetail->order_id = $orderId;
                    $newOrderDetail->menu_item_id = $menuItemId;
                    $newOrderDetail->amount_ordered = $amountOrdered;
                    $newOrderDetail->price = $price;
                    $operationStatus2 = $newOrderDetail->save();
                }

                if ($operationStatus2) {

                    // get order
                    $order = Order::find($orderId);
                    $order = $this->orderDetailsModifier($order);

                    // Send notification to admins

                    $fcmTokens = [];
                    $admins = User::all();
                    foreach ($admins as $admin) {
                        if (isset($admin->fcm_token)) {
                            array_push($fcmTokens, $admin->fcm_token);
                        }
                    }

                    FireBaseHelper::orderNotification('order', 'received', $order, $fcmTokens);

                    $responseBody = [
                        'message' => 'Order successfully added.',
                        'data' => null
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'order.create.unknown_error',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }
            } else {
                $responseBody = [
                    'statusCode' => 500,
                    'error' => 'order.create.unknown_error',
                    'message' => 'Something went wrong, please try again.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        } else {
            $responseBody = [
                'statusCode' => 422,
                'error' => 'order.validation-failure',
                'message' => $validate->errors()->first()
            ];
            return ApiMethods::apiResponse('error', $responseBody);
        }
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

        //get hotel
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
}
