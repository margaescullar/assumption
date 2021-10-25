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
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li><a href="#"> Student List</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('reports','student_list','search'))}}"></i> Search</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Search Parameters</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class='form-horizontal'>
                        <div class='form-group'>
                            <div class='col-sm-2'>
                                <label>School Year</label>
                                <select id='school_year' class='form-control select2'>
                                    <option value='all'>All</option>
                                    <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
<!--                                    @foreach ($school_years as $school_year)
                                    <option value='{{$school_year->school_year}}'>{{$school_year->school_year}}-{{$school_year->school_year+1}}</option>
                                    @endforeach-->
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <label>Period</label>
                                <select id='period' class='form-control select2'>
                                    <option value='all'>All</option>
                                    <option value='1st Semester'>1st Semester</option>
                                    <option value='2nd Semester'>2nd Semester</option>
                                    <option value='Summer'>Summer</option>
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <label>Level</label>
                                <select id='level' class='form-control select2'>
                                    <option value='all'>All</option>
                                    <option value='1st Year'>1st Year</option>
                                    <option value='2nd Year'>2nd Year</option>
                                    <option value='3rd Year'>3rd Year</option>
                                    <option value='4th Year'>4th Year</option>
                                </select>
                            </div>
                            <div class='col-sm-6'>
                                <label>Program</label>
                                <select id='academic_program' class='form-control select2'>
                                    <option value='all'>All</option>
                                    @foreach ($programs as $program)
                                    <option value='{{$program->program_code}}'>{{$program->program_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='form form-group'>
                            <div class='col-sm-12'>
                                <button class='col-sm-12 btn btn-success' onclick='search(school_year.value, period.value, level.value, academic_program.value)'>SEARCH</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id='display_search'>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $('#display_search').hide();
</script>
<script>
    function search(school_year, period, level, program_code) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        array['level'] = level;
        array['program_code'] = program_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/reports/student_list/search",
            data: array,
            success: function (data) {
                $('#display_search').fadeIn().html(data);
            }

        });
    }
    
    function print_search(school_year, period, level, program_code) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        array['level'] = level;
        array['program_code'] = program_code;
        
        window.open('/registrar_college/reports/student_list/print_search/' + array['school_year'] + "/" + array['period'] + "/" + array['level'] + "/" + array['program_code'], "_blank") ;
    }
</script>
@endsection