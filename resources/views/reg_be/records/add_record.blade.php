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
                    <form class='form form-horizontal' method="post" action="{{url('transcript_of_records',array($idno,'add'))}}">
                        {{ csrf_field() }}
                        <div class="col-sm-12">
                            <input name="idno" type="hidden" value="{{$idno}}">
                            <input name="id" type="hidden" value="{{($id) ? $id : ""}}">
                            <div class="form form-group">
                                <div class="col-sm-6">
                                    <label>School Name</label>
                                    <input name="school_name" type='text' class='form form-control' value="{{($record) ? $record->school_name : ""}}">
                                </div>
                            </div>
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
                                <div class="col-sm-2">
                                <label>Subject Code</label>
                                <input name="subject_code" type='text' class='form form-control' value="{{($record) ? $record->subject_code : ""}}">
                                </div>
                                <div class="col-sm-5">
                                <label>Subject Name</label>
                                <input name="subject_name" type='text' class='form form-control' value="{{($record) ? $record->subject_name : ""}}">
                                </div>
                                <div class="col-sm-5">
                                <label>Display Subject Name</label>
                                <input name="card_name" type='text' class='form form-control' value="{{($record) ? $record->card_name : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-2">
                                <label>Level</label>
                                <input name="level" type='text' class='form form-control' placeholder="Grade 1" value="{{($record) ? $record->level : ""}}">
                                </div>
                                <div class="col-sm-2">
                                <label>Units</label>
                                <input name="units" type='text' class='form form-control' placeholder="1" value="{{($record) ? $record->units : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-2">
                                    <label>1st Grading</label>
                                    <input name="first_grading" type='text' class='form form-control' value="{{($record) ? $record->first_grading : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>2nd Grading</label>
                                    <input name="second_grading" type='text' class='form form-control' value="{{($record) ? $record->second_grading : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>3rd Grading</label>
                                    <input name="third_grading" type='text' class='form form-control' value="{{($record) ? $record->third_grading : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>4th Grading</label>
                                    <input name="fourth_grading" type='text' class='form form-control' value="{{($record) ? $record->fourth_grading : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>Final Grade</label>
                                    <input name="final_grade" type='text' class='form form-control' value="{{($record) ? $record->final_grade : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-2">
                                    <label>1st Letter Grade</label>
                                    <input name="first_grading_letter" type='text' class='form form-control' value="{{($record) ? $record->first_grading_letter : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>2nd Letter Grade</label>
                                    <input name="second_grading_letter" type='text' class='form form-control' value="{{($record) ? $record->second_grading_letter : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>3rd Letter Grade</label>
                                    <input name="third_grading_letter" type='text' class='form form-control' value="{{($record) ? $record->third_grading_letter : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>4th Letter Grade</label>
                                    <input name="fourth_grading_letter" type='text' class='form form-control' value="{{($record) ? $record->fourth_grading_letter : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>Final Letter Grade</label>
                                    <input name="final_grade_letter" type='text' class='form form-control' value="{{($record) ? $record->final_grade_letter : ""}}">
                                </div>
                            </div>
                            <div class="form form-group">
                                <div class="col-sm-2">
                                    <label>1st Remarks</label>
                                    <input name="first_remarks" type='text' class='form form-control' value="{{($record) ? $record->first_remarks  : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>2nd Remarks</label>
                                    <input name="second_remarks" type='text' class='form form-control' value="{{($record) ? $record->second_remarks : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>3rd Remarks</label>
                                    <input name="third_remarks" type='text' class='form form-control' value="{{($record) ? $record->third_remarks : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>4th Remarks</label>
                                    <input name="fourth_remarks" type='text' class='form form-control' value="{{($record) ? $record->fourth_remarks : ""}}">
                                </div>
                                <div class="col-sm-2">
                                    <label>Final Remarks</label>
                                    <input name="final_remarks" type='text' class='form form-control' value="{{($record) ? $record->final_remarks : ""}}">
                                </div>
                            </div>
                            <input type="submit" name="button" value="{{$type}}" class="btn btn-success">
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
