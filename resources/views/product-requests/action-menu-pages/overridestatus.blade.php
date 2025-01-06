<div class="row">
    <!-- begin panel -->
    <div class="panel panel-inverse">
        <div class="panel-heading mb-4">
            <h4 class="panel-title">Override Document <strong>{{ $document['policy_no'] }}</strong></h4>
        </div>
        <div class="panel-body">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form class="form-horizontal" id="overrideForm" method="post" action="">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="tbl_application_status_id" class="form-label">Application Status</label>
                            <div class="mb-3">
                                <select name="tbl_application_status_id_id" id="tbl_application_status_id" class="form-select" required>
                                    <option value="">Select...</option>
                                    @foreach ($status as $main)
                                    <option value="{{ $main['id'] }}" {{ $document['tbl_application_status_id'] == $main['id'] ? 'selected' : '' }}>
                                        {{ $main['status_name'] }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="application_status_id" name="tbl_application_status_id" value="{{ $document['tbl_application_status_id'] }}">
                            </div>
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="btnOverride" value="{{ $id }}">
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-lg-12">
                                <table id="datatable-default" class="table table-striped no-wrap table-bordered responsive" style="font-size: 12px;color: #000;">
                                    <thead>
                                        <tr style="background-color: #69d;color: #fff;font-size: 12px;">
                                            <th class="col-md-2">Workflow Required</th>
                                            <th class="col-md-2">Workflow Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doc_workflow as $dwfl)
                                        <tr>
                                            <input type="hidden" name="doc_workflow[]" value="{{ $dwfl['id'] }}">
                                            <td>
                                                @foreach ($document_workflow_type as $dwt)
                                                @if ($dwfl['tbl_document_workflow_type_id'] == $dwt['id'])
                                                <input type="text" class="form-control" value="{{ $dwt['name'] }}" disabled>
                                                <input type="hidden" class="form-control" name="tbl_document_workflow_type_id[]" value="{{ $dwt['id'] }}">
                                                @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <select data-dwfl="{{ $dwfl['id'] }}" class="form-select tbl_workflow_status_id" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($workflow_status as $ws)
                                                    <option value="{{ $ws['id'] }}" {{ $dwfl['tbl_workflow_status_id'] == $ws['id'] ? 'selected' : '' }}>
                                                        {{ $ws['status_name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" id="tblwsid{{ $dwfl['id'] }}" name="tbl_workflow_status_id[]" value="{{ $dwfl['tbl_workflow_status_id'] }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-lg-12" style="text-align: center;">
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1 btnOverride">
                                    Override Status
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel -->
</div>

<script>
    $(document).ready(function () {
        $(document).on('change', 'select#tbl_application_status_id', function () {
            console.log('here');
            var application_status_id = $(this).val();
            console.log(application_status_id);
            $('#application_status_id').val(application_status_id);
        });
    
        $(document).on('change', 'select.tbl_workflow_status_id', function () {
            // console.log($(this).val());
            // console.log($(this).data('dwfl'));
    
            let vl = $(this).val();
            let dwfl = $(this).data('dwfl');
            let id = '#tblwsid'+dwfl;
            // console.log(id);
            // console.log($(id).val());
            // console.log(vl);
            $(id).val(vl); 
        });
    
    });
</script>
