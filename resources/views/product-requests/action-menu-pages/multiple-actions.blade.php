<form method="post" action="" enctype="multipart/form-data" id="multiple-actionsForm" class="form-horizontal">
    @csrf
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group">
                    <label class="form-label">Comment</label>
                    <div class="col-md-12 col-sm-12">
                        <textarea name="comments" class="form-control" required></textarea>
                    </div>
                </div>
                @if($rec == 'REVIEW' || $rec == 'DECLINE')
                @endif
                @if($rec == 'REVIEW')
                <div class="form-group">
                    <label class="form-label">Sent to Department </label>
                    <div class="col-md-12 col-sm-12">
                        <select name="tbl_departments_id" class="form-control">
                            @php
                                $reqs = App\Models\DocumentWorkflow::with('tbl_document_setup_details')->where('tbl_document_applications_id', $record->id)
                                ->where('tbl_document_workflow_type_id', 1)
                                ->where('deleted', 0)
                                ->get();
                            @endphp
                            <option value=""></option>
                            <option value="0">Requester - {{ $record->tbl_users->full_name }}</option>
                            @if ($reqs)
                                @foreach($reqs as $req)
                                <?php $dept = App\Models\Department::find($req->tbl_document_setup_details?->reference) ?>
                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @if($record->tbl_documents_products_id == 2)
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="bypass_check">
                                &nbsp;ByPass Security &amp; Forensics
                            </label>
                        </div>
                    </div>
                </div>
                @endif
                @endif
                <div class="panel-footer mt-3">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <input type="hidden" name="action" value="{{ $rec }}">
                    <button type="submit" name="bntValidate" class="btn btn-sm btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
