<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
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
        Subject Related Fee
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i> Home</li>
        <li class="active"><a href="{{url('/dean/srf')}}"> Subject Related Fee</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><span class='fa fa-search'></span> Search</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group" id="level-form">
                        <label>School Year</label>
                        <select class="form form-control select2" id="school_year" style="width: 100%;" onchange='get_courses(this.value, period.value)'>
                            <option value="">Select School Year</option>
                            <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="period-form">
                        <label>Period</label>
                        <select class="form form-control select2" id="period" style="width: 100%;" onchange='get_courses(school_year.value, this.value)'>
                            <option value="">Select Period</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" id="course_code-form">
                        <label>Course</label>
                        <select class="form form-control select2" id="course_code" style="width: 100%;">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="submit-form">
                        <label><br></label>
                        <button type="submit" class="btn btn-success col-sm-12" onclick="displayResult(course_code.value,school_year.value,period.value)">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>        
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><span class='fa fa-edit'></span> Result</h3>
            <a onclick='print_result(course_code.value,school_year.value,period.value)'><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <div id="result">
            </div>
        </div>   
    </div>        
</div>
@endsection
@section('footerscript')
<script>
    function get_courses(school_year,period) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        $.ajax({
            type: "GET",
            url: "/ajax/dean/srf/get_courses/",
            data: array,
            success: function (data) {
                $('#course_code-form').html(data);
            }

        });
    }
    function displayResult(course_code,school_year,period) {
        array = {};
        array['course_code'] = course_code;
        array['school_year'] = school_year;
        array['period'] = period;
        $.ajax({
            type: "GET",
            url: "/ajax/dean/srf/get_student_list/",
            data: array,
            success: function (data) {
                $('#result').html(data);
            }

        });
    }
    
    function print_result(course_code,school_year,period) {
        array = {};
        array['course_code'] = course_code;
        array['school_year'] = school_year;
        array['period'] = period;
        
        window.open('/dean/srf/print_student_list_now/' + array['school_year'] + "/" + array['period'] + "/" + array['course_code'], "_blank") ;
    }
</script>
@endsection
