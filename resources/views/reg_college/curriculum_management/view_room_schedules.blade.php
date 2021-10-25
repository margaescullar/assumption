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
<li class="dropdown messages-menu no-print">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success"></span>
    </a>
</li>
<li class="dropdown notifications-menu no-print">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
</li>

<li class="dropdown tasks-menu no-print">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <span class="label label-danger"></span>
    </a>
</li>
@endsection
@section('header')
<section class="content-header no-print">
    <h1>
        View Room Schedules
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','view_room_schedules'))}}"> View Room Schedules</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="box">
    <div class="box-body no-print">
        <div class="col-md-2">
            <div class="form-group">
                <label>School Year</label>
                <select id="school_year" class="form-control select2" style="width: 100%;" onchange="select_room()">
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
                <select id="period" class="form-control select2" style="width: 100%;" onchange="select_room()">
                    <option>Select period</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
        </div>
        <div class="col-md-2" id="room-form">
            <div class="form-group">
                <label>Room</label>
                <select id="room" class="form-control select2" style="width: 100%;">
                    <option>Select room</option>
                </select>
            </div>
        </div>
        <div class="col-md-2" id="room-form">
            <div class="form-group">
                <label>&nbsp;</label>
                <button class="col-sm-12 btn btn-primary" onclick="generateRoom()">Generate Report</button>
            </div>
        </div>
        <div class="col-md-2" id="room-form">
            <div class="form-group">
                <label>&nbsp;</label>
                <button class="col-sm-12 btn btn-warning" onclick="print_now()">Print Now</button>
            </div>
        </div>
    </div>
    <div class="box-body" id="generateRoom">
        
    </div>
</div>
@endsection

@section('footerscript')
<script>
    function select_room(){        
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        $.ajax({
        type: "GET",
                url: "/ajax/registrar_college/curriculum_management/get_rooms/",
                data: array,
                success: function (data) {
                $('#room-form').html(data);
                }

        });
    }
    
    function generateRoom(){
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        array['room'] = $("#room").val();
        $.ajax({
        type: "GET",
                url: "/ajax/registrar_college/curriculum_management/generateRoom/",
                data: array,
                success: function (data) {
                $('#generateRoom').html(data);
                }

        });
    }
    
    function print_now(){
        window.open("/registrar_college/curriculum_management/print_room_schedule/" +$("#school_year").val() + "/" + $("#period").val() + "/" + $("#room").val())
    }
</script>
@endsection