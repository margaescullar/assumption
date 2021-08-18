<?php
$total_depo = 0;
$total_other = 0;
$total_misc = 0;
?>
<html>
    <head>
        <style>
            body {
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                font-size: 10pt;
            }
            .tab {
                margin-left: 30px;
            }
        </style>
    </head>
    <body>
        <b>ASSUMPTION COLLEGE INC.<br>
            SCHEDULE OF FEES</b>
        <hr><br>
        @if($level == "1st Year" || $level == "2nd Year" || $level == "3rd Year" || $level == "4th Year" || $level == "5th Year")
        {{$period}}<br>
        @endif
        {{$level}}<br><br>
        <table width="75%">
            <tr>@if($level == "1st Year" || $level == "2nd Year" || $level == "3rd Year" || $level == "4th Year" || $level == "5th Year")
                <td>Tuition Fee per Unit</td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($amount,2)}} x no. of units</div></td>
                @else
                <td>Tuition Fee</td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($amount,2)}}</div></td>
                @endif
            </tr>
            
            
            
            
            <tr>
                <td>Miscellaneous Fees</td>
                <td></td>
            </tr>
            @foreach ($miscellaneous_fees as $miscellaneous_fee)
            <?php $total_misc = $total_misc + $miscellaneous_fee->amount; ?>
            <tr>
                <td><div class='tab'>{{$miscellaneous_fee->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$miscellaneous_fee->amount}}</div></td>
            </tr>
            @endforeach
            @foreach ($other_collections as $other_collection)
            @if($other_collection->category == "Miscellaneous Fees")
            <?php $total_misc = $total_misc + $other_collection->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_collection->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$other_collection->amount}}</div></td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td><div class='tab'>Total Miscellaneous Fees</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($total_misc,2)}}</div></td>
            </tr>
            
            
            
            
            <tr>
                <td>Other Fees</td>
                <td></td>
            </tr>
            @foreach ($other_fees as $other_fee)
            <?php $total_other = $total_other + $other_fee->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_fee->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$other_fee->amount}}</div></td>
            </tr>
            @endforeach
            @foreach ($other_collections as $other_collection)
            @if($other_collection->category == "Other Fees")
            <?php $total_other = $total_other + $other_collection->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_collection->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($other_collection->amount,2)}}</div></td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td><div class='tab'>Total Other Fees</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($total_other,2)}}</div></td>
            </tr>
            
            
            <tr>
                <td>Depository Fees</td>
                <td></td>
            </tr>
            @foreach ($depository_fees as $depository_fee)
            <?php $total_depo = $total_depo + $depository_fee->amount; ?>
            <tr>
                <td><div class='tab'>{{$depository_fee->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$depository_fee->amount}}</div></td>
            </tr>
            @endforeach
            @foreach ($other_collections as $other_collection)
            @if($other_collection->category == "Depository Fees")
            <?php $total_depo = $total_depo + $other_collection->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_collection->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$other_collection->amount}}</div></td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td><div class='tab'>Total Depository Fees</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($total_depo,2)}}</div></td>
            </tr>
            
            <tr>
                <td><br></td>
                <td></td>
            </tr>
            <tr>@if($level == "1st Year" || $level == "2nd Year" || $level == "3rd Year" || $level == "4th Year" || $level == "5th Year")
                <td>Total Miscellaneous, Other, and Depository Fees</td>
                <td align="right"><div style="border-bottom: 3px double black">{{number_format($total_other + $total_misc + $total_depo,2)}}</div></td>
                @else
                <td>Total School Fees</td>
                <td align="right"><div style="border-bottom: 3px double black">{{number_format($amount + $total_other + $total_misc + $total_depo,2)}}</div></td>
                @endif
            </tr>
        </table>
    </body>
</html>