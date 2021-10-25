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
        Set Subject Related Fee
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i> Home</li>
        <li>Subject Related Fee</li>
        <li class="active"><a href="{{url('dean', array('srf', 'modify', $course_code))}}"> {{$course_code}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{$course_code}} - {{$course_name}} - {{$period}}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
            <div class="col-sm-4">
                <form class="form-horizontal" method="post" action="{{url('dean', array('srf','set_srf'))}}">
                    <div class="form-group">
                        <label>Set Subject Related Fee</label>
                        {{ csrf_field() }}
                        <input type="text" class="form-control" name="srf" value="{{$srf}}">
                        <input type="hidden" name="course_code" value="{{$course_code}}">
                        <input type="hidden" name="period" value="{{$period}}">
                    
                        <label>Laboratory Fee</label>
                        <input type="text" class="form-control" name="lab_fee" value="{{$lab_fee}}">
                        <input type="hidden" name="course_code" value="{{$course_code}}">
                        
                        <label>Group</label>
                        <select name="srf_group" class="form-control" required="">
                            <option value="">Select Group</option>
                            <option @if($srf_group == "General Education") selected="" @endif>General Education</option>
                            <option @if($srf_group == "Laboratory") selected="" @endif>Laboratory</option>
                            <option @if($srf_group == "Thesis") selected="" @endif>Thesis</option>
                            <option @if($srf_group == "Business Department") selected="" @endif>Business Department</option>
                            <option @if($srf_group == "Education Department") selected="" @endif>Education Department</option>
                            <option @if($srf_group == "Psychology Department") selected="" @endif>Psychology Department</option>
                            <option @if($srf_group == "Performing Department") selected="" @endif>Performing Department</option>
                            <option @if($srf_group == "Communication Department") selected="" @endif>Communication Department</option>
                            <option @if($srf_group == "Interior Design Department") selected="" @endif>Interior Design Department</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Set SRF" class="col-sm-12 btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>        
</div>
@endsection
@section('footerscript')
@endsection
