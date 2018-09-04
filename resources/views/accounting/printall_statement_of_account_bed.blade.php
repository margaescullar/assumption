<?php 
$tdcounter=1;
?>
<style>
    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 8pt;
    }
    #bold {
        font-weight: bold;
    }
    .page_break { page-break-before: always; }
</style>
<style>
    body { margin: -1.2cm; }
</style>
<body>
    <?php $number=1; ?>
    <div style="margin:1.2cm;">
        
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="4" align="center"><strong>Assumption College</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">Statement of Account</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">Basic Education Department</td>
                    </tr>
        </table>
        <br>
        <br>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="border-bottom: 1px solid black">#</th>
                <th style="border-bottom: 1px solid black">ID Number</th>
                <th style="border-bottom: 1px solid black">Name</th>
                <th style="border-bottom: 1px solid black">Plan</th>
                <th style="border-bottom: 1px solid black; text-align: right">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <?php 
            $totaldiscount=0;
            $totaldm=0;
            $totalpayment=0;
            $ledger_amount=0;
            $due_amount=0;
            $ledger_main_tuition = \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$student->idno)->where('category_switch','<=','6')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get(); 
            $ledger_others = \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$student->idno)->whereRaw('category_switch = 7')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get(); 
            $previouses=  \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$student->idno)->where('category_switch','>','9')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
            ?>
            <?php
    $final_date = date('Y-m-31',strtotime($due_date));
    $ledger_due_dates = \App\LedgerDueDate::where('idno', $student->idno)->whereRaw("due_date <= '$final_date'")->get();
            ?>
            @foreach($ledger_due_dates as $ledger_due_date)
                <?php $ledger_amount = $ledger_amount + $ledger_due_date->amount;?>
            @endforeach
            @foreach($ledger_main_tuition as $main_tuition)
               <?php
               $totaldiscount=$totaldiscount+$main_tuition->discount;
               $totaldm=$totaldm+$main_tuition->debit_memo;
               $totalpayment=$totalpayment+$main_tuition->payment;
               $less=$totaldiscount+$totaldm+$totalpayment;
               ?>
            @endforeach

            <?php 
            $totaldiscount=0;
            $totaldm=0;
            $totalpayment=0;
            $totalamount=0;
            $less2=0;
            ?>
            @foreach($ledger_others as $other_tuition)
               <?php
               $totaldiscount=$totaldiscount+$other_tuition->discount;
               $totaldm=$totaldm+$other_tuition->debit_memo;
               $totalpayment=$totalpayment+$other_tuition->payment;
               $totalamount=$totalamount+$other_tuition->amount;
               $less2=$totaldiscount+$totaldm+$totalpayment;
               ?>
            @endforeach
            <?php $others=$totalamount-$less2 ?>

            <?php 
            $totaldiscount=0;
            $totaldm=0;
            $totalpayment=0;
            $totalamount=0;
            $less3=0;
            ?>
            @foreach($previouses as $previous_tuition)
               <?php
               $totaldiscount=$totaldiscount+$previous_tuition->discount;
               $totaldm=$totaldm+$previous_tuition->debit_memo;
               $totalpayment=$totalpayment+$previous_tuition->payment;
               $totalamount=$totalamount+$previous_tuition->amount;
               $less3=$totaldiscount+$totaldm+$totalpayment;
               ?>
            @endforeach
            <?php $previous=$totalamount-$less3 ?>


            <?php $due_amount = ($ledger_amount-$less)+$others+$previous; ?>
            @if($due_amount >= 0)
            <tr>
                <td style="border-bottom: 1px solid black">{{$number}}.<?php $number++; ?></td>
                <td style="border-bottom: 1px solid black">{{$student->idno}}</td>
                <td style="border-bottom: 1px solid black">{{$student->lastname}}, {{$student->firstname}}</td>
                <td style="border-bottom: 1px solid black">{{$student->type_of_plan}}</td>
                <td style="border-bottom: 1px solid black" style="color:red; font-weight: bold" align="right">{{number_format(($ledger_amount-$less)+$others+$previous,2)}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    </div>
    
    
    
    <div class="page_break"></div>
    
    
    
    
    <table width="100%" cellpadding="30" border="0">
        @foreach($students as $student)
        <?php 
        $student = \App\User::where('idno', $student->idno)->first();
        $status = \App\Status::where('idno', $student->idno)->first();

        $totaldiscount = 0;
        $totaldm = 0;
        $totalpayment = 0;
        $main_totalamount = 0;
        $ledger_amount = 0;
        $due_amount = 0;
        $less = 0;

        $other_totaldiscount = 0;
        $other_totaldm = 0;
        $other_totalpayment = 0;
        $other_totalamount = 0;
        $other_less = 0;

        $previous_totaldiscount = 0;
        $previous_totaldm = 0;
        $previous_totalpayment = 0;
        $previous_totalamount = 0;
        $previous_less = 0;

        $ledger_main_tuition = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $student->idno)->where('category_switch', '<=', '6')
                        ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
        $ledger_others = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $student->idno)->whereRaw('category_switch = 7')
                        ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
        $previouses = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $student->idno)->where('category_switch', '>', '9')
                        ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();

        $final_date = date('Y-m-31',strtotime($due_date));
        $ledger_due_dates = \App\LedgerDueDate::where('idno', $student->idno)->whereRaw("due_date <= '$final_date'")->get();
        $due_dates = \App\LedgerDueDate::where('idno', $student->idno)->get();
        ?>

        @foreach($ledger_due_dates as $ledger_due_date)
        <?php
        $ledger_amount = $ledger_amount + $ledger_due_date->amount;
        ?>
        @endforeach

        <!--Main Account-->
        @foreach($ledger_main_tuition as $main_tuition)
        <?php
        $totaldiscount = $totaldiscount + $main_tuition->discount;
        $totaldm = $totaldm + $main_tuition->debit_memo;
        $totalpayment = $totalpayment + $main_tuition->payment;
        $main_totalamount = $main_totalamount + $main_tuition->amount;
        $less = $totaldiscount + $totaldm + $totalpayment;
        ?>
        @endforeach

        <!--Other Account-->
        @foreach($ledger_others as $other_tuition)
        <?php
        $other_totaldiscount = $other_totaldiscount + $other_tuition->discount;
        $other_totaldm = $other_totaldm + $other_tuition->debit_memo;
        $other_totalpayment = $other_totalpayment + $other_tuition->payment;
        $other_totalamount = $other_totalamount + $other_tuition->amount;
        $other_less = $other_totaldiscount + $other_totaldm + $other_totalpayment;
        ?>
        @endforeach
        <?php $others = $other_totalamount - $other_less ?>

        <!--Previous Account-->
        @foreach($previouses as $previous_tuition)
        <?php
        $previous_totaldiscount = $previous_totaldiscount + $previous_tuition->discount;
        $previous_totaldm = $previous_totaldm + $previous_tuition->debit_memo;
        $previous_totalpayment = $previous_totalpayment + $previous_tuition->payment;
        $previous_totalamount = $previous_totalamount + $previous_tuition->amount;
        $previous_less = $previous_totaldiscount + $previous_totaldm + $previous_totalpayment;
        ?>
        @endforeach
        <?php $previous = $previous_totalamount - $previous_less ?>

        <?php $due_amount = ($ledger_amount - $less) + $others + $previous; ?>

            @if($due_amount > 0)
                @if($tdcounter == 1)
                    <tr><td width="50%" valign="top">
                @else
                    <td width="50%" valign="top">
                @endif
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="4" align="center"><strong>Assumption College</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">Statement of Account</td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">Basic Education Department</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">ID NUMBER:</td><td colspan="2">{{$student->idno}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">NAME:</td><td colspan="2">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                    </tr>
                    <tr>
                        <td width="30%" colspan="2">PLAN:</td><td colspan="2">{{$status->type_of_plan}}</td>
                    </tr>
                    <tr>
                        <td>LEVEL:</td><td>{{$status->level}}</td><td width="15%">SECTION:</td><td>{{$status->section}}</td>
                    </tr>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                </table>

<!--                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2" style="background-color: silver"><strong>MAIN FEES</strong></td>
                    </tr>
                    <tr>
                        <td width="30%">Total Fees:</td><td align="right">{{number_format($main_totalamount,2)}}</td>
                    </tr>
                    <tr>
                        <td>Less:</td><td align="right"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Debit Memo:</td><td align="right">({{number_format($totaldm,2)}})</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Discount:</td><td align="right">({{number_format($totaldiscount,2)}})</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Payment:</td><td align="right">({{number_format($totalpayment,2)}})</td>
                    </tr>
                    <tr>
                        <td id="bold">Balance:</td><td id="bold" align="right">Php {{number_format($main_totalamount-($totaldm+$totaldiscount+$totalpayment),2)}}</td>
                    </tr>
                </table>
                <br>-->
                @if($other_totalamount-($other_totaldm+$other_totaldiscount+$other_totalpayment)>0)
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2" style="background-color: silver"><strong>OTHER FEES</strong></td>
                    </tr>
                    <tr>
                        <td width="30%">Total Fees:</td><td align="right">{{number_format($other_totalamount,2)}}</td>
                    </tr>
                    <tr>
                        <td>Less:</td><td align="right"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Debit Memo:</td><td align="right">({{number_format($other_totaldm,2)}})</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Discount:</td><td align="right">({{number_format($other_totaldiscount,2)}})</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Payment:</td><td align="right">({{number_format($other_totalpayment,2)}})</td>
                    </tr>
                    <tr>
                        <td id="bold">Balance:</td><td id="bold" align="right">Php {{number_format($other_totalamount-($other_totaldm+$other_totaldiscount+$other_totalpayment),2)}}</td>
                    </tr>
                </table>
                <br>
                @endif
                @if($previous_totalamount-($previous_totaldm+$previous_totaldiscount+$previous_totalpayment)>0)
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2" style="background-color: silver"><strong>PREVIOUS BALANCE</strong></td>
                    </tr>
                    <tr>
                        <td width="30%">Total Fees:</td><td align="right">{{number_format($previous_totalamount,2)}}</td>
                    </tr>
                    <tr>
                        <td>Less:</td><td align="right"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Debit Memo:</td><td align="right">({{number_format($previous_totaldm,2)}})</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Discount:</td><td align="right">({{number_format($previous_totaldiscount,2)}})</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;Payment:</td><td align="right">({{number_format($previous_totalpayment,2)}})</td>
                    </tr>
                    <tr>
                        <td id="bold">Balance:</td><td id="bold" align="right">Php {{number_format($previous_totalamount-($previous_totaldm+$previous_totaldiscount+$previous_totalpayment),2)}}</td>
                    </tr>
                </table>
                <br>
                @endif
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2" style="background-color: silver"><strong>SCHEDULE OF PAYMENT</strong></td>
                    </tr>
                    @foreach($due_dates as $due)
                        @if($due->due_switch=="0")
                        <?php $duedate = "Upon Enrollment";?>
                        @else
                        <?php $duedate = date('F d, Y',strtotime($due->due_date)); ?>
                        @endif

                        <?php 
                        if($less >= $due->amount){
                            $less = $less - $due->amount;
                        } else {
                            $date = $duedate;
                            $display = "Php ".number_format($due->amount-$less,2);
                            $less=0;
                            $remark="";
                        echo "<tr><td>".$date."</td><td align=\"right\">".$display."</td></tr>";
                        }
                        ?>
                    @endforeach
<!--                    @foreach($due_dates as $due)
                        @if($due->due_switch=="0")
                        <?php //$duedate = "Upon Enrollment";?>
                        @else
                        <?php //$duedate = date('F d, Y',strtotime($due->due_date)); ?>
                        @endif

                        <?php 
//                        if($less >= $due->amount){
//                            $date = "<span style=\"font-style: italic ;text-decoration: line-through\">".$duedate."<span>";  
//                            $display = "<span style=\"font-style: italic; text-decoration: line-through\">Php ".number_format($due->amount,2)."<span>";  
//                            $less = $less - $due->amount;
//                            $remark = "<span style=\"font-style: italic; font-style:italic;color:#f00\">paid</span>";
//                        } else {
//                            $date = $duedate;
//                            $display = "Php ".number_format($due->amount-$less,2);
//                            $less=0;
//                            $remark="";
//                        }
                        ?>

                        <tr><td>{!!$date!!}</td><td align="right">{!!$display!!}</td><td align="center">{!!$remark!!}</td></tr>
                    @endforeach-->

                </table>
                <br>
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="30%"><strong>Due Date</strong></td><td align="right"><strong>{{date('F j, Y',strtotime($due_date))}}</strong></td>
                    </tr>
                    <tr>
                        <td><strong style="color: red;">Due Amount</strong></td><td align="right"><strong style="color: red;">Php {{number_format($due_amount,2)}}</strong></td>
                    </tr>
                </table>
                <br>
                <strong>Reminder:</strong><br> {{$remarks}}<br>
                <br>
                <table width="100%" style="font-size: 8pt">
                    <tr>
                        <td>
                            ADVISORY:<br>
                                -Surcharge of Php 100.00 every month of late payment.<br>
                                -Succeeding Statement of Account from now on will only be available in digital form at <i><u>portal.assumption.edu.ph</u></i><br><br><br><br>
                                
                                
                                
                                PLEASE PRESENT THIS BILL WHEN PAYING<br><br>
                            Kindly disregard this notice if payment has been made.<br><br>
                            

                            For Inquiries, please contact Ms. Joy Aggabao<br>
                            Tel.: (02) 817-0757 loc. 1056<br><br>

                            Please pay ON or BEFORE<br>
                            Due Date: <strong>{{date('F j, Y',strtotime($due_date))}}</strong><br><br><br><br><br>

                            Certified by:<br><br>

                            <strong>Ms. Joy Aggabao</strong><br>
                            Student Fees Officer<br><br>

                            Please fax DEPOSIT SLIP/CONFIRMATION - (02) 817-0757 to<br> validate payments made through:<br>

                            -BPI Bank(Over the counter)<br>&nbsp;&nbsp;&nbsp;Account No.: <u>1811-0005-54</u><br>&nbsp;&nbsp;&nbsp;Ref No.: <u>{{$student->idno}}</u>(Student ID Number)<br>
                            -BPI Expresslink(online payment)<br>
                            -Email: <i><u>finance@assumption.edu.ph</u></i>
                        </td>
                    </tr>
                </table>
                @if($tdcounter == 1)
                    </td>
                    <?php $tdcounter=2; ?>
                @else
                    </td>
                </tr>
                    <?php $tdcounter=1; ?>
                @endif
            @endif
        @endforeach
    </table>
</body>