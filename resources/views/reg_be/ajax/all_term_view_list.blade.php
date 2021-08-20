<?php
$ctr = 1;
function getAdviser($school_year,$period,$level,$strand,$section){
    if($level != "Grade 11" || $level != "Grade 12"){
        $adviserData = \App\AcademicRole::where('school_year',$school_year)->where('level',$level)->where('section',$section)->where('role','advisory')->first();
    }else{
        $adviserData = \App\AcademicRole::where('period',$period)->where('strand',$strand)->where('school_year',$school_year)->where('level',$level)->where('section',$section)->where('role','advisory')->first();
    }
    if($adviserData){
        return $adviserData->getFullNameAttribute();
    }else{
        return "No Class Adviser Assigned";
    }
}
?>
<a href="javascript:void(0)" onclick = "export_all_term_summary('default')" class="form btn btn-success pull-right"> Export All Term Summary</a>
<a href="javascript:void(0)" onclick = "export_all_term_summary('name_only')" class="form btn btn-warning pull-right"> Export (Names Only)</a>
<table class="table table-striped table-condensed table-bordered" style='font-size: 10pt'>
    <tr>
        <td style='font-weight: bold;' colspan='2'>Class Adviser</td><td>{{getAdviser($school_year,$period,$level,$strand,$section)}}</td>
    <tr>
        <td style='font-weight: bold;' colspan='2'>Grade Level</td><td>{{$level}}</td>
    </tr>
    <tr>
        <td style='font-weight: bold;' colspan='2'>Section</td><td>{{$section}}</td>
    </tr>
    <tr>
        <td style='font-weight: bold;' colspan='2'>School Year</td><td>{{$school_year}} - {{$school_year+1}}</td>
    </tr>
    <tr>
        <td style='font-weight: bold;' colspan='2'>Quarter</td><td>All Term</td>
    </tr>
    @if($type == "default")
    <tr>
        <th>#</th>
        <th>ID Number</th>
        <th>Name</th>
        <th>Term</th>
        @foreach($subject_heads as $subject_head)
        <th>{{$subject_head->group_code}}</th>
        @endforeach
        <!--<th style='background-color: whitesmoke'>Quarter Ave</th>-->
    </tr>
    @foreach($lists as $list)
    <tr>
        <td>{{$ctr++}}</td>
        <td>{{$list->idno}}</td>
        <td>{{strtoupper($list->lastname)}}, {{strtoupper($list->firstname)}} {{strtoupper($list->middlename)}}</td>
        <?php $terms = ['1stQtr','2ndQtr','3rdQtr','4thQtr']; ?>
        @foreach($terms as $key => $quarter)
        @if(!$loop->first)
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        @endif
            <td>{{$quarter}}</td>
        @foreach($subject_heads as $subject_head)
        <td>
            @if($quarter == "1stQtr")
                <!--1st Quarter-->
                @foreach ($list->grades->where('group_code',$subject_head->group_code) as $grade)
                    @if($school_year == 2019)
                        {{$grade->final_remarks}} @if($grade->first_grading != null)({{round($grade->first_grading,2)}})@endif
                            @if($grade->first_grading_letter != null)
                                {{$grade->first_grading_letter}}
                            @endif
                    @else
                        @if($grade->is_alpha > 0)
                        @if($is_ee == 2 && $grade->first_grading_letter == "EE")
                        {{$grade->first_grading_letter}}
                        @elseif($is_ee == 3 && $grade->first_grading_letter == "NI")
                        <strong style="color: red">{{$grade->first_grading_letter}}</strong>
                        @elseif($is_ee <= 1)
                        @if($grade->first_grading_letter == "NI")
                        <strong style="color:red">{{$grade->first_grading_letter}}</strong>
                        @else
                        {{$grade->first_grading_letter}}
                        @endif
                        @endif
                        @else
                        {{$grade->first_remarks}} @if($grade->first_grading>0)({{round($grade->first_grading)}})@endif
                        @endif
                    @endif
                @endforeach
            @elseif($quarter == "2ndQtr")
                <!--2nd Quarter-->
                @foreach ($list->grades->where('group_code',$subject_head->group_code) as $grade)
                    @if($school_year == 2019)
                        {{$grade->final_remarks}} @if($grade->second_grading != null)({{round($grade->second_grading,2)}})@endif
                            @if($grade->second_grading_letter != null)
                                {{$grade->second_grading_letter}}
                            @endif
                    @else
                       @if($grade->is_alpha > 0)
                        @if($is_ee == 2 && $grade->second_grading_letter == "EE")
                        {{$grade->second_grading_letter}}
                        @elseif($is_ee == 3 && $grade->second_grading_letter == "NI")
                        <strong style="color: red">{{$grade->second_grading_letter}}</strong>
                        @elseif($is_ee <= 1)
                        @if($grade->second_grading_letter == "NI")
                        <strong style="color:red">{{$grade->second_grading_letter}}</strong>
                        @else
                        {{$grade->second_grading_letter}}
                        @endif
                        @endif
                        @else
                        {{$grade->second_remarks}} @if($grade->second_grading>0)({{round($grade->second_grading)}})@endif
                        @endif
                    @endif
                @endforeach
            @elseif($quarter == "3rdQtr")
                <!--3rd Quarter-->
                @foreach ($list->grades->where('group_code',$subject_head->group_code) as $grade)
                    @if($school_year == 2019)
                        {{$grade->final_remarks}} @if($grade->third_grading != null)({{round($grade->third_grading,2)}})@endif
                            @if($grade->third_grading_letter != null)
                                {{$grade->third_grading_letter}}
                            @endif
                    @else
                       @if($grade->is_alpha > 0)
                        @if($is_ee == 2 && $grade->third_grading_letter == "EE")
                        {{$grade->third_grading_letter}}
                        @elseif($is_ee == 3 && $grade->third_grading_letter == "NI")
                        <strong style="color: red">{{$grade->third_grading_letter}}</strong>
                        @elseif($is_ee <= 1)
                        @if($grade->third_grading_letter == "NI")
                        <strong style="color:red">{{$grade->third_grading_letter}}</strong>
                        @else
                        {{$grade->third_grading_letter}}
                        @endif
                        @endif
                        @else
                        {{$grade->third_remarks}} @if($grade->third_grading>0)({{round($grade->third_grading)}})@endif
                        @endif
                    @endif
                @endforeach
            @elseif($quarter == "4thQtr")
                <!--4th Quarter-->
                @foreach ($list->grades->where('group_code',$subject_head->group_code) as $grade)
                    @if($school_year == 2019)
                        {{$grade->final_remarks}} @if($grade->fourth_grading != null)({{round($grade->fourth_grading,2)}})@endif
                            @if($grade->fourth_grading_letter != null)
                                {{$grade->fourth_grading_letter}}
                            @endif
                    @else
                       @if($grade->is_alpha > 0)
                        @if($is_ee == 2 && $grade->fourth_grading_letter == "EE")
                        {{$grade->fourth_grading_letter}}
                        @elseif($is_ee == 3 && $grade->fourth_grading_letter == "NI")
                        <strong style="color: red">{{$grade->fourth_grading_letter}}</strong>
                        @elseif($is_ee <= 1)
                        @if($grade->fourth_grading_letter == "NI")
                        <strong style="color:red">{{$grade->fourth_grading_letter}}</strong>
                        @else
                        {{$grade->fourth_grading_letter}}
                        @endif
                        @endif
                        @else
                        {{$grade->fourth_remarks}} @if($grade->fourth_grading>0)({{round($grade->fourth_grading)}})@endif
                        @endif
                    @endif
                @endforeach
            @endif
        </td>
        @endforeach
        @endforeach
        @endforeach
        
        @else
        <tr>
        <th>#</th>
        <th>ID Number</th>
        <th>Name</th>
        <th>Section</th>
        </tr>
        
    @foreach($lists as $list)
    <tr>
        <td>{{$ctr++}}</td>
        <td>{{$list->idno}}</td>
        <td>{{strtoupper($list->lastname)}}, {{strtoupper($list->firstname)}} {{strtoupper($list->middlename)}}</td>
        <td>{{$list->section}}</td>
    </tr>
        @endforeach
        
        @endif
    </tr>
</table>