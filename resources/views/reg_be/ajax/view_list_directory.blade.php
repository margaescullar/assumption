<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
?>
<div class="table-responsive">
<h3>Assumption College</h3>
<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif
@if($section=="All")
<table border="1" class="table table-responsive table-striped">
    <tr>
        <th>#</th>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Section</th>
        @if(Auth::user()->accesslevel == env('EDUTECH'))
        <th>Main Email</th>
        <th>Father's Email</th>
        <th>Mother's Email</th>
        @else
        <th>Street</th>
        <th>Barangay</th>
        <th>Municipality/City</th>
        <th>Province</th>
        <th>Zip</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
        <th>Father</th>
        <th>Company</th>
        <th>Tel No.</th>
        <th>Cell No</th>
        <th>Email</th>
        <th>Mother</th>
        <th>Company</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
        <th>Siblings</th>
        @endif
    </tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <?php $get_directory = \App\BedProfile::where('idno',$name->idno)->first(); ?>
    <?php $email = \App\User::where('idno',$name->idno)->first(); ?>
    <?php $get_parent = \App\BedParentInfo::where('idno',$name->idno)->first(); ?>
    <?php $get_siblings = \App\BedSiblings::where('idno',$name->idno)->get(); ?>
    <tr>
        <td>{{$i++}}</td>
        <td>{{$name->idno}}</td>
        <td>{{get_name($name->idno)}}</td>
        <td>{{$name->section}}</td>
        @if(count($get_directory)>0)
        @if(Auth::user()->accesslevel == env('EDUTECH'))
        <td>{{$email->email}}</td>
        <td>{{$get_parent->f_email}}</td>
        <td>{{$get_parent->m_email}}</td>
        @else
        <td>{{$get_directory->street}}</td>
        <td>{{$get_directory->barangay}}</td>
        <td>{{$get_directory->municipality}}</td>
        <td>{{$get_directory->province}}</td>
        <td>{{$get_directory->zip}}</td>
        <td>{{$get_directory->tel_no}}</td>
        <td>{{$get_directory->cell_no}}</td>
        <td>{{$email->email}}</td>
        <td>{{$get_parent->father}}</td>
        <td>{{$get_parent->f_company_name}}</td>
        <td>{{$get_parent->f_phone}}</td>
        <td>{{$get_parent->f_cell_no}}</td>
        <td>{{$get_parent->f_email}}</td>
        <td>{{$get_parent->mother}}</td>
        <td>{{$get_parent->m_company_name}}</td>
        <td>{{$get_parent->m_phone}}</td>
        <td>{{$get_parent->m_cell_no}}</td>
        <td>{{$get_parent->m_email}}</td>
        <td>
            @if(count($get_siblings)> 0)
            @foreach($get_siblings as $sibling)
            {{$sibling->sibling}}<br>
            @endforeach
            @endif
        </td>
        @endif
        @else
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @endif
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="8">No List For This Level</td>
    </tr>
    @endif
    
</table> 
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "export_student_directory()" class="form btn btn-primary"> Print Student List</a>
</div> 
@else

<div>Section : {{$section}}</div>

<table border="1" class="table table-responsive table-striped">
    <tr>
        <th>#</th>
        <th>Student ID</th>
        <th>Student Name</th>
        @if(Auth::user()->accesslevel == env('EDUTECH'))
        <th>Main Email</th>
        <th>Father's Email</th>
        <th>Mother's Email</th>
        @else
        <th>Street</th>
        <th>Barangay</th>
        <th>Municipality/City</th>
        <th>Province</th>
        <th>Zip</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
        <th>Father</th>
        <th>Company</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
        <th>Mother</th>
        <th>Company</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
        <th>Siblings</th>
        @endif
    </tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <?php $get_directory = \App\BedProfile::where('idno',$name->idno)->first(); ?>
    <?php $email = \App\User::where('idno',$name->idno)->first(); ?>
    <?php $get_parent = \App\BedParentInfo::where('idno',$name->idno)->first(); ?>
    <?php $get_siblings = \App\BedSiblings::where('idno',$name->idno)->get(); ?>
    <tr>
        <td>{{$i++}}</td>
        <td>{{$name->idno}}</td>
        <td>{{get_name($name->idno)}}</td>
        @if(count($get_directory)>0)
        @if(Auth::user()->accesslevel == env('EDUTECH'))
        <td>{{$email->email}}</td>
        <td>{{$get_parent->f_email}}</td>
        <td>{{$get_parent->m_email}}</td>
        @else
        <td>{{$get_directory->street}}</td>
        <td>{{$get_directory->barangay}}</td>
        <td>{{$get_directory->municipality}}</td>
        <td>{{$get_directory->province}}</td>
        <td>{{$get_directory->zip}}</td>
        <td>{{$get_directory->tel_no}}</td>
        <td>{{$get_directory->cell_no}}</td>
        <td>{{$email->email}}</td>
        <td>{{$get_parent->father}}</td>
        <td>{{$get_parent->f_company_name}}</td>
        <td>{{$get_parent->f_phone}}</td>
        <td>{{$get_parent->f_cell_no}}</td>
        <td>{{$get_parent->f_email}}</td>
        <td>{{$get_parent->mother}}</td>
        <td>{{$get_parent->m_company_name}}</td>
        <td>{{$get_parent->m_phone}}</td>
        <td>{{$get_parent->m_cell_no}}</td>
        <td>{{$get_parent->m_email}}</td>
        <td>
            @if(count($get_siblings)> 0)
            @foreach($get_siblings as $sibling)
            {{$sibling->sibling}}<br>
            @endforeach
            @endif
        </td>
        @endif
        @else
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @endif
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="8">No List For This Level</td>
    </tr>
    @endif
    
</table>    
</div>
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "export_student_directory()" class="form btn btn-primary"> Export Directory</a>
</div>    
@endif