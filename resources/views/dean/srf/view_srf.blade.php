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
                <input type="hidden" value="all" id="program">
<!--                <div class="col-md-3">
                    <div class="form-group" id="program-form">
                        <label>Program</label>
                        <select class="form form-control select2" id="program" style="width: 100%;">
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                            <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>-->
                <div class="col-md-3">
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
                    <div class="form-group" id="submit-form">
                        <label><br></label>
                        <button type="submit" class="btn btn-success col-sm-12" onclick="displayResult(program.value,level.value,period.value)">Search</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="period-form">
                        <label>Search Course Code</label>
                        <input type="text" id="search" class="form-control" placeholder="Type Course Code...">
                    </div>
                </div>
            </div>
        </div>
    </div>        
</div>
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><span class='fa fa-edit'></span> Set Up</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
<!--<script>
    $("#level-form").hide();
    $("#period-form").hide();
    $("#submit-form").hide();

    $("#program-form").change(function () {
        $("#period-form").hide();
        $("#submit-form").hide();
        $("#level-form").fadeIn();
    });
    $("#level-form").change(function () {
        $("#submit-form").hide();
        $("#period-form").fadeIn();
    });
    $("#period-form").change(function () {
        $("#submit-form").fadeIn();
    });
</script>  -->
<script>
    function displayResult(program,level,period) {
        array = {};
        array['program_code'] = program;
        array['level'] = level;
        array['period'] = period;
        $.ajax({
            type: "GET",
            url: "/ajax/dean/srf/get_list/",
            data: array,
            success: function (data) {
                $('#result').html(data);
            }

        });
    }
    $(document).ready(function(){
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['search'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/ajax/dean/srf/get_search_list/",
                  data:array,
                  success:function(data){
                    $('#result').html(data);
                    $("#search").val("");
                  }
              })
          } 
       }); 
    });
</script>
@endsection
