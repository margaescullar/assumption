<?php

namespace App\Http\Controllers\AdmissionHED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use Mail;
use PDF;

class ViewInfoAdmissionHedController extends Controller {

    
    public function __construct() {
        $this->middleware('auth');
    }
    function view_info($idno) {
        if (Auth::user()->accesslevel == env('ADMISSION_HED')) {

            $addparent = \App\HedTestingStudent::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\HedTestingStudent;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\CollegeAboutYou::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\CollegeAboutYou;
                $addpar->idno = $idno;
                $addpar->save();
            }
            
            $users = \App\User::where('idno', $idno)->first();
            $adhedinfo = \App\AdmissionHed::where('idno', $idno)->first();
            $studentinfos = \App\StudentInfo::where('idno', $idno)->first();
            $admissionreq = \App\AdmissionHedRequirements::where('idno', $idno)->first();
            $email = \App\EmailBlast::where('idno',$idno)->where('is_done',1)->first();
            $status = \App\Status::where('idno',$idno)->first();
            $user = \App\User::where('idno', $idno)->first();
            $about = \App\CollegeAboutYou::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            return view('admission-hed.viewinfo.view', compact('email','status','idno', 'users', 'adhedinfo', 'studentinfos', 'admissionreq','user', 'info','about'));
        }
    }

    function update_info(Request $request) {
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'municipality' => 'required',
            'province' => 'required',
 //           'birthdate' => 'required',
//            'gender' => 'required',
            'email' => 'required',
        ]);
        if ($validate) {
            DB::beginTransaction();
            $this->updatePersonalInfo($request);
            $this->update_admission_checklist($request);
            $this->updateCollegeAboutYou($request);
            DB::Commit();

            
             \App\Http\Controllers\Admin\Logs::log("Update information of $request->idno");
            
            Session::flash('message', "Information Updated!");

            return redirect(url('admission_hed', array('view_info', $request->idno)));
        }
    }

    function updatePersonalInfo($request) {
        $updatePersonalInfo = \App\User::where('idno', $request->idno)->first();
        $updatePersonalInfo->firstname = $request->firstname;
        $updatePersonalInfo->middlename = $request->middlename;
        $updatePersonalInfo->lastname = $request->lastname;
        $updatePersonalInfo->extensionname = $request->extensionname;
        $updatePersonalInfo->email = $request->email;
        $updatePersonalInfo->is_foreign = $request->is_foreign;
        if($request->student_status == 1){    
            $updatePersonalInfo->password = bcrypt($request->idno);
            $updatePersonalInfo->status = 1;
            $updateStatus= \App\Status::where('idno',$request->idno)->first();
            $updateStatus->status = 0;
            $updateStatus->save();
        }else if($request->student_status == 0){
            $updatePersonalInfo->status = 0;
            $updateStatus= \App\Status::where('idno',$request->idno)->first();
            $updateStatus->status = 21;
            $updateStatus->save();
        }else if($request->student_status == 2){
//            $updatePersonalInfo->status = 1;
            $updateStatus= \App\Status::where('idno',$request->idno)->first();
            $updateStatus->status = 20;
            $updateStatus->save();
        }
        $updatePersonalInfo->update();

        $updatePersonalInfo = \App\StudentInfo::where('idno', $request->idno)->first();
        $updatePersonalInfo->street = $request->street;
        $updatePersonalInfo->region = $request->region;
        $updatePersonalInfo->barangay = $request->barangay;
        $updatePersonalInfo->municipality = $request->municipality;
        $updatePersonalInfo->province = $request->province;
        $updatePersonalInfo->zip = $request->zip;
        $updatePersonalInfo->birthdate = $request->birthdate;
        $updatePersonalInfo->place_of_birth = $request->place_of_birth;
        $updatePersonalInfo->tel_no = $request->tel_no;
        $updatePersonalInfo->cell_no = $request->cell_no;
        $updatePersonalInfo->civil_status = $request->civil_status;
        $updatePersonalInfo->nationality = $request->specify_citizenship;
        $updatePersonalInfo->last_school_attended = $request->last_school_attended;
        $updatePersonalInfo->last_school_address = $request->last_school_address;
        $updatePersonalInfo->update();

        
        $program_code = \App\CtrAcademicProgram::findCode($request->program_name);
        
        $updatePersonalInfo = \App\AdmissionHed::where('idno', $request->idno)->first();
        $updatePersonalInfo->assumption_scholar = $request->assumption_scholar;
        $updatePersonalInfo->partner_scholar = $request->partner_scholar;
        $updatePersonalInfo->agreement = $request->agreement;
        $updatePersonalInfo->admission_status = $request->admission_status;
        $updatePersonalInfo->summer_classes = $request->summer_classes;
        $updatePersonalInfo->program_code = $program_code;
        $updatePersonalInfo->program_name = $request->program_name;
        $updatePersonalInfo->applying_for_sy = $request->applying_for_sy;
        $updatePersonalInfo->strand = $request->strand;
        $updatePersonalInfo->student_status = $request->student_status;
        $updatePersonalInfo->tagged_as = $request->tagged_as;
        $updatePersonalInfo->specify_citizenship = $request->specify_citizenship;
        $updatePersonalInfo->applying_for = $request->applying_for;
        $updatePersonalInfo->medical = $request->medical;
        $updatePersonalInfo->psychological = $request->psychological;
        $updatePersonalInfo->learning_disability = $request->learning_disability;
        $updatePersonalInfo->emotional = $request->emotional;
        $updatePersonalInfo->social = $request->social;
        $updatePersonalInfo->others = $request->others;
        $updatePersonalInfo->see_professional = $request->see_professional;
        $updatePersonalInfo->specify_condition = $request->specify_condition;
        $updatePersonalInfo->guardian_name = $request->guardian_name;
        $updatePersonalInfo->guardian_contact = $request->guardian_contact;
        $updatePersonalInfo->guardian_email = $request->guardian_email;
        $updatePersonalInfo->guardian_type = $request->guardian_type;
        $updatePersonalInfo->update();
//            if($request->admission_status == 'Probationary'){
//                $updatePersonalInfo->admission_status = $request->agreement;
//            }else{
//                $updatePersonalInfo->admission_status = $request->admission_status;
//            }
//            if($request->conditionType == 'Others'){
//                $updatePersonalInfo->condition = $request->specifyCondition;
//            }else{
//                $updatePersonalInfo->condition = $request->conditionType; 
//            }
    }

    function update_admission_checklist($request) {
        $update_admission_checklist = \App\AdmissionHedRequirements::where('idno', $request->idno)->first();
        $update_admission_checklist->birth_certificate = $request->birth_certificate;
        $update_admission_checklist->form138 = $request->form138;
        $update_admission_checklist->labtest = $request->labtest;
        $update_admission_checklist->admission_agreement = $request->admission_agreement;
        $update_admission_checklist->parent_partnership = $request->parent_partnership;
        $update_admission_checklist->school_rec = $request->school_rec;
        $update_admission_checklist->tor = $request->tor;
        $update_admission_checklist->honor_dismiss = $request->honor_dismiss;
        $update_admission_checklist->course_desc = $request->course_desc;
        $update_admission_checklist->cbc = $request->cbc;
        $update_admission_checklist->bt = $request->bt;
        $update_admission_checklist->x_ray = $request->x_ray;
        $update_admission_checklist->visa = $request->visa;
        $update_admission_checklist->passport = $request->passport;
        $update_admission_checklist->photocopy_diploma = $request->photocopy_diploma;
        $update_admission_checklist->marriage_contract = $request->marriage_contract;
        $update_admission_checklist->child_birth_cert = $request->child_birth_cert;
        $update_admission_checklist->medical_clearance = $request->medical_clearance;
        $update_admission_checklist->remarks = $request->remarks;
        $update_admission_checklist->update();
    }
    
    function email($idno) {
        if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
            DB::beginTransaction();
            $check = \App\EmailBlast::where('idno',$idno)->first();
            if(count($check)==0){
            $email_blast = new \App\EmailBlast();
            $email_blast->idno = $idno;
            $email_blast->save();
            }
            
            
            try{
            $this->sendEmail($idno);
            $this->sendEmailtoRegistrar($idno);
            
            $admission = \App\AdmissionHed::where('idno', $idno)->first();
            $admission->is_first_enrollment = 1;
            $admission->save();
                
            $blast = \App\EmailBlast::where('idno', $idno)->first();
            $blast->is_done = 1;
            $blast->save();
            
            Session::flash('message', 'Email sent!');
            }catch (\Exception $e){
            Session::flash('danger', 'Email not sent! Their default password is their username.');
            }
            
            
            DB::Commit();
            return redirect(url('admission_hed',array('view_info',$idno)));
//            return "Done";
        }
    }

    function sendEmail($idno) {
        $applicant_details = \App\User::where('idno', $idno)->first();
        $applicant_details->password = bcrypt($idno);
        $applicant_details->is_first_login = 1;
        $applicant_details->status = 1;
        $applicant_details->save();
        
        Mail::send('admin.mail', compact('applicant_details'), function($message) use($applicant_details) {
            $message->to($applicant_details->email, $applicant_details->firstname . " " . $applicant_details->lastname)
                    ->subject('AC Portal Access');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }

    function sendEmailtoRegistrar($idno) {
        $applicant_details = \App\User::where('idno', $idno)->first();
        
        $data = array('name' => "HED Registrar", 'email' => env("HED_REGISTRAR_EMAIL"));
        Mail::send('admin.mailtoRegistrar', compact('applicant_details'), function($message) use($data) {
            $message->to($data['email'], $data['name'])
                    ->subject('New Student for Advising');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }
    
    
    function print_pre_application_form($idno){
        if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $users = \App\User::where('idno', $idno)->first();
            $adhedinfo = \App\AdmissionHed::where('idno', $idno)->first();
            $studentinfos = \App\StudentInfo::where('idno', $idno)->first();
            $admissionreq = \App\AdmissionHedRequirements::where('idno', $idno)->first();
            $email = \App\EmailBlast::where('idno',$idno)->where('is_done',1)->first();
            $status = \App\Status::where('idno',$idno)->first();
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            
            $pdf = PDF::loadView('admission-hed.viewinfo.print_pre_application_form', compact('users','adhedinfo','studentinfos','admissionreq','email','status','info','user'));
            $pdf->setPaper('letter','portrait');
            return $pdf->stream("pre_application_form-$idno.pdf"); 
        }
    }
    
    function remove_application($idno){
        $user = \App\User::where('idno',$idno)->first();
        $user->delete();
        return redirect('/');
    }

    function updateCollegeAboutYou($request) {
        $update = \App\CollegeAboutYou::where('idno', $request->idno)->first();
        $update->interest = $request->interest;
        $update->goals = $request->goals;
        $update->challenges = $request->challenges;
        $update->com_channel = $request->com_channel;
        $update->awareness = isset($request->awareness);
        $update->commitment = isset($request->commitment);
        $update->kindness = isset($request->kindness);
        $update->simplicity = isset($request->simplicity);
        $update->humility = isset($request->humility);
        $update->integrity = isset($request->integrity);
        $update->oneness = isset($request->oneness);
        $update->nature = isset($request->nature);
        $update->others = $request->other_core;
        $update->save();
    }

}
