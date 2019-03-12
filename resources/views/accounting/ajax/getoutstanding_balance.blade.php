<style>

    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 10pt;
    }
    td{
        padding:2px;
    }
</style>

<strong>Assumption College</strong><br>
{{$department}}<br/>
<h4>Outstanding Balances as of {{date("F d, Y")}}</h4>
<h5>S.Y. {{$school_year}} - {{$school_year + 1}} {{$period}}</h5>

@if(count($lists)>0)
<?php $total = 0;
$x = 0 ?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
    <?php $x = 0;?>
    <thead>
        <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th style='border-bottom: 1px solid black'>Plan</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black' align="center">Section</th>
            @endif
            <th style='border-bottom: 1px solid black' align='right'>Main Fees</th>
            <th style='border-bottom: 1px solid black' align='right'>Other Fees</th>
            <th style='border-bottom: 1px solid black' align='right'>Balance</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
            @if($list->balance > 0)
                @if($list->level == $head->level)
                <?php $total += $list->balance; $x++; ?>
                <tr>
                    <td>{{$x}}  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}},{{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}} </th>
                        @endif
                    <td>{{$list->type_of_plan}}</td>
                    @if($department != "College Department")
                    <td align='center'>{{$list->section}}</td>
                    @endif
                    <?php
    if ($department == "College Department") {
                $dep = '%Department';
                //$lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l,college_levels cl WHERE u.idno = cl.idno AND l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' AND cl.status = '".env('ENROLLED')."' AND s.school_year = '".$school_year."' AND s.period = '".$period."' ORDER BY u.lastname,s.program_code,s.level,s.section");
                //$heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,college_levels cl WHERE l.balance != 0.00 and u.idno = cl.idno AND  s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '".$school_year."' AND s.period = '".$period."' AND cl.status = '".env('ENROLLED')."' GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            
                $main_fees  = collect(\DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' and category_switch <= 6 and idno = '$list->idno' GROUP BY idno) l ,college_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section "))->first();
                $other_fees = collect(\DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' and category_switch = 7 and idno = '$list->idno' GROUP BY idno) l ,college_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section "))->first();
                
    }else {
                $dep = $department;
                if($dep == 'Senior High School'){
                    //OLD$lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l,bed_levels cl WHERE cl.idno = s.idno and l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' AND cl.status = '".env('ENROLLED')."' AND s.school_year = '".$school_year."' AND s.period = '".$period."' ORDER BY u.lastname,s.program_code,s.level,s.section");
                    //OLD$heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,bed_levels cl WHERE cl.idno = s.idno and l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '".$school_year."' AND s.period = '".$period."' AND cl.status = '".env('ENROLLED')."' GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                    $main_fees  = collect(\DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' and category_switch <= 6 and idno = '$list->idno' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section"))->first();
                    $other_fees = collect(\DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' and category_switch  = 7 and idno = '$list->idno' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section"))->first();
                    
                }
                else{
                    $period = "";
                    $main_fees  = collect(\DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' and category_switch <= 6 and idno = '$list->idno' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' ORDER BY u.lastname,s.level,s.section "))->first();
                    $other_fees = collect(\DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' and category_switch = 7 and idno = '$list->idno' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' ORDER BY u.lastname,s.level,s.section "))->first();
                    
                }
            }
    
    ?>
                    <td align='right'>@if(!isset($main_fees)) 0.00 @else {{number_format($main_fees->balance,2)}} @endif</td>
                    <td align='right'>@if(!isset($other_fees)) 0.00 @else {{number_format($other_fees->balance,2)}} @endif</td>
                    <td align='right'>{{number_format($list->balance,2)}}</td>
                </tr>
                @endif
            @endif
            @endforeach
            <tr><td align="right" colspan="7">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>

@else
@endif