<?php
function get_name($idno,$schoolyear,$period){
    $names = \App\User::where('idno',$idno)->first();
    if($period == "Select Period"){
    $is_widthraw = \App\BedLevel::where('idno',$idno)->where('school_year', $schoolyear)->first();
    }else{
    $is_widthraw = \App\BedLevel::where('idno',$idno)->where('school_year', $schoolyear)->where('period', $period)->first();
    }
    
    if($names->middlename == NULL){
        $names->middlename = "";
    }else{
        $names->middlename = "(".ucwords(strtolower($names->middlename)).")";
    }
    
    if ($is_widthraw->status == 4){
        $print = "Withdrawn-". $is_widthraw->date_dropped;
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
<body>
    @if(Auth::user()->accesslevel == env('BED_CL'))
<script type="text/php">
        if ( isset($pdf) ) {
            $watermark = $pdf->open_object();
            $x = 110;
            $y = 450;
            $text = "C O N F I D E N T I A L";
            $font = $fontMetrics->get_font("helvetica");
            $size = 40;
            $color = array(0.565, 0.565, 0.565);
            $pdf->set_opacity(0.9);
            $pdf->text($x, $y, $text, $font, $size,$color);
            $pdf->close_object();
            $pdf->add_object($watermark, 'all');
        }
</script>
@endif
    
    
<center>
<div><strong>Assumption College</strong></div>
<div>Basic Education Division</div>
<div>School Year {{$schoolyear}}@if($level == "Grade 11" || $level == "Grade 12"), {{$period}}
@endif
</div>
</center><br>

@if($section=="All")
<table width="100%">
    <tr>
        <td>Subject</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Quarter</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Teacher</td>
        <td style="border-bottom: 1px solid" width="40%"></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        
        @if($value == 'w' || $value == 'new')
        <th colspan="2" style="font-size: 12pt">
            @else
        <th style="font-size: 12pt">
            @endif
        <center>
            {{$level}}
                @if($level=="Grade 11" || $level=="Grade 12")
                    ({{$strand}})
                @endif
                
        </center></th>
        <th width="5%" align="center">Sect</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
   
    @if(count($status)>0)
    @foreach($status as $name)
    @if($period == "Select Period")
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->first(); ?>
    @else
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->where('period', $period)->first(); ?>
    @endif
    <tr>
        <td>{{$i++}}.</td>
        @if($value == 'w' || $value == 'new')
        <td width="1%">{{$name->idno}}</td>
        @endif
        <td width="50%">
            @if ($is_new->is_new == 1)
            <strong><i>{{get_name($name->idno, $schoolyear, $period)}}{{get_ns($name->idno, $schoolyear, $period)}}</i></strong>
            @else
            {{get_name($name->idno,$schoolyear, $period)}}{{get_ns($name->idno,$schoolyear, $period)}}
            @endif
        </td>
        <td align="center">{{$name->section}}</td>
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
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 
@else
<table width="100%">
    <tr>
        <td>Subject</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Quarter</td>
        <td style="border-bottom: 1px solid" width="30%"></td>
        <td>Teacher</td>
        <td style="border-bottom: 1px solid" width="40%"></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        @if($value == 'wo')
        <th style="font-size: 12pt">
        @else
        <th colspan="2" style="font-size: 12pt">
        @endif
        <center>
            {{$level}}
                @if($level=="Grade 11" || $level=="Grade 12")
                    ({{$strand}})
                @endif
                - {{$section}}
        </center></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    @if(count($status)>0)
    @foreach($status as $name)
    @if($period == "Select Period")
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->first(); ?>
    @else
    <?php $is_new = \App\BedLevel::where('idno',$name->idno)->where('school_year', $schoolyear)->where('period', $period)->first(); ?>
    @endif
    <tr>
        <td>{{$i++}}.</td>
        @if($value == 'w' || $value == 'new')
        <td width="1%">{{$name->idno}}</td>
        @endif
        <td width="40%">
            @if ($is_new->is_new == 1)
            <strong><i>{{get_name($name->idno, $schoolyear, $period)}}{{get_ns($name->idno,$schoolyear, $period)}}</i></strong>
            @else
            {{get_name($name->idno,$schoolyear,$period)}}{{get_ns($name->idno,$schoolyear,$period)}}
            @endif
        </td>
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
    </tr>
    @endforeach
    @else
    <tr><td colspan="13">No List For This Level</td></tr>
    @endif
    
</table>    

 
@endif
{{date('M d, Y')}}

</body>
