<?php

namespace App\Http\Controllers\Api\Hotel\ProtectedPages;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api-hotel');
    }

    public function editHotelProfileDetail(Request $request)
    {
        $hotel = JWTAuth::toUser();

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

            $checkHotelMobile = Hotel::where([['mobile', '=', $request->mobile], ['id', '<>', $hotel->id]])->first();
            $checkHotelEmail = Hotel::where([['email', '=', $request->email], ['id', '<>', $hotel->id]])->first();

            if ($checkHotelMobile) {

                $responseBody = [
                    'statusCode' => 422,
                    'error' => 'hotel-profile.mobile_taken',
                    'message' => 'This mobile number is already taken, please try a different one.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);

            } elseif ($checkHotelEmail) {

                $responseBody = [
                    'statusCode' => 422,
                    'error' => 'hotel-profile.email_taken',
                    'message' => 'This email is already taken, please try a different one.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);

            } else {
                $hotelModel = Hotel::find($hotel->id);
                if (isset($hotelModel)) {


                    if ($request->name) {
                        $hotelModel->name = $request->name;
                    }
                    if ($request->email) {
                        $hotelModel->email = $request->email;
                    }
                    if ($request->mobile) {
                        $hotelModel->mobile = $request->mobile;
                    }
                    if ($request->address) {
                        $hotelModel->address = $request->address;
                    }
                    if ($request->password) {
                        $hotelModel->password = bcrypt($request->password);
                        $hotelModel->password_plain = $request->password;
                    }

                    //profile_picture_path
                    $profilePicture = $request->file('profile-picture');
                    if ($request->hasFile('profile-picture') && isset($profilePicture)) {

                        if ($profilePicture->isValid()) {

                            $profilePictureOriginalExtension = $profilePicture->getClientOriginalExtension();
                            $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                            if (in_array($profilePictureOriginalExtension, $allowedExtensions)) {
                                $profilePictureName = 'profile_picture_' . time() . '.' . $profilePictureOriginalExtension;
                                $profilePicture->move(public_path('assets/images/hotel-images'), $profilePictureName);

                                $hotelModel->hotel_image = $profilePictureName;
                            } else {
                                $responseBody = [
                                    'statusCode' => 422,
                                    'error' => 'hotel-profile.update_failure.invalid_image_type',
                                    'message' => 'Invalid file type.'
                                ];
                                return ApiMethods::apiResponse('error', $responseBody);
                            }
                        }
                    }

                    // Coordinates
                    if ($request->lat) {
                        $hotelModel->lat = $request->lat;
                    }
                    if ($request->long) {
                        $hotelModel->long = $request->long;
                    }

                    $operation = $hotelModel->save();
                    $updatedHotelModel = Hotel::find($hotel->id);

                    $token = $this->guard()->fromUser($updatedHotelModel);
                    $updatedHotelModel['token'] = $token;
                    if ($operation) {
                        $responseBody = [
                            'message' => 'Store details successfully updated',
                            'data' => ApiMethods::convertNullToEmptyOnHotel($updatedHotelModel)
                        ];
                        return ApiMethods::apiResponse('success', $responseBody);
                    } else {
                        $responseBody = [
                            'statusCode' => 500,
                            'error' => 'hotel-profile.update_failure',
                            'message' => 'Something went wrong, please try again.'
                        ];
                        return ApiMethods::apiResponse('error', $responseBody);
                    }
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'hotel-profile.update_failure',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }
            }
        }
    }

    public function updateHotelProfilePicture(Request $request)
    {
        $hotel = JWTAuth::toUser();

        $validate = $request->validate([
            'profile-picture' => 'sometimes|required'
        ], [
            'profile-picture.required' => 'A profile picture is required',
        ]);

        if ($validate) {
            $hotelModel = Hotel::find($hotel->id);
            if (isset($hotelModel)) {

                //profile_picture_path
                $profilePicture = $request->file('profile-picture');
                $profilePictureName = null;
                if ($request->hasFile('profile-picture') && isset($profilePicture)) {

                    if ($profilePicture->isValid()) {

                        $profilePictureOriginalExtension = $profilePicture->getClientOriginalExtension();
                        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                        if (in_array($profilePictureOriginalExtension, $allowedExtensions)) {
                            $profilePictureName = 'profile_picture_' . time() . '.' . $profilePictureOriginalExtension;
                            $profilePicture->move(public_path('assets/images/hotel-images'), $profilePictureName);
                        } else {
                            $responseBody = [
                                'statusCode' => 422,
                                'error' => 'hotel-profile-picture.update_failure.invalid_image_type',
                                'message' => 'Invalid file type.'
                            ];
                            return ApiMethods::apiResponse('error', $responseBody);
                        }
                    }
                }

                $hotelModel->hotel_image = $profilePictureName;
                $operation = $hotelModel->save();
                $updatedHotelModel = Hotel::find($hotel->id);

                $token = $this->guard()->fromUser($updatedHotelModel);
                $updatedHotelModel['token'] = $token;
                if ($operation) {
                    $responseBody = [
                        'message' => 'Profile picture successfully updated',
                        'data' => ApiMethods::convertNullToEmptyOnHotel($updatedHotelModel)
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'hotel-profile-picture.update_failure',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }
            } else {
                $responseBody = [
                    'statusCode' => 500,
                    'error' => 'hotel-profile-picture.update_failure',
                    'message' => 'Something went wrong, please try again.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        }
    }

    public function updateHotelCoordinates(Request $request)
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
            $hotel = JWTAuth::toUser();

            $hotelModel = Hotel::find($hotel->id);
            if (isset($hotelModel)) {
                $hotelModel->lat = $request->lat;
                $hotelModel->long = $request->long;
                $operation = $hotelModel->save();
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

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard('api-hotel');
    }
}
