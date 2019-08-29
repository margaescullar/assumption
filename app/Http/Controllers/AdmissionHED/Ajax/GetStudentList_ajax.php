<?php

namespace App\Http\Controllers\AdmissionHED\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class GetStudentList_ajax extends Controller
{
    //
    function getstudentlist(){
        if (Request::ajax()) {
            $search = Input::get("search");
            $lists = \App\User::where('academic_type', 'College')
                    ->where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),"like","%$search%")
                              ->orWhere("idno",$search);
                    })->get();
            return view('admission-hed.ajax.getstudentlist', compact('lists'));
        }
    }
    
}
