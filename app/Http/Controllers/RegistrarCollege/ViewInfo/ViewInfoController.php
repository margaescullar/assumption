<?php

namespace App\Http\Controllers\RegistrarCollege\ViewInfo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class ViewInfoController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view_info($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            return view('reg_college.view_info.view', compact('idno', 'user', 'info'));
        }
    }

    function save_info(Request $request) {
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required',
        ]);

        if ($validate) {
            DB::beginTransaction();
            //$this->updateStatus($request);
            $this->updateInfo($request);
            $this->updateFamilyBackground($request);
            $this->updateEducBackground($request);
            //$this->updateUser($request);
            DB::Commit();
            return "updated";
        }
    }
    
    function updateFamilyBackground($request){
        $updatefamilybackground = \App\StudentInfo::where('idno', $request->idno)->first();
        $updatefamilybackground->father = $request->father;
        $updatefamilybackground->f_is_living = $request->f_is_living;
        $updatefamilybackground->f_occupation = $request->f_occupation;
        $updatefamilybackground->f_phone = $request->f_phone;
        $updatefamilybackground->f_address = $request->f_address;
        $updatefamilybackground->mother = $request->mother;
        $updatefamilybackground->m_is_living = $request->m_is_living;
        $updatefamilybackground->m_occupation = $request->m_occupation;
        $updatefamilybackground->m_phone = $request->m_phone;
        $updatefamilybackground->m_address = $request->m_address;
        $updatefamilybackground->spouse = $request->spouse;
        $updatefamilybackground->s_is_living = $request->s_is_living;
        $updatefamilybackground->s_occupation = $request->s_occupation;
        $updatefamilybackground->s_phone = $request->s_phone;
        $updatefamilybackground->s_address = $request->s_address;
        $updatefamilybackground->save();
    }
    
    function updateEducBackground($request){
        $updateEducbackground = \App\StudentInfo::where('idno', $request->idno)->first();
        $updateEducbackground->primary = $request->primary;
        $updateEducbackground->primary_address = $request->primary_address;
        $updateEducbackground->primary_year = $request->primary_year;
        $updateEducbackground->gradeschool = $request->gradeschool;
        $updateEducbackground->gradeschool_address = $request->gradeschool_address;
        $updateEducbackground->gradeschool_year = $request->gradeschool_year;
        $updateEducbackground->highschool = $request->highschool;
        $updateEducbackground->highschool_address = $request->highschool_address;
        $updateEducbackground->highschool_year = $request->highschool_year;
        $updateEducbackground->last_school_attended = $request->last_school_attended;
        $updateEducbackground->last_school_address = $request->last_school_address;
        $updateEducbackground->last_school_year = $request->last_school_year;
        $updateEducbackground->save();
    }
    
    function updateStatus($request){
        
    }
    
    function updateInfo($request){  
        $updateInfo = \App\StudentInfo::where('idno', $request->idno)->first();
        $updateInfo->street = $request->street;
        $updateInfo->barangay = $request->barangay;
        $updateInfo->municipality = $request->municipality;
        $updateInfo->province = $request->province;
        $updateInfo->zip = $request->zip;
        $updateInfo->tel_no = $request->tel_no;
        $updateInfo->cell_no = $request->cell_no;
        $updateInfo->birthdate = $request->birthdate;
        $updateInfo->place_of_birth = $request->place_of_birth;
        $updateInfo->gender = $request->gender;
        $updateInfo->civil_status = $request->civil_status;
        $updateInfo->nationality = $request->nationality;
        $updateInfo->religion = $request->religion;
        $updateInfo->immig_status = $request->immig_status;
        $updateInfo->auth_stay = $request->auth_stay;
        $updateInfo->passport = $request->passport;
        $updateInfo->passport_exp_date = $request->passport_exp_date;
        $updateInfo->passport_place_issued = $request->passport_place_issued;
        $updateInfo->acr_no = $request->acr_no;
        $updateInfo->acr_date_issued = $request->acr_date_issued;
        $updateInfo->acr_place_issued = $request->acr_place_issued;
        $updateInfo->save();
    }
    
    function updateUser($request){
        
    }

}
