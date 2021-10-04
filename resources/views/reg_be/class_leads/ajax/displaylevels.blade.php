<div class="modal-dialog">
    <div class="modal-content">
        <form action='{{url('update_class_leads_level')}}' method='post'>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Assigned Levels</h4>
        </div>
            <div class="modal-body">
                {{csrf_field()}}
                <input type='hidden' value='{{$idno}}' name='idno'>
                <input type='checkbox' name='pre_kinder'>Pre - Kinder<br>
                <input type='checkbox' name='kinder'>Kinder<br>
                <input type='checkbox' name='grade1'>Grade 1<br>
                <input type='checkbox' name='grade2'>Grade 2<br>
                <input type='checkbox' name='grade3'>Grade 3<br>
                <input type='checkbox' name='grade4'>Grade 4<br>
                <input type='checkbox' name='grade5'>Grade 5<br>
                <input type='checkbox' name='grade6'>Grade 6<br>
                <input type='checkbox' name='grade7'>Grade 7<br>
                <input type='checkbox' name='grade8'>Grade 8<br>
                <input type='checkbox' name='grade9'>Grade 9<br>
                <input type='checkbox' name='grade10'>Grade 10<br>
                <input type='checkbox' name='grade11'>Grade 11<br>
                <input type='checkbox' name='grade12''>Grade 12<br>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Assign</button>
        </div>
    </div>
</div>