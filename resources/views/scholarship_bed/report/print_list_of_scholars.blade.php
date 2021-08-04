<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
    .table, .th, .td {
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }
    .table2 {
        border: 1px solid black transparent;
        border-collapse: collapse;
        font: 9pt;
    }
    .underline {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
    }
    .top-line {
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        text-align: center;
    }
    .no-border {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

</style>
<div>    
    <div style='float: left; margin-left: 290px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>{{\App\CtrDiscount::where('discount_code', $scholarship)->first()->discount_description}}</b><br><b>{{$school_year}} - {{$school_year + 1}}</b></div>
</div>
<div>
    <?php $control = 1; ?>
    @if(count($scholars)>0)
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <tr>
            <th align="center">#</th>
            <th align="center">ID Number</th>
            <th align="center">Name</th>
            <th align="center">Level</th>
            <th align="center">Tuition %</th>
            <th align="center">Misc %</th>
            <th align="center">Others %</th>
            <th align="center">Depository %</th>
            <th align="center">Non Discounted %</th>
            <th align="center">SRF %</th>
            <th align="center">Remarks</th>
        </tr>
        @foreach($scholars as $scholar)
        <tr>
            <td align="center">{{$control++}}.</td>
            <td align="center">{{$scholar->idno}}</td>
            <td>{{$scholar->getFullNameAttribute()}}</td>
            <td align="center">{{$scholar->level}}</td>
            <td align="center">{{$scholar->tuition_fee}}</td>
            <td align="center">{{$scholar->misc_fee}}</td>
            <td align="center">{{$scholar->other_fee}}</td>
            <td align="center">{{$scholar->depository_fee}}</td>
            <td align="center">{{$scholar->non_discounted}}</td>
            <td align="center">{{$scholar->srf}}</td>
            <td align="center">{{$scholar->remarks}}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="11">Total: {{$control-1}}</td>
        </tr>
    </table>
    @endif
    <br>
    <table width="100%">
        <thead>
            <tr>
                <td>Prepared By:<br><br><br></td>
                <td>Noted By:<br><br><br></td>
            </tr>
        </thead>
        <tbody>
            <tr>               
                <td>___________________________</td>
                <td>___________________________</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table> 
</div>