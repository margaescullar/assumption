<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$curriculum_years = \App\Curriculum::distinct()->where('program_code', $program_code)->get(['curriculum_year']);
$levels = \App\Curriculum::distinct()->where('program_code', $program_code)->orderBy('level')->get(['level']);
$periods = \App\Curriculum::distinct()->where('program_code', $program_code)->orderBy('period')->get(['period']);
$program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first(['program_name']);
?>
<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
@extends('layouts.appreg_college')
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
        Course Offering
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"></i> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','course_offering'))}}"></i> Course Offering</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{$program_code}} - {{$program_name->program_name}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" id="curriculum_year-form">
                                <label>Curriculum Year</label>
                                <select id="curriculum_year" class="form-control select2" style="width: 100%;">
                                    <option value=" ">Select Curriculum</option>
                                    @foreach ($curriculum_years as $curriculum_year)
                                    <option value="{{$curriculum_year->curriculum_year}}">{{$curriculum_year->curriculum_year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="level-form">
                                <label>Level</label>
                                <select id="level" class="form-control select2" style="width: 100%;">
                                    <option value=" ">Select Level</option>
                                    @foreach ($levels as $level)
                                    <option value="{{$level->level}}">{{$level->level}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="period-form">
                            <div class="form-group">
                                <label>Period</label>
                                <select id="period" class="form-control select2" style="width: 100%;">
                                    <option value=" ">Select Period</option>
                                    @foreach ($periods as $period)
                                    <option value="{{$period->period}}">{{$period->period}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="section-form">
                                <label>Section</label>
                                <select id="section" class="form-control select2" style="width: 100%;" onchange="getList('{{$program_code}}')">
                                    <option value=" ">Select Section</option>
                                    <option value="1">Section 1</option>
                                    <option value="2">Section 2</option>
                                    <option value="3">Section 3</option>
                                    <option value="4">Section 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div id="course_to_offer">
            </div>
        </div>
        <div class="col-sm-6">
            <div id="course_offered">
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $("#level-form").hide();
    $("#period-form").hide();
    $("#section-form").hide();
    
    $("#curriculum_year-form").change(function(){
        $("#level-form").fadeIn();
    });
    $("#level-form").change(function(){
        $("#period-form").fadeIn();
    });
    $("#period-form").change(function(){
        $("#section-form").fadeIn();
    });
</script>
<script>
    function getList(program_code){
    array = {};
    array['curriculum_year'] = $("#curriculum_year").val();
    array['level'] = $("#level").val();
    array['period'] = $("#period").val();
    array['section'] = $("#section").val();
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/view_offering/" + program_code,
            data: array,
            success: function (data) {
            $('#course_to_offer').hide().html(data).fadeIn();
            }
    });
    getCourseOffered(array, program_code);
    }

    function getCourseOffered(array, program_code){
    array['curriculum_year'];
    array['level'];
    array['period'];
    array['section'];
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/view_course_offered/" + program_code,
            data: array,
            success: function (data) {
            $('#course_offered').hide().html(data).fadeIn();
            }

    });
    }

    function addtocourseoffering(course_code) {
    array = {};
    array['curriculum_year'] = $("#curriculum_year").val();
    array['level'] = $("#level").val();
    array['period'] = $("#period").val();
    array['section'] = $("#section").val();
    array['program_code'] = $("#program_code").val();
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/add_to_course_offered/" + course_code,
            data: array,
            success: function (data) {
            $('#course_offered').html(data);
            }

    });
    }
    
    function addAllSubjects() {
    array = {};
    array['program_code'] = $("#program_code").val();
    array['curriculum_year'] = $("#curriculum_year").val();
    array['section'] = $("#section").val();
    array['level'] = $("#level").val();
    array['period'] = $("#period").val();
    array['course_code'] = $("#course_code").val();
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/add_all_to_course_offered/",
            data: array,
            success: function (data) {
            $('#course_offered').html(data);
            }

    });
    }
    
    function removecourse(id) {
    array = {};
    array['id'] = id;
    array['program_code'] = $("#program_code").val();
    array['curriculum_year'] = $("#curriculum_year").val();
    array['section'] = $("#section").val();
    array['level'] = $("#level").val();
    array['period'] = $("#period").val();
    if (confirm("Are You Sure To Remove?")) {
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/remove_course_offered/" + id,
            data: array,
            success: function (data) {
            $('#course_offered').html(data);
            }

    });
    }
    }

</script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>
@endsection