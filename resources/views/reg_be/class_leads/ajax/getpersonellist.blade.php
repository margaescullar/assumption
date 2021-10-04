<table class="table table-striped"><tr><th>User ID</th><th>Name</th><th>Email</th><th>Assigned Levels</th><th></th><tr>
    @if($class_leads != null)
        @foreach($class_leads as $academic)
        <?php $get_levels = \App\ClassLeadLevel::where('idno', $academic->idno)->get(['level']); ?>
        <tr>
            <td>{{$academic->idno}}</td>
            <td>{{$academic->FullName}}</td>
            <td>{{$academic->email}}</td>
            <td>
                @if(count($get_levels)> 0)
                @foreach($get_levels as $get_level)
                    @if($loop->last)
                    {{$get_level->level}}
                    @else
                    {{$get_level->level}}, 
                    @endif
                @endforeach
                @else
                No Assigned Level
                @endif
            </td>
            <td><a href="javascript:void()" class="assign" data-toggle="modal" data-target="#modal-default" reference="{{$academic->idno}}">Assign</a></td>
            </tr>
        @endforeach
       
    @endif
</table>