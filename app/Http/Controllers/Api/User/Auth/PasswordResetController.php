<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Jobs\ApiSendUserPasswordResetMail;
use App\Models\ApiUser;
use App\Models\ApiUserPasswordReset;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function sendPasswordResetMail(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'The email is required',
            'email.email' => 'The email entered must be of a proper format'
        ]);

        if ($validate) {
            $email = $request->email;
            $user = ApiUser::where('email', $email)->first();
            if (isset($user)) {

                $checkToken = ApiUserPasswordReset::where('email', $email)->first();
                if (isset($checkToken)) {
                    $this->sendMail($user, $checkToken);

                    $responseBody = [
                        'message' => 'Password reset mail successfully sent, please check your mail.',
                    ];
                    return ApiMethods::apiResponse('success', $responseBody);


                } else {
                    $newToken = new ApiUserPasswordReset();
                    $newToken->email = $email;
                    $newToken->token = str_random(60);
                    $operation = $newToken->save();
                    if ($operation) {

                        $this->sendMail($user, $newToken);

                        $responseBody = [
                            'message' => 'Password reset mail successfully sent, please check your mail.',
                        ];
                        return ApiMethods::apiResponse('success', $responseBody);
                    } else {

                        $responseBody = [
                            'statusCode' => 422,
                            'error' => 'password-reset.error',
                            'message' => 'Something went wrong, please try again.'
                        ];
                        return ApiMethods::apiResponse('error', $responseBody);
                    }
                }

            } else {
                $responseBody = [
                    'statusCode' => 422,
                    'error' => 'password-reset.no_user',
                    'message' => 'A user with this email address doesn\'t exist'
                ];
                return ApiMethods::apiResponse('error', $responseBody);
            }
        }
    }

    public function resetPasswordForm(Request $request, $token)
    {
        $checkToken = ApiUserPasswordReset::where('token', $token)->first();
        if ($checkToken) {
            return view('pages.api-password-reset.api-user-update-password', compact('token'));
        } else {
            return view('pages.api-password-reset.invalid-token-error');
        }
    }

    public function resetPasswordUpdate(Request $request, $token)
    {
        $password = $request->password;
        $confirmPassword = $request->input('confirm-password');

        if (($password == null || $password == "") || ($confirmPassword == null || $confirmPassword == "")) {
            $error = 'required';
            return view('pages.api-password-reset.password-error', compact('error'));
        } else if ((strlen($password) < 6) || (strlen($confirmPassword) < 6)) {
            $error = 'length';
            return view('pages.api-password-reset.password-error', compact('error'));
        } else if ($password != $confirmPassword) {
            $error = 'confirm';
            return view('pages.api-password-reset.password-error', compact('error'));
        } else {
            $checkToken = ApiUserPasswordReset::where('token', $token)->first();
            if ($checkToken) {
                $email = $checkToken->email;

                $user = ApiUser::where('email', $email)->first();

                $user->password = bcrypt($request->password);
                $operation = $user->save();
                if ($operation) {

                    $checkToken->forceDelete();

                    return view('pages.api-password-reset.password-reset-success');
                } else {
                    return view('pages.api-password-reset.general-error');
                }

            } else {
                return view('pages.api-password-reset.invalid-token-error');
            }
        }

    }

    public function sendMail($user, $resetData)
    {
        $detail['name'] = $user->name;
        $detail['email'] = $resetData->email;
        $detail['token'] = $resetData->token;

        $this->dispatch(new ApiSendUserPasswordResetMail($detail));
    }
}
