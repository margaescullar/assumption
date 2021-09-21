<?php $year = date('Y'); ?>

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
        Change Grading Period
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','grading_sy'))}}">Change Grading Period</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-6'>
    <div class='box'>
        <div class='box-body'>
            <form class='form-horizontal' action='{{url('registrar_college', array('grade_management','grading_sy', $school_year->id))}}' method='post'>
                {{ csrf_field() }}
                <table class="table table-condensed">
                    <tr>
                        <td>School Year</td>
                        <td>Period</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="school_year" class="form form-control">
                                <option @if($school_year->school_year == "2019") selected="" @endif>2019</option>
                                <option @if($school_year->school_year == "2020") selected="" @endif>2020</option>
                                <option @if($school_year->school_year == "2021") selected="" @endif>2021</option>
                                <option @if($school_year->school_year == "2022") selected="" @endif>2022</option>
                                <option @if($school_year->school_year == "2023") selected="" @endif>2023</option>
                            </select>
                        </td>
                        <td>
                            <select name="period" class="form form-control">
                                <option @if($school_year->period == "1st Semester") selected="" @endif>1st Semester</option>
                                <option @if($school_year->period == "2nd Semester") selected="" @endif>2nd Semester</option>
                                <option @if($school_year->period == "Summer") selected="" @endif>Summer</option>
                            </select>
                        <td><input type="submit" value="Update"  class="btn btn-success"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
@endsection