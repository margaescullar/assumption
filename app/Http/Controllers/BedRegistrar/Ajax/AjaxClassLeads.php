<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxClassLeads extends Controller
{
    //

    function update_class_leads() {
        if (Request::ajax()) {
            $academic = null;
            if (Input::get('button') == "Add") {
                $academic = new \App\User;
                $academic->idno = Input::get('idno');
                $academic->accesslevel = env("BED_CL");
                $academic->password = bcrypt(Input::get('idno'));
            } else {
                $academic = \App\User::find(Input::get('id'));
            }

            $academic->firstname = Input::get('firstname');
            $academic->middlename = Input::get('middlename');
            $academic->lastname = Input::get('lastname');
            $academic->email = Input::get('email');
            $academic->save();

            $class_leads = \app\User::where('accesslevel', env('BED_CL'))->orderBy('lastname')->orderBy('firstname')->get();

            return view('reg_be.class_leads.ajax.getpersonellist', compact('class_leads'));
        }
    }

    function modify_levels() {
        if (Request::ajax()) {
            $idno = Input::get('idno');
            $get_levels = \App\ClassLeadLevel::where('idno', $idno)->get(['level']);
            return view('reg_be.class_leads.ajax.displaylevels', compact('get_levels','idno'));
        }
    }
    
    
}
