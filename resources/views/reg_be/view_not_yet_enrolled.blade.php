<?php
$i = 1;
?>

<h3>Assumption College</h3>
<?php
if ($department == "Elementary") {
    $academic_type = "BED";
} else {
    $academic_type = "SHS";
}
$present_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', $academic_type)->first();
?>
<h4>Student Not Yet Enrolled for <br>School Year: {{$present_school_year->school_year}}<br>
    @if($academic_type == "SHS")
    Period: {{$present_school_year->period}}
    @endif

</h4>
<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Previous Level</th><th>Previous Strand</th><th>Section</th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{$name->getFullNameAttribute()}}</td><td>{{$name->level}}</td><td>{{$name->strand}}</td><td>{{$name->section}}</td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif

</table> 