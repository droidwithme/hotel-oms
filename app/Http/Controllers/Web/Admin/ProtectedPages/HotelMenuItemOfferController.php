<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemOfferRequest;
use App\Models\MenuItem;
use App\Models\MenuItemOffer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HotelMenuItemOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $productId
     * @return Response
     */
    public function index($hotelId, $productId)
    {
        $menuItemOfferList = MenuItemOffer::where(['product_id' => $productId, 'hotel_id' => $hotelId])->orderBy('id', 'DESC')->get();
        $menuItem = MenuItem::find($productId);
        return view('pages.admin.product-offer.index', compact('menuItemOfferList', 'menuItem', 'hotelId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param $productId
     * @return Response
     */
    public function create(Request $request, $hotelId, $productId)
    {
        $menuItemOfferList = MenuItemOffer::where(['product_id' => $productId, 'hotel_id' => $hotelId])->get();
        if ($menuItemOfferList->count() > 0) {
            $request->session()->flash('flash_notification.message', 'Only one offer per product is allowed');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
        } else {
            return view('pages.admin.product-offer.create', compact('hotelId', 'productId'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MenuItemOfferRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuItemOfferRequest $request, $hotelId, $productId)
    {
        $menuItemOfferList = MenuItemOffer::where(['product_id' => $productId, 'hotel_id' => $hotelId])->get();
        if ($menuItemOfferList->count() > 0) {
            $request->session()->flash('flash_notification.message', 'Only one offer per product is allowed');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
        } else {
            $offerName = $request->input('offer-name');
            $offerDescription = $request->input('offer-description');
            $offerType = $request->input('offer-type');
            $offerTypeDetail = $request->input('offer-type-detail');

            //store in database
            $newOffer = new MenuItemOffer();
            $newOffer->hotel_id = $hotelId;
            $newOffer->product_id = $productId;
            $newOffer->offer_name = $offerName;
            $newOffer->offer_description = $offerDescription;
            $newOffer->offer_type = $offerType;

            if ($offerType == '0' || $offerType == '1') {
                $newOffer->discount_amount = $offerTypeDetail;
            } else if ($offerType == '2') {
                $newOffer->products_free_quantity = $offerTypeDetail;
            }
            $operationStatus = $newOffer->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Product item offer successfully created.');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MenuItemOfferRequest $request
     * @param $productId
     * @param  int $id
     * @return Response
     */
    public function edit(MenuItemOfferRequest $request, $hotelId, $productId, $id)
    {
        $item = MenuItemOffer::where(['id' => $id, 'product_id' => $productId, 'hotel_id' => $hotelId])->first();
        if (isset($item)) {
            return view('pages.admin.product-offer.edit', compact('item', 'hotelId', 'productId'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MenuItemOfferRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(MenuItemOfferRequest $request, $hotelId, $productId, $id)
    {
        $updateItem = MenuItemOffer::where(['id' => $id, 'product_id' => $productId, 'hotel_id' => $hotelId])->first();
        if (isset($updateItem)) {

            $offerName = $request->input('offer-name');
            $offerDescription = $request->input('offer-description');
            $offerType = $request->input('offer-type');
            $offerTypeDetail = $request->input('offer-type-detail');

            $updateItem->offer_name = $offerName;
            $updateItem->offer_description = $offerDescription;
            $updateItem->offer_type = $offerType;

            if ($offerType == '0' || $offerType == '1') {
                $updateItem->discount_amount = $offerTypeDetail;
                $updateItem->products_free_quantity = null;
            } else if ($offerType == '2') {
                $updateItem->discount_amount = null;
                $updateItem->products_free_quantity = $offerTypeDetail;
            }

            $operationStatus = $updateItem->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Product item offer successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.menu-item.offer.edit', ['hotelId' => $hotelId, 'productId' => $productId, 'id' => $productId])->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.offer.edit', ['hotelId' => $hotelId, 'productId' => $productId, 'id' => $productId])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuItemOfferRequest $request
     * @param $productId
     * @param  int $id
     * @return Response
     */
    public function destroy(MenuItemOfferRequest $request, $hotelId, $productId, $id)
    {
        $item = MenuItemOffer::find($id);
        if (isset($item)) {

            $operationStatus = $item->forceDelete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Product item offer successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.offer.index', ['hotelId' => $hotelId, 'productId' => $productId]);
        }
    }
}
