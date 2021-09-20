<?php
$total_tutorial = 0;
$layout="";
if(Auth::user()->accesslevel == env("CASHIER")){
    $layout = "layouts.appcashier";
} else if (Auth::user()->accesslevel == env("ACCTNG_STAFF")){
    $layout="layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")){
    $layout="layouts.appaccountinghead";
}
?>
@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"></span>
            </a>
</li>
<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"></span>
            </a>
</li>
          
<li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger"></span>
            </a>
</li>
@endsection
@section('header')
<style>
   
</style>

<section class="content-header">
      <h1>
        Student Ledger
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student ledger</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <div class="col-md-3 form-horizontal">
        
    </div>    
    <div class="col-md-9">
        <div class="col-md-6">
       
            </div>
        <div class="col-md-6">
        
        </div>    
    </div> 
    <div class="clearfix"></div>
    <div class="col-md-8">
        <div class="col-md-8"> 
             <table class="table table-responsive">
                 @if($status->status != 0)
                 @if($status->academic_type == "BED")
            <tr><td>A.Y. : </td><td align="left">
                    @if($status->status != 11)
                    {{$status->school_year}}<!---$status->school_year+1-->
                @endif
                </td></tr>
                 @else
            <tr><td>A.Y. : </td><td align="left">
                    
                    @if($status->status != 11){{$status->school_year}}<!---$status->school_year+1-->, {{$status->period}}
                @endif</td></tr>
                 @endif
                 @endif
            <tr><td>Student Number : </td><td align="left">{{$user->idno}}</td></tr>
            <tr><td>Student Name : </td><td align="left"><b>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b></td></tr>   
            @if(count($status)>0)
            
            <?php
            switch($status->status){
            case 0:
                echo "<tr><td>Status : </td><td>Not Yet Advised or Assessed For This School Year</td><tr>";
                break;
            case env("ADVISING"):
                echo "<tr><td>Status : </td><td>Alreary Advised but Not Assessed Yet</td><tr>";
                break;
            case env("ASSESSED"):
                echo "<tr><td>Status : </td><td>Assessed</td><tr>";
                    if($status->academic_type=="College"){     
                        if(count($levels)>0){
                        echo "<tr><td>Program/Level : </td><td>".$levels->program_code ." - ".$levels->level."</td><tr>";    
                        }
                        
                    } else {
                    
                    if($levels->level=="Grade 11" || $levels->level=="Grade 12"){
                        echo "<tr><td>Strand : </td><td>".$levels->strand."</td><tr>";    
                    }
                    echo "<tr><td>Level/Section : </td><td>".$levels->level ." - ".$levels->section."</td><tr>";
                    
                    
                    }
                    
                break;
            case env("ENROLLED"):
                 echo "<tr><td>Status : </td><td>Enrolled</td><tr>";
                if($levels->level=="Grade 11" || $levels->level=="Grade 12"){
                        echo "<tr><td>Strand : </td><td>".$levels->strand."</td><tr>";    
                    }
                if($status->academic_type=="College"){
                 echo "<tr><td>Level/Section : </td><td>".$levels->program_code ." - ".$levels->level."</td><tr>";
                } elseif($status->academic_type=="BED" || $status->academic_type=="SHS") {
                 echo "<tr><td>Level : </td><td>".$levels->level."</td><tr>";
                }
                 break;
            case 4:
                 echo "<tr><td>Status : </td><td>Dropped/Withdraw</td><tr>";
            }
            ?></b>
            
            @else
            <tr><td>Status : </td><td align="left"><span style="color:#f00">Not Enrolled</span></td></tr>
            @endif
            
            @if (Auth::user()->accesslevel == env("REG_COLLEGE") || Auth::user()->accesslevel==env("REG_COLLEGE"))
                @if ($status->status == env('ASSESSED'))
                    <tr><td colspan="2"><a role="button" class="col-md-12 btn btn-danger" href="{{url('/accounting',array('manual_marking',$user->idno))}}" onclick="return confirm('This process cannot be undone. Do you wish to continue?')"><b>Mark student as Enrolled</b></a></td></tr>
                @endif
            @endif
            
            @if ($status->status == env('ASSESSED') || $status->status == env('ENROLLED'))
            @if($status->academic_type == "BED" || $status->academic_type == "SHS")
            <tr>
                <td colspan="2"><a href="{{url('/bedregistrar',array('print_assessment',$user->idno))}}" class="btn btn-success form form-control">Print Assessment Form</a></td>
            </tr>
            @else
            <tr>
                <td colspan="2"><a href="{{url('/registrar_college',array('print_registration_form',$user->idno,$status->school_year,$status->period))}}" class="btn btn-success form form-control">Print Assessment Form</a></td>
            </tr>
            @endif
            @endif
        </table> 
        </div>
        
    <div class="col-md-12">
            <div class='form-horizontal'>
        <div class='form-group'>
            <div class='col-sm-4'>
                <label>School Year</label>
                <select class="form form-control select2" name="school_year" id='school_year'>
                    <option value="">Select School Year</option>
                    <option value="2017" @if ($school_year == 2017) selected = "" @endif>2017-2018</option>
                <option value="2018" @if ($school_year == 2018) selected = "" @endif>2018-2019</option>
                <option value="2019" @if ($school_year == 2019) selected = "" @endif>2019-2020</option>
                <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                <option value="2020-2021 - 2nd Semester" @if ($school_year == '2020-2021 - 2nd Semester') selected = "" @endif>2020-2021 - 2nd Semester</option>
                <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                </select>
            </div>   
            <div class='col-sm-4'>
                <label>&nbsp;</label>
                <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Set School Year</span></button>
            </div>
        </div>
            </div>
    </div>
    <div class="col-md-12">
        
    <div class="accordion">
        @foreach ($periods as $key => $value)
        
        
        <?php 
        $period = "$periods[$key]";
        if($period == "Yearly"){
            $period = NULL;
        }
        $ledger_main = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $idno)
                ->where(function($query) {
                                $query->where('category_switch', 1)
                                ->orWhere('category_switch', 2)
                                ->orWhere('category_switch', 3)
                                ->orWhere('category_switch', 4)
                                ->orWhere('category_switch', 5)
                                ->orWhere('category_switch', 6)
                                ->orWhere('category_switch', 11)
                                ->orWhere('category_switch', 12)
                                ->orWhere('category_switch', 13)
                                ->orWhere('category_switch', 14)
                                ->orWhere('category_switch', 15)
                                ->orWhere('category_switch', 16);
                            })
                            ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->where('school_year', $school_year)->where('period', $period)->orderBy('category_switch')->get();

            $ledger = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                            ->where(function($query) {
                                $query->where('category_switch', 4)
                                ->orWhere('category_switch', 5)
                                ->orWhere('category_switch', 14)
                                ->orWhere('category_switch', 15);
                            })->groupBy('category', 'category_switch')->where('school_year', $school_year)->where('period', $period)->where('category', '!=', 'SRF')->orderBy('category_switch')->get();

            $ledger_srf = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                            ->where(function($query) {
                                $query->where('category_switch', 4)
                                ->orWhere('category_switch', 14);
                            })->groupBy('category', 'category_switch')->where('school_year', $school_year)->where('period', $period)->where('category', 'SRF')->orderBy('category_switch')->get();

            $ledger_main_tuition = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                    ->where(function($query) {
                                $query->where('category_switch', 6)
                                ->orWhere('category_switch', 16);
                            })->where('school_year', $school_year)->where('period', $period)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_main_misc = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                    ->where(function($query) {
                                $query->where('category_switch', 1)
                                ->orWhere('category_switch', 11);
                            })->where('school_year', $school_year)->where('period', $period)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_main_other = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                    ->where(function($query) {
                                $query->where('category_switch', 2)
                                ->orWhere('category_switch', 12);
                            })->where('school_year', $school_year)->where('period', $period)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_main_depo = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                    ->where(function($query) {
                                $query->where('category_switch', 3)
                                ->orWhere('category_switch', 13);
                            })->where('school_year', $school_year)->where('period', $period)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_tutorials = \App\Ledger::SelectRaw('supply_remarks, category')->where('idno', $idno)->where('category', 'Tutorial Fee')
                    ->where(function($query) {
                                $query->where('category_switch', 4)
                                ->orWhere('category_switch', 14);
                            })->where('school_year', $school_year)->where('period', $period)->orderBy('category')->get();

//for accounting displaying particulars
$ledger_list_tuition = \App\Ledger::where('idno',$user->idno)
        ->where(function($query) {
                                $query->where('category_switch', 6)
                                ->orWhere('category_switch', 16);
                            })->where('school_year', $school_year)->where('period', $period)->first();
$ledger_list_misc = \App\Ledger::where('idno',$user->idno)
        ->where(function($query) {
                                $query->where('category_switch', 1)
                                ->orWhere('category_switch', 11);
                            })->where('school_year', $school_year)->where('period', $period)->get();
$ledger_list_other = \App\Ledger::where('idno',$user->idno)
        ->where(function($query) {
                                $query->where('category_switch', 2)
                                ->orWhere('category_switch', 12);
                            })->where('school_year', $school_year)->where('period', $period)->get();
$ledger_list_depo = \App\Ledger::where('idno',$user->idno)
        ->where(function($query) {
                                $query->where('category_switch', 3)
                                ->orWhere('category_switch', 13);
                            })->where('school_year', $school_year)->where('period', $period)->get();
$ledger_list = \App\Ledger::where('idno',$user->idno)->where('category', 'SRF')->where('school_year', $school_year)->where('period', $period)
        ->where(function($query) {
                                $query->where('category_switch', 4)
                                ->orWhere('category_switch', 14);
                            })->get();
$ledger_list_additional = \App\Ledger::where('idno',$user->idno)->where('category', '!=','SRF')->where('category', '!=','Family Council')->where('category', '!=','Tutorial Fee')->where('school_year', $school_year)->where('period', $period)
        ->where(function($query) {
                                $query->where('category_switch', 5)
                                ->orWhere('category_switch', 15);
                            })->get();
                            
/////
        if($school_year=="2018" and $period == "2nd Semester"){
            $payments = \App\Payment::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
        }else{
            
            if($school_year=="2018"){
            $payments = \App\Payment::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
            }else{
                if($period == NULL){
            $payments = \App\Payment::where('idno', $idno)->where('school_year', $school_year)
                    ->where(function ($query) {
                        $query->where("period","like","Yearly")
                                ->orWhere('school_year', 'like','%')
                              ->orWhere("period",NULL);
                    })->orderBy('transaction_date')->get();
                }else{
            $payments = \App\Payment::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('transaction_date')->get();
                }
            }
        }

/////        
        if($school_year=="2018" and $period == "2nd Semester"){
            $debit_memos = \App\DebitMemo::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
        }else{
            
            if($school_year=="2018"){
            $debit_memos = \App\DebitMemo::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
            }else{
                if($period == NULL){
                $debit_memos = \App\DebitMemo::where('idno', $idno)->where('school_year', $school_year)
                    ->where(function ($query) {
                        $query->where("period","like","Yearly")
                                ->orWhere('school_year', 'like','%')
                              ->orWhere("period",NULL);
                    })->orderBy('transaction_date')->get();
                }else{
                $debit_memos = \App\DebitMemo::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('transaction_date')->get();
                }
            }
        }
        
/////        
        if($school_year=="2018" and $period == "2nd Semester"){
            $student_deposits = \App\AddToStudentDeposit::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
        }else{
            
            if($school_year=="2018"){
            $student_deposits = \App\AddToStudentDeposit::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
            }else{
                if($period == NULL){
                $student_deposits = \App\AddToStudentDeposit::where('idno', $idno)->where('school_year', $school_year)
                    ->where(function ($query) {
                        $query->where("period","like","Yearly")
                                ->orWhere('school_year', 'like','%')
                              ->orWhere("period",NULL);
                    })->orderBy('transaction_date')->get();
                }else{
                $student_deposits = \App\AddToStudentDeposit::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('transaction_date')->get();
                }
            }
        }
        
/////        
        if($school_year=="2018" and $period == "2nd Semester"){
            $overpayments = \App\OverpaymentMemo::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
        }else{
            
            if($school_year=="2018"){
            $overpayments = \App\OverpaymentMemo::where('idno', $idno)
                    ->where(function($query) use($school_year) {
                                $query->where('school_year', $school_year)
                                ->orWhere('school_year', 'like','%')
                                ->orWhere('school_year', NULL);
                            })->orderBy('transaction_date')->get();
            }else{
                if($period == NULL){
                $overpayments = \App\OverpaymentMemo::where('idno', $idno)->where('school_year', $school_year)
                    ->where(function ($query) {
                        $query->where("period","like","Yearly")
                                ->orWhere('school_year', 'like','%')
                              ->orWhere("period",NULL);
                    })->orderBy('transaction_date')->get();
                }else{
                $overpayments = \App\OverpaymentMemo::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('transaction_date')->get();
                }
            }
        }
                     
                        $ledger_others = \App\Ledger::where('idno', $idno)
                                ->where(function($query) {
                                $query->where('category_switch', 7)
                                ->orWhere('category_switch', 17);
                            })->where('school_year', $school_year)->where('period', $period)->get();
            $ledger_others_noreturn = \App\Ledger::where('idno', $idno)
                    ->where(function($query) {
                                $query->where('category_switch', 7)
                                ->orWhere('category_switch', 17);
                            })->where('is_returned_check',0)->where('school_year', $school_year)->where('period', $period)->get();
        ?>
        
        
    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-{{$key}}">A.Y. {{$school_year}}<!---$school_year+1--> {{$period}}</a>
         
        <div id="accordion-{{$key}}" class="accordion-section-content open">
            @if($status->academic_type == 'College' && (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD")))
            <h3>COURSES ENROLLED</h3>
                @if($status->status > 1)
            <table class="table table-bordered table-condensed">
                @if(count($ledger_tutorials)>0)
                @foreach($ledger_tutorials as $tutorial)
                <?php $total_tutorial+=$tutorial->supply_remarks; ?>
                @endforeach
                <tr>
                    <th colspan="2">Total Number of Tutorials in Units</th>
                    <th>{{$total_tutorial}}</th>
                </tr>
                @endif
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Units</th>
                </tr>
                <?php $units=0; $grades = \App\GradeCollege::where('idno', $user->idno)->where('school_year', $school_year)->where('period', $period)->get(); ?>
                @if(count($grades)>0)
                @foreach ($grades as $grade)
                <tr>
                    <td width='25%'>{{$grade->course_code}}</td>
                    <td>{{$grade->course_name}}</td>
                    <td>{{$grade->lec + $grade->lab}}<?php $units = $grade->lec + $grade->lab + $units; ?></td>
                </tr>
                @endforeach
                <tr>
                    <th colspan='2'>Total Units</th>
                    <th>{{$units}}</th>
                </tr>
                
                @else
                No Courses/Enrolled/Advised
                @endif
            </table>
                @else <h5>No Courses Enrolled/Advised</h5>
                @endif
        @endif
            
            
            <h3>MAIN FEES</h3>
           @if(count($ledger_main)>0)
           @if(Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
            <table class="table table-bordered table-condensed"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Net</th><th>Debit Memo</th><th>Payment</th><th>Balance</th><th>Edit</th></tr>
                @else
            <table class="table table-bordered table-condensed"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Net</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
                @endif
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;$totalnet=0;
           ?>
                
                
                
           @foreach($ledger_main_tuition as $main_tuition)
           <?php
               $totalamount=$totalamount+$main_tuition->amount;
               $totaldiscount=$totaldiscount+$main_tuition->discount;
               $totaldm=$totaldm+$main_tuition->debit_memo;
               $totalpayment=$totalpayment+$main_tuition->payment;
               $balance=+$main_tuition->amount-$main_tuition->discount-$main_tuition->debit_memo-$main_tuition->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $main_tuition->amount - ($main_tuition->discount);
               $totalnet = $totalnet + $net;
               ?>
               <tr style="background-color: white"><td>{{$main_tuition->category}}</td>
               <td align="right">{{number_format($main_tuition->amount,2)}}</td>
               <td align="right">{{number_format($main_tuition->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($main_tuition->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main_tuition->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               </tr>
           @endforeach
           
           
           
           
           
           @foreach($ledger_main_misc as $main_misc)
           <?php
               $totalamount=$totalamount+$main_misc->amount;
               $totaldiscount=$totaldiscount+$main_misc->discount;
               $totaldm=$totaldm+$main_misc->debit_memo;
               $totalpayment=$totalpayment+$main_misc->payment;
               $balance=+$main_misc->amount-$main_misc->discount-$main_misc->debit_memo-$main_misc->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $main_misc->amount - ($main_misc->discount);
               $totalnet = $totalnet + $net;
               ?>
               <tr style="background-color: white"><td>{{$main_misc->category}}</td>
               <td align="right">{{number_format($main_misc->amount,2)}}</td>
               <td align="right">{{number_format($main_misc->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($main_misc->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main_misc->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach    
           @if(Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
           @foreach($ledger_list_misc as $list_misc)
           <?php $balance=+$list_misc->amount-$list_misc->discount-$list_misc->debit_memo-$list_misc->payment; ?>
           <?php $listnet = $list_misc->amount - ($list_misc->discount); ?>
               <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$list_misc->subsidiary}}</td>
               <td align="right">{{number_format($list_misc->amount,2)}}</td>
               <td align="right">{{number_format($list_misc->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($listnet,2)}}</span></td>
               <td align="right">{{number_format($list_misc->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($list_misc->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               <td><a href="{{url('/accounting', array('edit_ledger', $list_misc->id))}}">Edit</a></td>
               </tr>
           @endforeach  
           @endif
           
           
           
           
           
           
           @foreach($ledger_main_other as $main_other)
           <?php
               $totalamount=$totalamount+$main_other->amount;
               $totaldiscount=$totaldiscount+$main_other->discount;
               $totaldm=$totaldm+$main_other->debit_memo;
               $totalpayment=$totalpayment+$main_other->payment;
               $balance=+$main_other->amount-$main_other->discount-$main_other->debit_memo-$main_other->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $main_other->amount - ($main_other->discount);
               $totalnet = $totalnet + $net;
               ?>
               <tr style="background-color: white"><td>{{$main_other->category}}</td>
               <td align="right">{{number_format($main_other->amount,2)}}</td>
               <td align="right">{{number_format($main_other->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($main_other->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main_other->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           @if(Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
           @foreach($ledger_list_other as $list_other)
           <?php $balance=+$list_other->amount-$list_other->discount-$list_other->debit_memo-$list_other->payment; ?>
           <?php $listnet = $list_other->amount - ($list_other->discount); ?>
               <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$list_other->subsidiary}}</td>
               <td align="right">{{number_format($list_other->amount,2)}}</td>
               <td align="right">{{number_format($list_other->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($listnet,2)}}</span></td>
               <td align="right">{{number_format($list_other->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($list_other->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               <td><a href="{{url('/accounting', array('edit_ledger', $list_other->id))}}">Edit</a></td>
               </tr>
           @endforeach
           @endif
           
           
           
           
           
           
           
           @foreach($ledger_main_depo as $main_depo)
           <?php
               $totalamount=$totalamount+$main_depo->amount;
               $totaldiscount=$totaldiscount+$main_depo->discount;
               $totaldm=$totaldm+$main_depo->debit_memo;
               $totalpayment=$totalpayment+$main_depo->payment;
               $balance=+$main_depo->amount-$main_depo->discount-$main_depo->debit_memo-$main_depo->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $main_depo->amount - ($main_depo->discount);
               $totalnet = $totalnet + $net;
               ?>
               <tr style="background-color: white"><td>{{$main_depo->category}}</td>
               <td align="right">{{number_format($main_depo->amount,2)}}</td>
               <td align="right">{{number_format($main_depo->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($main_depo->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main_depo->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           @if(Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
           @foreach($ledger_list_depo as $list_depo)
           <?php $balance=+$list_depo->amount-$list_depo->discount-$list_depo->debit_memo-$list_depo->payment; ?>
           <?php $listnet = $list_depo->amount - ($list_depo->discount); ?>
               <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$list_depo->subsidiary}}</td>
               <td align="right">{{number_format($list_depo->amount,2)}}</td>
               <td align="right">{{number_format($list_depo->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($listnet,2)}}</span></td>
               <td align="right">{{number_format($list_depo->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($list_depo->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               <td><a href="{{url('/accounting', array('edit_ledger', $list_depo->id))}}">Edit</a></td>
               </tr>
           @endforeach
           @endif
           <tr style="background-color: lightgray;"><td>Total School Fees</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right"><span class="net">{{number_format($totalnet,2)}}</span></td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td>
           </tr>
           
           
           
           
           
       
           @foreach($ledger as $main)
            <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $main->amount - ($main->discount);
               $totalnet = $totalnet + $net;
            ?>
               <tr style="background-color: white"><td>{{$main->category}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               </tr>
           @endforeach
           
           @if(Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
           @foreach($ledger_list_additional as $list)
           <?php $balance=+$list->amount-$list->discount-$list->debit_memo-$list->payment; ?>
           <?php $listnet = $list->amount - ($list->discount); ?>
               <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$list->subsidiary}}</td>
               <td align="right">{{number_format($list->amount,2)}}</td>
               <td align="right">{{number_format($list->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($listnet,2)}}</span></td>
               <td align="right">{{number_format($list->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($list->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               <td><a href="{{url('/accounting', array('edit_ledger', $list->id))}}">Edit</a></td>
               </tr>
           @endforeach
           @endif
           
           
           
           @foreach($ledger_srf as $srf)
           <?php
               $totalamount=$totalamount+$srf->amount;
               $totaldiscount=$totaldiscount+$srf->discount;
               $totaldm=$totaldm+$srf->debit_memo;
               $totalpayment=$totalpayment+$srf->payment;
               $balance=+$srf->amount-$srf->discount-$srf->debit_memo-$srf->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $srf->amount - ($srf->discount);
               $totalnet = $totalnet + $net;
               ?>
               <tr style="background-color: white"><td>{{$srf->category}}</td>
               <td align="right">{{number_format($srf->amount,2)}}</td>
               <td align="right">{{number_format($srf->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($srf->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($srf->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               </tr>
           @endforeach
           
           
           
           
           @if(Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
           @foreach($ledger_list as $list)
           <?php $balance=+$list->amount-$list->discount-$list->debit_memo-$list->payment; ?>
           <?php $listnet = $list->amount - ($list->discount); ?>
               <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$list->subsidiary}}</td>
               <td align="right">{{number_format($list->amount,2)}}</td>
               <td align="right">{{number_format($list->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($listnet,2)}}</span></td>
               <td align="right">{{number_format($list->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($list->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td>
               <td><a href="{{url('/accounting', array('edit_ledger', $list->id))}}">Edit</a></td>
               </tr>
           @endforeach
           @endif
           
           
           
           
           
               <tr style="background-color: silver;"><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right"><span class="net">{{number_format($totalnet,2)}}</span></td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table> 
            @else
            <h5>This Student Is Not Yet Assessed</h5>
            @endif
            
            
            
            <!-- OTHER FEES -->
            <h3>OTHER FEES</h3>
            @if(count($ledger_others)>0)
            <table class="table table-bordered table-condensed"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Net</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;$totalnet=0;
           ?>
           @foreach($ledger_others as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               $net = $main->amount - ($main->discount);
               $totalnet = $totalnet + $net;
               ?>
               <tr><td>{{$main->receipt_details}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right"><span class="net">{{number_format($net,2)}}</span></td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
               <tr><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right"><span class="net">{{number_format($totalnet,2)}}</span></td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table>  
            @else
            <h5>No Other Payment</h5>
            @endif 
            
            
            <!--PAYMENT HISTORY-->
            <h3>PAYMENT HISTORY</h3>
            @if(count($payments)>0)
         <table class="table table-responsive table-condensed"><tr><td>Date</td><td>Receipt No</td><td>Explanation</td><td>Amount</td><td>Status</td><td>View</td></tr>
          @foreach($payments as $payment)
          <tr><td>{{$payment->transaction_date}}</td>
              <td>{{$payment->receipt_no}}</td>
              <td>{{$payment->remarks}}</td>
              <td align='right'>{{number_format($payment->cash_amount+$payment->check_amount+$payment->credit_card_amount+$payment->deposit_amount,2)}}</td>
              <td>@if($payment->is_reverse=='0') Ok @else Canceled @endif</td>
              <td><a href="{{url('/cashier',array('viewreceipt',$payment->reference_id))}}">View</a></td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Payment Has Been Made Yet</h5>
         @endif
         
         <!--DEBIT MEMO-->
         <h3>DEBIT MEMO</h3>
         @if(count($debit_memos)>0)
        
         <table class="table table-responsive table-condensed"><tr><td>Date</td><td>DM No</td><td>Explanation</td><td>Amount</td><td>Status</td><td>View</td></tr>
          @foreach($debit_memos as $payment)
          <tr><td>{{$payment->transaction_date}}</td>
              <td>{{$payment->dm_no}}</td>
              <td>{{$payment->explanation}}</td>
              <td align='right'>{{number_format($payment->amount,2)}}</td>
              <td>@if($payment->is_reverse=='0') Ok @else Canceled @endif</td>
              <td><a  href="{{url('/accounting',array('view_debit_memo',$payment->reference_id))}}">View</a></td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Debit Memo For This Account</h5>
         @endif
         
         
            
            <!--ADDED AS STUDENT DEPOSIT-->
            <h3>ADDED AS SD/RESERVATION</h3>
            @if(count($student_deposits)>0)
        
         <table class="table table-responsive table-condensed"><tr><td>Date</td><td>SD No</td><td>Explanation</td><td>Amount</td><td>Status</td><td>View</td></tr>
          @foreach($student_deposits as $payment)
          <tr><td>{{$payment->transaction_date}}</td>
              <td>{{$payment->sd_no}}</td>
              <td>{{$payment->explanation}}</td>
              <td align='right'>{{number_format($payment->amount,2)}}</td>
              <td>@if($payment->is_reverse=='0') Ok @else Canceled @endif</td>
              <td><a  href="{{url('/accounting',array('view_add_to_student_deposit',$payment->reference_id))}}">View</a></td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Added to Student Deposit For This Account</h5>
         @endif
         
         
            
            <!--OVERPAYMENT MEMO-->
            <h3>OVERPAYMENT MEMO</h3>
            @if(count($overpayments)>0)
        
         <table class="table table-responsive table-condensed"><tr><td>Date</td><td>Overpayment No</td><td align="right">Amount</td><td>Status</td></tr>
          @foreach($overpayments as $payment)
          <tr><td>{{$payment->transaction_date}}</td>
              <td>{{$payment->op_no}}</td>
              <td align='right'>{{number_format($payment->amount,2)}}</td>
              <td>Ok</td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Overpayment Memo For This Account</h5>
         @endif
         
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    
        @endforeach
    
<!--    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-2">Other Fees</a>
         
        <div id="accordion-2" class="accordion-section-content open">
            
        </div>end .accordion-section-content
    </div>end .accordion-section-->
    
    
    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-4">Previous Balance</a>
    <div id="accordion-4" class="accordion-section-content">
            @if(count($previous)>0)
            <table class="table table-bordered table-condensed"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;
           ?>
           @foreach($previous as $main)
           <?php 
            $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
           ?>
           @if(!$balance == 0)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->subsidiary}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
               @endif
           @endforeach
           @if(!$totalbalance == 0)
               <tr><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
               @endif
            </table>  
            @else
            <h5>No Previous Balance</h5>
            @endif 
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    
     </div>
        <hr>
     
         
    </div>  
    </div>
    <div class="col-md-4">
        <div class="form-group">
        <label>Total Due:</label>
        <div class="form form-control" id="due_display">
            @if($totaldue>0)
            {{number_format($totaldue,2)}}
            @else
            0.00
            @endif
        </div>
        </div>
        @if(Auth::user()->accesslevel==env("CASHIER"))
        @if($status->status == env('ASSESSED'))
            @if($is_early_enrollment == 1)
            <div class="form-group">
                <h2>Cannot Process Payment</h2>
            </div>
            @else
            <div class="form-group">
            <a href="{{url('/cashier',array('main_payment',$user->idno))}}" class="form form-control btn btn-primary">Process Payment</a>
            </div>
            @endif
        @else
        <div class="form-group">
        <a href="{{url('/cashier',array('main_payment',$user->idno))}}" class="form form-control btn btn-primary">Process Payment</a>
        </div>
        @endif
        <div class="form-group">
        <a href="{{url('/cashier',array('other_payment',$user->idno))}}" class="form form-control btn btn-success">Other Payment</a>
        </div>
         <div class="form-group">
        <a class="form form-control btn btn-success" href="{{url('cashier',array('reservation',$user->idno))}}">Reservation/Student Deposit</a>
        </div>
        <div class="form-group">
        <a href="{{url('/accounting',array('breakdown_of_fees',$user->idno))}}" class="form form-control btn btn-primary">Breakdown of Fees</a>
        </div>
        @elseif(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD"))
<!--        <div class="form-group">
        <a href="{{url('/accounting',array('edit_ledger',$user->idno))}}" class="form form-control btn btn-primary">EDIT LEDGER</a>
        </div>-->
        <div class="form-group">
        <a href="{{url('/accounting',array('debit_memo',$user->idno))}}" class="form form-control btn btn-primary">DEBIT MEMO</a>
        </div>
        <div class="form-group">
        <a href="{{url('/accounting',array('add_to_student_deposit',$user->idno))}}" class="form form-control btn btn-primary">ADD TO SD/RESERVATION</a>
        </div>
        <div class="form-group">
        <a href="{{url('/accounting',array('add_to_account',$user->idno))}}" class="form form-control btn btn-primary">OTHER PAYMENT</a>
        </div>
        <div class="form-group">
        <a href="{{url('/accounting',array('breakdown_of_fees',$user->idno))}}" class="form form-control btn btn-primary">BREAKDOWN OF FEES</a>
        </div>
        @if(($status->academic_type == "BED" || $status->academic_type =="SHS" || $status->academic_type == "College") && $status->status==env("ENROLLED"))
        <div class="form-group">
        <a href="{{url('/accounting',array('change_plan',$user->idno))}}" class="form form-control btn btn-primary">CHANGE PLAN</a>
        </div>
        @endif
        
        @endif
        @if(count($due_dates)>0)
        <label>Schedule of Payment</label>
        <div class="form-group">
            <?php $totalpay = $totalpay; $display=""; $remark="";?>
            <table class="table table-striped"><tr><td>Due Date</td><td align="right">Due Amount</td><td>Remarks</td></tr>
            @foreach($due_dates as $due_date)
            <?php 
            if($totalpay >= $due_date->amount){
                $display = "<span class=\"text_through\">".number_format($due_date->amount,2)."<span>";  
                $totalpay = $totalpay - $due_date->amount;
                $remark = "<span style=\"font-style:italic;color:#f00\">paid</span>";
            } else {
                $display = number_format($due_date->amount-$totalpay,2);
                $totalpay=0;
                $remark="";
            }
            ?>
            @if($due_date->due_switch=="0")
            <?php $duedate = "Upon Enrollment";?>
            @else
            <?php $duedate = $due_date->due_date;?>
            @endif
            <tr><td>{{$duedate}}</td><td align="right">{!!$display!!}</td><td align="center">{!!$remark!!}</td></tr>
            @endforeach
            </table>    
        </div>
        @endif
        
        <label>Overpayment</label>
        <table class="table table-striped">
            <tr>
                <td>Amount</td>
                <td align='right'><strong>Php {{number_format(abs($negative),2)}}</strong></td>
            </tr>
            @if(abs($negative) > 0 || abs($negative) < 0)
            <tr>
                <td colspan="2">
                @if(Auth::user()->accesslevel!=env("CASHIER") && $totaldue >= abs($negative) && abs($negative!=0))
                <a href="{{url('apply_overpayment/sd', $idno)}}"><button class="btn btn-danger pull-right">Apply to Student Deposit</button></a>
                <a href="{{url('apply_overpayment/ld', $idno)}}"><button class="btn btn-warning pull-left">Apply to Ledger</button></a>
                @elseif(Auth::user()->accesslevel!=env("CASHIER") && abs($negative) > 0)
                <a href="{{url('apply_overpayment/sd', $idno)}}"><button class="btn btn-warning pull-right" >Move to Student Deposit</button></a>
                @endif
                </td>
            </tr>
            @endif
        </table>
        
        @if(count($reservations)>0)
        <label>Reservation</label>
        <table class="table table-striped">
            <tr><td>Date</td><td>OR#</td><td>Amount</td><td>Status</td><td></td></tr>
            @foreach($reservations as $reservation)
            <tr><td>{{$reservation->transaction_date}}</td>
                @if($reservation->reference_id != null)
                <?php $orNumber = \App\Payment::where('reference_id', $reservation->reference_id)->first(); ?>
                @if($orNumber)
                <td><a href="{{url('cashier', array('viewreceipt',$reservation->reference_id))}}">{{$orNumber->receipt_no}}</a></td>
                @else
                <td></td>
                @endif
                @else
                <td></td>
                @endif
                <td align="right">{{number_format($reservation->amount,2)}}</td>
                <td>
                    @if($reservation->is_reverse=="1")
                    <i class="fa fa-close"></i> Canceled
                    @else
                    @if($reservation->is_consumed=="1")
                    <i class="fa fa-times"></i> Used
                    @elseif($reservation->is_consumed=="2")
                    <i class="fa fa-times"></i> Tag as Used
                    @else
                    <i class="fa fa-check"></i> Unused
                    @endif
                    @endif
                </td>
                <td>
                    @if($reservation->is_reverse=="1" || $reservation->is_consumed=="1"  || $reservation->is_consumed=="2")
                    @else
                    <a href="{{url('reservation',array('tag_as_used',$school_year,$reservation->reference_id))}}">Tag as Used</a>
                    @endif
                </td>
                </tr>
            @endforeach
        </table>    
        @else
        <label>Reservation</label>
        <table class="table table-striped">
            <tr><td><i class="label label-danger">No Reservation Has Been Made Yet!!!!!</i></td></tr>
        </table>
        
        
        @endif
        
        @if(count($deposits)>0)
        <label>Student Deposit</label>
        <table class="table table-striped">
            <tr><td>Date</td><td>OR#</td><td>Amount</td><td>Status</td><td></td></tr>
            @foreach($deposits as $reservation)
            <tr><td>{{$reservation->transaction_date}}</td>
                @if($reservation->reference_id != null)
                <?php 
                $orNumber = \App\Payment::where('reference_id', $reservation->reference_id)->first();
                if(count($orNumber)==0){
                $orNumber = \App\AddToStudentDeposit::where('reference_id', $reservation->reference_id)->first();
                }
                ?>
                <td><a href="{{url('cashier', array('viewreceipt',$reservation->reference_id))}}">{{$orNumber->receipt_no}}</a></td>
                @else
                <td></td>
                @endif
                <td align="right">{{number_format($reservation->amount,2)}}</td>
                <td>@if($reservation->is_reverse=="1")
                    <i class="fa fa-close"></i> Canceled
                    @else
                    @if($reservation->is_consumed=="1")
                    <i class="fa fa-times"></i> Used
                    @elseif($reservation->is_consumed=="2")
                    <i class="fa fa-times"></i> Tag as Used
                    @else
                    <i class="fa fa-check"></i> Unused
                    @endif
                    @endif
                    </td>
                    <td>
                        @if($reservation->is_reverse=="1" || $reservation->is_consumed=="1"  || $reservation->is_consumed=="2")
                        @else
                        <a href="{{url('reservation',array('tag_as_used',$school_year,$reservation->reference_id))}}">Tag as Used</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>    
        @else
        <label>Student Deposit</label><table class="table table-striped">
            <tr><td><i class="label label-danger">No Student Deposit Has Been Made Yet!!!!!</i></td></tr>
            
        </table>
        @endif
    </div>
     
</div>    
@endsection
@section('footerscript')
<style>
     #due_display{
        text-align:right;
        font-size:30pt; 
        font-weight: bold; 
        color:#9F0053;
        height:70px;
    }
    .payment{
        color:#f00;
        font-weight: bold;
    }
    .net{
        color:#000077;
        font-weight: bold;
    }
    .history{
        background-color: #ccc;
        padding: 10px;
    }
    
    .accordion, .accordion * {
    -webkit-box-sizing:border-box; 
    -moz-box-sizing:border-box; 
    box-sizing:border-box;
    
}
 
.accordion {
    overflow:hidden;
    box-shadow:0px 1px 3px rgba(0,0,0,0.25);
    border-radius:3px;
    background:#f7f7f7;
}
 
/*----- Section Titles -----*/
.accordion-section-title {
    width:100%;
    padding:5px;
    display:inline-block;
    border-bottom:1px solid #1a1a1a;
    background:goldenrod;
    transition:all linear 0.15s;
    /* Type */
    font-size:1.200em;
    text-shadow:0px 1px 0px #1a1a1a;
    color:#fff;
}
 
.accordion-section-title.active, .accordion-section-title:hover {
    background:goldenrod;
    /* Type */
    text-decoration:none;
    color:#fff;
}
 
.accordion-section:last-child .accordion-section-title {
    border-bottom:none;
}
 
/*----- Section Content -----*/
.accordion-section-content {
    padding:15px;
    display:none;
}

.text_through{
    text-decoration: line-through;
    color: #aaa;
}
</style>
<script>
   $(document).ready(function() {
    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
    
    $('#accordion-0').slideUp(300).addClass('open');
    $('#accordion-1').slideUp(300).addClass('open');
    $('#accordion-2').slideUp(300).addClass('open'); 
    $('#accordion-4').slideUp(300).addClass('open'); 
    
    if("{{$status->school_year}}" == "{{$school_year}}"){
        if("{{$status->academic_type}}"=="BED"){
        $('#accordion-0').slideDown(300).addClass('open');
        }else if("{{$status->academic_type}}"=="SHS"){
            if("{{$status->period}}" == "1st Semester"){
                $('#accordion-1').slideDown(300).addClass('open'); 
            }else if("{{$status->period}}" == "2nd Semester"){
                $('#accordion-2').slideDown(300).addClass('open');
            }else if("{{$status->period}}" == "Summer"){
                $('#accordion-3').slideDown(300).addClass('open');
            }
        }else{
            if("{{$status->period}}" == "1st Semester"){
                $('#accordion-0').slideDown(300).addClass('open'); 
            }else if("{{$status->period}}" == "2nd Semester"){
                $('#accordion-1').slideDown(300).addClass('open');
            }else if("{{$status->period}}" == "Summer"){
                $('#accordion-2').slideDown(300).addClass('open');
            }
        }
    }
    
    $('.accordion-section-title').click(function(e) {
        // Grab current anchor value
        var currentAttrValue = $(this).attr('href');
 
        if($(e.target).is('.active')) {
            $(this).removeClass('active');
             $('.accordion ' + currentAttrValue).slideUp(300).addClass('open');
            //close_accordion_section();
        }else {
            //close_accordion_section();
 
            // Add active class to section title
            $(this).addClass('active');
            // Open up the hidden content panel
            $('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
        }
 
        e.preventDefault();
    });
});
</script>    
<script>
    $(document).ready(function(){
      $("#view-button").on('click',function(e){
        document.location="{{url('/cashier',array('viewledger'))}}" + "/" + $("#school_year").val() + "/" + "{{$idno}}";
      });
    });
</script>
<!--<script>
    $('body').addClass('sidebar-collapse');
</script>-->
@endsection
