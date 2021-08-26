
<table class='table'>
    <tr>
        <td align='center'>SY</td>
        <!--<td align='center'>Applicants</td>-->
        <td align='center'>Pre-Registered</td>
        <td align='center'>For Approval</td>
        <td align='center'>Regret</td>
        <td align='center'>Approved</td>
    </tr>
    @foreach($stats->groupBy('admission_sy') as $sy=>$stat)
    <tr>
        <td colspan='6' align='center'>{{$sy}}</td>
    </tr>
    @foreach($stat->groupBy('level') as $level=>$stat2)
    <?php
    $total_applicants = $stat2->count('idno');
    $non_paid = $stat->where('is_complete', 0)->count('is_complete');
    $paid = $stat2->where('is_complete', 1)->count('is_complete');
    $waived = $stat2->where('is_complete', 2)->count('is_complete');
    $for_approval = count(\App\Status::where('statuses.status', env('FOR_APPROVAL'))->where('statuses.academic_type', "!=", "College")->where('level', $level)->where('users.admission_sy', $sy)->join('users', 'users.idno', '=', 'statuses.idno')->get());
    $approved = count(\App\Status::where('statuses.status', "<=", env('ENROLLED'))->where('statuses.academic_type', "!=", "College")->where('level', $level)->where('users.admission_sy', $sy)->join('users', 'users.idno', '=', 'statuses.idno')->get());
    $regret_final = count(\App\Status::where('statuses.status', env('REGRET_FINAL'))->where('statuses.academic_type', "!=", "College")->where('level', $level)->where('users.admission_sy', $sy)->join('users', 'users.idno', '=', 'statuses.idno')->get());
    $regret_retreive = count(\App\Status::where('statuses.status', env('REGRET_RETREIVE'))->where('statuses.academic_type', "!=", "College")->where('level', $level)->where('users.admission_sy', $sy)->join('users', 'users.idno', '=', 'statuses.idno')->get());
    ?>
    <tr>
        <td align='center'>{{$level}}</td>
        <td align='center'>{{$total_applicants}}</td>
        <!--<td align='center'>{{$non_paid+$paid+$waived}}</td>-->
        <td align='center'>{{$for_approval}}</td>
        <td align='center'>{{$regret_final}}</td>
        <td align='center'><strong>{{$approved}}</strong></td>
    <tr>
        @endforeach
        @endforeach
</table>
