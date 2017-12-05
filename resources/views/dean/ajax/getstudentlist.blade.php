@if(count($lists)>0)
<table class="table table-responsive table-striped table-condensed">
    <tr><th>Student ID</th><th>Student Name</th><th>Assess</th><th>View Record</th></tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td><td><a href="{{url('/dean',array('assessment',$list->idno))}}">Assess</a></td>
    <td><a href="{{url('/dean',array('viewrecord',$list->idno))}}">View Record</a></td></tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

