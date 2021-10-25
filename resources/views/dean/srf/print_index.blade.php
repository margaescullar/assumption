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
        Print SRF
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i> Home</li>
        <li class="active"><a href="{{url('/dean/srf/print_srf')}}"> Print SRF</a></li>
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
                    <div class="form-group" id="program-form">
                        <label>Program</label>
                        <select class="form form-control select2" id="program" style="width: 100%;">
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                            <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="curriculum-form">
                        <label>Curriculum Year</label>
                        <select class="form form-control select2" id="curriculum_year" style="width: 100%;">
                            <option value="">Select Curriculum Year</option>
                            @foreach ($curriculum_years as $curriculum_year)
                            <option value="{{$curriculum_year->curriculum_year}}">{{$curriculum_year->curriculum_year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="level-form">
                        <label>Level</label>
                        <select class="form form-control select2" id="level" style="width: 100%;">
                            <option value="">Select Level</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="period-form">
                        <label>Period</label>
                        <select class="form form-control select2" id="period" style="width: 100%;">
                            <option value="">Select Period</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="submit-form">
                        <label><br></label>
                        <button type="submit" class="btn btn-success col-sm-12" onclick="displayResult(program.value,curriculum_year.value,level.value,period.value)">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>        
</div>
<div class="col-md-12" id="bb">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><span class='fa fa-edit'></span> Result</h3>
            <a onclick='print_search(program.value,curriculum_year.value,level.value,period.value)'><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
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
    $("#level-form").hide();
    $("#period-form").hide();
    $("#curriculum-form").hide();
    $("#submit-form").hide();
    $("#bb").hide();

    $("#program-form").change(function () {
        $("#period-form").hide();
        $("#submit-form").hide();
        $("#level-form").hide();
    $("#bb").hide();
        $("#curriculum-form").fadeIn();
    });
    $("#curriculum-form").change(function () {
        $("#submit-form").hide();
    $("#bb").hide();
        $("#period-form").hide();
        $("#level-form").fadeIn();
    });
    $("#level-form").change(function () {
        $("#submit-form").hide();
    $("#bb").hide();
        $("#period-form").fadeIn();
    });
    $("#period-form").change(function () {
    $("#bb").hide();
        $("#submit-form").fadeIn();
    });
</script>  
<script>
    function displayResult(program,curriculum_year,level,period) {
        array = {};
        array['program_code'] = program;
        array['level'] = level;
        array['period'] = period;
        array['curriculum_year'] = curriculum_year;
        $.ajax({
            type: "GET",
            url: "/ajax/dean/srf/print_get_list/",
            data: array,
            success: function (data) {
                $('#bb').fadeIn();
                $('#result').html(data);
            }

        });
    }
    
    function print_search(program,curriculum_year,level,period) {
        array = {};
        array['program_code'] = program;
        array['level'] = level;
        array['period'] = period;
        array['curriculum_year'] = curriculum_year;
        
        window.open('/dean/srf/print_srf_now/' + array['program_code'] + "/" + array['level'] + "/" + array['period'] + "/" + array['curriculum_year'], "_blank") ;
    }
</script>
@endsection
