<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Models\Hotel;
use App\Models\MenuItem;
use App\Models\MenuItemCategory;
use App\Models\MenuItemImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class HotelMenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $hotelId
     * @return Response
     */
    public function index($hotelId)
    {
        $hotel = Hotel::find($hotelId);
        $menuItemList = MenuItem::where('hotel_id', $hotelId)->orderBy('id','DESC')->get();
        foreach ($menuItemList as $item) {
            $menuItemCategoryId = $item->item_category;
            $menuItemId = $item->id;

            $menuItemCategory = MenuItemCategory::find($menuItemCategoryId);
            if ($menuItemCategory) {
                $item['menu_category_name'] = $menuItemCategory->category_name;
            } else {
                $item['menu_category_name'] = "N/A";
            }

            $menuItemImages = MenuItemImage::where('item_id', $menuItemId)->get();
            if($menuItemImages->isNotEmpty()) {
                $menuItemFirstImage = $menuItemImages->first();
                $item['item_photo'] = $menuItemFirstImage->item_photo;
                $item['item_extra_photo_count'] = ($menuItemImages->count() > 0) ? $menuItemImages->count() - 1 : 0;
            } else {
                $item['item_photo'] = null;
                $item['item_extra_photo_count'] = 0;
            }
        }
        return view('pages.admin.menu-item.index', compact('hotel', 'menuItemList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $hotelId
     * @return Response
     */
    public function create($hotelId)
    {
        $itemCategoryList = MenuItemCategory::orderBy('id','DESC')->get();
        return view('pages.admin.menu-item.create', compact('hotelId', 'itemCategoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MenuItemRequest $request
     * @param $hotelId
     * @return RedirectResponse
     */
    public function store(MenuItemRequest $request, $hotelId)
    {
        $itemCategory = $request->input('item-category');
        $itemName = $request->input('item-name');
        $itemIngredients = $request->input('item-ingredients');
        $itemPrice = $request->input('item-price');

        //store in database
        $newMenuItem = new MenuItem();
        $newMenuItem->hotel_id = $hotelId;
        $newMenuItem->item_title = $itemName;
        $newMenuItem->item_ingredients = $itemIngredients;
        $newMenuItem->item_price = $itemPrice;
        $newMenuItem->item_category = $itemCategory;
        $operationStatus = $newMenuItem->save();

        $itemPhotos = $request->file('item-photos');
        if ($request->hasFile('item-photos') && isset($itemPhotos)) {

            foreach ($itemPhotos as $key=>$itemPhoto) {

                $time = time().mt_rand();
                if ($itemPhoto->isValid()) {

                    $newItemPhotos = new MenuItemImage();
                    $newItemPhotos->item_id = $newMenuItem->id;

                    $itemImageOriginalExtension = $itemPhoto->getClientOriginalExtension();
                    echo $itemImageOriginalExtension;
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                    if (in_array($itemImageOriginalExtension, $allowedExtensions)) {
                        $itemImageName = 'item_picture_' . $time . '.' . $itemImageOriginalExtension;
                        $itemPhoto->move(public_path('assets/images/menu-item-images'), $itemImageName);

                        $newItemPhotos->item_photo = $itemImageName;
                        if (!$newItemPhotos->save()) {
                            $request->session()->flash('flash_notification.message', 'Failed to upload images');
                            $request->session()->flash('flash_notification.level', 'danger');
                            return redirect()->route('admin.hotel.menu-item.create', $hotelId)->withInput();
                        }

                    } else {
                        $request->session()->flash('flash_notification.message', 'One or more images are not valid png, jpg, jpeg, gif or bmp images. ');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect()->route('admin.hotel.menu-item.create', $hotelId)->withInput();
                    }
                }
            }
        }

        if ($operationStatus) {
            $request->session()->flash('flash_notification.message', 'Menu item successfully created.');
            $request->session()->flash('flash_notification.level', 'success');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);

        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);
        }
    }

    /**
     * Show the form for duplicating a resource.
     *
     * @param MenuItemRequest $request
     * @param $hotelId
     * @param $menuItemId
     * @return Response
     */
    public function duplicate(MenuItemRequest $request, $hotelId, $menuItemId)
    {
        $menuItem = MenuItem::find($menuItemId);
        if(isset($menuItem)) {

            $menuItemImages = MenuItemImage::where('item_id', $menuItem->id)->get();
            $menuItem['item_photo'] = $menuItemImages;

            $itemCategoryList = MenuItemCategory::orderBy('id', 'DESC')->get();
            return view('pages.admin.menu-item.duplicate', compact('hotelId', 'itemCategoryList', 'menuItem'));
        } else {
            $request->session()->flash('flash_notification.message', 'The item to be duplicated doesnt exist.');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);
        }
    }


    /**
     * Storing Duplicate
     * @param MenuItemRequest $request
     * @param $hotelId
     * @return RedirectRespons
     */
    public function storeDuplicate(MenuItemRequest $request, $hotelId)
    {
        $itemCategory = $request->input('item-category');
        $itemName = $request->input('item-name');
        $itemIngredients = $request->input('item-ingredients');
        $itemPrice = $request->input('item-price');

        //store in database
        $duplicateMenuItem = new MenuItem();
        $duplicateMenuItem->hotel_id = $hotelId;
        $duplicateMenuItem->item_title = $itemName;
        $duplicateMenuItem->item_ingredients = $itemIngredients;
        $duplicateMenuItem->item_price = $itemPrice;
        $duplicateMenuItem->item_category = $itemCategory;
        $operationStatus = $duplicateMenuItem->save();

        // If images are selected prefer them
        $itemPhotos = $request->file('item-photos');
        if ($request->hasFile('item-photos') && isset($itemPhotos)) {

            foreach ($itemPhotos as $key=>$itemPhoto) {

                $time = time().mt_rand();
                if ($itemPhoto->isValid()) {

                    $duplicatedItemPhotos = new MenuItemImage();
                    $duplicatedItemPhotos->item_id = $duplicateMenuItem->id;

                    $itemImageOriginalExtension = $itemPhoto->getClientOriginalExtension();
                    echo $itemImageOriginalExtension;
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                    if (in_array($itemImageOriginalExtension, $allowedExtensions)) {
                        $itemImageName = 'item_picture_' . $time . '.' . $itemImageOriginalExtension;
                        $itemPhoto->move(public_path('assets/images/menu-item-images'), $itemImageName);

                        $duplicatedItemPhotos->item_photo = $itemImageName;
                        if (!$duplicatedItemPhotos->save()) {
                            $request->session()->flash('flash_notification.message', 'Failed to duplicate menu item');
                            $request->session()->flash('flash_notification.level', 'danger');
                            return redirect()->route('admin.hotel.menu-item.index', $hotelId)->withInput();
                        }

                    } else {
                        $request->session()->flash('flash_notification.message', 'Failed to duplicate menu item.');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect()->route('admin.hotel.menu-item.index', $hotelId)->withInput();
                    }
                }
            }
        }
        // Else prefer duplicated images
        else if(sizeof($request->input('duplicated-photos')) > 0){

            $duplicatedPhotos = $request->input('duplicated-photos');
            foreach ($duplicatedPhotos as $duplicatedPhoto){
                $duplicatedItemPhotos = new MenuItemImage();
                $duplicatedItemPhotos->item_id = $duplicateMenuItem->id;
                $duplicatedItemPhotos->item_photo = $duplicatedPhoto;

                if (!$duplicatedItemPhotos->save()) {
                    $request->session()->flash('flash_notification.message', 'Failed to duplicate menu item');
                    $request->session()->flash('flash_notification.level', 'danger');
                    return redirect()->route('admin.hotel.menu-item.index', $hotelId)->withInput();
                }
            }
        }

        if ($operationStatus) {
            $request->session()->flash('flash_notification.message', 'Menu item successfully duplicated.');
            $request->session()->flash('flash_notification.level', 'success');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);

        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MenuItemRequest $request
     * @param $hotelId
     * @param int $id
     * @return Response
     */
    public function edit(MenuItemRequest $request, $hotelId, $id)
    {
        $item = MenuItem::find($id);

        $menuItemImages = MenuItemImage::where('item_id', $id)->get();
        $item['item_photo'] = $menuItemImages;

        $itemCategoryList = MenuItemCategory::orderBy('id','DESC')->get();
        if (isset($item)) {
            return view('pages.admin.menu-item.edit', compact('hotelId', 'item', 'itemCategoryList'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MenuItemRequest $request
     * @param $hotelId
     * @param int $id
     * @return Response
     */
    public function update(MenuItemRequest $request, $hotelId, $id)
    {
        $updateItem = MenuItem::find($id);
        if (isset($updateItem)) {

            $itemCategory = $request->input('item-category');
            $itemName = $request->input('item-name');
            $itemIngredients = $request->input('item-ingredients');
            $itemPrice = $request->input('item-price');

            $updateItem->hotel_id = $hotelId;
            $updateItem->item_title = $itemName;
            $updateItem->item_ingredients = $itemIngredients;
            $updateItem->item_price = $itemPrice;
            $updateItem->item_category = $itemCategory;


            $itemPhotos = $request->file('item-photos');
            if ($request->hasFile('item-photos') && isset($itemPhotos)) {

                // Delete previous images
                MenuItemImage::where('item_id', $id)->forceDelete();

                foreach ($itemPhotos as $key=>$itemPhoto) {

                    $time = time().mt_rand();
                    if ($itemPhoto->isValid()) {

                        $updateItemPhotos = new MenuItemImage();
                        $updateItemPhotos->item_id = $id;

                        $itemImageOriginalExtension = $itemPhoto->getClientOriginalExtension();
                        echo $itemImageOriginalExtension;
                        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                        if (in_array($itemImageOriginalExtension, $allowedExtensions)) {
                            $itemImageName = 'item_picture_' . $time . '.' . $itemImageOriginalExtension;
                            $itemPhoto->move(public_path('assets/images/menu-item-images'), $itemImageName);

                            $updateItemPhotos->item_photo = $itemImageName;
                            if (!$updateItemPhotos->save()) {
                                $request->session()->flash('flash_notification.message', 'Failed to upload images');
                                $request->session()->flash('flash_notification.level', 'danger');
                                return redirect()->route('admin.hotel.menu-item.edit', ['hotelId' => $hotelId, 'id' => $id])->withInput();
                            }

                        } else {
                            $request->session()->flash('flash_notification.message', 'One or more images are not valid png, jpg, jpeg, gif or bmp images. ');
                            $request->session()->flash('flash_notification.level', 'danger');
                            return redirect()->route('admin.hotel.menu-item.edit', ['hotelId' => $hotelId, 'id' => $id])->withInput();
                        }
                    }
                }
            }

            $operationStatus = $updateItem->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.menu-item.index', $hotelId);
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.menu-item.edit', ['hotelId' => $hotelId, 'id' => $id])->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.edit', ['hotelId' => $hotelId, 'id' => $id])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuItemRequest $request
     * @param $hotelId
     * @param int $id
     * @return Response
     */
    public function destroy(MenuItemRequest $request, $hotelId, $id)
    {
        $item = MenuItem::find($id);
        if (isset($item)) {

            $operationStatus = $item->delete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.menu-item.index', $hotelId);
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.menu-item.index', $hotelId);
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.index', $hotelId);
        }
    }

    /**
     * Display the deleted resources
     *
     * @param $hotelId
     * @return Response
     */

    public function showDeleted($hotelId)
    {
        $hotel = Hotel::find($hotelId);
        $menuItemList = MenuItem::onlyTrashed()->where('hotel_id', $hotelId)->orderBy('id','DESC')->get();
        foreach ($menuItemList as $item) {
            $menuItemCategoryId = $item->item_category;
            $menuItemId = $item->id;

            $menuItemCategory = MenuItemCategory::find($menuItemCategoryId);
            if ($menuItemCategory) {
                $item['menu_category_name'] = $menuItemCategory->category_name;
            } else {
                $item['menu_category_name'] = "N/A";
            }

            $menuItemImages = MenuItemImage::where('item_id', $menuItemId)->get();
            if($menuItemImages->isNotEmpty()) {
                $menuItemFirstImage = $menuItemImages->first();
                $item['item_photo'] = $menuItemFirstImage->item_photo;
                $item['item_extra_photo_count'] = ($menuItemImages->count() > 0) ? $menuItemImages->count() - 1 : 0;
            } else {
                $item['item_photo'] = null;
                $item['item_extra_photo_count'] = 0;
            }
        }
        return view('pages.admin.menu-item.deleted', compact('hotel', 'menuItemList'));
    }

    /**
     * Restore the selected resource
     *
     * @param MenuItemRequest $request
     * @param $hotelId
     * @param $id
     * @return RedirectResponse
     */

    public function restoreDeleted(MenuItemRequest $request, $hotelId, $id)
    {

        $item = MenuItem::withTrashed()->find($id);
        if (isset($item)) {

            $operationStatus = $item->restore();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item successfully restored. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel.menu-item.deleted.show', $hotelId);
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel.menu-item.deleted.show', $hotelId);
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel.menu-item.deleted.show', $hotelId);
        }
    }
}
