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
                    return "Grade 12";
            }else{
                    return "Grade 11";
            }
            break;
        case "Grade 12":
            if($period == "2nd Semester"){
                    return "College";
            }else{
                    return "Grade 12";
            }
            break;
    }
}
?>
<table width='100%' cellpadding='0' cellspacing='0'>
    <tr><td><strong colspan="7">Assumption College</strong></td></tr>
    <tr><td colspan="7">{{$department}}</td></tr>
    <tr><td colspan="7">Unused Reservations</td></tr>
    <tr><td colspan="7"><h5>S.Y. {{$school_year}} - {{$school_year + 1}}, {{$period}}</h5>
</td></tr>
    </tr>
    @foreach($heads as $head)
    <?php $x = 0; $prev_idno = "";?>
    <thead>
        <tr><td colspan="6"><h4>Incoming: {{getPromotion($head->level,$period)}}</h4></td></tr>
        <tr>
            <th width="5" style='border-bottom: 1px solid black'> </th>
            <th width="10"  style='border-bottom: 1px solid black'>ID No.</th>
            <th width="60"  style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th width="20" style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th width="10"  style='border-bottom: 1px solid black'>Level</th>
            <th width="15" style='border-bottom: 1px solid black'>OR Number</th>
            <th width="15" style='border-bottom: 1px solid black'>Date</th>
            <th width="15" style='border-bottom: 1px solid black; text-align: right'>Amount</th>
            <th width="15" style='border-bottom: 1px solid black; text-align: right'>Status</th>
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
                    <td>{{$list->level}}</td>
                    <td>{{$list->receipt_no}}</td>
                    <td>{{$list->transaction_date}}</td>
                    <td align='right'>{{$list->amount}}</td>
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
            <tr><td align="right" @if($department == "College Department") colspan="7" @else colspan="6" @endif>SUB TOTAL</td><td align="right"><strong>{{$head->total}}</strong></td></tr>
    @endforeach
            <tr>
                <td style="border-top:1px solid black" @if($department == "College Department") colspan="7" @else colspan="6" @endif><strong>Total</strong></td>
                <td style="border-top:1px solid black" align='right'><strong>{{$total}}</strong></td><td style="border-top:1px solid black"></td>
            </tr>
    </tbody>
</table>
<br><br>

@else
@endif