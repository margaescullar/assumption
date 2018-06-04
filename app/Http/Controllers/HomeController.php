<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $accesslevel = \Auth::user()->accesslevel;

        if ($accesslevel > 0) {
            if (Auth::user()->is_first_login == "1") {
                return view('set_password');
            }
        }

        switch ($accesslevel) {
            case 0:
                Auth::logout();
                return view('auth.login')->withErrors("Access Denied - Not Authorized");
            case 1:
                return view('college_instructor.index');
                break;
            case 10:
                return view('dean.index');
                break;
            case 11:
                return view('mesil.index');
                break;
            case 12:
                return view('msbmw.index');
                break;
            case 20:
                return view('reg_college.index');
                break;
            case 21:
                return view('reg_be.index', compact('school_year'));
                break;
            case 30:
                return view('accounting.index');
                break;
            case 31:
                return view('accounting.index');
                break;
            case 40:
                return view('cashier.index');
                break;
            case 100:
                return view('admin.index');
                break;
            case 50:
                return view('bookstore.index');
                break;
            case 60:
                return view('admission-hed.index');
                break;
            case 61:
                return view('admission-bed.index');
                break;
            case 70:
                return view('guidance_hed.index');
                break;
            case 71:
                return view('guidance_bed.index');
                break;
        }
    }

    function set_password(Request $request) {

        $validation = $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validation) {
            $user = \App\User::where('idno', Auth::user()->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login = 0;
            $user->update();
            return redirect(url('/'));
        }
    }

}
