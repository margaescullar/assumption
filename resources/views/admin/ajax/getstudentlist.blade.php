@if(count($lists)>0)
<div class='table-responsive'>
    <table class="table table-striped table-condensed">
        <tr><th>ID Number</th><th>Name</th><th>Access Level</th><th>View Information</th></tr>
        @foreach($lists as $list)
        @if($list->accesslevel > '0')
        <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
            <td>
                @switch ($list->accesslevel)
                @case (1)
                    College Instructor
                @break
                @case (10)
                    Dean
                @break
                @case (11)
                    Dean MESIL
                @break
                @case (12)
                    Dean MSBMW
                @break
                @case (20)
                    Registrar HED
                @break
                @case (21)
                    Registrar BED
                @break
                @case (22)
                    OSA
                @break
                @case (23)
                    EduTech
                @break
                @case (24)
                    BED Academic Director
                @break
                @case (25)
                    BED Class Lead
                @break
                @case (26)
                    OAA
                @break
                @case (27)
                    Club Moderator
                @break
                @case (30)
                    Accounting Head
                @break
                @case (31)
                    Accounting Staff
                @break
                @case (40)
                    Cashier
                @break
                @case (100)
                    Admin
                @break
                @case (50)
                    Bookstore
                @break
                @case (60)
                    Admission HED
                @break
                @case (61)
                    Admission BED
                @break
                @case (62)
                    Admission SHS
                @break
                @case (70)
                    Guidance HED
                @break
                @case (71)
                    Guidance BED
                @break
                @case (80)
                    Scholarship HED
                @break
                @case (81)
                    Scholarship BED
                @break
                @case (90)
                    BED Academic
                @break
                @endswitch
            </td>
            <td><a href="{{url('/admin', array('view_information', $list->idno))}}">View</a></td></tr>
        @endif
        @endforeach
    </table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

