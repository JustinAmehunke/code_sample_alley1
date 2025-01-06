
<!-- Start container-fluid -->
<div class="cow">

    <div class="col-12">
        {{-- message_box(true) --}}
    <h4 class="header-title mb-3">Document Request Details for #<?= $record['request_no'] ?> </h4>
    <div class="pull-right">
        <button type="button" class="btn btn-success btn-rounded btn-sm"><strong><?= $record->tbl_application_status['status_name'] ?></strong></button>
    </div>

    </div>
    <br><br>
    <div class="row ">
        <div class="col-md-6 mb-3">
            <h4 class="card-title">Policy Number</h4>
            <h6 class="card-subtitle font-14 text-muted">{{$record['policy_no']}}</h6>
        </div>
        <div class="col-md-6 mb-3">
            <h4 class="card-title">Product</h4>
            <h6 class="card-subtitle font-14 text-muted">{{$record->tbl_documents_products['product_name']}}</h6>
        </div>
   </div>
   <div class="row ">
        <div class="col-md-6 mb-3">
            <h4 class="card-title">Branch</h4>
            <h6 class="card-subtitle font-14 text-muted">{{$record->tbl_branch ? $record->tbl_branch['branch_name'] : '--'}}</h6>
        </div>
        <div class="col-md-6 mb-3">
            <h4 class="card-title">Mobile Number</h4>
            <h6 class="card-subtitle font-14 text-muted">{{$record['mobile_no'] }}</h6>
        </div>
    </div>
    <div class="row ">
        <div class="col-md-6 mb-3">
            <h4 class="card-title">Source</h4>
            <h6 class="card-subtitle font-14 text-muted">{{$record['source']}}</h6>
        </div>
    </div>

    <br>

    @if (count($documents) > 0)
    <div class="row">
        <div class="col-8">
            <h4 class="header-title mb-3">Documents Attached to Request</h4>
            @if (count($documents) > 0)
            <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Document Type</th>
                        <th>Document Name</th>
                        <th>Processed By</th>
                        <th>Processed On</th>
                        <th>Document</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if (count($documents) > 0)
                    @foreach ($documents as $key => $document)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $document->tbl_document_type->document_name }}</td>
                            <td>{{ $document->document_name }}</td>
                            <td>{{ $document->tbl_users->firstname }}</td>
                            <td>{{ $document->createdon }}</td>
                            <td>
                                @if ($document->tbl_document_type_id == 3 && $document->system_generated == 1)
                                <a href="#" id="previewID" data-id="{{ $document->id }}" data-token="{{ $record->token }}"><img src="../doc_logos/{{ $document->tbl_document_images->images }}"></a>
                                @else
                                <a href="#" id="previewloaderNP" data-id="{{ $document->id }}"><img src="../doc_logos/{{ $document->tbl_document_images->images }}"></a>
                                @endif
                            </td>
                            <td><a href="{{ 'documents/' . $document->document }}" download><i class="fa fa-download" aria-hidden="true"></i></a></td>
                            {{-- Get from aws path --}}
                        </tr>
                    @endforeach
                @endif
                    
                </tbody>
            </table>
            @else
            <strong>No Document(s) attached to request</strong>
            @endif
        </div>
    </div>
    @endif
    
    <br>
    <div class="row">

    <div class="col-12">
        <h4 class="header-title mb-3">Document Checklist</h4>
        <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Checklist</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($checklists) > 0)
                    @foreach ($checklists as $key => $checklist)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $checklist->tbl_document_type->document_name }}</td>
                            <td>
                                <button type="button" class="btn btn-{{ $checklist->tbl_checklist_status->colour }} btn-sm">{{ $checklist->tbl_checklist_status->status_name }}</button>
                            </td>
                            <td>{{ $checklist->reason }}</td>
                            <td>
                                @if ($checklist->mode == 4)
                                @php
                                    $count = \App\Models\Document::where('tbl_document_applications_id', $record->id)
                                        ->where('tbl_document_type_id', $checklist->tbl_document_type_id)
                                        ->count();
                                    $check = ($count > 0 ? 1 : 0);
                                    // $check = checkProposalForm($record->id, $checklist->tbl_document_type_id);
                                @endphp
                                @if ($check > 0)
                                <a href="#" id="previewForm" data-token="{{ $record->token }}">
                                    <button class="btn btn-warning">AVAILABLE</button>
                                </a>
                                @elseif ($checklist->tbl_document_type_id == 2)
                                <a class="btn btn-success ml-sm" 
                                href="{{route('document-digital-form', ['section' =>'UmVxdWVzdC1Qcm9maWxl', 'token' => $record->token, 'mandate' => 'mandate'])}}"
                                id="approveform" data-toggle="tooltip" data-placement="top" data-original-title="Fill Form">Fill Mandate form</a>
                                @else
                                <a class="btn btn-success ml-sm" 
                                href="{{route('document-digital-form', ['section' =>'UmVxdWVzdC1Qcm9maWxl', 'token' => $record->token])}}"
                                id="approveform" data-toggle="tooltip" data-placement="top" data-original-title="Fill Form">Fill {{ $record->tbl_documents_products->product_name }} form</a>
                                @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
    </div>

    
        
    
    @if (count($workflows) > 0)
        <div class="row">
        <div class="col-12">
            @if (count($workflows) > 0)
                @foreach ($workflows as $workflow)
                    @if ((getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS APPROVERS" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS PROCESSORS") && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                    <h4 class="header-title mb-3">SLAMS</h4>
                    
                    <form id="slams_forms">
                        <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important; ">
                    
                            <thead>
                                <tr>
                                    @if (getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS APPROVERS" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS PROCESSORS" && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                    <th>Basic Premium</th>
                                    <th>Sum Assured</th>
                                    <th>Policy Fee</th>
                                    <th>Client Type</th>
                                    <th>Occ. Class</th>
                                    <th>With Rider</th>
                                    <th>Repped By Off. Mandate</th>
                                    <th>Payment Amt<br>& Date</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS APPROVERS" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS PROCESSORS" && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                    
                                <tr>
                                    <td>
                                        <input required type="number" min="1" class="form-control" placeholder="Premium" id="basic_premium" name="basic_premium">
                                    </td>
                                    <td>
                                        <input required type="number" min="1" class="form-control" placeholder="Sum Assured" id="sum_assured" name="sum_assured">
                                    </td>
                                    <td>
                                        <input required type="number" min="1" class="form-control" placeholder="Policy Fee" id="policyFee" name="policyFee">
                                    </td>
                                    <td>
                                        <select id="client_type" name="client_type" required>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="occup_class" name="occup_class" required>
                                            <option value="005">NA</option>
                                            <option value="001">Class 1</option>
                                            <option value="002">Class 2</option>
                                            <option value="003">Class 3</option>
                                            <option value="004">Class 4</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="with_rider" name="with_rider" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="clientRepresentedByOfficialMandate" name="clientRepresentedByOfficialMandate" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </td>
                                    <td>
                                        @if ($paid_amount > 0)
                                        <input type="number" readonly min="1" class="form-control" placeholder="Amt" id="payment_amt" name="payment_amt" value="{{ $paid_amount }}">
                                        @else
                                        <input type="number" min="1" class="form-control" placeholder="Amt" id="payment_amt" name="payment_amt">
                                        @endif
                                        <br>
                                        @if ($paid_date != "")
                                        <input type="text" readonly class="form-control" id="basic_premium" name="payment_date" value="{{ $paid_date }}">
                                        @else
                                        <input type="date" class="form-control" id="basic_premium" name="payment_date">
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <input type="hidden" class="form-control" id="record_id" name="record_id" value="{{ base64_encode($record['id']) }}">
                            </tbody>
                            <thead>
                                <tr>
                                    @if (getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS APPROVERS" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS PROCESSORS" && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                    <th style="min-width: 100px;">Product Category</th>
                                    <th style="min-width: 100px;">Client Attributes</th>
                                    <th style="min-width: 100px;">Nature Of Product</th>
                                    <th>Source Of Funds</th>
                                    <th>Client Conduct</th>
                                    <th>Delivery Channel</th>
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS APPROVERS" && getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) != "CLAIMS PROCESSORS" && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                <tr>
                                    <td>
                                        <select id="product_category" name="product_category">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="client_attributes" name="client_attributes">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="nature_of_product" name="nature_of_product">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="source_of_funds" name="source_of_funds">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="client_conduct" name="client_conduct">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="delivery_channel" name="delivery_channel">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" id="push_slams_btn" class="btn btn-info btn-sm" value="PUSH" />
                                    </td>
                                </tr>
                                @endif
                                @if ((getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS APPROVERS" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS PROCESSORS") && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                <thead>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="submit" id="push_slams_btn" class="btn btn-info btn-sm" value="PUSH" />
                                        </td>
                                    </tr>
                                </tbody>
                                @endif
                    
                        </table>
                    </form>
                    
                    @elseif ((getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS APPROVERS" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS PROCESSORS") && $record['slams_create_policy_or_claim_status'] == 1)
                    <h4 class="header-title mb-3">SLAMS</h4>
                    <table class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important; ">
                        <thead>
                            <tr>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span type="button" class="btn btn-success btn-sm">POLICY IN SLAMS</span></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    @elseif ((getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "DATA CAPTURER" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS APPROVERS" || getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference) == "CLAIMS PROCESSORS") && $record['slams_create_policy_or_claim_status'] == -1)
                    <h4 class="header-title mb-3">SLAMS</h4>
                    <table class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important; ">
                        <thead>
                            <tr>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span type="button" class="btn btn-danger btn-sm">FAILED IN SLAMS</span></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                @endforeach
            @endif
            
            

            <div class="text-right mr-lg">
            <!-- <a href="#" class="btn btn-default">Submit Invoice</a> -->
            </div>
        </div>
        </div>

        <div class="row">

        <div class="col-12">
            <h4 class="header-title mb-3">Document Workflow Approval Tray</h4>
            <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important; ">

            <thead>
                <tr>
                <th>#</th>
                <th>Workflow Required</th>
                <th>Reference</th>
                <th>Workflow Status</th>
                <th>Processed By</th>
                <th>Processed Date</th>
                <th>Reference(s)</th>
                <th>Evidence</th>
                </tr>
            </thead>
            <tbody id="">
                @php $i = 1; @endphp
                @foreach ($workflows as $workflow)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $workflow->tbl_document_workflow_type['name'] }}</td>
                    <td>{{ getReference($workflow->tbl_document_workflow_type['id'], $workflow->tbl_document_setup_details['reference'], $workflow['reference']) }}</td>
                    <td>
                        @if (in_array($workflow['tbl_workflow_status_id'], [8, 9]))
                            @if ($workflow['review_type'] == 'CUSTOMER')
                                @if ($workflow['reference'] == 0)
                                    @php $showRec_ = $record->tbl_users['full_name']; @endphp
                                @else
                                    @php $showRec_ = $workflow['processed_by']; @endphp
                                @endif
                            @else
                                @php $showRec = \App\Models\Department::find($workflow['reference']); @endphp
                                @php $showRec_ = $showRec['department_name']; @endphp
                            @endif
                            <button type="button" class="btn btn-{{ $workflow->tbl_workflow_status['colour'] }} btn-sm">{{ $workflow->tbl_workflow_status['status_name'] }} >> {{ $showRec_ }}</button>
                        @else
                            <button type="button" class="btn btn-{{ $workflow->tbl_workflow_status['colour'] }} btn-sm">{{ $workflow->tbl_workflow_status['status_name'] }}</button>
                        @endif
                    </td>
                    <td>
                        {{ (in_array($workflow['tbl_workflow_status_id'], [4, 5, 6, 7, 8, 11, 12])) ? $workflow['processed_by'] : '' }}
                    </td>
                    <td>
                        {{ (in_array($workflow['tbl_workflow_status_id'], [4, 5, 6, 7, 8, 11, 12])) ? date('d-M-Y H:i:s', strtotime($workflow['processed_date'])) : '' }}
                    </td>
                    <td>
                        @if (in_array($workflow->tbl_document_workflow_type['id'], [1, 2]))
                            @if ($workflow['completed_yn'] > 0)
                                @php $image_details = getImageDetailsDocuments($record['id'], $workflow['id']); @endphp
                                    @if ($image_details)
                                        <a href="#" id="previewloader3" data-id="{{ $image_details['id'] }}"><img src="../doc_logos/{{ getImageDetailsImgDocument($record['id'], $workflow['id']) }}"></a>
                                    @endif
                                    @if ($workflow['comments'] <> '')
                                        <br>Comments: {{ $workflow['comments'] }}<br>
                                    @endif
                                @endif
                        @elseif ($workflow->tbl_document_workflow_type['id'] == 1)
                            @if ($workflow['tbl_workflow_status_id'] == 8 || $workflow['tbl_workflow_status_id'] == 10 || $workflow['tbl_workflow_status_id'] == 4 || $workflow['tbl_workflow_status_id'] == 11)
                                @php $depts = \App\Models\Department::find($workflow['reference']); @endphp
                                Comments: {{ $workflow['comments'] }}<br>
                                To: {{ ($depts['department_name'] <> '') ? $depts['department_name'] : 'Requester' }}
                            @else
                                {{ $workflow['reference'] }}
                            @endif
                        @endif
                        @if ($workflow['requester_review'] > 0)
                            @php $depts2 =  \App\Models\Department::find($workflow['reference']); @endphp
                            Comments: {{ $workflow['comments'] }}<br>
                            To: {{ ($depts2['department_name'] <> '') ? $depts2['department_name'] : 'Requester' }}
                        @endif
                    </td>
                    <td>
                        @if ($workflow['document_evidence'] <> '')
                            <a href="#" id="previewloader2" data-id="{{ $workflow['document_evidence'] }}"><img src="../documents/{{ $workflow['document_evidence'] }}" width="100"></a>
                        @endif
                    </td>
                </tr>
                @php $i++; @endphp
                @endforeach
            </tbody>
            
            </table>
            <div class="text-right mr-lg">
            <!-- <a href="#" class="btn btn-default">Submit Invoice</a> -->



            </div>
        </div>


        </div>
    @endif
    <br>
    <div class="row">
    <div class="col-12">
        @if (count($comments) > 0)
            <h4 class="header-title mb-3">Comments</h4>
            <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important; ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Processed Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php $z = 1; @endphp
                    @foreach ($comments as $comment)
                        <tr>
                            <td>{{ $z }}</td>
                            <td>{{ $comment->tbl_users->firstname }} {{ $comment->tbl_users->lastname }}</td>
                            <td>{{ $comment['status'] }}</td>
                            <td>{{ $comment['message'] }}</td>
                            <td>{{ date('d-M-Y H:i:s', strtotime($comment['createdon'])) }}</td>
                        </tr>
                        @php $z++; @endphp
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-default">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>No comments available on request</strong>
            </div>
        @endif
    </div>
    </div>

</div>
<!-- end container-fluid -->
