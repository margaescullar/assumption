<?php
function get_value($id,$reference_id){
    $get_ledger = \App\Accounting::where('reference_id',$reference_id)->where('accounting_type',1)->where('reference_number',$id)->first();
    if(count($get_ledger)>0){
        return "$get_ledger->credit";
    }else{
        return 0.00;
    }
}
?>

<?php
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
<!--<h1>Module not yet done...</h1>-->
<section class="content-header">
      <h1>
        Update OR Number: {{$payment->receipt_no}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Receipt</li>
      </ol>
</section>
@endsection
@section('maincontent')
<form action="{{url('/update_receipt')}}" method="POST">
{{csrf_field()}}
<input type='hidden' value='{{$payment->reference_id}}' name='reference_id'>
<input type='hidden' value='{{$payment->posted_by}}' name='posted_by'>
<input type='hidden' value='{{$payment->transaction_date}}' name='transaction_date'>

<!--start of form-->
<!--Payment Details-->
    <div class="box">
        <div class="box-header">
            <div class="box-title">Payment Details</div>
        </div>
        <div class="box-body">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Receipt No:</label>
                    <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="{{$payment->receipt_no}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Cash Amount:</label>
                    <input type="text" class="form-control" id="amount_received" name="amount_received" value="{{$payment->amount_received}}">
                </div>
                <div class="col-sm-3">
                    <label>Change:</label>
                    <input type="text" class="form-control" id="change" name="change" value="{{$payment->amount_received-$payment->cash_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-2">
                    <label>Credit Card Bank:</label>
                    <input type="text" class="form-control" id="credit_card_bank" name="credit_card_bank" value="{{$payment->credit_card_bank}}">
                </div>
                <div class="col-sm-2">
                    <label>Type:</label>
                    <input type="text" class="form-control" id="credit_card_type" name="credit_card_type" value="{{$payment->credit_card_type}}">
                </div>
                <div class="col-sm-3">
                    <label>Approval Number:</label>
                    <input type="text" class="form-control" id="approval_number" name="credit_card_type" value="{{$payment->approval_number}}">
                </div>
                <div class="col-sm-3">
                    <label>Credit Card Number:</label>
                    <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" value="{{$payment->credit_card_number}}">
                </div>
                <div class="col-sm-2">
                    <label>Amount:</label>
                    <input type="text" class="form-control" id="credit_card_amount" name="credit_card_amount" value="{{$payment->credit_card_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Check Bank:</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{$payment->bank_name}}">
                </div>
                <div class="col-sm-3">
                    <label>Check Number:</label>
                    <input type="text" class="form-control" id="check_number" name="check_number" value="{{$payment->check_number}}">
                </div>
                <div class="col-sm-3">
                    <label>Amount:</label>
                    <input type="text" class="form-control" id="check_amount" name="check_amount" value="{{$payment->check_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Deposit Reference:</label>
                    <input type="text" class="form-control" id="deposit_reference" name="deposit_reference" value="{{$payment->deposit_reference}}">
                </div>
                <div class="col-sm-3">
                    <label>Amount:</label>
                    <input type="text" class="form-control" id="deposit_amount" name="deposit_amount" value="{{$payment->deposit_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Remarks:</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{$payment->remarks}}">
                </div>
            </div>
        </div>
    </div>

<!--Payment Particulars-->
    <div class="box">
        <div class="box-header">
            <div class="box-title">Payment Particulars</div>
        </div>
        <div class="box-body">
            <div class="form-group">
                @foreach($ledger_school_years as $ledger_school_year)
                    <?php $periods = \App\Ledger::where('idno',$payment->idno)->where('school_year',$ledger_school_year->school_year)->groupBy('period')->get(['period']) ?>
                    @foreach($periods as $period)
                    <table class='table-condensed table' width='100%'>
                        <tr>
                            <td colspan='3'><strong>{{$ledger_school_year->school_year}} - {{$period->period}}</strong></td>
                        </tr>
                        <tr>
                            <td>Control ID</td>
                            <td>Subsidiary</td>
                            <td>Amount</td>
                            <td>Balance</td>
                            <td>Amount</td>
                        </tr>
                    @foreach($ledger_particulars->where('school_year',$ledger_school_year->school_year)->where('period',$period->period) as $particular)
                    <?php $credit=get_value($particular->id,$reference_id); ?>
                    <tr>
                        <td>{{$particular->id}}</td>
                        <td>{{$particular->subsidiary}}</td>
                        <td>{{$particular->amount}}</td>
                        <td>{{$balance = ($particular->amount+$credit) - ($particular->payment+$particular->discount+$particular->debit_memo)}}</td>
                        @if($balance > 0)
                        <td><input type='text' name="reference_number[{{$particular->id}}]" value="{{$credit}}"></td>
                        @else
                        <td><input disabled=""></td>
                        @endif
                    </tr>
                    @endforeach
                    </table><br>
                    @endforeach
                @endforeach
                <input type="submit" class="btn btn-success col-sm-12" value="UPDATE RECEIPT">
            </div>
        </div>
    </div>



</form>
@endsection
@section('footerscript')
@endsection
