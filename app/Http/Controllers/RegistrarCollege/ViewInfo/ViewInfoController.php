<?php

namespace App\Http\Controllers\RegistrarCollege\ViewInfo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use PDF;

class ViewInfoController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function withdraw($value, $date_today, $idno) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            if ($value == "w") {
                $v = env('WITHDRAWN');
            } else if ($value == "e") {
                $v = env('ENROLLED');
            }
            $status = \App\Status::where('idno', $idno)->first();
            if ($value == "w") {
                $status->date_dropped = $date_today;
                $mes = "Withdraw";
            } else if ($value == "e") {
                $status->date_dropped = NULL;
                $mes = "Enrolled";
            }
            $status->status = $v;
            $status->save();

            $bedlevel = \App\CollegeLevel::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
            if ($value == "w") {
                $bedlevel->date_dropped = $date_today;
            } else if ($value == "e") {
                $bedlevel->date_dropped = NULL;
            }
            $bedlevel->status = $v;
            $bedlevel->save();

            \App\Http\Controllers\Admin\Logs::log("$mes student $idno.");
            return redirect(url('/registrar_college', array('view_info', $idno)));
        }
    }

    function view_info($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $user = \App\User::where('idno', $idno)->first();

            $addparent = \App\StudentInfoCoursesRank::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoCoursesRank;
                $addpar->idno = $idno;
                $addpar->save();
            }

            $addparent = \App\BedApplicantFail::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedApplicantFail;
                $addpar->idno = $idno;
                $addpar->save();
            }

            $addparent = \App\StudentInfoEmergency::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoEmergency;
                $addpar->idno = $idno;
                $addpar->save();
            }

            $addparent = \App\StudentInfoAlmuni::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoAlmuni;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoSibling::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoSibling;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoChildren::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoChildren;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoAttendedCollege::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoAttendedCollege;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoHonor::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoHonor;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoDiscontinuance::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoDiscontinuance;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoFailSubject::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoFailSubject;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoRepeat::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoRepeat;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoSuspension::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoSuspension;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoActivity::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoActivity;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoIntend::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoIntend;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoSchoolRank::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoSchoolRank;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoEmergency::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoEmergency;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoParentInfo::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoParentInfo;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\StudentInfoPregnant::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\StudentInfoPregnant;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $info = \App\StudentInfo::where('student_infos.idno', $idno)->join('student_info_parent_infos', 'student_info_parent_infos.idno', '=', 'student_infos.idno')->first();

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
        ]);

        if ($validate) {
            DB::beginTransaction();
            //$this->updateStatus($request);
            $this->updateInfo($request);
            $this->updateFamilyBackground($request);

            $this->updateAlumni($request);
            $this->updateSiblings($request);
            $this->updateChildren($request);
            $this->updateAttendeds($request);
            $this->updateHonors($request);
            $this->updateDiscontinuances($request);
            $this->updateFails($request);
            $this->updateRepeats($request);
            $this->updateSuspensions($request);
            $this->updateActivities($request);
            $this->updateIntends($request);
            $this->updateRanks($request);
            $this->updateCourseRanks($request);
            $this->updateEmergency($request);
            $this->updatePregnant($request);
            $this->updateParentInfo($request);

            $this->updateEducBackground($request);
            $this->updateUser($request);

            \App\Http\Controllers\Admin\Logs::log("Update information of student: $request->idno, User status: $request->user_status");
            DB::Commit();

            Session::flash('message', 'Information Updated!');
            return redirect("registrar_college/view_info/$request->idno");
        }
    }

    function updateFamilyBackground($request) {
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
        $updatefamilybackground->guardian = $request->guardian;
        $updatefamilybackground->g_is_living = $request->g_is_living;
        $updatefamilybackground->g_occupation = $request->g_occupation;
        $updatefamilybackground->g_phone = $request->g_phone;
        $updatefamilybackground->g_address = $request->g_address;
        $updatefamilybackground->spouse = $request->spouse;
        $updatefamilybackground->s_is_living = $request->s_is_living;
        $updatefamilybackground->s_occupation = $request->s_occupation;
        $updatefamilybackground->s_phone = $request->s_phone;
        $updatefamilybackground->s_address = $request->s_address;
        $updatefamilybackground->save();
    }

    function updateEducBackground($request) {
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
        $updateEducbackground->senior_highschool = $request->senior_highschool;
        $updateEducbackground->senior_highschool_address = $request->senior_highschool_address;
        $updateEducbackground->senior_highschool_year = $request->senior_highschool_year;
        $updateEducbackground->last_school_attended = $request->last_school_attended;
        $updateEducbackground->last_school_address = $request->last_school_address;
        $updateEducbackground->last_school_year = $request->last_school_year;
        $updateEducbackground->dean = $request->dean;
        $updateEducbackground->guidance_counselor = $request->guidance_counselor;
        $updateEducbackground->last_school_number = $request->last_school_number;
        $updateEducbackground->are_you_candidate = $request->are_you_candidate;
        $updateEducbackground->save();
    }

    function updateStatus($request) {
        
    }

    function updateInfo($request) {
        $updateInfo = \App\StudentInfo::where('idno', $request->idno)->first();
        $updateInfo->street = $request->street;
        $updateInfo->barangay = $request->barangay;
        $updateInfo->region = $request->region;
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
        $updateInfo->applied_year_course = $request->applied_year_course;
        $updateInfo->applied_leaving = $request->applied_leaving;
        $updateInfo->is_expelled_reason = $request->is_expelled_reason;
        $updateInfo->is_officer = $request->is_officer;
        $updateInfo->is_modelling = $request->is_modelling;
        $updateInfo->save();
    }

    function updateUser($request) {
        $updateUser = \App\User::where('idno', $request->idno)->first();
        $updateUser->firstname = $request->firstname;
        $updateUser->middlename = $request->middlename;
        $updateUser->lastname = $request->lastname;
        $updateUser->extensionname = $request->extensionname;
        $updateUser->email = $request->email;
        $updateUser->is_foreign = $request->is_foreign;
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $updateUser->status = $request->user_status;
        }
        $updateUser->save();
    }

    function reset_password(Request $request) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE") or Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $user = \App\User::where('idno', $request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login = 1;
            $user->update();

            \App\Http\Controllers\Admin\Logs::log("Reset password of student: $request->idno");
            return redirect(url('/registrar_college', array('view_info', $request->idno)));
        }
    }

    function print_envelope($idno) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            $student_info = \App\StudentInfo::where('idno', $idno)->first();
            $pdf = PDF::loadView('reg_college.view_info.envelope', compact('student_info'));
            $pdf->setPaper(array(0, 0, 684, 297));
            return $pdf->stream("envelope_$idno.pdf");
        }
    }

    function updateAlumni($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoAlmuni::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoAlmuni;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $alumni_name = $request->alumni_name;
        $alumni_relationship = $request->alumni_relationship;
        $alumni_year_graduated = $request->alumni_year_graduated;
        $alumni_department = $request->alumni_department;
        $alumni_location = $request->alumni_location;

        $updates = \App\StudentInfoAlmuni::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($alumni_name[$i])) {

                $add = new \App\StudentInfoAlmuni;
                $add->idno = $idno;
                $add->name = $alumni_name[$i];
                $add->relationship = $alumni_relationship[$i];
                $add->year_graduated = $alumni_year_graduated[$i];
                $add->department = $alumni_department[$i];
                $add->location = $alumni_location[$i];
                $add->save();
            }
        }
    }

    function updateSiblings($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoSibling::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoSibling;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $siblings_name = $request->siblings_name;
        $siblings_age = $request->siblings_age;
        $siblings_level = $request->siblings_level;
        $siblings_school = $request->siblings_school;

        $updates = \App\StudentInfoSibling::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($siblings_name[$i])) {

                $add = new \App\StudentInfoSibling;
                $add->idno = $idno;
                $add->name = $siblings_name[$i];
                $add->age = $siblings_age[$i];
                $add->level = $siblings_level[$i];
                $add->school = $siblings_school[$i];
                $add->save();
            }
        }
    }

    function updateChildren($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoChildren::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoChildren;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $children_name = $request->children_name;
        $children_age = $request->children_age;
        $children_level = $request->children_level;
        $children_school = $request->children_school;

        $updates = \App\StudentInfoChildren::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($children_name[$i])) {

                $add = new \App\StudentInfoChildren;
                $add->idno = $idno;
                $add->name = $children_name[$i];
                $add->age = $children_age[$i];
                $add->level = $children_level[$i];
                $add->school = $children_school[$i];
                $add->save();
            }
        }
    }

    function updateAttendeds($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoAttendedCollege::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoAttendedCollege;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $attendeds_college = $request->attendeds_college;
        $attendeds_address = $request->attendeds_address;
        $attendeds_course = $request->attendeds_course;
        $attendeds_school_year = $request->attendeds_school_year;

        $updates = \App\StudentInfoAttendedCollege::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($attendeds_college[$i])) {

                $add = new \App\StudentInfoAttendedCollege;
                $add->idno = $idno;
                $add->college = $attendeds_college[$i];
                $add->address = $attendeds_address[$i];
                $add->course = $attendeds_course[$i];
                $add->school_year = $attendeds_school_year[$i];
                $add->save();
            }
        }
    }

    function updateHonors($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoHonor::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoHonor;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $honors_honor = $request->honors_honor;
        $honors_level = $request->honors_level;
        $honors_event = $request->honors_event;

        $updates = \App\StudentInfoHonor::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($honors_honor[$i])) {

                $add = new \App\StudentInfoHonor;
                $add->idno = $idno;
                $add->honor = $honors_honor[$i];
                $add->level = $honors_level[$i];
                $add->event = $honors_event[$i];
                $add->save();
            }
        }
    }

    function updateDiscontinuances($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoDiscontinuance::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoDiscontinuance;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $discontinuances_school_year = $request->discontinuances_school_year;
        $discontinuances_reason = $request->discontinuances_reason;

        $updates = \App\StudentInfoDiscontinuance::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($discontinuances_school_year[$i])) {

                $add = new \App\StudentInfoDiscontinuance;
                $add->idno = $idno;
                $add->school_year = $discontinuances_school_year[$i];
                $add->reason = $discontinuances_reason[$i];
                $add->save();
            }
        }
    }

    function updateFails($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoFailSubject::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoFailSubject;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $fails_subject = $request->fails_subject;
        $fails_period = $request->fails_period;
        $fails_level = $request->fails_level;
        $fails_reason = $request->fails_reason;

        $updates = \App\StudentInfoFailSubject::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($fails_subject[$i])) {

                $add = new \App\StudentInfoFailSubject;
                $add->idno = $idno;
                $add->subject = $fails_subject[$i];
                $add->period = $fails_period[$i];
                $add->level = $fails_level[$i];
                $add->reason = $fails_reason[$i];
                $add->save();
            }
        }
    }

    function updateRepeats($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoRepeat::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoRepeat;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $repeats_subject = $request->repeats_subject;
        $repeats_level = $request->repeats_level;
        $repeats_reason = $request->repeats_reason;

        $updates = \App\StudentInfoRepeat::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($repeats_subject[$i])) {

                $add = new \App\StudentInfoRepeat;
                $add->idno = $idno;
                $add->subject = $repeats_subject[$i];
                $add->level = $repeats_level[$i];
                $add->reason = $repeats_reason[$i];
                $add->save();
            }
        }
    }

    function updateSuspensions($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoSuspension::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoSuspension;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $suspensions_offense = $request->suspensions_offense;
        $suspensions_penalty = $request->suspensions_penalty;
        $suspensions_period = $request->suspensions_period;

        $updates = \App\StudentInfoSuspension::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($suspensions_offense[$i])) {

                $add = new \App\StudentInfoSuspension;
                $add->idno = $idno;
                $add->offense = $suspensions_offense[$i];
                $add->penalty = $suspensions_penalty[$i];
                $add->period = $suspensions_period[$i];
                $add->save();
            }
        }
    }

    function updateActivities($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoActivity::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoActivity;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $activities_activity = $request->activities_activity;
        $activities_level = $request->activities_level;
        $activities_hours = $request->activities_hours;

        $updates = \App\StudentInfoActivity::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($activities_activity[$i])) {

                $add = new \App\StudentInfoActivity;
                $add->idno = $idno;
                $add->activity = $activities_activity[$i];
                $add->level = $activities_level[$i];
                $add->hours = $activities_hours[$i];
                $add->save();
            }
        }
    }

    function updateIntends($request) {

        $idno = $request->idno;
        $addprofile = \App\StudentInfoIntend::where('idno', $idno)->first();

        if (count($addprofile) == 0) {
            $addpro = new \App\StudentInfoIntend;
            $addpro->idno = $idno;
            $addpro->save();
        }
        $intends_college = $request->intends_college;
        $intends_course = $request->intends_course;
        $intends_is_taken = $request->intends_is_taken;

        $updates = \App\StudentInfoIntend::where('idno', $idno)->get();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($intends_college[$i])) {

                $add = new \App\StudentInfoIntend;
                $add->idno = $idno;
                $add->college = $intends_college[$i];
                $add->course = $intends_course[$i];
                $add->is_taken = $intends_is_taken[$i];
                $add->save();
            }
        }
    }

    function updateRanks($request) {
        $updaterank = \App\StudentInfoSchoolRank::where('idno', $request->idno)->first();
        $updaterank->academic_excellence = $this->checkValue($request->academic_excellence);
        $updaterank->family = $this->checkValue($request->family);
        $updaterank->friend = $this->checkValue($request->friend);
        $updaterank->ac_student = $this->checkValue($request->ac_student);
        $updaterank->womens_college = $this->checkValue($request->womens_college);
        $updaterank->security = $this->checkValue($request->security);
        $updaterank->assumption_career = $this->checkValue($request->assumption_career);
        $updaterank->newspaper = $this->checkValue($request->newspaper);
        $updaterank->values_formation = $this->checkValue($request->values_formation);
        $updaterank->college_fair = $this->checkValue($request->college_fair);
        $updaterank->parents_choice = $this->checkValue($request->parents_choice);
        $updaterank->career_opportunities = $this->checkValue($request->career_opportunities);
        $updaterank->flyer = $this->checkValue($request->flyer);
        $updaterank->hs_counselor = $this->checkValue($request->hs_counselor);
        $updaterank->courses = $this->checkValue($request->courses);
        $updaterank->ac_graduate = $this->checkValue($request->ac_graduate);
        $updaterank->location = $this->checkValue($request->location);
        $updaterank->prestige = $this->checkValue($request->prestige);
        $updaterank->save();
    }

    function checkValue($value) {
        if ($value == 0) {
            return NULL;
        } else {
            return $value;
        }
    }

    function updateCourseRanks($request) {
        $updaterank = \App\StudentInfoCoursesRank::where('idno', $request->idno)->first();
        $updaterank->rank_1 = $request->rank_1;
        $updaterank->rank_2 = $request->rank_2;
        $updaterank->rank_3 = $request->rank_3;
        $updaterank->why_most_preferred = $request->why_most_preferred;
        $updaterank->who_decided = $request->who_decided;
        $updaterank->save();
    }

    function updateEmergency($request) {
        $updaterank = \App\StudentInfoEmergency::where('idno', $request->idno)->first();
        $updaterank->lastname = $request->emer_lastname;
        $updaterank->firstname = $request->emer_firstname;
        $updaterank->middlename = $request->emer_middlename;
        $updaterank->extensionname = $request->emer_extensionname;
        $updaterank->relation = $request->emer_relation;
        $updaterank->phone = $request->emer_phone;
        $updaterank->address = $request->emer_address;
        $updaterank->business_phone = $request->emer_business_phone;
        $updaterank->mobile = $request->emer_mobile;
        $updaterank->save();
    }

    function updateParentInfo($request) {
        $updaterank = \App\StudentInfoParentInfo::where('idno', $request->idno)->first();
        
        $updaterank->g_personal_address = $request->g_personal_address;
        $updaterank->g_email = $request->g_email;
        $updaterank->g_personal_phone = $request->g_personal_phone;
        $updaterank->g_attainment = $request->g_attainment;
        $updaterank->g_citizenship = $request->g_citizenship;
        $updaterank->g_company_name = $request->g_company_name;

        $updaterank->f_personal_address = $request->f_personal_address;
        $updaterank->f_email = $request->f_email;
        $updaterank->f_personal_phone = $request->f_personal_phone;
        $updaterank->f_attainment = $request->f_attainment;
        $updaterank->f_citizenship = $request->f_citizenship;
        $updaterank->f_company_name = $request->f_company_name;

        $updaterank->m_personal_address = $request->m_personal_address;
        $updaterank->m_email = $request->m_email;
        $updaterank->m_personal_phone = $request->m_personal_phone;
        $updaterank->m_attainment = $request->m_attainment;
        $updaterank->m_citizenship = $request->m_citizenship;
        $updaterank->m_company_name = $request->m_company_name;

        $updaterank->s_personal_address = $request->s_personal_address;
        $updaterank->s_email = $request->s_email;
        $updaterank->s_personal_phone = $request->s_personal_phone;
        $updaterank->s_attainment = $request->s_attainment;
        $updaterank->s_citizenship = $request->s_citizenship;
        $updaterank->s_company_name = $request->s_company_name;
        $updaterank->s_dob = $request->s_dob;
        $updaterank->save();
    }

    function updatePregnant($request) {
        $updaterank = \App\StudentInfoPregnant::where('idno', $request->idno)->first();
        $updaterank->ever_pregnant = $request->ever_pregnant;
        $updaterank->pregnant_now = $request->pregnant_now;
        $updaterank->save();
    }

    function print_info($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('student_infos.idno', $idno)->join('student_info_parent_infos', 'student_info_parent_infos.idno', '=', 'student_infos.idno')->first();

            $pdf = PDF::loadView("reg_college.view_info.print_info", compact('user', 'info', 'idno','status'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('information-sheet-' . $idno . '.pdf');
        }
    }

}
