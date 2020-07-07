<?php

namespace App\Http\Controllers\Web\Admin\ProtectedPages;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiUserRequest;
use App\Models\ApiUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class ApiUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $apiUserList = ApiUser::orderBy('id', 'DESC')->get();
        return view('pages.admin.app-user.index', compact('apiUserList'));
    }

    /**
     *Show the App User password
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function showAppUserPassword(Request $request)
    {
        $appUser = $request->input('app-user-id');
        $password = $request->input('password');

        // Check password
        $securePass = env('STORE_PASSWORD_MASTER_PASSWORD', 'secret');

        if ($password === $securePass) {
            // Get the password
            $password = ApiUser::find($appUser);
            return response(['status' => true, 'password' => $password->password_plain]);
        } else {
            return response(['status' => false]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param ApiUserRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(ApiUserRequest $request, $id)
    {
        $item = ApiUser::find($id);
        if (isset($item)) {

            $operationStatus = $item->forceDelete();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'App user successfully deleted. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.app-user.index');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.app-user.index');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.app-user.index');
        }
    }

    /**
     * Display the deleted resources
     *
     * @return Response
     */

    public function showDeleted()
    {
        $apiUserList = ApiUser::onlyTrashed()->get();
        return view('pages.admin.app-user.deleted', compact('apiUserList'));
    }

    /**
     * Restore the selected resource
     *
     * @param ApiUserRequest $request
     * @param $id
     * @return RedirectResponse
     */

    public function restoreDeleted(ApiUserRequest $request, $id)
    {

        $item = ApiUser::withTrashed()->find($id);
        if (isset($item)) {

            $operationStatus = $item->restore();

            if ($operationStatus) {
                $request->session()->flash('flash_notification.message', 'App user successfully restored. ');
                $request->session()->flash('flash_notification.level', 'success');
                return redirect()->route('admin.app-user.deleted.show');
            } else {
                $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
                $request->session()->flash('flash_notification.level', 'danger');
                return redirect()->route('admin.app-user.deleted.show');
            }
        } else {
            $request->session()->flash('flash_notification.message', 'An error occurred, please try again later. ');
            $request->session()->flash('flash_notification.level', 'danger');
            return redirect()->route('admin.app-user.deleted.show');
        }
    }
}
