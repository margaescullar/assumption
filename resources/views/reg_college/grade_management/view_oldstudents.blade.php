<?php
$close = \App\CtrCollegeGrading::where('academic_type', "College")->first();
?>
<?php $number = 1; ?>
<?php
$students = \App\CollegeGrades2018::where('college_grades2018s.course_code', "$course_code")->where('school_year', $school_year)->where('period',"$period")->join('users', 'users.idno', '=', 'college_grades2018s.idno')->select('college_grades2018s.id','users.idno', 'users.firstname', 'users.lastname', 'college_grades2018s.finals')->orderBy('users.lastname')->get();
?>

<form class="form form-horizontal" method="post" action="{{url('college_instructor', array('grades','save_submit'))}}">
    {{csrf_field()}}
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="8%">ID number</th>
                            <th>Name</th>
                            <th width="5%">Finals</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$number}}<?php $number = $number + 1; ?></td>
                            <td>{{$student->idno}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}}</td>
                            <td>
                                <select class="grade" name="finals[{{$student->id}}]" id="finals" onchange="change_finals(this.value, '{{$student->id}}', '{{$student->idno}}', 'old')">
                                    <option></option>
                                    <option @if ($student->finals == "PASSED") selected='' @endif>PASSED</option>
                                    <option @if ($student->finals == 1.00) selected='' @endif>1.00</option>
                                    <option @if ($student->finals == 1.20) selected='' @endif>1.20</option>
                                    <option @if ($student->finals == 1.50) selected='' @endif>1.50</option>
                                    <option @if ($student->finals == 1.70) selected='' @endif>1.70</option>
                                    <option @if ($student->finals == 2.00) selected='' @endif>2.00</option>
                                    <option @if ($student->finals == 2.20) selected='' @endif>2.20</option>
                                    <option @if ($student->finals == 2.50) selected='' @endif>2.50</option>
                                    <option @if ($student->finals == 2.70) selected='' @endif>2.70</option>
                                    <option @if ($student->finals == 3.00) selected='' @endif>3.00</option>
                                    <option @if ($student->finals == 3.50) selected='' @endif>3.50</option>
                                    <option @if ($student->finals == 4.00) selected='' @endif>4.00</option>
                                    <option @if ($student->finals == "FAILED") selected='' @endif>FAILED</option>
                                    <option @if ($student->finals == "FA") selected='' @endif>FA</option>
                                    <option @if ($student->finals == "INC") selected='' @endif>INC</option>
                                    <option @if ($student->finals == "NA") selected='' @endif>NA</option>
                                    <option @if ($student->finals == "NG") selected='' @endif>NG</option>
                                    <option @if ($student->finals == "UD") selected='' @endif>UD</option>
                                    <option @if ($student->finals == "W") selected='' @endif>W</option>
                                    <option @if ($student->finals == "AUDIT") selected='' @endif>AUDIT</option>
                                </select>
                                <!--<input class='grade' type="text" name="finals[{{$student->id}}]" id="finals" value="{{$student->finals}}" size=1 >-->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>