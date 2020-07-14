<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemCategoryRequest;
use App\Models\MenuItemCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class MenuItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menuItemCategoryList = MenuItemCategory::orderBy('id','DESC')->get();
        return view('pages.admin.menu-item-category.index', compact('menuItemCategoryList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.admin.menu-item-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MenuItemCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(MenuItemCategoryRequest $request)
    {
        $categoryName = $request->input('category-name');

        //store in database
        $newMenuItemCategory = new MenuItemCategory();
        $newMenuItemCategory->category_name = $categoryName;

        $categoryImage = $request->file('category-image');
        if ($request->hasFile('category-image') && isset($categoryImage)) {

            if ($categoryImage->isValid()) {

                $categoryImageOriginalExtension = $categoryImage->getClientOriginalExtension();
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                if (in_array($categoryImageOriginalExtension, $allowedExtensions)) {
                    $categoryImageName = 'category_picture_' . time() . '.' . $categoryImageOriginalExtension;
                    $categoryImage->move(public_path('assets/images/menu-item-category-images'), $categoryImageName);

                    $newMenuItemCategory->category_photo = $categoryImageName;
                } else {
                    $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                    $request->session()->flash('flash_notification.level', 'danger');
                    return redirect()->route('admin.menu-item-category.create')->withInput();
                }
            }
        }

        $operationStatus = $newMenuItemCategory->save();

        if ($operationStatus) {
            $request->session()->flash('flash_notification.message', 'Menu item category successfully created.');
            $request->session()->flash('flash_notification.level', 'success');
            return redirect()->route('admin.menu-item-category.index');

        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.menu-item-category.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MenuItemCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(MenuItemCategoryRequest $request, $id)
    {
        $item = MenuItemCategory::find($id);
        if (isset($item)) {
            return view('pages.admin.menu-item-category.edit', compact('item'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.menu-item-category.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MenuItemCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function update(MenuItemCategoryRequest $request, $id)
    {
        $updateItem = MenuItemCategory::find($id);
        if (isset($updateItem)) {

            $updateItem->category_name = $request->input('category-name');

            $categoryImage = $request->file('category-image');
            if ($request->hasFile('category-image') && isset($categoryImage)) {

                if ($categoryImage->isValid()) {

                    $categoryImageOriginalExtension = $categoryImage->getClientOriginalExtension();
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                    if (in_array($categoryImageOriginalExtension, $allowedExtensions)) {
                        $categoryImageName = 'category_picture_' . time() . '.' . $categoryImageOriginalExtension;
                        $categoryImage->move(public_path('assets/images/menu-item-category-images'), $categoryImageName);

                        $updateItem->category_photo = $categoryImageName;
                    } else {
                        $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect()->route('admin.menu-item-category.edit', $id)->withInput();
                    }
                }
            }

            $operationStatus = $updateItem->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item category successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.menu-item-category.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.menu-item-category.edit', $id)->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.menu-item-category.edit', $id)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuItemCategoryRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(MenuItemCategoryRequest $request, $id)
    {
        $item = MenuItemCategory::find($id);
        if (isset($item)) {

            $operationStatus = $item->delete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item category successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.menu-item-category.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.menu-item-category.index');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.menu-item-category.index');
        }
    }

    /**
     * Display the deleted resources
     *
     * @return Response
     */

    public function showDeleted()
    {
        $menuItemCategoryList = MenuItemCategory::onlyTrashed()->orderBy('id','DESC')->get();
        return view('pages.admin.menu-item-category.deleted', compact('menuItemCategoryList'));
    }

    /**
     * Restore the selected resource
     *
     * @param MenuItemCategoryRequest $request
     * @param $id
     * @return RedirectResponse
     */

    public function restoreDeleted(MenuItemCategoryRequest $request, $id)
    {

        $item = MenuItemCategory::withTrashed()->find($id);
        if (isset($item)) {

            $operationStatus = $item->restore();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item category successfully restored. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.menu-item-category.deleted.show');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.menu-item-category.deleted.show');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.menu-item-category.deleted.show');
        }
    }
}
