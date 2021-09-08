
<div class="box">
    <div class="box-body">
        <form method="post" action="{{url('update_form_details')}}" class="form">
            {{csrf_field()}}
            <input type="hidden" name="id" value="{{$form_id}}">
            <div class="form-group">
                <label>Document Group</label>
                <input class="form-control" name="document_group" value="{{$form_details->document_group}}">
            </div>
            <div class="form-group">
                <label>Document Name</label>
                <input class="form-control" name="document_name" value="{{$form_details->document_name}}">
            </div>
            <div class="form-group">
                <label>Price</label>
                <input class="form-control" name="cost" value="{{$form_details->cost}}">
            </div>
            <div class="form-group">
                <label>Requirements</label>
                <input class="form-control" name="requirements" value="{{$form_details->requirements}}">
            </div>
            <div class="form-group">
                <input type="submit" name="button" value="Delete" class="btn btn-danger">
                <input type="submit" name="button" value="Submit" class="pull-right btn btn-primary">
            </div>
        </form>
    </div>
</div>