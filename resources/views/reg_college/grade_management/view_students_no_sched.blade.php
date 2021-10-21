<?php
//$instructor_id = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
//$close = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $instructor_id)->first();
?>
<?php $number = 1;
$raw = "";
$allsection = ""; ?>
<?php
//if ($key == 0){
$raw = $raw . "grade_colleges.school_year = " . $school_year . " and grade_colleges.period = '" . $period . "' and grade_colleges.course_offering_id is not null and course_code = '" . $course_code . "'";
//$allsection = $allsection. "$course_id->section_name";
//} else {
//$raw = $raw. " or course_code = ".$course_code;
////$allsection = $allsection. "/$course_id->section_name";
//}
?>
<?php
$students = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'users.middlename', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.midterm_absences', 'grade_colleges.finals', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status', 'college_levels.is_audit')->orderBy('users.lastname')->get();
$checkstatus_midterm1 = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 1)->get();
$checkstatus_midterm = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 2)->get();
$checkstatus_midterm3 = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 3)->get();

$checkstatus_finals1 = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 1)->get();
$checkstatus_finals = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 2)->get();
$checkstatus_finals3 = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 3)->get();
?>
@if (count($students)>0)
<div class='col-sm-12'><div class='pull-right'><a target='_blank' href="{{url('registrar_college',array('print_grade_list', $school_year,$period,'no_sched',$course_code))}}"><button class='btn btn-primary'>Print Grade</button></a></div></div>
<form class="form form-horizontal" method="post" action="{{url('college_instructor', array('grades','save_submit'))}}">
    {{csrf_field()}}
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Section: All Sections</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="8%">ID number</th>
                            <th>Name</th>
                            <th width="10%">Midterm Absences</th>
                            <th width="10%">Midterm</th>
                            <th>Status</th>
                            <th width="10%">Finals Absences</th>
                            <th width="10%">Finals</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$number}}<?php $number = $number + 1; ?></td>
                            <td>{{$student->idno}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->is_audit==1)(Audit-Special Learning Needs) @elseif($student->is_audit==2) (Audit-Special Interest) @elseif($student->is_audit==3) (Exchange Student) @endif</td>
                            <td><input class='grade' type="text" value="{{$student->midterm_absences}}" size=3 readonly=""></td>
                            <td>
                                @if($student->midterm_status<=2)
                                <select class="grade" name="midterm[{{$student->id}}]" id="midterm" onchange="change_midterm(this.value, {{$student->id}}, '{{$student->idno}}')">
                                    @else
                                <select disabled="" class="grade" name="midterm[{{$student->id}}]" id="midterm" onchange="change_midterm(this.value, {{$student->id}}, '{{$student->idno}}')">
                                    @endif
                                    <option></option>
                                    <option @if ($student->midterm == "PASSED") selected='' @endif>PASSED</option>
                                    <option @if ($student->midterm == 1.00) selected='' @endif>1.00</option>
                                    <option @if ($student->midterm == 1.20) selected='' @endif>1.20</option>
                                    <option @if ($student->midterm == 1.50) selected='' @endif>1.50</option>
                                    <option @if ($student->midterm == 1.70) selected='' @endif>1.70</option>
                                    <option @if ($student->midterm == 2.00) selected='' @endif>2.00</option>
                                    <option @if ($student->midterm == 2.20) selected='' @endif>2.20</option>
                                    <option @if ($student->midterm == 2.50) selected='' @endif>2.50</option>
                                    <option @if ($student->midterm == 2.70) selected='' @endif>2.70</option>
                                    <option @if ($student->midterm == 3.00) selected='' @endif>3.00</option>
                                    <option @if ($student->midterm == 3.50) selected='' @endif>3.50</option>
                                    <option @if ($student->midterm == 4.00) selected='' @endif>4.00</option>
                                    <option @if ($student->midterm == "FAILED") selected='' @endif>FAILED</option>
                                    <option @if ($student->midterm == "FA") selected='' @endif>FA</option>
                                    <option @if ($student->midterm == "INC") selected='' @endif>INC</option>
                                    <option @if ($student->midterm == "NA") selected='' @endif>NA</option>
                                    <option @if ($student->midterm == "NG") selected='' @endif>NG</option>
                                    <option @if ($student->midterm == "UD") selected='' @endif>UD</option>
                                    <option @if ($student->midterm == "W") selected='' @endif>W</option>
                                    <option @if ($student->midterm == "AUDIT") selected='' @endif>AUDIT</option>
                                </select>
                            </td>
                            @if(auth::user()->accesslevel == env('DEAN'))
                            <td>
                                @if($student->midterm_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                @elseif ($student->midterm_status == 1)
                                <div class="btn btn-danger col-sm-12" onclick="unlock_midterm('{{$student->idno}}', schedule_id.value, '{{$student->id}}')">Back this grade to Instructor</div>
                                @elseif ($student->midterm_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                            @if(auth::user()->accesslevel == env('REG_COLLEGE'))
                            <td>
                                @if($student->midterm_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                @elseif ($student->midterm_status == 1)
                                <i class="label label-primary">For checking</i>
                                @elseif ($student->midterm_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                            <td><input class='grade' type="text" value="{{$student->finals_absences}}" size=3 readonly=""></td>
                            <td>
                                @if($student->finals_status<=2)
                                <select class="grade" name="finals[{{$student->id}}]" id="finals" onchange="change_finals(this.value, {{$student->id}}, '{{$student->idno}}')">
                                    @else
                                    <select disabled="" class="grade" name="finals[{{$student->id}}]" id="finals" onchange="change_finals(this.value, {{$student->id}}, '{{$student->idno}}')">
                                        @endif
                                        <option></option>
                                        <option @if ($student->finals == "PASSED") selected='' @endif>PASSED</option>
                                        <option @if ($student->finals == 1.00) selected='' @endif>1.00</option>
                                        <option @if ($student->finals == 1.20) selected='' @endif>1.20</option>
                                        <option @if ($student->finals == 1.50) selected='' @endif>1.50</option>
                                        <option @if ($student->finals == 1.70) selected='' @endif>1.70</option>
                                        <option @if ($student->finals == 2.00) selected='' @endif>2.00</option>
                                        <option @if ($student->finals == 2.20) selected='' @endif>2.20</option>
                                        <option @if ($student->finals == 2.50) selected='' @endif>2.50</option>
                                        <option @if ($student->finals == 2.70) selected='' @endif>2.70</option>
                                        <option @if ($student->finals == 3.00) selected='' @endif>3.00</option>
                                        <option @if ($student->finals == 3.50) selected='' @endif>3.50</option>
                                        <option @if ($student->finals == 4.00) selected='' @endif>4.00</option>
                                        <option @if ($student->finals == "FAILED") selected='' @endif>FAILED</option>
                                        <option @if ($student->finals == "FA") selected='' @endif>FA</option>
                                        <option @if ($student->finals == "INC") selected='' @endif>INC</option>
                                        <option @if ($student->finals == "NA") selected='' @endif>NA</option>
                                        <option @if ($student->finals == "NG") selected='' @endif>NG</option>
                                        <option @if ($student->finals == "UD") selected='' @endif>UD</option>
                                        <option @if ($student->finals == "W") selected='' @endif>W</option>
                                        <option @if ($student->finals == "AUDIT") selected='' @endif>AUDIT</option>
                                    </select>
                            </td>
                            @if(auth::user()->accesslevel == env('DEAN'))
                            <td>
                                @if($student->finals_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                <!--<div class="btn btn-warning col-sm-12" onclick="lock({{$student->idno}}, schedule_id.value, {{$student->id}})">Lock</div>-->
                                @elseif ($student->finals_status == 1)
                                <div class="btn btn-danger col-sm-12" onclick="unlock_finals('{{$student->idno}}', schedule_id.value, '{{$student->id}}')">Backs this grade to Instructor</div>
                                @elseif ($student->finals_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                            @if(auth::user()->accesslevel == env('REG_COLLEGE'))
                            <td>
                                @if($student->finals_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                @elseif ($student->finals_status == 1)
                                <i class="label label-primary">For checking</i>
                                @elseif ($student->finals_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif


    @if(auth::user()->accesslevel == env('DEAN'))
    <div class="col-sm-6">
        <span onclick="if (confirm('Do you really want to approve MIDTERM grades?'))
                    return approveall_midterm(schedule_id.value, course_code.value);
            else
                    return false;" class='btn btn-success col-sm-12' >Approve MIDTERM grades</span>
    </div>

    <!--    <div class="col-sm-6">
            <span onclick="if (confirm('Do you really want to cancel submission of midterm grades?'))
                        return cancelall_midterm(schedule_id.value);
                    else
                        return false;" class='btn btn-danger col-sm-12' >Cancel submission of MIDTERM grades</span>
        </div>-->
    @endif



    @if(auth::user()->accesslevel == env('DEAN'))
    <div class="col-sm-6">
        <span onclick="if (confirm('Do you really want to approve FINAL grades?'))
                    return approveall_finals(schedule_id.value, course_code.value);
            else
                    return false;" class='btn btn-success col-sm-12' >Approve FINAL Grades</span>
    </div>

    <!--    <div class="col-sm-6">
            <span onclick="if (confirm('Do you really want to cancel submission of final grades?'))
                        return cancelall_finals(schedule_id.value);
                    else
                        return false;" class='btn btn-danger col-sm-12' >Cancel submission of FINAL grades</span>
        </div>-->
    @endif

</form>