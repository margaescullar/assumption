<?php

function get_plan($level, $category) {
    $amount = \App\CtrBedFee::selectRaw('sum(amount) as amount, level, category')->whereRaw("level = '$level' and category = '$category'")->groupBy('level', 'category')->first();
    if ($level != "Grade 11" && $level != "Grade 12") {
        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10"){
        $other_amount = \App\JhsOtherCollection::selectRaw('sum(amount) as amount, category')->whereRaw("category = '$category'")->groupBy('category')->first();
        }else{
        $other_amount = \App\OtherCollection::selectRaw('sum(amount) as amount, category')->whereRaw("category = '$category'")->groupBy('category')->first();
        }
        if (count($other_amount) > 0) {
            $amount = $amount->amount + $other_amount->amount;
        } else {
            $amount = $amount['amount'];
        }
        
    } else {
        $other_amount = \App\ShsOtherCollection::selectRaw('sum(amount) as amount, category')->whereRaw("category = '$category'")->groupBy('category')->first();
        if (count($other_amount) > 0) {
            $amount = $amount->amount + $other_amount->amount;
        } else {
            if(count($amount)>0){
                $amount = $amount->amount;
            }
        }
    }
    return $amount;
}

function get_category_plan($plan,$level,$type) {
        $amount = 0;
        $total_tuition = get_plan($level, 'Tuition Fee');
        $total_misc = get_plan($level, 'Miscellaneous Fees');
        $total_others = get_plan($level, 'Other Fees');
        $total_dep = get_plan($level, 'Depository Fees');
        $total_total = $total_tuition + $total_misc + $total_others + $total_dep;
        if($plan == "Annual"){
            $amount = $total_total;
        }
        elseif($plan == "Semestral"){
            $interest = \App\CtrBedPlan::where('plan',"Plan B")->first()->interest;
            
//            $semestral_fee = (round($total_tuition*1.01)) / 2;
            $semestral_fee = (round($total_tuition*($interest/100)+$total_tuition)) / 2;
            $whole = floor($semestral_fee);
            $decimal = $semestral_fee - $whole;
            if($type == "UE"){
                $amount = $semestral_fee + ($total_total - $total_tuition) + $decimal;
            }
            elseif($type == "Total"){
                $amount = (round($total_tuition*($interest/100)+$total_tuition)) + ($total_total - $total_tuition);
            }
            else{
                $amount = $semestral_fee - $decimal;
            }
        }
        elseif($plan == "Quarterly"){
            $interest = \App\CtrBedPlan::where('plan',"Plan C")->first()->interest;
            $quarterly_fee = (round($total_tuition*($interest/100)+$total_tuition)) / 4;
            $whole = floor($quarterly_fee);
            $decimal = $quarterly_fee - $whole;
            if($type == "UE"){
                $amount = $quarterly_fee + ($total_total - $total_tuition) + ($decimal*3);
            }
            elseif($type == "Total"){
                $amount = (round($total_tuition*($interest/100)+$total_tuition)) + ($total_total - $total_tuition);
            }
            else{
                $amount = $quarterly_fee - $decimal;
            }
        }
        elseif($plan == "Monthly"){
            $interest = \App\CtrBedPlan::where('plan',"Plan D")->first()->interest;
            $monthly_fee = (round($total_tuition*($interest/100)+$total_tuition)) / 10;
            $whole = floor($monthly_fee);
            $decimal = $monthly_fee - $whole;
            if($type == "UE"){
                $amount = $monthly_fee + ($total_total - $total_tuition) + ($decimal*9);
            }
            elseif($type == "Total"){
                $amount = (round($total_tuition*($interest/100)+$total_tuition)) + ($total_total - $total_tuition);
            }
            else{
                $amount = $monthly_fee - $decimal;
            }
        }
        echo number_format($amount,2);
}

function get_category_plan_shs($plan,$level,$type) {
        $amount = 0;
        $total_tuition = get_plan($level, 'Tuition Fee');
        $total_misc = get_plan($level, 'Miscellaneous Fees');
        $total_others = get_plan($level, 'Other Fees');
        $total_dep = get_plan($level, 'Depository Fees');
        $total_total = $total_tuition + $total_misc + $total_others + $total_dep;
        if($plan == "Plan A"){
            $amount = $total_total;
        }
        elseif($plan == "Plan B"){
            $interest = \App\CtrBedPlan::where('plan',$plan)->first()->interest;
            $semestral_fee = (round($total_tuition*($interest/100)+$total_tuition)) / 2;
            $whole = floor($semestral_fee);
            $decimal = $semestral_fee - $whole;
            if($type == "UE"){
                $amount = $semestral_fee + ($total_total - $total_tuition) + $decimal;
            }
            elseif($type == "Total"){
                $amount = (round($total_tuition*($interest/100)+$total_tuition)) + ($total_total - $total_tuition);
            }
            else{
                $amount = $semestral_fee - $decimal;
            }
        }
        elseif($plan == "Plan C"){
            $interest = \App\CtrBedPlan::where('plan',$plan)->first()->interest;
            $quarterly_fee = (round($total_tuition*($interest/100)+$total_tuition)) / 3;
            $whole = floor($quarterly_fee);
            $decimal = $quarterly_fee - $whole;
            if($type == "UE"){
                $amount = $quarterly_fee + ($total_total - $total_tuition) + ($decimal*2);
            }
            elseif($type == "Total"){
                $amount = (round($total_tuition*($interest/100)+$total_tuition)) + ($total_total - $total_tuition);
            }
            else{
                $amount = $quarterly_fee - $decimal;
            }
        }
        elseif($plan == "Plan D"){
            $interest = \App\CtrBedPlan::where('plan',$plan)->first()->interest;
            $monthly_fee = (round($total_tuition*($interest/100)+$total_tuition)) / 5;
            $whole = floor($monthly_fee);
            $decimal = $monthly_fee - $whole;
            if($type == "UE"){
                $amount = $monthly_fee + ($total_total - $total_tuition) + ($decimal*4);
            }
            elseif($type == "Total"){
                $amount = (round($total_tuition*($interest/100)+$total_tuition)) + ($total_total - $total_tuition);
            }
            else{
                $amount = $monthly_fee - $decimal;
            }
        }
        echo number_format($amount,2);
}

function get_srf($level,$strand){
    $srf = \App\CtrBedSrf::where('level',$level)->where('strand',$strand)->first();
    if(count($srf) > 0){
        echo number_format($srf->amount);
    }
    else{
        echo "0.00";
    }
}

function get_total($level){
    $srf = \App\CtrBedSrf::selectRaw("sum(amount) as amount")->where('level',$level)->first();
    if(count($srf) > 0){
        echo number_format($srf->amount);
    }
    else{
        echo "0.00";
    }
}


?>


@if($department == "Pre School")
<?php 
$totalprekinder = 0;
$prekindertuition = get_plan('Pre-Kinder','Tuition Fee');
$prekindermisc = get_plan('Pre-Kinder','Miscellaneous Fees');
$prekinderothers=get_plan('Pre-Kinder','Other Fees');
$prekinderdep = get_plan('Pre-Kinder','Depository Fees');
$totalprekinder = $prekindertuition+$prekindermisc+$prekinderothers+$prekinderdep;
$totalkinder = 0;
$kindertuition = get_plan('Kinder','Tuition Fee');
$kindermisc = get_plan('Kinder','Miscellaneous Fees');
$kinderothers=get_plan('Kinder','Other Fees');
$kinderdep = get_plan('Kinder','Depository Fees');
$totalkinder = $kindertuition+$kindermisc+$kinderothers+$kinderdep;
?>
<div class="col-md-6">
<table class="table table-striped">
    <tr><td>Particular</td><td>Pre-Kinder</td><td>Kinder</td></tr>
    <tr><td>Tuition Fee</td><td align="right">{{number_format($prekindertuition,2)}}</td><td align="right">{{number_format($kindertuition,2)}}</td></tr>
    <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($prekindermisc,2)}}</td><td align="right">{{number_format($kindermisc,2)}}</td></tr>
    <tr><td>Other Fees</td><td align="right">{{number_format($prekinderothers,2)}}</td><td align="right">{{number_format($kinderothers,2)}}</td></tr>
    <tr><td>Depository Fees</td><td align="right">{{number_format($prekinderdep,2)}}</td><td align="right">{{number_format($kinderdep,2)}}</td></tr>
    <tr><td>Total</td><td align="right">{{number_format($totalprekinder,2)}}</td><td align="right">{{number_format($totalkinder,2)}}</td></tr>
</table>    
</div>    

<table border ="1" class="table table-striped">
    <tr><td>Mode of Payment</td><td>Upon Enrollment</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Total</td></tr>
    <tr><td colspan="12"><b>Pre-Kinder</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Pre-Kinder','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Pre-Kinder','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Pre-Kinder','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Pre-Kinder','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Pre-Kinder','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Pre-Kinder','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Pre-Kinder','')}}</td><td></td><td>{{get_category_plan('Quarterly','Pre-Kinder','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Pre-Kinder','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Pre-Kinder','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Pre-Kinder','UE')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','')}}</td><td>{{get_category_plan('Monthly','Pre-Kinder','Total')}}</td></tr>

            <tr><td colspan="12"><b>Kinder</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Kinder','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Kinder','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Kinder','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Kinder','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Kinder','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Kinder','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Kinder','')}}</td><td></td><td>{{get_category_plan('Quarterly','Kinder','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Kinder','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Kinder','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Kinder','UE')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','')}}</td><td>{{get_category_plan('Monthly','Kinder','Total')}}</td></tr>
        </table>    
@endif

@if($department == "Elementary")
<?php 
$grade1total = 0;
$grade1tuition = get_plan('Grade 1','Tuition Fee');
$grade1misc = get_plan('Grade 1','Miscellaneous Fees');
$grade1others=get_plan('Grade 1','Other Fees');
$grade1dep = get_plan('Grade 1','Depository Fees');
$grade1total = $grade1tuition+$grade1misc+$grade1others+$grade1dep;

$grade2total = 0;
$grade2tuition = get_plan('Grade 2','Tuition Fee');
$grade2misc = get_plan('Grade 2','Miscellaneous Fees');
$grade2others=get_plan('Grade 2','Other Fees');
$grade2dep = get_plan('Grade 2','Depository Fees');
$grade2total = $grade2tuition+$grade2misc+$grade2others+$grade2dep;

$grade3total = 0;
$grade3tuition = get_plan('Grade 3','Tuition Fee');
$grade3misc = get_plan('Grade 3','Miscellaneous Fees');
$grade3others=get_plan('Grade 3','Other Fees');
$grade3dep = get_plan('Grade 3','Depository Fees');
$grade3total = $grade3tuition+$grade3misc+$grade3others+$grade3dep;

$grade4total = 0;
$grade4tuition = get_plan('Grade 4','Tuition Fee');
$grade4misc = get_plan('Grade 4','Miscellaneous Fees');
$grade4others=get_plan('Grade 4','Other Fees');
$grade4dep = get_plan('Grade 4','Depository Fees');
$grade4total = $grade4tuition+$grade4misc+$grade4others+$grade4dep;

$grade5total = 0;
$grade5tuition = get_plan('Grade 5','Tuition Fee');
$grade5misc = get_plan('Grade 5','Miscellaneous Fees');
$grade5others=get_plan('Grade 5','Other Fees');
$grade5dep = get_plan('Grade 5','Depository Fees');
$grade5total = $grade5tuition+$grade5misc+$grade5others+$grade5dep;

$grade6total = 0;
$grade6tuition = get_plan('Grade 6','Tuition Fee');
$grade6misc = get_plan('Grade 6','Miscellaneous Fees');
$grade6others=get_plan('Grade 6','Other Fees');
$grade6dep = get_plan('Grade 6','Depository Fees');
$grade6total = $grade6tuition+$grade6misc+$grade6others+$grade6dep;
 
 
?>
<div class="col-md-12">
<table class="table table-striped">
    <tr><td>Particular</td><td>Grade 1</td><td>Grade 2</td><td>Grade 3</td><td>Grade 4</td><td>Grade 5</td><td>Grade 6</td></tr>
    <tr><td>Tuition Fee</td><td align="right">{{number_format($grade1tuition,2)}}</td><td align="right">{{number_format($grade2tuition,2)}}</td><td align="right">{{number_format($grade3tuition,2)}}</td><td align="right">{{number_format($grade4tuition,2)}}</td><td align="right">{{number_format($grade5tuition,2)}}</td><td align="right">{{number_format($grade6tuition,2)}}</td></tr>
    <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($grade1misc,2)}}</td><td align="right">{{number_format($grade2misc,2)}}</td><td align="right">{{number_format($grade3misc,2)}}</td><td align="right">{{number_format($grade4misc,2)}}</td><td align="right">{{number_format($grade5misc,2)}}</td><td align="right">{{number_format($grade6misc,2)}}</td></tr>
    <tr><td>Other Fees</td><td align="right">{{number_format($grade1others,2)}}</td><td align="right">{{number_format($grade2others,2)}}</td><td align="right">{{number_format($grade3others,2)}}</td><td align="right">{{number_format($grade4others,2)}}</td><td align="right">{{number_format($grade5others,2)}}</td><td align="right">{{number_format($grade6others,2)}}</td></tr>
    <tr><td>Depository Fees</td><td align="right">{{number_format($grade1dep,2)}}</td><td align="right">{{number_format($grade2dep,2)}}</td><td align="right">{{number_format($grade3dep,2)}}</td><td align="right">{{number_format($grade4dep,2)}}</td><td align="right">{{number_format($grade5dep,2)}}</td><td align="right">{{number_format($grade6dep,2)}}</td></tr>
    <tr><td>Total</td><td align="right">{{number_format($grade1total,2)}}</td><td align="right">{{number_format($grade2total,2)}}</td><td align="right">{{number_format($grade3total,2)}}</td><td align="right">{{number_format($grade4total,2)}}</td><td align="right">{{number_format($grade5total,2)}}</td><td align="right">{{number_format($grade6total,2)}}</td></tr>
</table>    
</div>    

<table border ="1" class="table table-striped">
    <tr><td>Mode of Payment</td><td>Upon Enrollment</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Total</td></tr>
    <tr><td colspan="12"><b>Grade 1</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 1','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 1','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 1','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 1','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 1','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 1','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 1','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 1','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 1','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 1','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 1','UE')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','')}}</td><td>{{get_category_plan('Monthly','Grade 1','Total')}}</td></tr>
            
            <tr><td colspan="12"><b>Grade 2</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 2','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 2','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 2','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 2','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 2','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 2','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 2','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 2','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 2','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 2','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 2','UE')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','')}}</td><td>{{get_category_plan('Monthly','Grade 2','Total')}}</td></tr>
           
            <tr><td colspan="12"><b>Grade 3</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 3','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 3','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 3','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 3','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 3','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 3','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 3','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 3','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 3','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 3','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 3','UE')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','')}}</td><td>{{get_category_plan('Monthly','Grade 3','Total')}}</td></tr>

            <tr><td colspan="12"><b>Grade 4</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 4','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 4','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 4','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 4','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 4','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 4','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 4','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 4','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 4','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 4','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 4','UE')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','')}}</td><td>{{get_category_plan('Monthly','Grade 4','Total')}}</td></tr>

            <tr><td colspan="12"><b>Grade 5</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 5','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 5','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 5','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 5','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 5','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 5','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 5','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 5','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 5','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 5','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 5','UE')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','')}}</td><td>{{get_category_plan('Monthly','Grade 5','Total')}}</td></tr>
            
            <tr><td colspan="12"><b>Grade 6</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 6','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 6','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 6','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 6','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 6','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 6','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 6','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 6','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 6','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 6','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 6','UE')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','')}}</td><td>{{get_category_plan('Monthly','Grade 6','Total')}}</td></tr>
        </table>    
@endif

@if($department == "Junior High School")
<?php
$grade1total = 0;
$grade1tuition = get_plan('Grade 7','Tuition Fee');
$grade1misc = get_plan('Grade 7','Miscellaneous Fees');
$grade1others=get_plan('Grade 7','Other Fees');
$grade1dep = get_plan('Grade 7','Depository Fees');
$grade1total = $grade1tuition+$grade1misc+$grade1others+$grade1dep;

$grade2total = 0;
$grade2tuition = get_plan('Grade 8','Tuition Fee');
$grade2misc = get_plan('Grade 8','Miscellaneous Fees');
$grade2others=get_plan('Grade 8','Other Fees');
$grade2dep = get_plan('Grade 8','Depository Fees');
$grade2total = $grade2tuition+$grade2misc+$grade2others+$grade2dep;

$grade3total = 0;
$grade3tuition = get_plan('Grade 9','Tuition Fee');
$grade3misc = get_plan('Grade 9','Miscellaneous Fees');
$grade3others=get_plan('Grade 9','Other Fees');
$grade3dep = get_plan('Grade 9','Depository Fees');
$grade3total = $grade3tuition+$grade3misc+$grade3others+$grade3dep;

$grade4total = 0;
$grade4tuition = get_plan('Grade 10','Tuition Fee');
$grade4misc = get_plan('Grade 10','Miscellaneous Fees');
$grade4others=get_plan('Grade 10','Other Fees');
$grade4dep = get_plan('Grade 10','Depository Fees');
$grade4total = $grade4tuition+$grade4misc+$grade4others+$grade4dep;
?>
<div class="col-md-10">
<table class="table table-striped">
    <tr><td>Particular</td><td>Grade 7</td><td>Grade 8</td><td>Grade 9</td><td>Grade 10</td></tr>
    <tr><td>Tuition Fee</td><td align="right">{{number_format($grade1tuition,2)}}</td><td align="right">{{number_format($grade2tuition,2)}}</td><td align="right">{{number_format($grade3tuition,2)}}</td><td align="right">{{number_format($grade4tuition,2)}}</td></tr>
    <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($grade1misc,2)}}</td><td align="right">{{number_format($grade2misc,2)}}</td><td align="right">{{number_format($grade3misc,2)}}</td><td align="right">{{number_format($grade4misc,2)}}</td></tr>
    <tr><td>Other Fees</td><td align="right">{{number_format($grade1others,2)}}</td><td align="right">{{number_format($grade2others,2)}}</td><td align="right">{{number_format($grade3others,2)}}</td><td align="right">{{number_format($grade4others,2)}}</td></tr>
    <tr><td>Depository Fees</td><td align="right">{{number_format($grade1dep,2)}}</td><td align="right">{{number_format($grade2dep,2)}}</td><td align="right">{{number_format($grade3dep,2)}}</td><td align="right">{{number_format($grade4dep,2)}}</td></tr>
    <tr><td>Total</td><td align="right">{{number_format($grade1total,2)}}</td><td align="right">{{number_format($grade2total,2)}}</td><td align="right">{{number_format($grade3total,2)}}</td><td align="right">{{number_format($grade4total,2)}}</td></tr>
</table>    
</div>    

<table border ="1" class="table table-striped">
    <tr><td>Mode of Payment</td><td>Upon Enrollment</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Total</td></tr>
    <tr><td colspan="12"><b>Grade 7</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 7','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 7','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 7','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 7','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 7','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 7','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 7','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 7','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 7','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 7','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 7','UE')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','')}}</td><td>{{get_category_plan('Monthly','Grade 7','Total')}}</td></tr>
            
            <tr><td colspan="12"><b>Grade 8</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 8','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 8','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 8','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 8','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 8','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 8','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 8','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 8','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 7','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 8','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 8','UE')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','')}}</td><td>{{get_category_plan('Monthly','Grade 8','Total')}}</td></tr>
            
            <tr><td colspan="12"><b>Grade 9</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 9','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 9','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 9','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 9','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 9','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 9','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 9','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 9','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 7','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 9','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 9','UE')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','')}}</td><td>{{get_category_plan('Monthly','Grade 9','Total')}}</td></tr>

            <tr><td colspan="12"><b>Grade 10</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan('Annual','Grade 10','Total')}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Annual','Grade 10','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan('Semestral','Grade 10','UE')}}</td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 10','')}}</td><td></td><td></td><td></td><td></td><td></td><td>{{get_category_plan('Semestral','Grade 10','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan('Quarterly','Grade 10','UE')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 10','')}}</td><td></td><td>{{get_category_plan('Quarterly','Grade 10','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 7','')}}</td><td></td><td></td><td>{{get_category_plan('Quarterly','Grade 10','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan('Monthly','Grade 10','UE')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','')}}</td><td>{{get_category_plan('Monthly','Grade 10','Total')}}</td></tr>
        </table>
@endif
@if($department == "Senior High School")
<?php
$grade1total = 0;
$grade1tuition = get_plan('Grade 11','Tuition Fee');
$grade1misc = get_plan('Grade 11','Miscellaneous Fees');
$grade1others=get_plan('Grade 11','Other Fees');
$grade1dep = get_plan('Grade 11','Depository Fees');
$grade1total = $grade1tuition+$grade1misc+$grade1others+$grade1dep;

$grade2total = 0;
$grade2tuition = get_plan('Grade 12','Tuition Fee');
$grade2misc = get_plan('Grade 12','Miscellaneous Fees');
$grade2others=get_plan('Grade 12','Other Fees');
$grade2dep = get_plan('Grade 12','Depository Fees');
$grade2total = $grade2tuition+$grade2misc+$grade2others+$grade2dep;
?>

<div class="col-md-6">
<table class="table table-striped">
    <tr><td>Particular</td><td>Grade 11</td><td>Grade 12</td></tr>
    <tr><td>Tuition Fee</td><td align="right">{{number_format($grade1tuition,2)}}</td><td align="right">{{number_format($grade2tuition,2)}}</td></tr>
    <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($grade1misc,2)}}</td><td align="right">{{number_format($grade2misc,2)}}</td></tr>
    <tr><td>Other Fees</td><td align="right">{{number_format($grade1others,2)}}</td><td align="right">{{number_format($grade2others,2)}}</td></tr>
    <tr><td>Depository Fees</td><td align="right">{{number_format($grade1dep,2)}}</td><td align="right">{{number_format($grade2dep,2)}}</td></tr>
    <tr><td>Total</td><td align="right">{{number_format($grade1total,2)}}</td><td align="right">{{number_format($grade2total,2)}}</td></tr>
</table>    
</div> 
<div class="col-md-6">
<table class="table table-striped">
    <tr><td>Strand</td><td>Grade 11</td><td>Grade 12</td></tr>
    <tr><td>ABM</td><td align="right">{{get_srf('Grade 11','ABM')}}</td><td align="right">{{get_srf('Grade 12','ABM')}}</td></tr>
    <tr><td>HUMMS</td><td align="right">{{get_srf('Grade 11','HUMSS')}}</td><td align="right">{{get_srf('Grade 12','HUMSS')}}</td></tr>
    <tr><td>STEM</td><td align="right">{{get_srf('Grade 11','STEM')}}</td><td align="right">{{get_srf('Grade 12','STEM')}}</td></tr>
    <tr><td>AD</td><td align="right">{{get_srf('Grade 11','AD')}}</td><td align="right">{{get_srf('Grade 12','AD')}}</td></tr>
    <tr><td>Total SRF</td><td align="right">{{get_total('Grade 11')}}</td><td align="right">{{get_total('Grade 12')}}</td></tr>
</table>    
</div>
<table border ="1" class="table table-striped">
    <tr>
        <td>Mode of Payment</td>
        <td>Upon Enrollment</td>
        <!--<td>Sept</td><td>Oct</td><td>Nov</td><td>Dec</td>-->
        <td>Feb</td><td>Mar</td><td>Apr</td><td>May</td>
        <td>Total</td>
    </tr>
        <tr><td colspan="12"><b>Grade 11</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan_shs('Plan A','Grade 11','Total')}}</td><td></td><td></td><td></td><td></td><td>{{get_category_plan_shs('Plan A','Grade 11','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan_shs('Plan B','Grade 11','UE')}}</td><td></td><td>{{get_category_plan_shs('Plan B','Grade 11','')}}</td><td></td><td></td><td>{{get_category_plan_shs('Plan B','Grade 11','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan_shs('Plan C','Grade 11','UE')}}</td><td>{{get_category_plan_shs('Plan C','Grade 11','')}}</td><td></td><td>{{get_category_plan_shs('Plan C','Grade 11','')}}</td><td></td><td>{{get_category_plan_shs('Plan C','Grade 11','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan_shs('Plan D','Grade 11','UE')}}</td><td>{{get_category_plan_shs('Plan D','Grade 11','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 11','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 11','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 11','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 11','Total')}}</td></tr>

                        <tr><td colspan="12"><b>Grade 12</b></td></tr>
            <tr><td>Plan A</td><td>{{get_category_plan_shs('Plan A','Grade 12','Total')}}</td><td></td><td></td><td></td><td></td><td>{{get_category_plan_shs('Plan A','Grade 12','Total')}}</td></tr>
            <tr><td>Plan B</td><td>{{get_category_plan_shs('Plan B','Grade 12','UE')}}</td><td></td><td>{{get_category_plan_shs('Plan B','Grade 12','')}}</td><td></td><td></td><td>{{get_category_plan_shs('Plan B','Grade 12','Total')}}</td></tr>
            <tr><td>Plan C</td><td>{{get_category_plan_shs('Plan C','Grade 12','UE')}}</td><td>{{get_category_plan_shs('Plan C','Grade 12','')}}</td><td></td><td>{{get_category_plan_shs('Plan C','Grade 12','')}}</td><td></td><td>{{get_category_plan_shs('Plan C','Grade 12','Total')}}</td></tr>
            <tr><td>Plan D</td><td>{{get_category_plan_shs('Plan D','Grade 12','UE')}}</td><td>{{get_category_plan_shs('Plan D','Grade 12','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 12','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 12','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 12','')}}</td><td>{{get_category_plan_shs('Plan D','Grade 12','Total')}}</td></tr>
        
  
 </table>

@endif
<a href="{{url('/accounting',array('ajax','print_getplan',$department))}}" class="btn btn-primary" target="_blank">Print Plan</a>