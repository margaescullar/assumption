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
        SRF Balances Report
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
                        <select class="form form-control select2" id="school_year" style="width: 100%;">
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
                        <select class="form form-control select2" id="period" style="width: 100%;">
                            <option value="">Select Period</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" id="program-form">
                        <label>Program</label>
                        <select class="form form-control select2" id="program" style="width: 100%;">
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                            <option value="{{$program->program_code}}">{{$program->program_code}}-{{$program->program_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" id="submit-form">
                        <label><br></label>
                        <button type="submit" class="btn btn-success col-sm-12" onclick="displayResult(school_year.value,period.value, program.value)">Search</button>
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
            <a onclick='print_result(school_year.value,period.value, program.value)'><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <div id="result">
            </div>
        </div>   
    </div>        
</div>

<div class="modal fade" id="show_subjects">

</div>
@endsection
@section('footerscript')
<script>
    function displayResult(school_year,period,program) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        array['program'] = program;
        $.ajax({
            type: "GET",
            url: "/ajax/dean/srf/get_srf_balances/",
            data: array,
            success: function (data) {
                $('#result').html(data);
            }

        });
    }
    
    function get_subjects(idno,school_year,period,program) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        array['program'] = program;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/dean/srf/get_subjects/",
            data: array,
            success: function (data) {
                $('#show_subjects').html(data);
            }

        });
    }
    
    function print_result(school_year,period,program) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        array['program'] = program;
        
        window.open('/dean/srf/print_srf_balances/' + array['school_year'] + "/" + array['period'] + "/" + array['program'], "_blank") ;
    }
</script>
@endsection
