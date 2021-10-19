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
        Add Grade Record
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Add Grade Record</li>
    </ol>
</section>
@endsection
@section('maincontent')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class='box'>
                <div class='box-body'>
                    <form class='form form-horizontal' method="post" action="{{url('transcript_of_records',array($idno,'update_gwa'))}}">
                        {{ csrf_field() }}
                        <div class="col-sm-12">
                            <input name="idno" type="hidden" value="{{$idno}}">
                            <input name="id" type="hidden" value="{{($id) ? $id : ""}}">
                            <div class="form form-group">
                                <div class="col-sm-4">
                                    <label>School Year(2018, 2019, 2020, etc.)</label>
                                    <input name="school_year" type='text' class='form form-control' value="{{($record) ? $record->school_year : ""}}">
                                </div>
                                <div class="col-sm-4">
                                    <label>Period</label>
                                    <input name="period" type='text' class='form form-control' placeholder="Leave blank if N/A"  value="{{($record) ? $record->period : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-3">
                                <label>Level</label>
                                <input name="level" type='text' class='form form-control' placeholder="Grade 1" value="{{($record) ? $record->level : ""}}">
                                </div>
                                <div class="col-sm-3">
                                <label>Strand</label>
                                <input name="strand" type='text' class='form form-control' placeholder="ABM, STEM, HUMSS, AD" value="{{($record) ? $record->strand : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-3">
                                <label>General Average</label>
                                <input name="gwa" type='text' class='form form-control' placeholder="00.000" value="{{($record) ? $record->gwa : ""}}">
                                </div>
                                <div class="col-sm-3">
                                <label>General Letter Average</label>
                                <input name="gwa_letter" type='text' class='form form-control' placeholder="A" value="{{($record) ? $record->gwa_letter : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-3">
                                    <label>Days of School</label>
                                    <input name="days_of_school" type='text' class='form form-control' value="{{($record) ? $record->days_of_school : ""}}">
                                </div>
                                <div class="col-sm-3">
                                    <label>Days Present</label>
                                    <input name="days_present" type='text' class='form form-control' value="{{($record) ? $record->days_present : ""}}">
                                </div>
                            </div>
                            
                            <input type="submit" name="button" value="Update Record" class="btn btn-success">
                        </div>
                    </form>  
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection
