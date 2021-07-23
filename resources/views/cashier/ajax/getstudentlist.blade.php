@if(count($lists)>0)
<?php $sy = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year; ?>
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>View Ledger</th></tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <?php $status = \App\Status::where('idno',$list->idno)->first(); ?>
    @if(isset($status))
    @if($status->status < 5 || $status->status == 11)
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td><td><a  href="{{url('/cashier',array('viewledger',$sy,$list->idno))}}">View Student Ledger</a></td>
    </tr>
    @endif
    @else
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td><td>No record</td>
    </tr>
    @endif
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

