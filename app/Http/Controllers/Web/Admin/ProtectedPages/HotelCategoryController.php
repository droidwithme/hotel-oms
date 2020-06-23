<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelCategoryRequest;
use App\Models\HotelCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class HotelCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $hotelCategoryList = HotelCategory::orderBy('id','DESC')->get();
        return view('pages.admin.hotel-category.index', compact('hotelCategoryList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.admin.hotel-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HotelCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(HotelCategoryRequest $request)
    {
        $categoryName = $request->input('category-name');

        //store in database
        $newHotelCategory = new HotelCategory();
        $newHotelCategory->category_name = $categoryName;

        $categoryImage = $request->file('category-image');
        if ($request->hasFile('category-image') && isset($categoryImage)) {

            if ($categoryImage->isValid()) {

                $categoryImageOriginalExtension = $categoryImage->getClientOriginalExtension();
                $allowedExtensions = ['png','jpg','jpeg','gif','bmp'];

                if(in_array($categoryImageOriginalExtension, $allowedExtensions)){
                    $categoryImageName = 'hotel_category_image_' . time() . '.' . $categoryImageOriginalExtension;
                    $categoryImage->move(public_path('assets/images/hotel-category-images'), $categoryImageName);

                    $newHotelCategory->category_image = $categoryImageName;
                } else {
                    $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                    $request->session()->flash('flash_notification.level', 'danger');
                    return redirect()->route('admin.hotel-category.create')->withInput();
                }
            }
        }

        $operationStatus = $newHotelCategory->save();

        if ($operationStatus) {
            $request->session()->flash('flash_notification.message', 'Store category successfully created.');
            $request->session()->flash('flash_notification.level', 'success');
            return redirect()->route('admin.hotel-category.index');

        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel-category.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HotelCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(HotelCategoryRequest $request, $id)
    {
        $item = HotelCategory::find($id);
        if (isset($item)) {
            return view('pages.admin.hotel-category.edit', compact('item'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel-category.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HotelCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function update(HotelCategoryRequest $request, $id)
    {
        $updateItem = HotelCategory::find($id);
        if (isset($updateItem)) {

            $updateItem->category_name = $request->input('category-name');

            $categoryImage = $request->file('category-image');
            if ($request->hasFile('category-image') && isset($categoryImage)) {

                if ($categoryImage->isValid()) {

                    $categoryImageOriginalExtension = $categoryImage->getClientOriginalExtension();
                    $allowedExtensions = ['png','jpg','jpeg','gif','bmp'];

                    if(in_array($categoryImageOriginalExtension, $allowedExtensions)){
                        $categoryImageName = 'hotel_category_image_' . time() . '.' . $categoryImageOriginalExtension;
                        $categoryImage->move(public_path('assets/images/hotel-category-images'), $categoryImageName);

                        $updateItem->category_image = $categoryImageName;
                    } else {
                        $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect()->route('admin.hotel-category.edit',$id)->withInput();
                    }
                }
            }

            $operationStatus = $updateItem->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store category successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel-category.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel-category.edit', $id)->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel-category.edit', $id)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HotelCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(HotelCategoryRequest $request, $id)
    {
        $item = HotelCategory::find($id);
        if (isset($item)) {

            $operationStatus = $item->delete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store category successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel-category.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel-category.index');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel-category.index');
        }
    }

    /**
     * Display the deleted resources
     *
     * @return Response
     */

    public function showDeleted()
    {
        $hotelCategoryList = HotelCategory::onlyTrashed()->orderBy('id','DESC')->get();
        return view('pages.admin.hotel-category.deleted', compact('hotelCategoryList'));
    }

    /**
     * Restore the selected resource
     *
     * @param HotelCategoryRequest $request
     * @param $id
     * @return RedirectResponse
     */

    public function restoreDeleted(HotelCategoryRequest $request, $id)
    {

        $item = HotelCategory::withTrashed()->find($id);
        if (isset($item)) {

            $operationStatus = $item->restore();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Store category successfully restored. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.hotel-category.deleted.show');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.hotel-category.deleted.show');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.hotel-category.deleted.show');
        }
    }
}
