<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
$y=1;
?>

<h3>Assumption College</h3>
<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif
@if($section=="All")
<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Section</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>{{$name->section}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "print_student_list('w')" class="form btn btn-primary"> Print Student List</a>
    <a href="javascript:void(0)" onclick = "print_student_list('wo')" class="form btn btn-success"> Print Student List w/o ID Numbers</a>
    <a href="javascript:void(0)" onclick = "print_new_student_list('new')" class="form btn btn-info"> Print Student List of New Student</a>
    <a href="javascript:void(0)" onclick = "export_student_list('w')" class="form btn btn-primary"> Export Student List</a>
</div>
@else

<div>Section : {{$section}}</div>

<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table>

<h3>List of Students that is not yet enrolled.</h3>
<table border="1" class="table table-responsive table-striped">
    <tr>
        <th>#</th>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>Last Enrolled</th>
    </tr>
    @if(count($students)>0)
    @foreach($students as $name)
    <?php $check_status = \App\Status::where('idno', $name->idno)->first(); ?>
    @if($check_status->status != 3)
    <tr>
        <td>{{$y++}}</td>
        <td>{{$name->idno}}</td>
        <td>{{get_name($name->idno)}}</td>
        <td>
            @if($check_status->status == 3)Enrolled
            @elseif($check_status->status == 2) <strong>Assessed</strong>
            @elseif($check_status->status == 10) Pre-Registered
            @elseif($check_status->status == 11) For Approval
            @elseif($check_status->status == 4) Withdrawn-{{$check_status->date_dropped}}
            @else <strong>Not Yet Enrolled</strong> @endif
        </td>
        <td><?php $getLevels = \App\BedLevel::where('idno',$name->idno)->orderBy('school_year','desc')->first(); ?>
        @if(isset($getLevels))
        {{$getLevels->school_year}}
        @endif
        </td>
    </tr>
    @endif
    @endforeach
    @endif
</table>
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "print_student_list('w')" class="form btn btn-primary"> Print Student List w/ ID Numbers</a>
    <a href="javascript:void(0)" onclick = "print_student_list('wo')" class="form btn btn-success"> Print Student List w/o ID Numbers</a>
    <a href="javascript:void(0)" onclick = "print_new_student_list('new')" class="form btn btn-info"> Print Student List of New Student</a>
    <a href="javascript:void(0)" onclick = "export_student_list('w')" class="form btn btn-primary"> Export Student List</a>
</div>    
@endif