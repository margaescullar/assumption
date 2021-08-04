
<?php $control = 1; ?>
@if(count($scholars)>0)
<table class="table table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th>Level</th>
            <th>Tuition %</th>
            <th>Misc %</th>
            <th>Others %</th>
            <th>Depository %</th>
            <th>Non Discounted %</th>
            <th>SRF %</th>
            <th>Remarks</th>
        <tr>
    </thead>
    <tbody>
        @foreach($scholars as $scholar)
        <tr>
            <td>{{$control++}}.</td>
            <td>{{$scholar->idno}}</td>
            <td>{{$scholar->getFullNameAttribute()}}</td>
            <td>{{$scholar->level}}</td>
            <td>{{$scholar->tuition_fee}}</td>
            <td>{{$scholar->misc_fee}}</td>
            <td>{{$scholar->other_fee}}</td>
            <td>{{$scholar->depository_fee}}</td>
            <td>{{$scholar->non_discounted}}</td>
            <td>{{$scholar->srf}}</td>
            <td>{{$scholar->remarks}}</td>
        </tr>
        @endforeach
    <tbody>
</table>
<a target='_blank' id='print_enroll' href='{{url('accounting', array('report', 'print_list_of_bed_scholars', $scholarship,$school_year))}}'><button class="btn btn-success col-sm-12">PRINT LIST OF SCHOLARS</button></a>
@endif
@section('footerscript')
@endsection