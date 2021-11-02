<?php
$totalcash = 0;
$totalcheck = 0;
$totalcreditcard = 0;
$totalbankdeposit = 0;
$total = 0;
$ntotal = 0;
$grandtotal = 0;

$totalcanceled = 0;
$layout = "";
if (Auth::user()->accesslevel == env("CASHIER")) {
    $layout = "layouts.appcashier";
} else if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
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
<section class="content-header">
    <h1>
        Cancelled OR
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cancelled OR</li>
    </ol>
</section>
@endsection
@section('maincontent')
<?php $cashier_users = \App\Payment::distinct()->join('users', 'users.idno', '=', 'payments.posted_by')->where('users.accesslevel', '!=', 0)->orderBy('payments.posted_by', 'asc')->get(['posted_by']); ?>
<!-- search form (Optional) -->
<div class="container-fluid">
    <div class="form-group">
        <label>Date range button:</label>
        <div class="input-group">
            <button type="button" class="btn btn-default pull-left" id="daterange">
                <span>
                    <i class="fa fa-calendar"></i> <span id='range'>{{$date_from}} , {{$date_to}}</span>
                </span>
                <i class="fa fa-caret-down"></i>

            </button>

            <a href="javascript:void(0)" class="btn btn-primary" id="view-button">View Summary</a>

            <input id="date_to" class="form-control" type="hidden" value="{{$date_to}}">
            <input id="date_from" class="form-control" type="hidden" value="{{$date_from}}">
            <label>Posted by:</label>
            <select name="posted_by" class='posted_by' id="posted_by" disabled=''>
                <option @if($posted_by == 'all') selected='' @endif value="all">All</option>
                @foreach($cashier_users as $cashier_user)
                <option @if($cashier_user->posted_by == $posted_by) selected='' @endif>{{$cashier_user->posted_by}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="box">    
        <div class="box-body table-responsive">

            <table id="example1" class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>OR No</th>
                        <th>ID No</th>
                        <th>Name</th>
                        <th>Remarks</th>
                        <th>Cash</th>
                        <th>Check No</th>
                        <th>Bank</th>
                        <th>Check Amount</th>
                        <th>Card No</th>
                        <th>Card Desc</th>
                        <th>Card Amount</th>
                        <th>Deposit References</th>
                        <th>Deposit Amount</th>
                        <th>Total</th>
                        <th>Status</th>
                        @if(Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD"))
                        <th>Posted By</th>
                        @endif
                        <th>View</th>
                        <th>Discrepancy</th></tr>
                </thead>
                <tbody>
                    @if(count($payments)>0)
                    @foreach($payments as $payment)

                    <?php
                    $totalcredit_per = 0;
                    $totaldebit_per = 0;

                    $credits_per = \App\Accounting::selectRaw('sum(credit) as credit')->where('credit', '>', '0')->where('reference_id', $payment->reference_id)->where('accounting_type', '1')->groupBy('reference_id')->get();
                    $debits_per = \App\Accounting::selectRaw('sum(debit) as debit')->where('debit', '>', '0')->where('reference_id', $payment->reference_id)->where('accounting_type', '1')->groupBy('reference_id')->get();
                    ?>
                    <?php
                    if ($payment->is_reverse == 0) {
                        $totalcash = $totalcash + $payment->cash_amount;
                        $totalcheck = $totalcheck + $payment->check_amount;
                        $totalcreditcard = $totalcreditcard + $payment->credit_card_amount;
                        $totalbankdeposit = $totalbankdeposit + $payment->deposit_amount;
                        $total = $payment->cash_amount + $payment->check_amount + $payment->credit_card_amount + $payment->deposit_amount;
                        $ntotal = $totalcash + $totalcheck + $totalcreditcard + $totalbankdeposit;
                        $grandtotal = $grandtotal + $total;
                    }
                    ?>
                    <tr><td>{{$payment->transaction_date}}</td>
                        <td>{{$payment->receipt_no}}</td>
                        @if($payment->idno == 999999)
                        <td></td>
                        @else
                        <td>{{$payment->idno}}</td>
                        @endif
                        <td>{{$payment->paid_by}}</td>
                        <td>{{$payment->remarks}}</td>
                        @if($payment->is_reverse=="0")
                        <td class="decimal">{{number_format($payment->cash_amount,2)}}</td>
                        <td class="decimal">{{$payment->check_number}}</td>
                        <td class="decimal">{{$payment->bank_name}}</td>
                        <td class="decimal">{{number_format($payment->check_amount,2)}}</td>
                        <td class="decimal">{{$payment->credit_card_no}}</td>
                        <td class="decimal">{{$payment->credit_card_bank}}</td>
                        <td class="decimal">{{number_format($payment->credit_card_amount,2)}}</td>
                        <td class="decimal">{{$payment->deposit_reference}}</td>
                        <td class="decimal">{{number_format($payment->deposit_amount,2)}}</td>
                        <td class="decimal"><b>{{number_format($total,2)}}</b></td>
                        <td>OK</td>
                        @else
                        <?php
                        $totalcanceled = $payment->cash_amount + $payment->check_amount + $payment->credit_card_amount + $payment->deposit_amount;
                        ?>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{number_format($payment->cash_amount,2)}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{$payment->check_number}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{$payment->bank_name}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{number_format($payment->check_amount,2)}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{$payment->credit_card_no}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{$payment->credit_card_bank}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{number_format($payment->credit_card_amount,2)}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{$payment->deposit_reference}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through'>
                                <span style='color:black'>{{number_format($payment->deposit_amount,2)}}</span></span></td>
                        <td class="decimal"><span style='color:red;text-decoration:line-through;'>
                                <span style='color:#999'>{{number_format($totalcanceled,2)}}</span></span></td>
                        <td>Canceled</td>
                        @endif
                        @if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD"))
                        <td>{{$payment->posted_by}}</td>
                        @endif
                        <td><a href="{{url('/cashier',array('viewreceipt',$payment->reference_id))}}">View</a></td>
                        <td>
                            @foreach($credits_per as $credits_pers)
                            <?php $totalcredit_per = $totalcredit_per + $credits_pers->credit; ?>
                            @endforeach
                            @foreach($debits_per as $debits_pers)
                            <?php $totaldebit_per = $totaldebit_per + $debits_pers->debit; ?>
                            @endforeach
                            {{$totaldebit_per-$totalcredit_per}}
                        </td>
                    </tr>
                    @endforeach
                    @else
                    @endif
                </tbody>
                <tfoot>
                    <tr><th colspan="5">Total</th>
                        <th class="decimal">{{number_format($totalcash,2)}}</th>
                        <th></th>
                        <th></th>
                        <th class="decimal">{{number_format($totalcheck,2)}}</th>
                        <th></th>
                        <th></th>
                        <th class="decimal">{{number_format($totalcreditcard,2)}}</th>
                        <th></th>
                        <th class="decimal">{{number_format($totalbankdeposit,2)}}</th>
                        <th class="decimal">{{number_format($grandtotal,2)}}</th>
                        <th></th></tr>

                </tfoot>    
            </table> 

            @if(count($credits)>0)
            <?php
            $totalcredit = 0;
            $totaldebit = 0;
            ?>
            <div class="col-sm-6">
                <span>
                    <br>Summary of Transactions
                </span>
                <table cellspacing="0" cellpadding="2" width="50%" class="table table-responsive">
                    <tr>
                        <td>Particular</td>
                        <td>Debit</td>
                        <td>Credit</td>
                    </tr>
                    @foreach($debits_summary as $debit)
                    <?php $totaldebit = $totaldebit + $debit->debit; ?>
                    <tr>
                        <td>{{$debit->receipt_details}}</td>
                        <td align="right">{{number_format($debit->debit,2)}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                    
                    @foreach($debits_summary_less as $debits_less)
                    <?php $totaldebit = $totaldebit + $debits_less->debit; ?>
                    <tr>
                        <td>{{$debits_less->receipt_details}}</td>
                        <td align="right">({{number_format($debits_less->debit,2)}})</td>
                        <td></td>
                    </tr>
                    @endforeach
                    @foreach($credits_summary as $credit)
                    <?php $totalcredit = $totalcredit + $credit->credit; ?>
                    <tr>
                        <td>{{$credit->receipt_details}}</td>
                        <td></td>
                        <td align="right">{{number_format($credit->credit,2)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>Total</td>
                        <td align="right">{{number_format($totaldebit,2)}}</td>
                        <td align="right">{{number_format($totalcredit,2)}}</td>
                    </tr>
                </table>    
                @endif
            </div>
        </div>
    </div> 
    <div class="col-md-3 pull-left">
        <a href="{{url('/cashier',array('print_cancelled_or',$date_from,$date_to,$posted_by))}}" class="btn btn-primary" target="_blank">Print</a>
    </div>    
</div>

@endsection
@section('footerscript')
<style>
    table  .decimal{
        text-align: right;
        padding-right: 10px;
    }
</style>    
<!-- daterange picker -->
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-daterangepicker','daterangepicker.css'))}}">
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','moment','min','moment.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','bootstrap-daterangepicker','daterangepicker.js'))}}"></script>
<script>
$(document).ready(function () {
    $('#example1').DataTable();
    $('#daterange').daterangepicker(
            {
                ranges: {
                    'Select': [moment(), moment()],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(),
                endDate: moment(),

            },
            function (start, end) {
                $('#daterange span #range').html(start.format('YYYY-MM-DD') + ' , ' + end.format('YYYY-MM-DD'));
                x = $('#range').html();
                splitdate = x.split(',');
                todate = splitdate[1];
                fromdate = splitdate[0];
                to = todate.trim()
                from = fromdate.trim()
                $('#date_to').val(to);
                $('#date_from').val(from);
            });
    $("#view-button").on('click', function (e) {
        document.location = "{{url('/cashier',array('cancelled_or'))}}" + "/" + $("#date_from").val() + "/" + $("#date_to").val() + "/" + $("#posted_by").val();
    });

});

</script>    
@endsection
