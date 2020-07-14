<?php

namespace App\Http\Controllers\Web\Hotel\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelProfileRequest;
use App\Models\Hotel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param HotelProfileRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(HotelProfileRequest $request)
    {
        $item = Hotel::find(Auth::user()->id);
        if (isset($item)) {
            return view('pages.hotel.profile.edit', compact('item'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('hotel.dashboard.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HotelProfileRequest $request
     * @return Response
     */
    public function update(HotelProfileRequest $request)
    {
        $hotel = Hotel::find(Auth::user()->id);
        if (isset($hotel)) {

            $name = $request->input('name');
            $email = $request->input('email');
            $mobile = $request->input('mobile');
            $address = $request->input('address');
            $lat = $request->input('lat');
            $long = $request->input('long');
            $password = $request->input('password');

            //store in database
            $hotel->name = $name;
            $hotel->email = $email;
            $hotel->mobile = $mobile;
            if (isset($password) && $password != null && $password != "") {
                $hotel->password = bcrypt($password);
                $hotel->password_plain = $password;
            }
            $hotel->address = $address;
            $hotel->lat = $lat;
            $hotel->long = $long;

            $hotelImage = $request->file('hotel-image');
            $hotelImageName = null;
            if ($request->hasFile('hotel-image') && isset($hotelImage)) {

                if ($hotelImage->isValid()) {

                    $hotelImageOriginalExtension = $hotelImage->getClientOriginalExtension();
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                    if (in_array($hotelImageOriginalExtension, $allowedExtensions)) {
                        $hotelImageName = 'hotel_picture_' . time() . '.' . $hotelImageOriginalExtension;
                        $hotelImage->move(public_path('assets/images/hotel-images'), $hotelImageName);
                    } else {
                        $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect()->route('hotel.profile.edit')->withInput();
                    }
                }
            }
            $hotel->hotel_image = $hotelImageName;

            $operationStatus = $hotel->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('hotel.order.proceeded');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('hotel.profile.edit')->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('hotel.profile.edit')->withInput();
        }
    }
}
