<?php
$programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(array('program_code', 'program_name'));
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
        View Course Offerings
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','view_course_offering'))}}"> View Course Offering</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
<!--schedule per room-->
    <div class="row">  
        <div class="col-sm-12">
            <h4>Per Room</h4>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>School Year</label>
                                <select id="school_year" class="form-control select2" style="width: 100%;">
                                    <option>Select school year</option>
                                    <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Period</label>
                                <select id="period" class="form-control select2" style="width: 100%;" onchange="get_room(school_year.value, this.value)">
                                    <option>Select period</option>
                                    <option value="1st Semester">1st Semester</option>
                                    <option value="2nd Semester">2nd Semester</option>
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>
                        </div>
<!--                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Program</label>
                                <select id="program_code" class="form-control select2" style="width: 100%;">
                                    <option>Select program</option>
                                    @foreach ($programs as $program)
                                    <option value="{{$program->program_code}}">{{$program->program_code}} - {{$program->program_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Level</label>
                                <select id="level" class="form-control select2" style="width: 100%;" onchange="get_room(school_year.value, period.value, program_code.value, this.value)">
                                    <option>Select level</option>
                                    <option>1st Year</option>
                                    <option>2nd Year</option>
                                    <option>3rd Year</option>
                                    <option>4th Year</option>
                                    <option>5th Year</option>
                                </select>
                            </div>
                        </div>-->
                        <div class="col-md-2">
                            <div class="form-group" id="room-form">
                                <label>Room</label>
                                <select id="room" class="form-control select2" style="width: 100%;">
                                    <option>Select room</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="room-form">
                                <button class="btn btn-success col-sm-12" onclick="get_offerings_room(school_year.value, period.value,  room.value)">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="offerings"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    function get_room(school_year, period,){
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
//        array['program_code'] = $("#program_code").val();
//        array['level'] = $("#level").val();
        $.ajax({
        type: "GET",
                url: "/ajax/registrar_college/curriculum_management/get_room/",
                data: array,
                success: function (data) {
                $('#room-form').html(data);
                }

        });
    }
    
    function get_offerings_room(school_year, period, room){
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
//        array['program_code'] = $("#program_code").val();
//        array['level'] = $("#level").val();
        array['room'] = $("#room").val();
        $.ajax({
        type: "GET",
                url: "/ajax/registrar_college/curriculum_management/get_offerings_room/",
                data: array,
                success: function (data) {
                $('#offerings').html(data);
                }

        });
    }
</script>
@endsection