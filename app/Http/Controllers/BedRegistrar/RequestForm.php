<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class RequestForm extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $forms_request_pending = \App\FormRequest::orderBy('created_at', 'desc')->where('status', 0)->get();
        $forms_request_paid = \App\FormRequest::orderBy('created_at', 'desc')->where('status', 1)->get();
        $forms_request_for_claiming = \App\FormRequest::orderBy('created_at', 'desc')->where('status', 2)->get();
        $forms_request_claimed = \App\FormRequest::orderBy('created_at', 'desc')->where('status', 3)->get();

        return view('reg_be.request_form.index', compact('forms_request_pending', 'forms_request_paid', 'forms_request_for_claiming', 'forms_request_claimed'));
    }

    function AjaxGetForm() {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            $reference_id = \Illuminate\Support\Facades\Input::get('reference_id');
            $form_requests = \App\FormRequest::orderBy('reference_id', $reference_id)->get();
            return view('reg_be.request_form.getforms', compact('form_requests', 'reference_id'));
        }
    }

    function updateOR(Request $request) {
        $form_requests = \App\FormRequest::where('reference_id', $request->reference_id)->first();
        $form_requests->or_number = $request->or_number;
        $form_requests->status = 1;
        $form_requests->save();

        return redirect('/bedregistrar/request_form');
    }

    function tag_as_claimed($reference_id) {
        $form_requests = \App\FormRequest::where('reference_id', $reference_id)->first();
        $form_requests->claim_date = date('Y-m-d');
        $form_requests->status = 3;
        $form_requests->save();

        return redirect('/bedregistrar/request_form');
    }

    function tag_as_for_claiming(Request $request) {
        $form_requests = \App\FormRequest::where('reference_id', $request->reference_id)->first();
        $form_requests->claiming_date = $request->date_for_claiming;
        $form_requests->status = 2;
        $form_requests->save();

        return redirect('/bedregistrar/request_form');
    }

    function reports() {
        $forms = \App\CtrForm::all();
        return $forms;
    }

    function settings() {
        $forms = \App\CtrForm::orderBy('document_group')->get();
        return view('reg_be.request_form.settings', compact('forms'));
    }

    function AjaxGetFormDetails() {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            $form_id = \Illuminate\Support\Facades\Input::get('id');
            $form_details = \App\CtrForm::where('id', $form_id)->first();
            return view('reg_be.request_form.getform_details', compact('form_details', 'form_id'));
        }
    }

    function UpdateFormDetails(Request $request) {
        $update = \App\CtrForm::where('id', $request->id)->first();
        if ($request->button == "Submit") {
            $update->document_group = $request->document_group;
            $update->document_name = $request->document_name;
            $update->cost = $request->cost;
            $update->requirements = $request->requirements;
            $update->processing_day = $request->processing_day;
            $update->save();
        } elseif($request->button=="Delete") {
            $update->delete();
        }elseif($request->button=="Add"){
            $addform = new \App\CtrForm();
            $addform->document_group = $request->document_group;
            $addform->document_name = $request->document_name;
            $addform->cost = $request->cost;
            $addform->requirements = $request->requirements;
            $addform->processing_day = $request->processing_day;
            $addform->save();
        }

        return redirect(url('bedregistrar/request_form/settings'));
    }

}
