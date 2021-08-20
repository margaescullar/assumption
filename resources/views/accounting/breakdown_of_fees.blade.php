<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
    }else if (Auth::user()->accesslevel==env("CASHIER")){
        $layout = "layouts.appcashier";    
    }
?>
@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        <b style="color: red;">{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Breakdown of Fees</li>
    </ol>
</section>
@endsection
@section('maincontent')

@foreach ($ledger_sy as $sy)
<div class="col-md-12">    
    <div class="accordion">
        <div class="accordion-section">
            <a class="accordion-section-title active" href="#accordion-{{$sy->school_year}}">{{$sy->school_year}}</a>

            <div id="accordion-{{$sy->school_year}}" class="accordion-section-content open">
                <?php $ledger_period = \App\Ledger::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->where('period',"!=",'Yearly')->get(['period']); ?>
                @if (count($ledger_period)>0)
                    @foreach ($ledger_period as $pr)
                    <?php
                    $totalamount = 0;
                    $totaldiscount = 0;
                    $totaldm = 0;
                    $totalpayment = 0;
                    $totalbalance = 0;
                    ?>
                    
                    {{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}
                    <?php $ledgers = \App\Ledger::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Debit Memo</th>
                            <th>Payment</th>
                            <th>Balance</th>
                        </tr>
                                @foreach ($ledgers as $ledger)
                                <?php $balance=$ledger->amount-$ledger->discount-$ledger->debit_memo-$ledger->payment; ?>
                        <tr>
                            <td>{{$ledger->subsidiary}}</td>
                            <td>{{number_format($ledger->amount,2)}}<?php $totalamount = $totalamount + $ledger->amount; ?></td>
                            <td>{{number_format($ledger->discount,2)}}<?php $totaldiscount = $totaldiscount + $ledger->discount; ?></td>
                            <td>{{number_format($ledger->debit_memo,2)}}<?php $totaldm = $totaldm + $ledger->debit_memo; ?></td>
                            <td>{{number_format($ledger->payment,2)}}<?php $totalpayment = $totalpayment + $ledger->payment; ?></td>
                            <td>{{number_format($balance,2)}}<?php $totalbalance = $totalbalance + $balance; ?></td>
                        </tr>
                                @endforeach
                        <tr>
                            <th>Total</th>
                            <th>{{$totalamount}}</th>
                            <th>{{$totaldiscount}}</th>
                            <th>{{$totaldm}}</th>
                            <th>{{$totalpayment}}</th>
                            <th>{{$totalbalance}}</th>
                        </tr>
                    </table>
                    @endforeach
                @else
                    <?php
                    $totalamount = 0;
                    $totaldiscount = 0;
                    $totaldm = 0;
                    $totalpayment = 0;
                    $totalbalance = 0;
                    ?>
                    {{$sy->school_year}}
                    <?php $ledgers = \App\Ledger::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->get(); ?>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Debit Memo</th>
                            <th>Payment</th>
                            <th>Balance</th>
                        </tr>
                                @foreach ($ledgers as $ledger)
                                
                                <?php $balance=$ledger->amount-$ledger->discount-$ledger->debit_memo-$ledger->payment; ?>
                        <tr>
                            <td>{{$ledger->subsidiary}}</td>
                            <td>{{number_format($ledger->amount,2)}}<?php $totalamount = $totalamount + $ledger->amount; ?></td>
                            <td>{{number_format($ledger->discount,2)}}<?php $totaldiscount = $totaldiscount + $ledger->discount; ?></td>
                            <td>{{number_format($ledger->debit_memo,2)}}<?php $totaldm = $totaldm + $ledger->debit_memo; ?></td>
                            <td>{{number_format($ledger->payment,2)}}<?php $totalpayment = $totalpayment + $ledger->payment; ?></td>
                            <td>{{number_format($balance,2)}}<?php $totalbalance = $totalbalance + $balance; ?></td>
                        </tr>
                                @endforeach
                        <tr>
                            <th>Total</th>
                            <th>{{$totalamount}}</th>
                            <th>{{$totaldiscount}}</th>
                            <th>{{$totaldm}}</th>
                            <th>{{$totalpayment}}</th>
                            <th>{{$totalbalance}}</th>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

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
    
    $('#accordion-1').slideDown(300).addClass('open');
    $('#accordion-2').slideDown(300).addClass('open'); 
    $('#accordion-4').slideDown(300).addClass('open'); 
    
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
@endsection
