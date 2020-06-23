<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Api\Methods\ApiMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use App\Models\HotelCategory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $hotelList = Hotel::orderBy('id','DESC')->get();
        foreach ($hotelList as $hotel) {
            $hotelCategoryName = HotelCategory::find($hotel->hotel_category);
            if ($hotelCategoryName) {
                $hotel['hotel_category_name'] = $hotelCategoryName->category_name;
            } else {
                $hotel['hotel_category_name'] = "N/A";
            }
        }
        return view('pages.admin.hotel.index', compact('hotelList'));
    }

    /**
     *Show the hotel password
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function showHotelPassword(Request $request){
        $hotel = $request->input('hotel-id');
        $password = $request->input('password');

        // Check password
        $securePass = 'secret';

        if($password === $securePass){
            // Get the password
            $password = Hotel::find($hotel);
            return response(['status' => true, 'password' => $password->password_plain]);
        } else {
            return response(['status' => false]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $hotelCategoryList = HotelCategory::orderBy('id','DESC')->get();
        return view('pages.admin.hotel.create', compact('hotelCategoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HotelRequest $request
     * @return RedirectResponse
     */
    public function store(HotelRequest $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $address = $request->input('address');
        $hotelCategory = $request->input('hotel_category');
        $lat = $request->input('lat');
        $long = $request->input('long');
        $password = $request->input('password');

        //store in database
        $hotel = new Hotel();
        $hotel->name = $name;
        $hotel->email = $email;
        $hotel->mobile = $mobile;
        $hotel->password = bcrypt($password);
        $hotel->password_plain = $password;
        $hotel->address = $address;
        $hotel->hotel_category = $hotelCategory;
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
                    return redirect()->route('admin.hotel.create')->withInput();
                }
            }
        }
        $hotel->hotel_image = $hotelImageName;

        $hotel->active = true;
        $operationStatus = $hotel->save();
        if ($operationStatus) {
            $request->session()->flash('flash_notification.message', 'Store successfully created.');
            $request->session()->flash('flash_notification.level', 'success');
            return redirect()->route('admin.hotel.index');


        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HotelRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(HotelRequest $request, $id)
    {
        $item = Hotel::find($id);
        $hotelCategoryList = HotelCategory::orderBy('id','DESC')->get();
        if (isset($item)) {
            return view('pages.admin.hotel.edit', compact('item', 'hotelCategoryList'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HotelRequest $request
     * @param int $id
     * @return Response
     */
    public function update(HotelRequest $request, $id)
    {
        $hotel = Hotel::find($id);
        if (isset($hotel)) {

            $name = $request->input('name');
            $email = $request->input('email');
            $mobile = $request->input('mobile');
            $address = $request->input('address');
            $hotelCategory = $request->input('hotel_category');
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
            $hotel->hotel_category = $hotelCategory;
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
                        return redirect()->route('admin.hotel.edit', $id)->withInput();
                    }
                }
            }
            $hotel->hotel_image = $hotelImageName;

            $operationStatus = $hotel->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.edit', $id)->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.edit', $id)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HotelRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(HotelRequest $request, $id)
    {
        $item = Hotel::find($id);
        if (isset($item)) {

            $operationStatus = $item->delete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.index');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.index');
        }
    }

    /**
     * Display the deleted resources
     *
     * @return Response
     */

    public function showDeleted()
    {
        $hotelList = Hotel::onlyTrashed()->orderBy('id','DESC')->get();
        foreach ($hotelList as $hotel) {
            $hotelCategoryName = HotelCategory::find($hotel->hotel_category);
            if ($hotelCategoryName) {
                $hotel['hotel_category_name'] = $hotelCategoryName->category_name;
            } else {
                $hotel['hotel_category_name'] = "N/A";
            }
        }
        return view('pages.admin.hotel.deleted', compact('hotelList'));
    }

    /**
     * Restore the selected resource
     *
     * @param HotelRequest $request
     * @param $id
     * @return RedirectResponse
     */

    public function restoreDeleted(HotelRequest $request, $id)
    {

        $item = Hotel::withTrashed()->find($id);
        if (isset($item)) {

            $operationStatus = $item->restore();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store successfully restored. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.deleted.show');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.deleted.show');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.deleted.show');
        }
    }
}
