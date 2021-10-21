@extends('layouts.appcollege_instructor')
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success"></span>
    </a>
</li>
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
</li>

<li class="dropdown tasks-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <span class="label label-danger"></span>
    </a>
</li>
@endsection
@section('header')
<?php

            $addparent = \App\CtrCollegeGrading::where('idno', Auth::user()->idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\CtrCollegeGrading;
                $addpar->idno = Auth::user()->idno;
                $addpar->academic_type = "College";
                $addpar->save();
            }
            
$close = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno',Auth::user()->idno)->first();
?>
<section class="content-header">
    <h1>
        {{$course_name}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('/college_instructor', array('grades', $schedule_id))}}">View List</a></li>
    </ol>
    
    @if ($close->midterm == 1 && $close->finals == 1 && $close->grade_point == 1)
    <br>
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info"></i> Note!</h4>Giving of grades is not yet open.
    </div>
    @endif
</section>
@endsection
@section('maincontent')
<?php $number = 1; $raw = ""; $allsection=""; ?>
@foreach ($courses_id as $key => $course_id)
<?php 
if ($key == 0){
$raw = $raw. " course_offering_id = ".$course_id->id;
$allsection = $allsection. "$course_id->section_name";
} else {
$raw = $raw. " or course_offering_id = ".$course_id->id;
$allsection = $allsection. "/$course_id->section_name";
}
?>
@endforeach
<?php
$school_year = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->school_year;
$period = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->period;
$students = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'college_levels.is_audit', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
$checkstatus_midterm0 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 0)->get();
$checkstatus_midterm = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 2)->get();
$checkstatus_midterm3 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 3)->get();

$checkstatus_finals0 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 0)->get();
$checkstatus_finals = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 2)->get();
$checkstatus_finals3 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 3)->get();
?>
@if (count($students)>0)

<form class="form form-horizontal" method="post" action="{{url('college_instructor', array('grades','save_submit'))}}">
    {{csrf_field()}}
    <input type="hidden" name="schedule_id" value="{{$schedule_id}}">
    <input type="hidden" name="midterm_status" value="{{$close->midterm}}">
    <input type="hidden" name="finals_status" value="{{$close->finals}}">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Section: {{$allsection}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="8%">ID number</th>
                            <th>Name</th>
                            <th width="5%">Midterm Absences</th>
                            <th width="5%">Midterm</th>
                            <th width="5%">Finals Absences</th>
                            <th width="5%">Finals</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$number}}<?php $number = $number + 1; ?></td>
                            <td>{{$student->idno}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} @if($student->is_audit==1)(Audit-Special Learning Needs) @elseif($student->is_audit==2) (Audit-Special Interest) @elseif($student->is_audit==3) (Exchange Student) @endif</td>
                            <td>
                                <input @if($close->midterm == 1) readonly="" @endif value="{{$student->midterm_absences}}" name="midterm_absences[{{$student->id}}]" id="midterm_absences" onchange="change_midterm_absences(this.value, {{$student->id}}, '{{$student->idno}}')"
                            </td>
                            <td>
                                <select class="grade" name="midterm[{{$student->id}}]" id="midterm" onchange="change_midterm(this.value, {{$student->id}}, '{{$student->idno}}')"
                                @if($student->midterm_status == 3)
                                disabled=''>
                                @else
                                
                                @if($student->midterm_status == 0 && $close->midterm == 0)
                                
                                @elseif($student->midterm_status == 1 && $close->midterm >= 0)
                                disabled='' style="color:green"
                                @elseif($student->midterm_status == 0 && $close->midterm == 1)
                                disabled=''
                                @elseif($student->midterm_status == 2 && $close->midterm >= 0)
                                disabled='' style="color:blue"
                                @elseif($student->midterm_status == 3 && $close->midterm >= 0)
                                disabled=''
                                @endif
                                >
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
                            <td>
                                <input @if($close->finals == 1) readonly="" @endif value="{{$student->finals_absences}}" name="finals_absences[{{$student->id}}]" id="finals_absences" onchange="change_finals_absences(this.value, {{$student->id}}, '{{$student->idno}}')"
                            </td>
                            <td>
                                <select class="grade" name="finals[{{$student->id}}]" id="finals" onchange="change_finals(this.value, {{$student->id}}, '{{$student->idno}}')"
                                @if($student->finals_status == 3)
                                disabled=''>
                                @else
                                
                                @if($student->finals_status == 0 && $close->finals == 0)
                                
                                @elseif($student->finals_status == 1 && $close->finals >= 0)
                                disabled='' style="color:green"
                                @elseif($student->finals_status == 0 && $close->finals == 1)
                                disabled=''
                                @elseif($student->finals_status == 2 && $close->finals >= 0)
                                disabled='' style="color:blue"
                                @elseif($student->finals_status == 3 && $close->finals >= 0)
                                disabled=''
                                @endif      
                                >
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    <div class='col-sm-12 form-group'>
    <div class="col-sm-2">
        <a targe="_blank" href='{{url('college_instructor', array('export_list',$schedule_id))}}'><div class="btn btn-warning col-sm-12">Export in Excel</div></a>
    </div>
    <div class="col-sm-2">
        <a href='{{url('college_instructor', array('print_list', $schedule_id))}}' target="_blank"><div class="btn btn-info col-sm-12">Print Class List</div></a>
    </div>
    <div class="col-sm-2">
        <a href='{{url('college_instructor', array('print_grade', $school_year,$period,$schedule_id))}}' target="_blank"><div class="btn btn-info col-sm-12">Print Grade Record</div></a>
    </div>
    </div>
    <div class='col-sm-12 form-group'>
    @if($close->midterm == 0)
    @if (count($checkstatus_midterm3) == count($students))
    
    @else
    @if ($close->midterm == 0 || $close->finals == 0)
    @if (count($checkstatus_midterm) == count($students))
        <div class="col-sm-6">
            
        <input type='submit' name="submit" onclick="if (confirm('Do you really want to forward and finalize MIDTERM grades?'))
                    return true;
                else
                    return false;" class='btn btn-warning col-sm-12' value="Forward to Records and Finalize MIDTERM grades">
        </div>
    @elseif (count($checkstatus_midterm0) > 0)
        <div class="col-sm-6">
            <input type='submit' name="submit" onclick="if (confirm('Do you really want to save and submit MIDTERM grades?'))
                        return true;
                    else
                        return false;" class='btn btn-success col-sm-12' value="Save & Submit MIDTERM grades for Checking of Dean">
        </div>
    @else
    @endif
    @else
    @endif
    @endif
    @endif
    
    
    @if($close->finals == 0)
    @if (count($checkstatus_finals3) == count($students))
    
    @else
    @if ($close->midterm == 0 || $close->finals == 0)
    @if (count($checkstatus_finals) == count($students))
        <div class="col-sm-6">
            
        <input type='submit' name="submit" onclick="if (confirm('Do you really want to forward and finalize FINALS grades?'))
                    return true;
                else
                    return false;" class='btn btn-warning col-sm-12' value="Forward to Records and Finalize FINALS grades">
        </div>
    @elseif (count($checkstatus_finals0) > 0)
        <div class="col-sm-6">
            <input type='submit' name="submit" onclick="if (confirm('Do you really want to save and submit FINALS grades?'))
                        return true;
                    else
                        return false;" class='btn btn-success col-sm-12' value="Save & Submit FINALS grades for Checking of Dean">
        </div>
    @else
    @endif
    @else
    @endif
    @endif
    @endif
    </div>
</form>
@endsection
@section('footerscript')  

<script>
    function change_midterm(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_midterm/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    function change_finals(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_finals/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    function change_midterm_absences(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_midterm_absences/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    function change_finals_absences(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_finals_absences/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    function change_grade_point(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_grade_point/" + idno,
            data: array,
            success: function () {
            }
        });
    }

    $(document).ready(function () {
        $(".grade").on('keypress', function (e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault)
                    theEvent.preventDefault();
            }
        });

    });
</script>
@endsection
