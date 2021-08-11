<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 8pt;
        }
</style>

<strong>Assumption College</strong><br>
{{$department}}<br>
@if($department == "College Department" or $department == "Senior High School")
{{$school_year}}-{{$school_year+1}}, {{$period}}
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
{{$school_year}}-{{$school_year+1}}
@endif
<h3>Unused Reservations</h3>

As of {{date('F d, Y')}}

@if(count($lists)>0)
<?php $total = 0;
$x = 0;

function getPromotion($level,$period=null) {
    switch ($level) {
        case "Pre-Kinder":
            return "Kinder";
            break;
        case "Pre Kinder":
            return "Kinder";
            break;
        case "Kinder":
            return "Grade 1";
            break;
        case "Grade 1":
            return "Grade 2";
            break;
        case "Grade 2":
            return "Grade 3";
            break;
        case "Grade 3":
            return "Grade 4";
            break;
        case "Grade 4":
            return "Grade 5";
            break;
        case "Grade 5":
            return "Grade 6";
            break;
        case "Grade 6":
            return "Grade 7";
            break;
        case "Grade 7":
            return "Grade 8";
            break;
        case "Grade 8":
            return "Grade 9";
            break;
        case "Grade 9":
            return "Grade 10";
            break;
        case "Grade 10":
            return "Grade 11";
            break;
        case "Grade 11":
            if($period == "2nd Semester"){
                    return "Grade 11";
            }else{
                    return "Grade 12";
            }
            break;
        case "Grade 12":
            if($period == "2nd Semester"){
                    return "Grade 12";
            }else{
                    return "College";
            }
            break;
        case "1st Year":
            if($period == "2nd Semester"){
                    return "1st Year";
            }else{
                    return "2nd Year";
            }
            break;
        case "2nd Year":
            if($period == "2nd Semester"){
                    return "2nd Year";
            }else{
                    return "3rd Year";
            }
            break;
        case "3rd Year":
            if($period == "2nd Semester"){
                    return "3rd Year";
            }else{
                    return "4th Year";
            }
            break;
        case "4th Year":
            if($period == "2nd Semester"){
                    return "4th Year";
            }else{
                    return "5th Year";
            }
            break;
        case "":
            return "New Student";
            break;
    }
}
?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
    <?php $x = 0; $prev_idno = "";?>
    <thead>
        <!--<tr><td colspan="6"><h4>Incoming: {{getPromotion($head->level,$period)}}</h4></td></tr>-->
        <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <!--<th style='border-bottom: 1px solid black'>Level</th>-->
            <th style='border-bottom: 1px solid black'>OR Number</th>
            <th style='border-bottom: 1px solid black'>Date</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Amount</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Status</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                @if($list->level == $head->level)
                <?php $total += $list->amount; ?>
                <tr>
                    <td>@if($prev_idno != $list->idno) <?php $x++; ?> {{$x}}. @endif  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}} </td>
                    @endif
<!--                    <td>{{$list->level}}</td>-->
                    <td>{{$list->receipt_no}}</td>
                    <td>{{$list->transaction_date}}</td>
                    <td align='right'>{{number_format($list->amount,2)}}</td>
                    <td align='right'>
                        @switch($list->is_consumed)
                        @case(1)
                        Used
                        @break
                        @case(0)
                        Unused
                        @break
                        @endswitch
                    </td>
                </tr>
                @endif
                <?php $prev_idno = $list->idno; ?>
            @endforeach
            <tr><td align="right" @if($department == "College Department") colspan="7" @else colspan="6" @endif>SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td></tr>
    @endforeach
            <tr>
                <td style="border-top:1px solid black" @if($department == "College Department") colspan="7" @else colspan="6" @endif><strong>Total</strong></td>
                <td style="border-top:1px solid black" align='right'><strong>{{number_format($total,2)}}</strong></td><td style="border-top:1px solid black"></td>
            </tr>
    </tbody>
</table>
<br><br>

@else
@endif