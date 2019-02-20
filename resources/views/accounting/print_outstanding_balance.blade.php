<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 10pt;
        }
        td{
            /*border-bottom: 1px solid black;*/
            padding:2px;
        }
</style>

<div align="center">
<strong>Assumption College</strong><br>
    {{$department}}<br/>
<h4>Outstanding Balances as of {{date("F d, Y")}}</h4>
<h5>S.Y. {{$school_year}} - {{$school_year + 1}} {{$period}}</h5>
</div>
@if(count($lists)>0)
<?php $total = 0;
$x = 0 ?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
    <?php $x = 0;?>
    <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
    <thead>
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
                    <td align='right'>{{number_format($list->balance,2)}}</td>
                </tr>
                @endif
                @endif
            @endforeach
            <tr><td align="right" colspan="5">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>

Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong>
@else
@endif