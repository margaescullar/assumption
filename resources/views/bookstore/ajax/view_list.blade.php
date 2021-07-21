<?php

function get_name($idno) {
    $names = \App\User::where('idno', $idno)->first();
    return $names->lastname . ", " . $names->firstname . " " . $names->middlename;
}

$i = 1;
$y = 1;

function view_order($idno) {
    $orders = \App\Ledger::where('idno', $idno)->where('category_switch', env("OPTIONAL_FEE"))->orderBy('category', 'asc')->get();
    return $orders;
}
function view_additional_orders($idno) {
    $additional_orders = \App\Ledger::where('idno', $idno)
                    ->where(function ($query) {
                        $query->where("category", "Books")
                        ->orWhere("category", "Materials")
                        ->orWhere("category", "Other Materials")
                        ->orWhere("category", "PE Uniforms/others");
                    })->where('category_switch', env("OTHER_MISC"))->get();
    return $additional_orders;
}
?>

<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif
@if($section=="All")
<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Section</th><th>Books Ordered/Materials Ordered</th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>{{$name->section}}</td>
        <td>
            @if(count(view_order($name->idno))>0)
            @foreach (view_order($name->idno) as $order)
            <?php $balance = $order->amount - ($order->payment+$order->debit_memo+$order->discount); ?>
            @if($balance == 0) [PAID] @endif *{{$order->subsidiary}}<br>
            @endforeach
            @endif
            
            @if(count(view_additional_orders($name->idno))>0)
            @foreach (view_additional_orders($name->idno) as $order)
            <?php $balance = $order->amount - ($order->payment+$order->debit_memo+$order->discount); ?>
            @if($balance == 0) [PAID] @endif *{{$order->subsidiary}}<br>
            @endforeach
            @endif
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif

</table> 
@else

<div>Section : {{$section}}</div>

<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Books/Materials Ordered</th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td>
        <td>
            @if(count(view_order($name->idno))>0)
            @foreach (view_order($name->idno) as $order)
            <?php $balance = $order->amount - ($order->payment+$order->debit_memo+$order->discount); ?>
            @if($balance == 0) [PAID] @endif *{{$order->subsidiary}}<br>
            @endforeach
            @endif
            
            @if(count(view_additional_orders($name->idno))>0)
            @foreach (view_additional_orders($name->idno) as $order)
            <?php $balance = $order->amount - ($order->payment+$order->debit_memo+$order->discount); ?>
            @if($balance == 0) [PAID] @endif *{{$order->subsidiary}}<br>
            @endforeach
            @endif
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif

</table>    
    <a href="javascript:void(0)" onclick = "print_list()" class="form btn btn-primary"> Print List</a>
@endif