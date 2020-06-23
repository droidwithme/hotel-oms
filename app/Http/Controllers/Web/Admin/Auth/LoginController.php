<?php

namespace App\Http\Controllers\Web\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest:hotel', 'guest:admin'])->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login-admin');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    public function loggedOut(Request $request)
    {
        return redirect()->route('admin.auth.login');
    }
}
