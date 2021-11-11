<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxRequestForm extends Controller {

    //
    function update_remarks() {
        if (Request::ajax()) {
            $remarks = Input::get("remarks");
            $reference_id = Input::get("reference_id");
            
            $update = \App\FormRequest::where('reference_id',$reference_id)->first();
            $update->remarks = $remarks;
            $update->save();
        }
    }

}
