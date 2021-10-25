<?php
    if(Auth::user()->accesslevel == env('REG_COLLEGE')){
    $layout = "layouts.appreg_college";
    } else if (Auth::user()->accesslevel == env('DEAN')){
    $layout = "layouts.appreg_college";
    } else if (Auth::user()->accesslevel == env('AA')){
    $layout = "layouts.appaa";
    } else {
    $layout = "layouts.appadmission-hed";
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
        Student List
        <small>Per Instructor</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li><a href="#"> Student List</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('reports','student_list','per_instructor'))}}"></i> Per Instructor</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Search</h3>
                </div>
                <div class="box-body">
                    <div class='form-horizontal'>
                        <div class='form-group'>
                            <div class='col-sm-3'>
                                <label>Instructor</label>
                                <select class="form form-control select2" name="instructor_id" id="instructor_id" onchange="get_course(this.value,school_year.value,period.value)">
                                    <option value="">Select Instructor</option>
                                    @foreach ($instructors as $instructor)
                                    <option value="{{$instructor->idno}}">{{$instructor->firstname}} {{$instructor->lastname}} {{$instructor->extensionname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <label>School Year</label>
                                <select class="form form-control select2" name="school_year" id="school_year" onchange="get_course(instructor_id.value,this.value,period.value)">
                                    <option value="">School Year</option>
                                    <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <label>Period</label>
                                <select class="form form-control select2" name="period" id="period" onchange="get_course(instructor_id.value,school_year.value,this.value)">
                                    <option value="">Period</option>
                                    <option>1st Semester</option>
                                    <option>2nd Semester</option>
                                    <option>Summer</option>
                                </select>
                            </div>
                            <div class='col-sm-2' id="form-course_code">
                                <label>Course</label>
                                <select class="form form-control select2" name="course_code" id="course_code">
                                    <option value="">Course</option>
                                </select>
                            </div>
                            <div class='col-sm-3' id="form-schedule_id">
                                <label>Schedule</label>
                                <select class="form form-control select2" name="schedule_id" id="schedule_id">
                                    <option value="">Schedule</option>
                                </select>
                            </div>
                        </div>
                        <div class="form form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-primary col-sm-12" onclick="getstudentlist(instructor_id.value,school_year.value,period.value,course_code.value,schedule_id.value)">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box" id="studentlist">
            </div>
        </div>
    </div>
</section>
@endsection
@section('footerscript')
<script>
    function get_course(instructor_id, school_year,period){
        array = {};
        array['instructor_id'] = instructor_id;
        array['school_year'] = school_year;
        array['period'] = period;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/reports/student_list/list_per_instructor/get_course",
            data: array,
            success: function (data) {
                $('#form-course_code').html(data);
            }

        });
    }
    function get_schedule(instructor_id, school_year,period,course_code){
        array = {};
        array['instructor_id'] = instructor_id;
        array['school_year'] = school_year;
        array['period'] = period;
        array['course_code'] = course_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/reports/student_list/list_per_instructor/get_schedule",
            data: array,
            success: function (data) {
                $('#form-schedule_id').html(data);
            }

        });
    }
    function getstudentlist(instructor_id, school_year,period,course_code,schedule_id){
        array = {};
        array['schedule_id'] = schedule_id;
        array['instructor_id'] = instructor_id;
        array['school_year'] = school_year;
        array['period'] = period;
        array['course_code'] = course_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/reports/student_list/list_per_instructor/getstudentlist",
            data: array,
            success: function (data) {
                $('#studentlist').html(data);
            }

        });
    }
    
    function print_per_instructor(instructor_id,school_year, period,course_code, schedule_id){
        array = {};
        array['schedule_id'] = schedule_id;
        array['instructor_id'] = instructor_id;
        array['school_year'] = school_year;
        array['period'] = period;
        array['course_code'] = course_code;
        window.open('/registrar_college/reports/student_list/print_per_instructor/' + array['instructor_id'] + "/" + array['school_year'] + "/" + array['period'] + "/" + array['schedule_id'] + "/" + array['course_code'], "_blank") ;
    }
    
</script>
@endsection