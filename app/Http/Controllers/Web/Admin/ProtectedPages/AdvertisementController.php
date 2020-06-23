<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementRequest;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $advertisementList = Advertisement::orderBy('id','DESC')->get();
        return view('pages.admin.advertisement.index', compact('advertisementList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.admin.advertisement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdvertisementRequest $request
     * @return RedirectResponse
     */
    public function store(AdvertisementRequest $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');

        //store in database
        $newAdvertisement = new Advertisement();
        $newAdvertisement->title = $title;
        $newAdvertisement->description = $description;

        $advertisementImage = $request->file('image');
        if ($request->hasFile('image') && isset($advertisementImage)) {

            if ($advertisementImage->isValid()) {

                $advertisementImageOriginalExtension = $advertisementImage->getClientOriginalExtension();
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                if (in_array($advertisementImageOriginalExtension, $allowedExtensions)) {
                    $advertisementImageName = 'advertisement_image_' . time() . '.' . $advertisementImageOriginalExtension;
                    $advertisementImage->move(public_path('assets/images/advertisement-images'), $advertisementImageName);

                    $newAdvertisement->image = $advertisementImageName;
                } else {
                    $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                    $request->session()->flash('flash_notification.level', 'danger');
                    return redirect()->route('admin.advertisement.create')->withInput();
                }
            }
        }

        $operationStatus = $newAdvertisement->save();

        if ($operationStatus) {
            $request->session()->flash('flash_notification.message', 'Menu item category successfully created.');
            $request->session()->flash('flash_notification.level', 'success');
            return redirect()->route('admin.advertisement.index');

        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.advertisement.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvertisementRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(AdvertisementRequest $request, $id)
    {
        $item = Advertisement::find($id);
        if (isset($item)) {
            return view('pages.admin.advertisement.edit', compact('item'));
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.advertisement.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdvertisementRequest $request
     * @param int $id
     * @return Response
     */
    public function update(AdvertisementRequest $request, $id)
    {
        $updateItem = Advertisement::find($id);
        if (isset($updateItem)) {

            $updateItem->title = $request->input('title');
            $updateItem->description = $request->input('description');

            $advertisementImage = $request->file('image');
            if ($request->hasFile('image') && isset($advertisementImage)) {

                if ($advertisementImage->isValid()) {

                    $advertisementImageOriginalExtension = $advertisementImage->getClientOriginalExtension();
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

                    if (in_array($advertisementImageOriginalExtension, $allowedExtensions)) {
                        $advertisementImageName = 'advertisement_image_' . time() . '.' . $advertisementImageOriginalExtension;
                        $advertisementImage->move(public_path('assets/images/advertisement-images'), $advertisementImageName);

                        $updateItem->image = $advertisementImageName;
                    } else {
                        $request->session()->flash('flash_notification.message', 'Image must be a png, jpg, jpeg, gif or bmp image. ');
                        $request->session()->flash('flash_notification.level', 'danger');
                        return redirect()->route('admin.advertisement.edit', $id)->withInput();
                    }
                }
            }

            $operationStatus = $updateItem->save();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item category successfully updated. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.advertisement.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.advertisement.edit', $id)->withInput();
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.advertisement.edit', $id)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvertisementRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(AdvertisementRequest $request, $id)
    {
        $item = Advertisement::find($id);
        if (isset($item)) {

            $operationStatus = $item->delete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item category successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.advertisement.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.advertisement.index');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.advertisement.index');
        }
    }

    /**
     * Display the deleted resources
     *
     * @return Response
     */

    public function showDeleted()
    {
        $advertisementList = Advertisement::onlyTrashed()->orderBy('id','DESC')->get();
        return view('pages.admin.advertisement.deleted', compact('advertisementList'));
    }

    /**
     * Restore the selected resource
     *
     * @param AdvertisementRequest $request
     * @param $id
     * @return RedirectResponse
     */

    public function restoreDeleted(AdvertisementRequest $request, $id)
    {

        $item = Advertisement::withTrashed()->find($id);
        if (isset($item)) {

            $operationStatus = $item->restore();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'Menu item category successfully restored. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.advertisement.deleted.show');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.advertisement.deleted.show');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.advertisement.deleted.show');
        }
    }
}
