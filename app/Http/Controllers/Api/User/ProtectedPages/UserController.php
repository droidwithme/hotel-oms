<?php

namespace App\Http\Controllers\Api\User\ProtectedPages;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Hotel;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api-user');
    }

    public function editUserProfileDetail(Request $request)
    {
        $user = JWTAuth::toUser();

        $validate = $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email',
            'mobile' => 'sometimes|required|digits_between:10,13|numeric',
            'address' => 'sometimes|required',
            'password' => 'sometimes|min:6',

            'lat' => ['sometimes', 'required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['sometimes', 'required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],

            'profile-picture' => 'sometimes|required'
        ], [
            'name.required' => 'A name is required',

            'email.required' => 'An email address is required',
            'email.email' => 'The email address entered must be a valid email address (example@domain.com)',

            'mobile.required' => 'A mobile number is required',
            'mobile.digits_between' => 'Mobile must be of at least 10 - 13 digits',
            'mobile.numeric' => 'The mobile number entered must be of a numeric format ( 0-9 )',

            'address.required' => 'An address is required',

            'password.required' => 'A password is required',
            'password.min' => 'The password entered must be of at least 6 characters',

            'confirm-password.required' => 'A confirm password is required',
            'confirm-password.min' => 'The confirm password entered must be of at least 6 characters',
            'confirm-password.same' => 'The confirm password entered must match the password',

            'lat.required' => 'A latitude is required',
            'lat.regex' => 'A valid latitude is required',
            'long.required' => 'A longitude is required',
            'long.regex' => 'A valid longitude is required',

            'profile-picture.required' => 'A profile picture is required',
        ]);

        if ($validate) {

            $checkUserMobile = ApiUser::where([['mobile', '=', $request->mobile], ['id', '<>', $user->id]])->first();
            $checkUserEmail = ApiUser::where([['email', '=', $request->email], ['id', '<>', $user->id]])->first();

            if ($checkUserMobile && $request->mobile) {

                $responseBody = [
                    'statusCode' => 422,
                    'error' => 'user-profile.mobile_taken',
                    'message' => 'This mobile number is already taken, please try a different one.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);

            } elseif ($checkUserEmail && $request->email) {

                $responseBody = [
                    'statusCode' => 422,
                    'error' => 'user-profile.email_taken',
                    'message' => 'This email is already taken, please try a different one.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);

            } else {
                $userModel = ApiUser::find($user->id);
                if (isset($userModel)) {

                    if ($request->name) {
                        $userModel->name = $request->name;
                    }
                    if ($request->email) {
                        $userModel->email = $request->email;
                    }
                    if ($request->mobile) {
                        $userModel->mobile = $request->mobile;
                    }
                    if ($request->address) {
                        $userModel->address = $request->address;
                    }
                    if ($request->password) {
                        $userModel->password = bcrypt($request->password);
                        $userModel->password_plain = $request->password;
                    }

                    //profile_picture_path
                    $profilePicture = $request->file('profile-picture');
                    if ($request->hasFile('profile-picture') && isset($profilePicture)) {

                        if ($profilePicture->isValid()) {

                            $profilePictureOriginalExtension = $profilePicture->getClientOriginalExtension();
                            $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                            if (in_array($profilePictureOriginalExtension, $allowedExtensions)) {
                                $profilePictureName = 'profile_picture_' . time() . '.' . $profilePictureOriginalExtension;
                                $profilePicture->move(public_path('assets/images/api-user-images'), $profilePictureName);

                                $userModel->profile_picture_path = $profilePictureName;
                            } else {
                                $responseBody = [
                                    'statusCode' => 422,
                                    'error' => 'user-profile-picture.update_failure.invalid_image_type',
                                    'message' => 'Invalid file type.'
                                ];
                                return ApiMethods::apiResponse('error', $responseBody);
                            }
                        }
                    }

                    // Coordinates
                    if ($request->lat) {
                        $userModel->lat = $request->lat;
                    }
                    if ($request->long) {
                        $userModel->long = $request->long;
                    }

                    $operation = $userModel->save();
                    $updatedUserModel = ApiUser::find($user->id);

                    $token = $this->guard()->fromUser($updatedUserModel);
                    $updatedUserModel['token'] = $token;
                    if ($operation) {
                        $responseBody = [
                            'message' => 'User details successfully updated',
                            'data' => ApiMethods::convertNullToEmptyOnUser($updatedUserModel)
                        ];
                        return ApiMethods::apiResponse('success', $responseBody);
                    } else {
                        $responseBody = [
                            'statusCode' => 500,
                            'error' => 'user-profile.update_failure',
                            'message' => 'Something went wrong, please try again.'
                        ];
                        return ApiMethods::apiResponse('error', $responseBody);
                    }
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'user-profile.update_failure',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }
            }
        }
    }

    public function updateUserProfilePicture(Request $request)
    {
        $user = JWTAuth::toUser();

        $validate = $request->validate([
            'profile-picture' => 'sometimes|required'
        ], [
            'profile-picture.required' => 'A profile picture is required',
        ]);

        if ($validate) {
            $userModel = ApiUser::find($user->id);
            if (isset($userModel)) {

                //profile_picture_path
                $profilePicture = $request->file('profile-picture');
                $profilePictureName = null;
                if ($request->hasFile('profile-picture') && isset($profilePicture)) {

                    if ($profilePicture->isValid()) {

                        $profilePictureOriginalExtension = $profilePicture->getClientOriginalExtension();
                        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                        if (in_array($profilePictureOriginalExtension, $allowedExtensions)) {
                            $profilePictureName = 'profile_picture_' . time() . '.' . $profilePictureOriginalExtension;
                            $profilePicture->move(public_path('assets/images/api-user-images'), $profilePictureName);
                        } else {
                            $responseBody = [
                                'statusCode' => 422,
                                'error' => 'user-profile-picture.update_failure.invalid_image_type',
                                'message' => 'Invalid file type.'
                            ];
                            return ApiMethods::apiResponse('error', $responseBody);
                        }
                    }
                }

                $userModel->profile_picture_path = $profilePictureName;
                $operation = $userModel->save();
                $updatedUserModel = ApiUser::find($user->id);
                $token = $this->guard()->fromUser($updatedUserModel);
                $updatedUserModel['token'] = $token;
                if ($operation) {
                    $responseBody = [
                        'message' => 'Profile picture successfully updated',
                        'data' => ApiMethods::convertNullToEmptyOnUser($updatedUserModel)
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'user-profile-picture.update_failure',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }
            } else {
                $responseBody = [
                    'statusCode' => 500,
                    'error' => 'user-profile-picture.update_failure',
                    'message' => 'Something went wrong, please try again.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        }
    }

    public function updateUserCoordinates(Request $request)
    {
        $validate = $request->validate([
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ], [
            'lat.required' => 'A latitude is required',
            'lat.regex' => 'A valid latitude is required',
            'long.required' => 'A longitude is required',
            'long.regex' => 'A valid longitude is required',
        ]);

        if ($validate) {
            $user = JWTAuth::toUser();

            $userModel = ApiUser::find($user->id);
            if (isset($userModel)) {
                $userModel->lat = $request->lat;
                $userModel->long = $request->long;
                $operation = $userModel->save();
                if ($operation) {
                    $responseBody = [
                        'message' => 'Location Successfully updated',
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'location.update_failure',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }
            } else {
                $responseBody = [
                    'statusCode' => 500,
                    'error' => 'location.update_failure',
                    'message' => 'Something went wrong, please try again.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        }
    }

    public function getUserOrderHistory(Request $request)
    {
        $user = JWTAuth::toUser();

        $orders = Order::where(['api_user_id' => $user->id])->orderBy('id', 'DESC')->get();
        $orders = $this->orderDetailsCollectionModifier($orders);

        /**
        $receivedOrders = Order::where(['api_user_id' => $user->id, 'order_status'=> 'received'])->orderBy('id','DESC')->get();
        $receivedOrders = $this->orderDetailsCollectionModifier($receivedOrders);

        $processingOrders = Order::where(['api_user_id' => $user->id, 'order_status'=> 'proceeded'])->orderBy('id','DESC')->get();
        $processingOrders = $this->orderDetailsCollectionModifier($processingOrders);

        $completedOrders = Order::where(['api_user_id' => $user->id, 'order_status'=> 'completed'])->orderBy('id','DESC')->get();
        $completedOrders = $this->orderDetailsCollectionModifier($completedOrders);
         */

        $responseBody = [
            'message' => 'Order history successfully retrieved',
            'data' => $orders
        ];
        return ApiMethods::apiResponse('success', $responseBody);
    }

    private function orderDetailsCollectionModifier($collection)
    {
        $orderHistoryList = $collection;
        foreach ($orderHistoryList as $key => $orderHistory) {
            $totalOrderAmount = 0;
            $orderHistoryId = $orderHistory->id;
            $hotelId = $orderHistory->hotel_id;

            //get hotel
            $hotel = Hotel::withTrashed()->find($hotelId);
            if ($hotel) {
                $hotel->email = ($hotel->email != null || $hotel->email != '') ? $hotel->email : "";
                $orderHistory['hotel_details'] = $hotel;
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
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard('api-user');
    }
}
