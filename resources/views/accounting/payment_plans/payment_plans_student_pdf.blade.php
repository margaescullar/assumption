<?php
if ($level == "1st Year" || $level == "2nd Year" || $level == "3rd Year" || $level == "4th Year") {
    $records = \App\CollegeLevel::join('users','users.idno','college_levels.idno')->where('school_year', $school_year)->where('period', $period)->where('level', $level)->orderBy('type_of_plan')->orderBy('lastname')->get();
} elseif ($level == "Grade 11" || $level == "Grade 12") {
    $records = \App\BedLevel::join('users','users.idno','bed_levels.idno')->where('level', $level)->where('school_year', $school_year)->where('period', $period)->orderBy('type_of_plan')->orderBy('lastname')->get();
} else {
    $records = \App\BedLevel::join('users','users.idno','bed_levels.idno')->where('level', $level)->where('school_year', $school_year)->orderBy('type_of_plan')->orderBy('lastname')->get();
}
?>
<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 10pt;
        }
</style>
<strong>Assumption College</strong><br>
{{$level}}<br>
@if($level == "1st Year" || $level == "2nd Year" or $level == "3rd Year" or $level == "4th Year" or $level == "Grade 11" or $level == "Grade 12")
{{$school_year}} - {{$period}}
@else
{{$school_year}}
@endif
<h3>Payment Plans List</h3>
@foreach($records->groupBy('level') as $conRecord=>$levels)
<table class="table table-condensed">
    <tr>
        <td colspan='4' align='center'><h1>{{$conRecord}}<h1></td>
    </tr>
    @foreach($levels->groupBy('type_of_plan') as $planRecord=>$plan)
    <tr>
        <td colspan='4' align='center'><h3>{{$planRecord}}</h3></td>
    </tr>
    <tr>
        <td></td>
        <td>ID Number</td>
        <td>Name</td>
        <td align='center'>Section</td>
    </tr>
    <?php $control=1; ?>
    @foreach($plan as $displayPlan)
    <tr>
        <td>{{$control++}}.</td>
        <td>{{$displayPlan->idno}}</td>
        <td>{{$displayPlan->lastname}}, {{$displayPlan->firstname}} {{$displayPlan->middlename}}</td>
        <td align='center'>{{$displayPlan->section}}</td>
    </tr>
    @endforeach
    @endforeach
</table>
<hr>
@endforeach