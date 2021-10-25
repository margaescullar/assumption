<?php
//$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
//$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
?>
    
<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
}else if(Auth::user()->accesslevel == env('AA')){
$layout = "layouts.appaa";
} else {
$layout = "layouts.appreg_college";
}
?>

@extends($layout)
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
<section class="content-header">
    <h1>
        Grades
        <small>A.Y. {{$school_year}}-{{$school_year+1}} - {{$period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','view_grades'))}}">View Grades</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
    <div class='form-horizontal'>
        <div class='form-group'>
            <div class='col-sm-2'>
                <label>School Year</label>
                <select class="form form-control select2" name="school_year" id='school_year'>
                    <option value="">Select School Year</option>
                    <option value="2017" @if ($school_year == 2017) selected = "" @endif>2017-2018</option>
                <option value="2018" @if ($school_year == 2018) selected = "" @endif>2018-2019</option>
                <option value="2019" @if ($school_year == 2019) selected = "" @endif>2019-2020</option>
                <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                </select>
            </div>
            <div class='col-sm-2'>
                <label>Period</label>
                <select class="form form-control select2" name="period" id='period'>
                    <option value="">Select Period</option>
                    <option value='1st Semester' @if ($period == "1st Semester") selected = "" @endif>1st Semester</option>
                    <option value='2nd Semester' @if ($period == "2nd Semester") selected = "" @endif>2nd Semester</option>
                    <option value='Summer' @if ($period == "Summer") selected = "" @endif>Summer</option>
                </select>    
            </div>   
            <div class='col-sm-4'>
                <label>&nbsp;</label>
                <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
            </div>
        </div>    
    </div>
    <div class='box'>
        <div class='box-body'>
            @if ($school_year>="2017")
            <?php $courses = \App\CourseOffering::distinct()->where('school_year', $school_year)->where('period', $period)->get(['course_code', 'course_name']); ?>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Select Courses</label>
                    <select class="form form-control select2" onchange="selectSchedule(this.value)" id="course_code">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                        <option value="{{$course->course_code}}">{{$course->course_code}} - {{$course->course_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4" id="sched">
            </div>
            <div class="col-sm-2" id="search">
                <label>&nbsp;</label>
                <button class="btn btn-primary col-sm-12" onclick="displayList(schedule_id.value,course_code.value)">Search</button>
            </div>
            
            @else
            <?php $courses = \App\CollegeGrades2018::distinct()->where('school_year', $school_year)->where('period', $period)->get(['course_code']); ?>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Select Course</label>
                    <select class="form form-control select2" id="old_course_code" onchange="displayOldList(old_course_code.value, '{{$school_year}}', '{{$period}}')">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                        <option value="{{$course->course_code}}">{{$course->course_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @endif
        </div>
    </div>
</div>
<div id="result">

</div>
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){
      $("#view-button").on('click',function(e){
        document.location="{{url('/registrar_college',array('grade_management'))}}" + "/view_grades/" + $("#school_year").val() + "/" + $("#period").val();
      });
    });
</script>
<script>
    $('#search').hide();
    function selectSchedule(course_code) {
        array = {};
        array['course_code'] = course_code;
        array['school_year'] = "{{$school_year}}";
        array['period'] = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/get_schedules",
            data: array,
            success: function (data) {
                $('#sched').hide().html(data).fadeIn();
                $('#search').fadeIn();
            }

        });
    }
    function displayList(schedule_id,course_code) {
        array = {};
        array['course_code'] = course_code;
        array['schedule_id'] = schedule_id;
        array['school_year'] = "{{$school_year}}";
        array['period'] = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/get_list_students",
            data: array,
            success: function (data) {
                $('#result').hide().html(data).fadeIn();
            }

        });
    }
    
    function displayOldList(course_code, school_year, period) {
        array = {};
        array['course_code'] = course_code;
        array['school_year'] = school_year;
        array['period'] = period;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/get_oldlist_students",
            data: array,
            success: function (data) {
                $('#result').hide().html(data).fadeIn();
            }

        });
    }
    
    function lock(idno, schedule_id, id){
        array = {};
        array['schedule_id'] = schedule_id;
        array['grade_id'] = id;
        array['school_year'] = "{{$school_year}}";
        array['period'] = "{{$period}}";
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/lock/" + idno + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function unlock_midterm(idno, schedule_id, id){
        array = {};
        array['schedule_id'] = schedule_id;
        array['grade_id'] = id;
        array['school_year'] = "{{$school_year}}";
        array['type'] = "midterm";
        array['period'] = "{{$period}}";
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/unlock/" + idno + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function unlock_finals(idno, schedule_id, id){
        array = {};
        array['schedule_id'] = schedule_id;
        array['grade_id'] = id;
        array['school_year'] = "{{$school_year}}";
        array['type'] = "finals";
        array['period'] = "{{$period}}";
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/unlock/" + idno + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function approveall_finals(schedule_id,course_code){
        array = {};
        array['schedule_id'] = schedule_id;
        array['course_code'] = course_code;
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/approve_all_finals" + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function cancelall_finals(schedule_id){
        array = {};
        array['schedule_id'] = schedule_id;
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/cancel_all_finals" + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function approveall_midterm(schedule_id,course_code){
        array = {};
        array['schedule_id'] = schedule_id;
        array['course_code'] = course_code;
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/approve_all_midterm" + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function cancelall_midterm(schedule_id){
        array = {};
        array['schedule_id'] = schedule_id;
        school_year = "{{$school_year}}";
        period = "{{$period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/cancel_all_midterm" + '/' + school_year + '/'+ period,
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    
    function change_midterm(grade, grade_id, idno,stat) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        array['stat'] = stat;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grades/change_midterm/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    
    function change_finals(grade, grade_id, idno,stat) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        array['stat'] = stat;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grades/change_finals/" + idno,
            data: array,
            success: function () {
            }
        });
    }
</script>
@endsection