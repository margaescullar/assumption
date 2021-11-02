<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxPaymentPlans extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function getstudents() {
        if (Request::ajax()) {
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $department = Input::get('department');

            return view('accounting.payment_plans.payment_plans_list',compact('school_year','period','department'));
        }
    }

    function getstudentslist() {
        if (Request::ajax()) {
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $level = Input::get('level');

            return view('accounting.payment_plans.payment_plans_student_list',compact('school_year','period','level'));
        }
    }

}
