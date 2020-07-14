<?php

namespace App\Http\Controllers\Api\Hotel\Auth;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api-hotel', ['except' => ['login', 'validateToken']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function login(Request $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $fcmToken = $request->input('fcm_token');

        $validate = $request->validate([
            'mobile' => 'required|numeric|digits_between:10,13',
            'password' => 'required|min:6',
            'fcm_token' => 'sometimes|required',
        ], [
            'mobile.required' => 'A mobile number is required',
            'mobile.digits_between' => 'Mobile must be of at least 10 - 13 digits',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be of a minimum of 6 characters',
            'fcm_token.required' => 'Fcm token is required',
        ]);

        if ($validate) {
            $checkUser = Hotel::where('mobile', $mobile)->first();
            $attemptLogin = $this->guard()->attempt(['mobile' => $mobile, 'password' => $password, 'active' => 1]);
            if ($attemptLogin) {
                $user = $this->guard()->user();

                // Update Fcm token in table
                $user->fcm_token = $fcmToken;
                $user->save();

                $user = ApiMethods::convertNullToEmptyOnHotel($user);

                // Give server token to app
                $data = $user->toArray();
                $data['token'] = $attemptLogin;

                $responseBody = [
                    'message' => 'Welcome back.',
                    'data' => $data
                ];
                return ApiMethods::apiResponse('success', $responseBody);
            } else {
                if (empty($checkUser)) {
                    $responseBody = [
                        'statusCode' => 404,
                        'error' => 'login.hotel_does_not_exist',
                        'message' => 'This store does not exist, please register.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                } else {
                    $responseBody = [
                        'statusCode' => 422,
                        'error' => 'login.unknown_error',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }

            }
        } else {
            $responseBody = [
                'statusCode' => 422,
                'error' => 'verify.validation_failed',
                'message' => 'Something went wrong, please try again.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);
        }
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        JWTAuth::parseToken()->invalidate();
        $responseBody = [
            'message' => 'Successfully logged Out',
        ];
        return ApiMethods::apiResponse('success', $responseBody);
    }

    /**
     * Check whether the token is valid
     */
    public function validateToken()
    {
        $isValid = JWTAuth::parseToken()->check();
        if ($isValid) {
            $responseBody = [
                'message' => 'valid token',
            ];
            return ApiMethods::apiResponse('success', $responseBody);
        } else {
            $responseBody = [
                'statusCode' => 401,
                'error' => 'validate_token.invalid_token',
                'message' => 'Token is invalid.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);
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
