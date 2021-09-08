<div class="modal-dialog" style='width: 1000px; margin: auto' >
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Tag Request Form as Paid</h4>
        </div>
            <form action="{{url('/update_or_form_request')}}" method="post">
        <div class="modal-body">
                {{csrf_field()}}
                <input type="hidden" name="reference_id" value="{{$reference_id}}">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>OR Number</label>
                        <input name="or_number" type="text" class="form form-control" required>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
            <input type="submit" value="Tag as Paid" class="btn btn-success">
        </div>
            </form>
    </div>
</div>