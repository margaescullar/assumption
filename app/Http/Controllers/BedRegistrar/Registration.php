<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;
use Mail;

class Registration extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function withdraw($value, $is_after_enrollment, $date_today,$idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            if ($value == "w") {
                $v = env('WITHDRAWN');
            } else if ($value == "e") {
                $v = env('ENROLLED');
            }
            //delete ledger
            if ($value == "w") {
                $status = \App\Status::where('idno', $idno)->first();
                if($status->academic_type == "SHS"){
                    $ledgers = \App\Ledger::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->get();
                    $ledger_due_dates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->get();
                }else{
                    $ledgers = \App\Ledger::where('idno', $idno)->where('school_year', $status->school_year)->get();
                    $ledger_due_dates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->get();
                }
                if($is_after_enrollment == "No"){
                    foreach ($ledgers as $ledger){
                        $ledger->delete();
                    }
                    foreach ($ledger_due_dates as $ledger_due_date){
                        $ledger_due_date->delete();
                    }
                    $add = "before enrollment.";
                }else{
                    $add = "after enrollment.";
                }
            }else{
                $add = ".";
            }
            
            //update status
            $status = \App\Status::where('idno', $idno)->first();
            if ($value == "w") {
                if($is_after_enrollment == "No"){
                $status->date_dropped = NULL;
                }else{
                $status->date_dropped = $date_today;
                }
                $mes = "Withdraw";
            } else if ($value == "e") {
                $status->date_dropped = NULL;
                $mes = "Enrolled";
            }
            if($is_after_enrollment == "No"){
                $status->status = 0;
            }else{
                $status->status = $v;
            }
            $status->save();

            //update levels
            $bedlevel = \App\BedLevel::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
            $status = \App\Status::where('idno', $idno)->first();
            if ($value == "w") {
                if($is_after_enrollment == "No"){
                $status->date_dropped = NULL;
                }else{
                $status->date_dropped = $date_today;
                }
            } else if ($value == "e") {
                $status->date_dropped = NULL;
            }
            if($is_after_enrollment == "No"){
                $bedlevel->delete();
            }else{
                $bedlevel->status = $v;
            }
            $bedlevel->save();

            \App\Http\Controllers\Admin\Logs::log("$mes student $idno $add");
            return redirect(url('/bedregistrar', array('info', $idno)));
        }
    }

    function register() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $referenceid = uniqid();
            return view('reg_be.registration', compact('referenceid'));
        }
    }

    function post_register(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
            ]);
            
            if(Auth::user()->accesslevel== env("ADMISSION_BED")){
                $auth_type =  "BED";
            }else if(Auth::user()->accesslevel== env("ADMISSION_SHS")){
                $auth_type = "SHS";
            }else{
                $auth_type =  "BED";
            }

            if ($validate) {
                DB::beginTransaction();
                $addstudent = new \App\User;
                $addstudent->idno = $request->referenceid;
                $addstudent->lastname = $request->lastname;
                $addstudent->firstname = $request->firstname;
                $addstudent->middlename = $request->middlename;
                $addstudent->extensionname = $request->extensionname;
                $addstudent->is_foreign = $request->is_foreign;
                $addstudent->academic_type = $auth_type;
                $addstudent->lrn = $request->lrn;
                $addstudent->save();

                $addprofile = new \App\BedProfile;
                $addprofile->idno = $request->referenceid;
                $addprofile->date_of_birth = $request->date_of_birth;
                $addprofile->street = $request->street;
                $addprofile->barangay = $request->barangay;
                $addprofile->municipality = $request->municipality;
                $addprofile->province = $request->province;
                $addprofile->zip = $request->zip;
                $addprofile->tel_no = $request->tel_no;
                $addprofile->cell_no = $request->cell_no;
                if(Auth::user()->accesslevel== env("ADMISSION_SHS")){
                $addprofile->applied_for = "Grade 11";
                $promotion = \App\Promotion::where('idno', $request->referenceid)->first();
                 if (count($promotion) == 0) {
                    $new = new \App\Promotion();
                    $new->idno = $request->referenceid;
                    $new->level = "Grade 11";
                    $new->strand = "No Strand Yet";
                    $new->section = 1;
                    $new->save();
                 }
                
                }
                $addprofile->save();

                $addstatus = new \App\Status;
                $addstatus->idno = $request->referenceid;
                $addstatus->section = "";
                $addstatus->status = 0;
                $addstatus->academic_type = $auth_type;
                $addstatus->save();

                $addParent = new \App\BedParentInfo;
                $addParent->idno = $request->referenceid;
                $addParent->save();

                \App\Http\Controllers\Admin\Logs::log("Register new student - [$request->referenceid]: $request->lastname, $request->firstname $request->middlename.");
                DB::Commit();
                return view('reg_be.successfull');
            }
        }
    }

    function info($idno) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {

            $addprofile = \App\BedProfile::where('idno', $idno)->first();
            
            if (count($addprofile) == 0) {
                $addpro = new \App\BedProfile;
                $addpro->idno = $idno;
                $addpro->save();
            }
            $addparent = \App\BedParentInfo::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedParentInfo;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedReceivedHonor::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedReceivedHonor;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedApplicantFail::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedApplicantFail;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedRepeat::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedRepeat;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedProbation::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedProbation;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedExtraActivity::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedExtraActivity;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedChurchInvolvement::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedChurchInvolvement;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedUndergoneTherapy::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedUndergoneTherapy;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedLimitations::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedLimitations;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedRequirement::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedRequirement;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedSiblings::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedSiblings;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedOtherAlumni::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedOtherAlumni;
                $addpar->idno = $idno;
                $addpar->save();
            }

            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $info = \App\BedProfile::where('bed_profiles.idno', $idno)->join('bed_parent_infos', 'bed_parent_infos.idno', '=', 'bed_profiles.idno')->first();

            return view("reg_be.info", compact('user', 'info', 'status'));
        }
    }

    function reset_password(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $user = \App\User::where('idno', $request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login = 1;
            $user->update();
            \App\Http\Controllers\Admin\Logs::log("Reset password of $request->idno.");
            if(Auth::user()->accesslevel == env("REG_BE")){
            return redirect(url('/bedregistrar', array('info', $request->idno)));
            }elseif(Auth::user()->accesslevel == env("ADMISSION_BED")){
            return redirect(url('/admissionbed', array('info', $request->idno)));
            }
        }
    }

    function student_list() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED') || Auth::user()->accesslevel == env("ADMISSION_SHS") || Auth::user()->accesslevel == env("EDUTECH") || Auth::user()->accesslevel == env("BED_CL")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.student_list", compact('students'));
        }
    }

    function withdrawn_students() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("EDUTECH") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("WITHDRAWN"))->get();
            return view("reg_be.withdrawn_students", compact('students'));
        }
    }

    function assessed_students() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("EDUTECH")) {
            $students = \App\Status::whereRaw("(academic_type = 'BED' or academic_type = 'SHS')")->where('status', env("ASSESSED"))->orderBy('level')->orderBy('strand')->get();
            return view("reg_be.assessed_students", compact('students'));
        }
    }

    function not_yet_enrolled() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("EDUTECH")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ASSESSED"))->orderBy('level')->orderBy('strand')->get();
            return view("reg_be.not_yet_enrolled", compact('students'));
        }
    }

    function updateinfo(Request $request) {
//        return $request;
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
//                'municipality' => 'required',
//                'province' => 'required',
//                'date_of_birth' => 'required',
//                'gender' => 'required',
//                'email' => 'required',
            ]);

            if ($validate) {
                DB::beginTransaction();
                $this->updateInfoNow($request);
                $this->updateFamilyBackground($request);
                $this->updateEducBackground($request);
                $this->updateUser($request);
                $this->updateBedAchievement($request);
                $this->updateBedFail($request);
                $this->updateRepeat($request);
                $this->updateProbation($request);
                $this->updateClub($request);
                $this->updateInvolvement($request);
                $this->updateTherapy($request);
                $this->updateLimitation($request);
                $this->updateSibling($request);
                $this->updateAlumni($request);
                $this->updateRequirements($request);

                \App\Http\Controllers\Admin\Logs::log("Update student information of $request->idno.");
                $status = \App\Status::where('idno', $request->idno)->first();
                if ($status->status == env("PRE_REGISTERED")) {
                    $status->status = env("FOR_APPROVAL");
                    $status->save();

                    $testing = new \App\TestingStudent;
                    $testing->idno = $request->idno;
                    $testing->save();

                    \App\Http\Controllers\Admin\Logs::log("Update student'status to REGISTERED of $request->idno.");
                }

                DB::Commit();

                Session::flash('message', 'Information Updated!');


                if (Auth::user()->accesslevel == env("REG_BE")) {
                    return redirect(url('/bedregistrar', array('info', $request->idno)));
                } else {
                    return redirect(url('/admissionbed', array('info', $request->idno)));
                }
            }
        }
    }

    function updateFamilyBackground($request) {
        $updatefamilybackground = \App\BedParentInfo::where('idno', $request->idno)->first();
        $updatefamilybackground->father = $request->father;
        $updatefamilybackground->f_citizenship = $request->f_citizenship;
        $updatefamilybackground->f_is_living = $request->f_is_living;
        $updatefamilybackground->f_religion = $request->f_religion;
        $updatefamilybackground->f_education = $request->f_education;
        $updatefamilybackground->f_school = $request->f_school;
        $updatefamilybackground->f_occupation = $request->f_occupation;
        $updatefamilybackground->f_company_name = $request->f_company_name;
        $updatefamilybackground->f_company_address = $request->f_company_address;
        $updatefamilybackground->f_company_number = $request->f_company_number;
        $updatefamilybackground->f_phone = $request->f_phone;
        $updatefamilybackground->f_cell_no = $request->f_cell_no;
        $updatefamilybackground->f_address = $request->f_address;
        $updatefamilybackground->f_email = $request->f_email;
        $updatefamilybackground->f_any_org = $request->f_any_org;
        $updatefamilybackground->f_type_of_org = $request->f_type_of_org;
        $updatefamilybackground->f_expertise = $request->f_expertise;


        $updatefamilybackground->mother = $request->mother;
        $updatefamilybackground->m_citizenship = $request->m_citizenship;
        $updatefamilybackground->m_is_living = $request->m_is_living;
        $updatefamilybackground->m_religion = $request->m_religion;
        $updatefamilybackground->m_education = $request->m_education;
        $updatefamilybackground->m_school = $request->m_school;
        $updatefamilybackground->m_occupation = $request->m_occupation;
        $updatefamilybackground->m_company_name = $request->m_company_name;
        $updatefamilybackground->m_company_address = $request->m_company_address;
        $updatefamilybackground->m_company_number = $request->m_company_number;
        $updatefamilybackground->m_phone = $request->m_phone;
        $updatefamilybackground->m_cell_no = $request->m_cell_no;
        $updatefamilybackground->m_address = $request->m_address;
        $updatefamilybackground->m_email = $request->m_email;
        $updatefamilybackground->m_any_org = $request->m_any_org;
        $updatefamilybackground->m_type_om_org = $request->m_type_om_org;
        $updatefamilybackground->m_expertise = $request->m_expertise;


        $updatefamilybackground->m_alumna_gradeschool_year = $request->m_alumna_gradeschool_year;
        $updatefamilybackground->m_alumna_highschool_year = $request->m_alumna_highschool_year;
        $updatefamilybackground->m_alumna_college_year = $request->m_alumna_college_year;


        $updatefamilybackground->parents_civil_status = $request->parents_civil_status;
        $updatefamilybackground->guardian = $request->guardian;
        $updatefamilybackground->g_relation = $request->g_relation;
        $updatefamilybackground->g_address = $request->g_address;
        $updatefamilybackground->g_contact_no = $request->g_contact_no;

        $updatefamilybackground->save();
    }

    function updateEducBackground($request) {
        $updateEducbackground = \App\BedProfile::where('idno', $request->idno)->first();

        $updateEducbackground->present_school = $request->present_school;
        $updateEducbackground->present_school_address = $request->present_school_address;
        $updateEducbackground->present_principal = $request->present_principal;
        $updateEducbackground->present_tel_no = $request->present_tel_no;
        $updateEducbackground->present_guidance = $request->present_guidance;

        $updateEducbackground->primary = $request->primary;
        $updateEducbackground->primary_address = $request->primary_address;
        $updateEducbackground->primary_year = $request->primary_year;
        $updateEducbackground->gradeschool = $request->gradeschool;
        $updateEducbackground->gradeschool_address = $request->gradeschool_address;
        $updateEducbackground->gradeschool_year = $request->gradeschool_year;
        $updateEducbackground->highschool = $request->highschool;
        $updateEducbackground->highschool_address = $request->highschool_address;
        $updateEducbackground->highschool_year = $request->highschool_year;
//        
        $updateEducbackground->save();
    }

    function updateInfoNow($request) {
        $updateInfo = \App\BedProfile::where('idno', $request->idno)->first();
        $updateInfo->street = $request->street;
        $updateInfo->barangay = $request->barangay;
        $updateInfo->municipality = $request->municipality;
        $updateInfo->province = $request->province;
        $updateInfo->zip = $request->zip;
        $updateInfo->region = $request->region;
        $updateInfo->tel_no = $request->tel_no;
        $updateInfo->cell_no = $request->cell_no;
        $updateInfo->date_of_birth = $request->date_of_birth;
        $updateInfo->place_of_birth = $request->place_of_birth;
        $updateInfo->gender = $request->gender;
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

    function updateUser($request) {
        $updateUser = \App\User::where('idno', $request->idno)->first();
        $updateUser->firstname = $request->firstname;
        $updateUser->middlename = $request->middlename;
        $updateUser->lastname = $request->lastname;
        $updateUser->extensionname = $request->extensionname;
        $updateUser->is_foreign = $request->is_foreign;
        $updateUser->email = $request->email;
        $updateUser->status = $request->user_status;
        $updateUser->lrn = $request->lrn;
        $updateUser->save();
    }

    function view_dpa() {
        $path = public_path('/AC Student Privacy Notice and Consent Form v2018.pdf');
        return response()->file($path);
    }

    function updateBedAchievement($request) {

        $idno = $request->idno;
        $achievement = $request->achievement;
        $achievement_level = $request->achievement_level;
        $achievement_event = $request->achievement_event;

        $updates = \App\BedReceivedHonor::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($achievement[$i])) {

                $add = new \App\BedReceivedHonor;
                $add->idno = $idno;
                $add->achievement = $achievement[$i];
                $add->level = $achievement_level[$i];
                $add->event = $achievement_event[$i];
                $add->save();
            }
        }
    }

    function updateBedFail($request) {

        $idno = $request->idno;
        $fail = $request->fail;
        $fail_level = $request->fail_level;

        $updates = \App\BedApplicantFail::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($fail[$i])) {

                $add = new \App\BedApplicantFail;
                $add->idno = $idno;
                $add->subject = $fail[$i];
                $add->level = $fail_level[$i];
                $add->save();
            }
        }
    }

    function updateRepeat($request) {

        $idno = $request->idno;
        $repeat_level = $request->repeat_level;

        $updates = \App\BedRepeat::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($repeat_level[$i])) {

                $add = new \App\BedRepeat;
                $add->idno = $idno;
                $add->level = $repeat_level[$i];
                $add->save();
            }
        }
    }

    function updateProbation($request) {

        $idno = $request->idno;
        $probation = $request->probation;
        $probation_date = $request->probation_date;
        $probation_penalty = $request->probation_penalty;

        $updates = \App\BedProbation::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($probation[$i])) {

                $add = new \App\BedProbation;
                $add->idno = $idno;
                $add->offense = $probation[$i];
                $add->date = $probation_date[$i];
                $add->penalty = $probation_penalty[$i];
                $add->save();
            }
        }
    }

    function updateClub($request) {

        $idno = $request->idno;
        $club = $request->club;
        $club_level = $request->club_level;

        $updates = \App\BedExtraActivity::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($club[$i])) {

                $add = new \App\BedExtraActivity;
                $add->idno = $idno;
                $add->club = $club[$i];
                $add->level = $club_level[$i];
                $add->save();
            }
        }
    }

    function updateInvolvement($request) {

        $idno = $request->idno;
        $involvement = $request->involvement;
        $involvement_year = $request->involvement_year;

        $updates = \App\BedChurchInvolvement::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($involvement[$i])) {

                $add = new \App\BedChurchInvolvement;
                $add->idno = $idno;
                $add->involvement = $involvement[$i];
                $add->year = $involvement_year[$i];
                $add->save();
            }
        }
    }

    function updateTherapy($request) {

        $idno = $request->idno;
        $therapy = $request->therapy;
        $therapy_period = $request->therapy_period;

        $updates = \App\BedUndergoneTherapy::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($therapy[$i])) {

                $add = new \App\BedUndergoneTherapy;
                $add->idno = $idno;
                $add->therapy = $therapy[$i];
                $add->treatment = $therapy_period[$i];
                $add->save();
            }
        }
    }

    function updateLimitation($request) {

        $idno = $request->idno;
        $limitation = $request->limitation;

        $updates = \App\BedLimitations::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }
        for ($i = 0; $i < 20; $i++) {
            if (isset($limitation[$i])) {
                $add = new \App\BedLimitations;
                $add->idno = $idno;
                $add->limitations = $limitation[$i];
                $add->save();
            }
        }
    }

    function updateSibling($request) {

        $idno = $request->idno;
        $sibling = $request->sibling;
        $sibling_age = $request->sibling_age;
        $sibling_level = $request->sibling_level;
        $sibling_school = $request->sibling_school;

        $updates = \App\BedSiblings::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }
        for ($i = 0; $i < 20; $i++) {
            if (isset($sibling[$i])) {
                $add = new \App\BedSiblings;
                $add->idno = $idno;
                $add->sibling = $sibling[$i];
                $add->age = $sibling_age[$i];
                $add->level = $sibling_level[$i];
                $add->school = $sibling_school[$i];
                $add->save();
            }
        }
    }

    function updateAlumni($request) {

        $idno = $request->idno;
        $alumni = $request->alumni;
        $alumni_relationship = $request->alumni_relationship;

        $updates = \App\BedOtherAlumni::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }
        for ($i = 0; $i < 20; $i++) {
            if (isset($alumni[$i])) {
                $add = new \App\BedOtherAlumni;
                $add->idno = $idno;
                $add->alumni = $alumni[$i];
                $add->relationship = $alumni_relationship[$i];
                $add->save();
            }
        }
    }

    function updateRequirements($request) {

        $idno = $request->idno;
        if ($request->psa == "on") {
            $request->psa = 1;
        } else {
            $request->psa = 0;
        }
        if ($request->recommendation_form == "on") {
            $request->recommendation_form = 1;
        } else {
            $request->recommendation_form = 0;
        }
        if ($request->baptismal_certificate == "on") {
            $request->baptismal_certificate = 1;
        } else {
            $request->baptismal_certificate = 0;
        }
        if ($request->passport_size_photo == "on") {
            $request->passport_size_photo = 1;
        } else {
            $request->passport_size_photo = 0;
        }
        if ($request->progress_report_card == "on") {
            $request->progress_report_card = 1;
        } else {
            $request->progress_report_card = 0;
        }
        if ($request->currentprevious_report_card == "on") {
            $request->currentprevious_report_card = 1;
        } else {
            $request->currentprevious_report_card = 0;
        }
        if ($request->narrative_assessment_report == "on") {
            $request->narrative_assessment_report = 1;
        } else {
            $request->narrative_assessment_report = 0;
        }
        if ($request->acr == "on") {
            $request->acr = 1;
        } else {
            $request->acr = 0;
        }
        if ($request->photocopy_passport == "on") {
            $request->photocopy_passport = 1;
        } else {
            $request->photocopy_passport = 0;
        }
        if ($request->visa_parent == "on") {
            $request->visa_parent = 1;
        } else {
            $request->visa_parent = 0;
        }
        if ($request->photocopy_of_dual == "on") {
            $request->photocopy_of_dual = 1;
        } else {
            $request->photocopy_of_dual = 0;
        }
        if ($request->citizenship_passport == "on") {
            $request->citizenship_passport = 1;
        } else {
            $request->citizenship_passport = 0;
        }
        
        if ($request->question_parent == "on") {
            $request->question_parent = 1;
        } else {
            $request->question_parent = 0;
        }
        
        if ($request->question_student == "on") {
            $request->question_student = 1;
        } else {
            $request->question_student = 0;
        }
        
        if ($request->essay == "on") {
            $request->essay = 1;
        } else {
            $request->essay = 0;
        }
        
        if ($request->dpa == "on") {
            $request->dpa = 1;
        } else {
            $request->dpa = 0;
        }
        
        if ($request->adviser_guidance_reco == "on") {
            $request->adviser_guidance_reco = 1;
        } else {
            $request->adviser_guidance_reco = 0;
        }
        
        if ($request->principal_reco == "on") {
            $request->principal_reco = 1;
        } else {
            $request->principal_reco = 0;
        }

        $update = \App\BedRequirement::where('idno', $idno)->first();
        $update->psa = $request->psa;
        $update->recommendation_form = $request->recommendation_form;
        $update->baptismal_certificate = $request->baptismal_certificate;
        $update->passport_size_photo = $request->passport_size_photo;
        $update->progress_report_card = $request->progress_report_card;
        $update->currentprevious_report_card = $request->currentprevious_report_card;
        $update->narrative_assessment_report = $request->narrative_assessment_report;
        $update->acr = $request->acr;
        $update->passport = $request->photocopy_passport;
        $update->visa_parent = $request->visa_parent;
        $update->photocopy_of_dual = $request->photocopy_of_dual;
        $update->citizenship_passport = $request->citizenship_passport;
        $update->question_parent = $request->question_parent;
        $update->question_student = $request->question_student;
        $update->essay = $request->essay;
        $update->dpa = $request->dpa;
        $update->adviser_guidance_reco = $request->adviser_guidance_reco;
        $update->principal_reco = $request->principal_reco;
        $update->save();
    }

    function sectioning($type=null) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("GUIDANCE_BED")) {
            return view("reg_be.sectioning",compact('type'));
        }
    }

}
