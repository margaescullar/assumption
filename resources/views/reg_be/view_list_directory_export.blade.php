<?php
function get_name($idno,$schoolyear,$period){
    $names = \App\User::where('idno',$idno)->first();
    if($period == "Select Period"){
    $is_new = \App\BedLevel::where('idno',$idno)->where('school_year', $schoolyear)->first();
    }else{
    $is_new = \App\BedLevel::where('idno',$idno)->where('school_year', $schoolyear)->where('period', $period)->first();
    }
    
    if($names->middlename == NULL){
        $names->middlename = "";
    }else{
        $names->middlename = "(".ucwords(strtolower($names->middlename)).")";
    }
    
    if ($is_new->status == 4){
        $print = "Withdrawn-". $is_new->date_dropped;
    } else {
        $print = "";
    }
    
    return strtoupper($names->lastname).", ".ucwords(strtolower($names->firstname))." ".$names->middlename." ".$print;

    
    }
function get_ns($idno,$schoolyear,$period){
    
    if($period == "Select Period"){
    $is_new = \App\BedLevel::where('idno',$idno)->where('school_year', $schoolyear)->first();
    }else{
    $is_new = \App\BedLevel::where('idno',$idno)->where('school_year', $schoolyear)->where('period', $period)->first();
    }
    if ($is_new->is_new == 1 && $is_new->level != "Pre-Kinder"){
    return " NS";
    } else {
        return "";
    }
}
$i=1;
?>

@if($section=="All")
<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        <th style="font-size: 12pt" colspan="2">
        <center>
            {{$level}}
                @if($level=="Grade 11" || $level=="Grade 12")
                    ({{$strand}})
                @endif
                
        </center></th>
        <th width="5%" align="center">Sect</th>
        
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
    
    @if($period == "Select Period")
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->first(); ?>
    @else
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->where('period', $period)->first(); ?>
    @endif
    <tr>
        <td>{{$i++}}.</td>
        <td width="10%">{{$name->idno}}</td>
        <td width="50%">
            @if ($is_new->is_new == 1)
            <strong><i>{{get_name($name->idno,$schoolyear,$period)}}{{get_ns($name->idno,$schoolyear,$period)}}</i></strong>
            @else
            {{get_name($name->idno,$schoolyear,$period)}}{{get_ns($name->idno,$schoolyear,$period)}}
            @endif
        </td>
        <td align="center">{{$name->section}}</td>
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
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 
@else
<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        <th colspan="2" style="font-size: 12pt">
        <center>
            {{$level}}
                @if($level=="Grade 11" || $level=="Grade 12")
                    ({{$strand}})
                @endif
                - {{$section}}
        </center></th>
        
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
        <th>Tel No.</th>
        <th>Cell No</th>
        <th>Email</th>
        <th>Mother</th>
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
    
    @if($period == "Select Period")
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->first(); ?>
    @else
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->where('period', $period)->first(); ?>
    @endif
    <tr>
        <td>{{$i++}}.</td>
        <td width="10%">{{$name->idno}}</td>
        <td width="40%">
            @if ($is_new->is_new == 1)
            <strong><i>{{get_name($name->idno,$schoolyear,$period)}}{{get_ns($name->idno,$schoolyear,$period)}}</i></strong>
            @else
            {{get_name($name->idno,$schoolyear,$period)}}{{get_ns($name->idno,$schoolyear,$period)}}
            @endif
        </td>
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
        <td>{{$get_parent->f_phone}}</td>
        <td>{{$get_parent->f_cell_no}}</td>
        <td>{{$get_parent->f_email}}</td>
        <td>{{$get_parent->mother}}</td>
        <td>{{$get_parent->m_phone}}</td>
        <td>{{$get_parent->m_cell_no}}</td>
        <td>{{$get_parent->m_email}}</td>
        <td>
            @if(count($get_siblings)> 0)
            @foreach($get_siblings as $sibling)
            {{$sibling->sibling}},
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
    <tr><td colspan="13">No List For This Level</td></tr>
    @endif
    
    <tr><td>{{date('M d, Y')}}</td></tr>
</table>    

 
@endif
