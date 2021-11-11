<input type="submit" value="Print All" class="col-sm-12 btn btn-success">
<table class="table table-condensed">
    <thead>
        <tr>
            <th width="10%">ID Number</th>
            <th width="60%">Name</th>
            <th width="10%">Plan</th>
            <th width="10%">Level</th>
            <th width="10%" style="text-align: right">Due Amount</th>
            <th width="10%" style="text-align: center">Print</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <?php 
        $status = \App\Status::where('idno', $student->idno)->first();
        $totaldiscount=0;
        $totaldm=0;
        $totalpayment=0;
        $ledger_amount=0;
        $less_return=0;
        $less_return1=0;
        $due_amount=0;
        $ledger_main_tuition = \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$student->idno)->where('category_switch','<=','6')
          ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get(); 
        $ledger_others = \App\Ledger::groupBy(array('category', 'category_switch'))->where('is_returned_check', 0)->where('idno', $student->idno)->whereRaw('category_switch = 7')
          ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
        $ledger_others_return = \App\Ledger::groupBy(array('category', 'category_switch'))->where('is_returned_check', 1)->where('idno', $student->idno)->whereRaw('category_switch = 7')
          ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
        $previouses=  \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$student->idno)->where('category_switch','>','9')
          ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
        ?>
        <?php
        $final_date = date('Y-m-30',strtotime($due_date));
        $ledger_due_dates = \App\LedgerDueDate::where('idno', $student->idno)->where('school_year', $status->school_year)->where('period', $status->period)->whereRaw("due_date <= '$final_date'")->get();
        $due_dates = \App\LedgerDueDate::where('idno', $student->idno)->where('school_year', $status->school_year)->where('period', $status->period)->orderBy('due_switch')->orderBy('due_date')->get();
        ?>
        @foreach($ledger_due_dates as $ledger_due_date)
            <?php $ledger_amount = $ledger_amount + $ledger_due_date->amount;?>
        @endforeach

        <!--Returned Checks-->
        @foreach($ledger_others_return as $main_return)
        <?php
        //$totaldiscount = $totaldiscount + $main_return->discount;
        //$totaldm = $totaldm + $main_return->debit_memo;
        //$totalpayment = $totalpayment + $main_return->payment;
        //$main_totalamount = $main_totalamount + $main_return->amount;
        $less_return = $main_return->amount - ($main_return->debit_memo + $main_return->payment);
        $less_return1 = $main_return->amount - ($main_return->debit_memo + $main_return->payment);
        ?>
        @endforeach

        @foreach($ledger_main_tuition as $main_tuition)
           <?php
           $totaldiscount=$totaldiscount+$main_tuition->discount;
           $totaldm=$totaldm+$main_tuition->debit_memo;
           $totalpayment=$totalpayment+$main_tuition->payment;
           $less=$totaldm+$totalpayment;
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
           $less2=$totaldm+$totalpayment;
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
           $less3=$totaldm+$totalpayment;
           ?>
        @endforeach
        <?php $previous=$totalamount-$less3 ?>
        <?php $due_amount = ($ledger_amount - ($less-$less_return)) + $others + $previous; ?>
        @if($due_amount >= 0)
        <tr>
            <td>{{$student->idno}}</td>
            <td>{{$student->lastname}}, {{$student->firstname}}</td>
            <td>{{$student->type_of_plan}}</td>
            <td>{{$student->level}}</td>
            <td style="color:red; font-weight: bold" align="right">{{number_format(($ledger_amount-$less)+$others+$previous,2)}}</td>
            <td align="center"><a href="javascript:void(0)" onclick='print_soa_student(due_date.value, remarks.value,"{{$student->idno}}")'>Print</a></td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>