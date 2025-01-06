<form class="form-horizontal" id="completeForm" method="post" action>

    <div class="mb-3">
        <label>Notes</label>
        <textarea name="note" class="form-control input-lg"></textarea>
        <input type="hidden" name="id" value="{{$req}}">
    </div>

    <div class="modal-footer">
        {{-- <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button> --}}
        <button type="submit" class="btn btn-success waves-effect waves-light">Complete Complaint</button>
    </div>


</form>