<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRegisterRequest;
use App\Models\ApiUser;
use DateTime;
use Exception;
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
        $this->middleware('auth:api-user', ['except' => ['login', 'register', 'verifyVerificationCode']]);
    }

    public function register(ApiRegisterRequest $request)
    {
        $checkUserMobile = ApiUser::where('mobile', $request->mobile)->first();
        $checkUserEmail = ApiUser::where('email', $request->email)->first();
        if ($checkUserMobile) {

            $responseBody = [
                'statusCode' => 422,
                'error' => 'register.user_exists',
                'message' => 'User already exists, please try logging in.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);

        } elseif ($checkUserEmail) {

            $responseBody = [
                'statusCode' => 422,
                'error' => 'register.email_taken',
                'message' => 'This email is already taken, please try a different one.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);

        } else {
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

            try {
                $date = new DateTime("NOW");
            } catch (Exception $e) {
            }
            $verification_code = null;

            $user = new ApiUser();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = bcrypt($request->password);
            $user->password_plain = $request->password;
            $user->address = $request->address;
            $user->lat = $request->lat;
            $user->long = $request->long;
            $user->verification_code = $verification_code;
            $user->profile_picture_path = $profilePictureName;
            $user->active = 1;
            $user->fcm_token = $request->fcm_token;
            $success = $user->save();
            if ($success) {
                $data = ApiUser::find($user->id);

                $token = $this->guard()->fromUser($data);
                $data['token'] = $token;
                $data = ApiMethods::convertNullToEmptyOnUser($data);

                $responseBody = [
                    'message' => 'Thank you for registering with us',
                    'data' => $data
                ];
                return ApiMethods::apiResponse('success', $responseBody);
            } else {
                $responseBody = [
                    'statusCode' => 500,
                    'error' => 'register.failure',
                    'message' => 'Something went wrong, please try again later.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        }
    }

    /**
     * Verify the user
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function verifyVerificationCode(Request $request)
    {
        $validate = $request->validate([
            'mobile' => 'required|numeric|digits_between:10,13',
            'code' => 'required|numeric',
        ], [
            'mobile.required' => 'A mobile number is required',
            'mobile.digits_between' => 'Mobile must be of at least 10 - 13 digits',
            'code.required' => 'Verification code is required',
            'code.numeric' => 'Verification code must be numeric',
        ]);

        if ($validate) {
            $check = ApiUser::where(['mobile' => $request->mobile, 'verification_code' => $request->code])->first();
            if ($check) {

                $check->verification_code = null;
                $check->active = 1;
                $operation = $check->save();
                if ($operation) {

                    $data = $check->toArray();
                    $data = ApiMethods::convertNullToEmptyOnUser($data);

                    $token = $this->guard()->fromUser($check);

                    $data['token'] = $token;

                    $responseBody = [
                        'message' => 'Welcome to Store app.',
                        'data' => $data
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);
                } else {
                    $responseBody = [
                        'statusCode' => 500,
                        'error' => 'verify.activation_failure',
                        'message' => 'Something went wrong, please try again.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }

            } else {
                $responseBody = [
                    'statusCode' => 422,
                    'error' => 'verify.wrong_otp',
                    'message' => 'The otp you entered is wrong, please enter a proper one.'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        } else {
            $responseBody = [
                'statusCode' => 500,
                'error' => 'verify.validation_failed',
                'message' => 'Something went wrong, please try again.'
            ];
            return ApiMethods::apiResponse('error', $responseBody);
        }
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
        $lat = $request->input('lat');
        $long = $request->input('long');

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
            $checkUser = ApiUser::where('mobile', $mobile)->first();
            $attemptLogin = $this->guard()->attempt(['mobile' => $mobile, 'password' => $password, 'active' => 1]);
            if ($attemptLogin) {
                $user = $this->guard()->user();

                $regex = '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/';
                //update lat long
                if (isset($lat) && isset($long) && preg_match_all($regex, $lat) && preg_match_all($regex, $long)) {
                    $user->lat = $lat;
                    $user->long = $long;
                }

                // Update Fcm token in table
                $user->fcm_token = $fcmToken;
                $user->save();

                $user = ApiMethods::convertNullToEmptyOnUser($user);
                // Give server token to app
                $data = $user->toArray();
                $data['token'] = $attemptLogin;

                $responseBody = [
                    'message' => 'Welcome back.',
                    'data' => $data
                ];
                return ApiMethods::apiResponse('success', $responseBody);
            } else {
                if (!empty($checkUser)) {
                    $checkUserPassword = $this->guard()->attempt(['mobile' => $mobile, 'password' => $password]);
                    if ($checkUserPassword) {
                        $responseBody = [
                            'statusCode' => 422,
                            'error' => 'login.user_not_verified',
                            'message' => 'You have not verified yourself, please verify then try again.'
                        ];
                        return ApiMethods::apiResponse('error', $responseBody);
                    } else {
                        $responseBody = [
                            'statusCode' => 401,
                            'error' => 'login.invalid_credentials',
                            'message' => 'Mobile number and password combination is incorrect.'
                        ];
                        return ApiMethods::apiResponse('error', $responseBody);
                    }
                } else {
                    $responseBody = [
                        'statusCode' => 404,
                        'error' => 'login.user_does_not_exist',
                        'message' => 'This user does not exist, please register.'
                    ];
                    return ApiMethods::apiResponse('error', $responseBody);
                }

            }
        } else {
            $responseBody = [
                'statusCode' => 500,
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
        return Auth::guard('api-user');
    }
}
