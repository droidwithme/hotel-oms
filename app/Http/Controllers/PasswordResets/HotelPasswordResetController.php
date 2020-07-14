<?php

namespace App\Http\Controllers\PasswordResets;

use App\Http\Controllers\Controller;
use App\Jobs\SendHotelPasswordResetMail;
use App\Models\Hotel;
use App\Models\HotelPasswordReset;
use Illuminate\Http\Request;

class HotelPasswordResetController extends Controller
{
    public function showPasswordResetForm()
    {
        return view('pages.password-reset.hotel-request-mail');
    }

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
            $hotel = Hotel::where('email', $email)->first();
            if (isset($hotel)) {

                $checkToken = HotelPasswordReset::where('email', $email)->first();
                if (isset($checkToken)) {
                    $this->sendMail($hotel, $checkToken);

                    $request->session()->flash('flash_notification.message', 'Password reset link was successfully sent, please check your email. ');
                    $request->session()->flash('flash_notification.level', 'success');
                    return redirect('/login');
                } else {
                    $newToken = new HotelPasswordReset();
                    $newToken->email = $email;
                    $newToken->token = str_random(60);
                    $operation = $newToken->save();
                    if ($operation) {

                        $this->sendMail($hotel, $newToken);

                        $request->session()->flash('flash_notification.message', 'Password reset link was successfully sent, please check your email. ');
                        $request->session()->flash('flash_notification.level', 'success');
                        return redirect('/login');
                    } else {
                        $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect('/login');
                    }
                }

            } else {
                return redirect()->route('hotel.auth.forgot-password')->withInput()->withErrors(['email' => 'not found']);
            }
        }
    }

    public function resetPasswordForm(Request $request, $token)
    {
        $checkToken = HotelPasswordReset::where('token', $token)->first();
        if ($checkToken) {
            return view('pages.password-reset.hotel-update-password', compact('token'));
        } else {
            $request->session()->flash('flash_notification.message', 'Invalid or expired reset token, please try again. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect('/login');
        }
    }

    public function resetPasswordUpdate(Request $request, $token)
    {
        $validate = $request->validate([
            'password' => 'required|min:6',
            'confirm-password' => 'required|min:6|same:password'
        ], [
            'password.required' => 'A password is required',
            'password.min' => 'The password entered must be of at least 6 characters',

            'confirm-password.required' => 'A confirm password is required',
            'confirm-password.min' => 'The confirm password entered must be of at least 6 characters',
            'confirm-password.same' => 'The confirm password entered must match the password',
        ]);

        if ($validate) {
            $checkToken = HotelPasswordReset::where('token', $token)->first();
            if ($checkToken) {
                $email = $checkToken->email;

                $hotel = Hotel::where('email', $email)->first();

                $hotel->password = bcrypt($request->password);
                $operation = $hotel->save();
                if ($operation) {

                    $checkToken->forceDelete();

                    $request->session()->flash('flash_notification.message', 'Password was successfully reset, please try loggin in. ');
                    $request->session()->flash('flash_notification.level', 'success');
                    return redirect('/login');
                } else {
                    $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                    $request->session()->flash('flash_notification.level', 'danger');
                    return redirect('/login');
                }

            } else {
                $request->session()->flash('flash_notification.message', 'Invalid reset token, please try again. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect('/login');
            }
        }
    }

    public function sendMail($hotel, $resetData)
    {
        $detail['name'] = $hotel->name;
        $detail['email'] = $resetData->email;
        $detail['token'] = $resetData->token;

        $this->dispatch(new SendHotelPasswordResetMail($detail));
    }
}
