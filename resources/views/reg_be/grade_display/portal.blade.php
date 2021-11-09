@extends("layouts.appbedregistrar")
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Portal Display Settings
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Portal Display Settings</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-6">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">View Grades</div>
        </div>
        <div class="box-body">
            <i>Note: This settings is for the displaying of Grades in the Student Portal. This is not for the displaying of report card.</i>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Levels</th>
                        <th>Current Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($view_grades as $view_grade)
                    <tr>
                        <td>{{$view_grade->level}}</td>
                        <td>@if($view_grade->is_display == 1) Open for Viewing @else <b style='color: red'>Close for Viewing</b> @endif</td>
                        <td><a href='{{url('/bedregistrar',array('grade_portal_display_settings','update_levels',$view_grade->level))}}'>Change Status</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>
<div class="col-md-6">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">Report Card</div>
        </div>
        <div class="box-body">
            <i>Note: This settings is for the displaying of Grades in the Student Portal for Report Card only.</i>
            <table class="table table-condensed">
                <form method='post' action='{{url('/update_report_card_sy_display')}}'>
                    {{csrf_field()}}
                <tr>
                    <td>School Year</td>
                    <td><input type='text' class='form-control' name='school_year' value='{{$period_setting->school_year}}'></td>
                </tr>
                <tr>
                    <td>Period</td>
                    <td><input type='text' class='form-control' name='period' value='{{$period_setting->period}}'></td>
                </tr>
                <tr>
                    <td>Is Open?</td>
                    <td>
                        <select name="is_open" class="form-control">
                            <option value="1" @if($period_setting->is_open == 1) selected=selected @endif>Yes</option>
                            <option value="0" @if($period_setting->is_open == 0) selected=selected @endif>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type='submit' value='Update Report Card Settings' class='btn btn-success col-sm-12'></td>
                </tr>
                </form>
            </table>
        </div>
    </div> 
</div>
@endsection
