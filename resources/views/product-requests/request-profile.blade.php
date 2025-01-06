@extends('layouts.main-master')
@section('content')
{{-- flexdatalist --}}
<link href="{{asset('/assets/libs/flexdatalist/css/jquery.flexdatalist.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<style>
    .flexdatalist-results li span.highlight {
    font-weight: 700;
    text-decoration: underline;
}
.highlight {
    background-color: #CCC;
    color: #FFF;
    padding: 3px 6px;
}
.flexdatalist-results li.active {
    background: #2B82C9;
    color: #fff;
    cursor: pointer;
}
</style>
<style>
    .list-nostyled{
        list-style: none;
    }
    .tab-primary {
    color: #fff;
    background-color: #0f9cf3 !important;
    border-color: #0f9cf3 !important;
    }
    .tab-white{
        color: #fff;
    }

    .card .collapsed .card-header{
        background-color: #f1f5f7 !important;
        border-bottom: 0 solid #f1f5f7 !important;
    }
    .card .collapsed .card-header .tab-white{
        color: #0a1832 !important;
    }
    
element.style {
}
.alert-danger {
    color: #921c32;
    background-color: #fdd5dd;
    border-color: #fbc1cb;
}
.alert-dismissible {
    padding-right: 3.75rem;
}
.alert {
    padding: 0.3rem 1.25rem;
}
.badge-soft-success {
    color: #169e38 !important; 
}
.badge-light {
    /* color: #000; */
    color: #817b7b;
    background-color: #d8dce1;
    /* hide upcoming steps */
    /* color: #817b7b00;
    background-color: #d8dce100; */
}
.mm-1 {
    margin: 0.15rem!important;
}
.black{
    color: #000 !important;
}

.mr-2{
    margin-right: 4px;
}
.form-content{
    border: 1px solid #e8e8e8;
    padding: 20px;
    background-color: #fff;
}
.card-header-b{
    border-bottom: 1px solid #dad3d3;
}
.card-body-grey{
    background-color: #f1f5f7;
}
.bb{
    border-bottom: 1px solid #5b5757;
    margin-bottom: 6px;
    margin-top: 20px;
}
.phone_number_invalid{
    font-size: 12px; 
    color: rgb(243, 47, 83); 
    margin-top: 5px; 
    display: none;
}
.modal-content{
    font-size: 13px;
}
</style>
{{-- <form class="custom-validation" action="{{route('update-company-profile')}}" method="post" enctype="multipart/form-data"> --}}
    @csrf
    @if(session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
    @endif
    @if(session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger parsley-danger">
            <ul> 
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('message'))
        <div class="alert alert-{{ session('message')['type'] }}">
            <h4 class="card-title">{{ session('message')['type'] }}</h4>
            {{ session('message')['message'] }}
        </div>
    @endif
    @php
        $param_id =  request()->query('id');
        $param_section = request()->query('section');

        if (request()->has('id')) {
            $id = base64_decode(request('id'));
            $record = App\Models\DocumentApplication::find($id);
            $requesteds = App\Models\DocumentChecklist::where(['tbl_document_applications_id' => $id, 'deleted' => 0])->get();
        }
    @endphp
    <div class="row">
        @include('product-requests.includes.request-side-menu')
        <div class="col-md-9">
            <div class="card">
                <h5 class="card-header">
                    Request Profile: <span id="policy-number">{{$record->policy_no}}</span> 
                </h5>
                
                <div class="card-body">
                   <div class="row ">
                        <div class="col-md-6 mb-3">
                            <h4 class="card-title">Customer Name</h4>
                            <h6 class="card-subtitle font-14 text-muted">{{$record->customer_name}}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h4 class="card-title">Policy Number <span style="color: red">{{$record->new_app_request ? "STAK V2 Request" : ""}}</span></h4> 
                            <h6 class="card-subtitle font-14 text-muted">{{$record->policy_no}}</h6>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6 mb-3">
                            <h4 class="card-title">Product</h4>
                            <h6 class="card-subtitle font-14 text-muted">{{$record->tbl_documents_products->product_name}}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h4 class="card-title">Branch</h4>
                            <h6 class="card-subtitle font-14 text-muted">{{ $record->tbl_branch ? $record->tbl_branch->branch_name : '-'}}</h6>
                        </div>
                    </div>
                    <div class="row">
                        @if ($record->sms)
                            <div class="col-md-6 mb-3">
                                <h4 class="card-title">Customer Mobile Number</h4>
                                <h6 class="card-subtitle font-14 text-muted">{{$record->sms}}</h6>
                            </div>
                        @endif
                        @if ($record->email)
                            <div class="col-md-6 mb-3">
                                <h4 class="card-title">Customer Email Address</h4>
                                <h6 class="card-subtitle font-14 text-muted">{{$record->email}}</h6>
                            </div>
                        @endif
                       
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h4 class="card-title">Source</h4>
                            <h6 class="card-subtitle font-14 text-muted">{{$record->source}}</h6>
                        </div>

                        @if ($record->flag_request)
                        <div class="col-md-6 mb-3">
                            <h4 class="card-title text-danger"> <i class="mdi mdi-block-helper me-2"></i> Flagged</h4>
                            <h6 class="card-subtitle font-14 text-muted">{{$record->flag_comment}}</h6>
                        </div>
                        @endif
                    </div>

                    <hr>
                    @if (count($documents) > 0)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4 class="header-title mb-3">Documents Attached to Request</h4>
                                <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive"
                                style="font-size: 13px; color: #000; padding: 5px 10px !important">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Type</th>
                                        <th>Document Name</th>
                                        <th>Processed By</th>
                                        <th>Processed On</th>
                                        <th>Document</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $i => $document)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $document->tbl_document_type->document_name }}</td>
                                            <td>{{ $document->document_name }}</td>
                                            <td>{{ $document->tbl_users?->firstname.' '.$document->tbl_users?->lastname }}</td>
                                            <td>{{ $document->createdon }}</td>
                                            <td>
                                                @php
                                                    $url = '';
                                                    $s3FileUrl = Storage::disk('s3')->url('documents/'.$document->document);
                                                    // if($document->tbl_document_type_id == 1){ //proposal
                                                    //     $url =  "storage/proposals/".$document->document;
                                                    // }

                                                    // if($document->tbl_document_type_id == 2){ //mandate
                                                    //     $url =  "storage/mandates/".$document->document;
                                                    // }
                                                @endphp
                                                @if ($document->tbl_document_type_id == 3 && $document->system_generated == 1)
                                                    <a href="{{$s3FileUrl}}" target="_blank" id="previewID" data-id="" data-token="{{ $record->token }}">
                                                        <img src="{{ asset('/assets/images/doc_logos/' . $document->tbl_document_images->images) }}">
                                                    </a>
                                                @else
                                                    <a href="{{$s3FileUrl}}" target="_blank" id="previewloaderNP" data-id="">
                                                        <img src="{{ asset('/assets/images/doc_logos/' . $document->tbl_document_images->images) }}">
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('your-route-name', ['id' => base64_encode($document->id), 'action' => base64_encode('delete'), 'section' => request()->query('section')]) }}" id="deleteca" data-toggle="tooltip" data-placement="top" data-original-title="Delete"> --}}
                                                
                                                <a href="#" id="delete-document" data-id="{{base64_encode($document->id)}}">
                                                    <i class="text-danger fa fa-trash-o" title="Delete"></i>
                                                </a>
                                            </td>
                                            <td>
                                                {{-- <a href="{{ getFullFilePathOnS3Download($document->document) }}" download data-toggle="tooltip" data-placement="top" data-original-title="Download"> --}}
                                                    <a href="{{$s3FileUrl}}" download>
                                                    <i class="fa fa-download" aria-hidden="true" title="Download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                            </div>
                        </div>
                    @endif
                  
                    @if (count($checklists) > 0)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4 class="header-title mb-3">Document Checklist</h4>
                                <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive"
                                style="font-size: 13px; color: #000; padding: 5px 10px !important">
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
                                    @foreach($checklists as $k => $checklist)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $checklist->tbl_document_type->document_name }}</td>
                                            <td>
                                                <button type="button" class="btn btn-{{ $checklist->tbl_checklist_status->colour }} btn-sm">{{ $checklist->tbl_checklist_status->status_name }}</button>
                                            </td>
                                            <td>{{ $checklist->reason }}</td>
                                            <td>
                                                
                                                {{-- @if (in_array($checklist['tbl_document_type_id'], [1, 21]) && ($checklist->form_filled > 0))
                                                    <button class="btn btn-warning previewProposal" data-token="{{ $record->token }}">AVAILABLE</button>
                                                @else
                                                    <a class="btn btn-success ml-sm"
                                                    href="{{route('document-digital-form', ['section' => $param_section, 'token' => $record->token])}}"
                                                    id="approveform" data-toggle="tooltip" data-placement="top"
                                                    title="Fill Form">Fill {{ $record->tbl_documents_products->product_name }} form</a>
                                                @endif
                                                @if (($checklist['tbl_document_type_id'] == 2) && ($checklist->form_filled > 0))
                                                    <button class="btn btn-warning previewMandate" data-token="{{ $record->token }}">AVAILABLE</button>
                                                @else
                                                    <a class="btn btn-success ml-sm"
                                                    href="{{route('document-digital-form', ['section' => $param_section, 'token' => $record->token, 'mandate' => 'mandate'])}}"
                                                    id="approveform" data-toggle="tooltip" data-placement="top"
                                                    title="Fill Mandate form">Fill Mandate form</a>
                                                @endif --}}
                                                @if (in_array($checklist['tbl_document_type_id'], [1, 21]) && ($checklist->form_filled > 0))
                                                    <button class="btn btn-warning previewProposal" data-token="{{ $record->token }}">AVAILABLE</button>
                                                @elseif ($checklist['tbl_document_type_id'] == 2  && ($checklist->form_filled > 0))  {{-- == 2  && ($checklist->form_filled > 0) --}}
                                                    <button class="btn btn-warning previewMandate" data-token="{{ $record->token }}">AVAILABLE</button>
                                                {{-- @elseif ($record->form_filled > 0)
                                                    @if (in_array($checklist['tbl_document_type_id'], [1, 21]) && ($checklist->form_filled > 0))
                                                        <button class="btn btn-warning previewProposal" data-token="{{ $record->token }}">AVAILABLE</button>
                                                    @endif
                                                    @if ($checklist['tbl_document_type_id'] == 2 && ($checklist->form_filled > 0))
                                                            <button class="btn btn-warning previewMandate" data-token="{{ $record->token }}">AVAILABLE</button>
                                                    @endif --}}
                                                @elseif ($checklist['tbl_document_type_id'] == 2 && ($checklist->form_filled == 0) && $record->tbl_documents_products_id == 5)
                                                    <a class="btn btn-success ml-sm"
                                                        href="{{route('document-digital-form', ['section' => $param_section, 'token' => $record->token, 'mandate' => 'mandate'])}}"
                                                        id="approveform" data-toggle="tooltip" data-placement="top"
                                                        title="Fill Mandate form">Fill Mandate form</a>
                                                @elseif ($checklist['mode'] == 4 && in_array($checklist['tbl_document_type_id'], [1, 21]) && ($checklist->form_filled == 0) || $record->tbl_documents_products_id == 5)
                                                    <a class="btn btn-success ml-sm"
                                                        href="{{route('document-digital-form', ['section' => $param_section, 'token' => $record->token])}}"
                                                        id="approveform" data-toggle="tooltip" data-placement="top"
                                                        title="Fill Form">Fill {{ $record->tbl_documents_products->product_name }} form</a>
                                                @endif
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>                            
                            </table>
                            </div>
                        </div>
                    @endif
                    
                    @if (count($workflows) > 0)
                        <div class="row">
                            <div class="col-12" >
                                @foreach($workflows as $workflow)
                                    @if(in_array(getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference), ["DATA CAPTURER", "CLAIMS APPROVERS", "CLAIMS PROCESSORS"]) && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                        <h4 class="header-title mb-3">SLAMS</h4>
                                        <form id="slams_forms" action="{{route('document.push.to.slam')}}" method="POST">
                                            <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000;">
                                                <thead>
                                                    <tr>
                                                        @if(in_array(getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference), ["DATA CAPTURER"]) && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                                            <th style="min-width: 50px;">Basic Premium</th>
                                                            <th style="min-width: 100px;">Sum Assured</th>
                                                            <th style="min-width: 70px;">Policy Fee</th>
                                                            <th>Client Type</th>
                                                            <th>Occ. Class</th>
                                                            <th>With Rider</th>
                                                            <th>Repped By Off. Mandate</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(in_array(getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference), ["DATA CAPTURER"]) && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                                        <tr>
                                                            <td>
                                                                <input required type="text" min="1" pattern="^\d+(\.\d+)?$" class="form-control" placeholder="Premium" id="basic_premium" name="basic_premium">
                                                                <div id="premium-error" style="color: red;"></div>
                                                            </td>
                                                            <td><input required type="number" min="1" class="form-control" placeholder="Sum Assured" id="sum_assured" name="sum_assured"></td>
                                                            <td><input required type="number" min="1" class="form-control" placeholder="Policy Fee" id="policyFee" name="policyFee"></td>
                                                            <td>
                                                                <select class="form-select" id="client_type" name="client_type" required>
                                                                    <option value="0">0</option>
                                                                    <option value="1">1</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select" id="occup_class" name="occup_class" required>
                                                                    <option value="005">NA</option>
                                                                    <option value="001">Class 1</option>
                                                                    <option value="002">Class 2</option>
                                                                    <option value="003">Class 3</option>
                                                                    <option value="004">Class 4</option>
                                                                </select>
                                                                <input type="hidden" class="form-control" id="id" name="id" value="{{ $id }}">
                                                            </td>
                                                            <td>
                                                                <select class="form-select" id="with_rider" name="with_rider" required>
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select" id="clientRepresentedByOfficialMandate" name="clientRepresentedByOfficialMandate" required>
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" class="form-control" id="record_id" name="record_id" value="{{ base64_encode($record['id']) }}">
                                                    @endif
                                                    @if(in_array(getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference), ["DATA CAPTURER"]) && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                                        <tr>
                                                            <td><input type="submit" id="push_slams_btn" class="btn btn-info btn-sm" value="PUSH" /></td>
                                                        </tr>
                                                    @endif
                                                    @if(in_array(getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference), ["CLAIMS APPROVERS", "CLAIMS PROCESSORS"]) && $workflow->tbl_workflow_status->id == 3 && $record['slams_create_policy_or_claim_status'] == 0)
                                                        <tr>
                                                            <td><input type="submit" id="push_slams_btn" class="btn btn-info btn-sm" value="PUSH" /></td>
                                                            <td>{{ $record['slams_create_policy_or_claim_response_message'] }}</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </form>
                                    @elseif(in_array(getReference($workflow->tbl_document_workflow_type->id, $workflow->tbl_document_setup_details->reference, $workflow->reference), ["DATA CAPTURER", "CLAIMS APPROVERS", "CLAIMS PROCESSORS"]) && $record['slams_create_policy_or_claim_status'] == -1)
                                        <h4 class="header-title mb-3">SLAMS</h4>
                                        <table class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important;">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <span type="button" class="btn btn-danger btn-sm">FAILED IN SLAMS</span></h4>
                                                    </td>
                                                    <td>{{ $record['slams_create_policy_or_claim_response_message'] }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                @endforeach
                            </div>
                            
                        
                        
                        </div>
                    @endif

                    @if (count($workflows) > 0)
                        <div class="row">
                            <div class="col-12">
                                <h4 class="header-title mb-3">Document Workflow Approval Tray</h4>
                                <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive"
                                    style="font-size: 13px;color: #000; padding: 5px 10px !important; ">
                        
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
                                    <tbody>
                                        @php
                                        $i = 1;
                                        @endphp
                                        @foreach ($workflows as $workflow)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $workflow->tbl_document_workflow_type['name'] }}</td>
                                                <td>{{ getReference($workflow->tbl_document_workflow_type['id'], $workflow->tbl_document_setup_details['reference'], $workflow['reference']) }}</td>
                                        
                                                <td>
                                                    @if (in_array($workflow['tbl_workflow_status_id'], [8, 9]))
                                                        @if ($workflow['review_type'] == 'CUSTOMER')
                                                            @if ($workflow['reference'] == 0)
                                                                @php
                                                                $showRec_ = $record->tbl_users['full_name'];
                                                                @endphp
                                                            @else
                                                                @php
                                                                $showRec_ = $workflow['processed_by'];
                                                                @endphp
                                                            @endif
                                                        @else
                                                            @php
                                                            // $showRec = $orm->tbl_departments[$workflow['reference']]; where('reference', $workflow['reference'])
                                                            // $showRec = App\Models\Department::where('reference', $workflow['reference'])->first();
                                                            $showRec = App\Models\Department::find($workflow['reference']);
                                                            $showRec_ = $showRec?->department_name;
                                                            @endphp
                                                        @endif
                                                        <button type="button" class="btn btn-{{ $workflow->tbl_workflow_status['colour'] }} btn-sm">
                                                            {{ $workflow->tbl_workflow_status['status_name'] }} >> {{ $showRec_ }}
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-{{ $workflow->tbl_workflow_status['colour'] }} btn-sm">
                                                            {{ $workflow->tbl_workflow_status['status_name'] }}
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (in_array($workflow['tbl_workflow_status_id'], [4, 5, 6, 7, 8, 11, 12]))
                                                        {{ $workflow['processed_by'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (in_array($workflow['tbl_workflow_status_id'], [4, 5, 6, 7, 8, 11, 12]))
                                                        {{ date('d-M-Y H:i:s', strtotime($workflow['processed_date'])) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (in_array($workflow->tbl_document_workflow_type['id'], [1, 2]))
                                                        @if ($workflow['completed_yn'] > 0)
                                                            @php
                                                            $image_details = getImageDetailsDocuments($record['id'], $workflow['id']);
                                                            @endphp
                                                            @if ($image_details)
                                                                <a href="#" id="previewloader3" data-id="{{ $image_details['id'] }}">
                                                                    <img src="../doc_logos/{{ getImageDetailsImgDocument($record['id'], $workflow['id']) }}">
                                                                </a>
                                                            @endif
                                                            @if ($workflow['comments'] <> '')
                                                                <br>Comments: {{ $workflow['comments'] }}<br>
                                                            @endif
                                                        @endif
                                                    @elseif ($workflow->tbl_document_workflow_type['id'] == 1)
                                                        @if (in_array($workflow['tbl_workflow_status_id'], [8, 10, 4, 11]))
                                                            @if ($workflow['review_type'] == 'CUSTOMER')
                                                                @php
                                                                // $depts = $orm->tbl_departments[$workflow['reference']];
                                                                $depts = App\Models\Department::find($workflow['reference']);
                                                                @endphp
                                                                Comments: {{ $workflow['comments'] }}<br>
                                                                To: {{ $depts?->department_name <> '' ? $depts?->department_name : 'Requester' }}
                                                            @else
                                                                @php
                                                                // $depts = $orm->tbl_users[$workflow['reference']];
                                                                $depts = App\Models\User::find($workflow['reference']);
                                                                @endphp
                                                                Comments: {{ $workflow['comments'] }}<br>
                                                                To: {{ $depts?->full_name <> '' ? $depts?->full_name : 'Requester' }}
                                                            @endif
                                                        @else
                                                            {{ $workflow['reference'] }}
                                                        @endif
                                                    @endif
                                        
                                                    @if ($workflow['requester_review'] > 0)
                                                        @php
                                                        // $depts2 = $orm->tbl_departments[$workflow['reference']];
                                                        $depts2 = App\Models\Department::find($workflow['reference']);
                                                        @endphp
                                                        Comments: {{ $workflow['comments'] }}<br>
                                                        To: {{ $depts2?->department_name <> '' ? $depts2?->department_name : 'Requester' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($workflow['document_evidence'] <> '')
                                                        <a href="#" id="previewloader2" data-id="{{ $workflow['document_evidence'] }}">
                                                            <img src="../documents/{{ $workflow['document_evidence'] }}" width="100">
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                                <div class="text-right mr-lg">
                                </div>
                            </div>
                        
                        
                        </div>
                    @endif

                    <div class="text-right mr-lg">
                        @php
                            $department_id = Auth()->user()->tbl_departments_id;
                            $check_app = App\Models\DocumentWorkflow::where('tbl_document_applications_id', $record['id'])
                                ->where('started_yn', 1)
                                ->where('completed_yn', 0)
                                ->where('deleted', 0)
                                ->whereHas('tbl_document_applications', function($query){
                                    $query->whereIn('tbl_application_status_id', [66, 71]);
                                })
                                ->whereHas('tbl_document_setup_details', function ($query) use ($department_id) {
                                    $query->where('reference', $department_id);
                                })
                                ->first();
                        @endphp
                    
                        @if ($check_app)
                            <a class="btn btn-success ml-sm open-viewComment getActionPage" href="#viewStock" data-toggle="modal" data-id="{{ $record['id'] }}" data-rec="APPROVE" data-title="APPROVE">
                                <i class="fa fa-check"></i> Approve
                            </a>
                            <a class="btn btn-warning ml-sm open-viewComment getActionPage" href="#viewStock" data-toggle="modal" data-id="{{ $record['id'] }}" data-rec="REVIEW" data-title="REVIEW">
                                <i class="fa fa-arrow-left"></i> Review
                            </a>
                        @endif
                        <button class="btn btn-warning " id="test-push" >TEST PUSH</button>
                    </div>
                    
                </div>
            </div>
           
        </div>
    </div>
{{-- </form> --}}
<div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="print-cont">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="myLargeModalLabel">Large modal</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="$('.bs-example-modal-lg').modal('hide');" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">

            </div>
            <div class="d-print-none" style="text-align: center; ">
                <div class="" style="padding: 5px;">
                    {{-- <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a> --}}
                    <a href="javascript:void(0)" onclick="printContent()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>

                    {{-- <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Send</a> --}}
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade bs-multiple-actions-page" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multiple-action-title">Multiple Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="render-audit">
            
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade bs-example-modal-push" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="print-cont">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="myLargeModalLabel">Large modal</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="$('.bs-example-modal-push').modal('hide');" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <form action="{{route('document.push.to.slam')}}" id="test-push-form" method="POST">
                    @csrf <!-- Laravel CSRF protection token -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="basic_premium">Premium:</label>
                            <input required type="number" min="1" class="form-control" placeholder="Premium" id="basic_premium" name="basic_premium">
                        </div>
                    
                        <div class="mb-3 col-md-6">
                            <label for="sum_assured">Sum Assured:</label>
                            <input required type="number" min="1" class="form-control" placeholder="Sum Assured" id="sum_assured" name="sum_assured">
                        </div>
                    </div>

                 
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="policyFee">Policy Fee:</label>
                            <input required type="number" min="1" class="form-control" placeholder="Policy Fee" id="policyFee" name="policyFee">
                        </div>
                    
                        <div class="mb-3 col-md-6">
                            <label for="client_type">Client Type:</label>
                            <select class="form-select" id="client_type" name="client_type" required>
                                <option value="0">0</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="occup_class">Occupation Class:</label>
                            <select class="form-select" id="occup_class" name="occup_class" required>
                                <option value="005">NA</option>
                                <option value="001">Class 1</option>
                                <option value="002">Class 2</option>
                                <option value="003">Class 3</option>
                                <option value="004">Class 4</option>
                            </select>
                        </div>
                    
                        <div class="mb-3 col-md-6">
                            <label for="with_rider">With Rider:</label>
                            <select class="form-select" id="with_rider" name="with_rider" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                  
                
                    <div class="mb-3 col-md-6">
                        <label for="clientRepresentedByOfficialMandate">Client Represented By Official Mandate:</label>
                        <select class="form-select" id="clientRepresentedByOfficialMandate" name="clientRepresentedByOfficialMandate" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                
                    <input type="hidden" class="form-control" id="record_id" name="record_id" value="{{ base64_encode($record['id']) }}"> 

                    <input type="hidden" class="form-control" id="id" name="id" value="{{ $id }}">
                    
                    <div class="d-print-none" style="text-align: center; ">
                        <div class="" style="padding: 5px;">
                            <button type="submit" class="btn btn-success waves-effect waves-light">PUSH</button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- <div class="d-print-none" style="text-align: center; ">
                <div class="" style="padding: 5px;">
                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                </div>
            </div> --}}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- <button type="button" id="getActionPage" data-title="APPROVE" data-id="{{ $record['id'] }}" data-rec="APPROVE" class="btn btn-success waves-effect waves-light getActionPage">Test Approve</button> --}}

@endsection
    
@section('application-status-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/libs/signature/js/jSignature.min.js')}}"></script>
    <script src="{{asset('/assets/libs/flexdatalist/js/jquery.flexdatalist.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{asset('/assets/js/ajax-utils.js')}}"></script>


    <!-- Sweet Alerts js -->
    <script src="{{asset('/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Sweet alert init js-->
    <script src="{{asset('/assets/js/pages/sweet-alerts.init.js')}}"></script>
    
    <script>
         $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        function printContent() {
            var printDiv = document.getElementById("print-cont");
            var printContents = printDiv.innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script>
        const premiumInput = document.getElementById('basic_premium');
        const premiumError = document.getElementById('premium-error');
    
        premiumInput.addEventListener('input', function(event) {
            const inputValue = event.target.value;
            if (!/^\d+(\.\d+)?$/.test(inputValue)) {
                premiumError.textContent = 'Please enter a valid number';
            } else {
                premiumError.textContent = '';
            }
        });
    </script>

    <script>
       
        // $(document).ready(function() {
        //     $('.rightbar-overlay').css('display', 'block');
        // })

        $(document).on('click', '.getActionPage', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let rec = $(this).data('rec');
            let title =  $(this).data('title');

            $('#multiple-action-title').html(title);

            console.log(id+'_'+rec);

            $.ajax({
                url: "/document/get/multiple/actions/page",
                type: 'POST',
                data: {'id' : id, 'rec' : rec},
                success: function(resp) {
                    $('#render-audit').html(resp);
                    $('.bs-multiple-actions-page').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                }
            });
        });

        $(document).on('submit', '#multiple-actionsForm', function(e) {
            e.preventDefault();
            let form = new FormData(this);

            $('.rightbar-overlay').css('display', 'block');
            
            $.ajax({
                url: "/document/save/multiple/actions/action",
                type: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function(resp) {
                    console.log(resp);
                    // Handle success
                    $('.rightbar-overlay').css('display', 'none');
                    
                    if(resp.status == 'success'){
                        $('.bs-multiple-actions-page').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: resp.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();

                    }else{
                        Swal.fire("Failed!",resp.message,"error");
                    }
                   
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                    Swal.fire("Failed!","Something went wrong!","error");
                    $('.rightbar-overlay').css('display', 'none');
                }
            });
        }); 
    </script>
 
    <script>
        // $(document).on('submit', '#test-push-form', function(){

        // }); test-push-form

        $('#slams_forms').submit(function(event){
            // Prevent default form submission
            event.preventDefault();
            
            // Serialize form data
            var formData = $(this).serialize();

            // AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                success: function(response){
                    // Handle successful response
                    console.log(response);
                    // For example, display a success message
                    alert('Form submitted successfully!');
                    window.location.reload();
                },
                error: function(xhr, status, error){
                    // Handle errors
                    console.error(xhr.responseText);
                    // For example, display an error message
                    alert('An error occurred while submitting the form. Please try again later.');
                }
            });
        });

        $(document).on('click', '#test-push', function(){
            // $('.bs-example-modal-push').modal('show');
            // return;
            $.ajax({
                url: "{{route('document.push.to.slam')}}",
                type: 'POST',
                data: {
                    id: '{{$id}}',
                    sum_assured: $('input[name=sum_assured]').val(),
                    basic_premium: 23.23, //$('input[name=basic_premium]').val(),
                    policyFee: 2, //$('input[name=policyFee]').val(),
                    client_type: $('select[name=client_type]').val(),
                    occup_class: $('select[name=occup_class]').val(),
                    with_rider: $('select[name=with_rider]').val(),
                    payment_date: $('input[name=payment_date]').val(),
                    payment_amt: $('input[name=payment_amt]').val(),
                    product_category: $('select[name=product_category]').val(),
                    client_attributes: $('select[name=client_attributes]').val(),
                    nature_of_product: $('select[name=nature_of_product]').val(),
                    source_of_funds: $('select[name=source_of_funds]').val(),
                    client_conduct: $('select[name=client_conduct]').val(),
                    clientRepresentedByOfficialMandate: $('select[name=clientRepresentedByOfficialMandate]').val(),
                },
                success: function(response) {
                    console.log(response);
                    alert(response);
                },
                error: function(xhr, status, error) {
                    // Handle error if any
                    console.error(error);
                }
            });
        })
        $(document).on('click', '.previewProposal', function(){
            let token = $(this).data('token');
            $('#previewContent').empty();

            $.ajax({
                url: '/document/preview-proposal/' + token,
                type: 'GET',
                success: function(response) {
                    // Update modal content with the retrieved preview content
                    $('#previewContent').html(response.previewContent);
                    // Show the modal
                    $('.bs-example-modal-lg').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error if any
                    console.error(error);
                }
            });
        })
        $(document).on('click', '.previewMandate', function(){
            let token = $(this).data('token')
            $('#previewContent').empty();

            $.ajax({
                url: '/document/preview-mandate/' + token,
                type: 'GET',
                success: function(response) {
                    // Update modal content with the retrieved preview content
                    $('#previewContent').html(response.previewContent);
                    // Show the modal
                    $('.bs-example-modal-lg').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error if any
                    console.error(error);
                }
            });
        })
    function openPreviewModal(templateDataId) {
        // Make AJAX request to fetch preview content
      
    }

    $(document).on('click', '.previewProposal3', function(e) {
        e.preventDefault();

        var ele = $(this);
        let token = $(this).data('token')
        
        // Make AJAX request to fetch the HTML content
        $.ajax({
            url: '/document/preview-proposal/' + token,
            type: 'GET',
            success: function(response) {
                // Create iframe element
                var iframe = $('<iframe>', {
                    src: 'about:blank',
                    frameborder: 0,
                    width: '100%',
                    height: '800px' // Adjust height as needed
                });

                // Append iframe to the document
                $('#previewContent').append(iframe);

                // Access the iframe document
                var iframeDocument = iframe[0].contentWindow.document;

                // Write HTML content to iframe document
                iframeDocument.open();
                iframeDocument.write(response.previewContent);
                iframeDocument.close();

                // Apply CSS styles for A4 format
                $('head', iframeDocument).append('<link rel="stylesheet" href="a4-styles.css" type="text/css" />');

                 // Show the modal
                 $('.bs-example-modal-lg').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching document:', error);
            }
        });

        return false;
    });


window.LoadModal = function(input){
  //console.log(input);
  var opts = {
    title: 'Modal Title',
    url: '',
    clone: false,
    persistent: false,
    loading: '<div id="page-loader" style="position:relative; height:60px"><span class="spinner"></span></div>',
    size: "md", //lg, md, sm
    params: {},
    method: "GET", //GET, POST
    type: 'page', // iframe, page
    print: false, // true/false, object {paper: 'A4', orientation: 'portrait'};
  };
  var printparams = {paper: 'A4', orientation: 'portrait', margins: '0.5in'};
  $.extend(opts, input);
  //  console.log(opts, input);
  var mod = $('div#modal-dialog');
  if(opts.clone){
    mod = mod.clone();
  }
  var size = "modal-" + opts.size;
  mod.find('div.modal-dialog').attr('class','modal-dialog ' + size);
  if(opts.persistent){
    mod.modal({
      backdrop: 'static',
      keyboard: false
    });
  }
  mod.find('.modal-title').html(opts.title);
  mod.find('div.modal-body').html(opts.loading);
  var btn = mod.find('div.btnlink');
  btn.data('url', opts.url);
  btn.data('params', opts.params);
  btn.data('method', opts.method);
  //console.log(opts.print);
  if(opts.print){
    if($.type(opts.print) == 'object'){
      $.extend(printparams, opts.print);
    }
    btn.show();
  }else{
    btn.hide();
  }
  for (var key in printparams) {
    if (printparams.hasOwnProperty(key)) {
      mod.find('div#optional_btn').data("print-" + key, printparams[key]);
    }
  }
  $.extend(opts.params,printparams);
  if(opts.method == "GET"){
    var parts = opts.url.split("?");
    //console.log(opts.url, parts);
    if(typeof parts[1] !== 'undefined'){
      if(!$.isEmptyObject(opts.params)){
        for (var key in opts.params) {
          if (opts.params.hasOwnProperty(key)) {
            if (opts.url) {
              var updateRegex = new RegExp('([\?&])' + key + '[^&]*');
              var removeRegex = new RegExp('([\?&])' + key + '=[^&;]+[&;]?');
              if (opts.url.match(updateRegex) !== null) { // If param exists already, remove it
                opts.url = opts.url.replace(removeRegex, "$1");
                opts.url = opts.url.replace( /[&;]$/, "" );
              }
            }
          }
        }
        opts.url = opts.url + '&' + $.param(opts.params);
      }else{
        opts.url = parts[0];
      }
    }else{
      if(!$.isEmptyObject(opts.params)){
        opts.url = opts.url + '?' + $.param(opts.params);
      }
    }
  }
  if(opts.type == 'iframe'){
    mod.find('div.modal-body').html('');
    var log = $('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item previewframe"  src="'+ opts.url +'" allowfullscreen id="myFrame"></iframe></div>');
    mod.find('div.modal-body').append(log);
  }else if(opts.type == 'page'){
    if(opts.method == "GET"){
      mod.find('div.modal-body').load(opts.url, function(response, status, xhr ){
        if ( status == "error" ) {
          var msg = "Sorry but there was an error: ";
          $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }else{
          mod.find(".numeric-only").each(function(){
            if (typeof $(this).data('numFormat') == "undefined") {
              $(this).number(true, 2);
            }
          });
          mod.find("input:checkbox.form-control").each(function(){
            $(this).bootstrapToggle(checkboxObj);
          });
        }

      });
    }else{
      mod.find('div.modal-body').load(opts.url, opts.params, function(response, status, xhr ){
        if ( status == "error" ) {
          var msg = "Sorry but there was an error: ";
          $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }else{
          mod.find(".numeric-only").each(function(){
            if (typeof $(this).data('numFormat') == "undefined") {
              $(this).number(true, 2);
            }
          });
          mod.find("input:checkbox.form-control").each(function(){
            $(this).bootstrapToggle(checkboxObj);
          });
        }

      });
    }
  }
  mod.modal('show');
  return false;
}
    </script>


<script>
    $(document).on('click', '#generateProposalForm', function(e) {
        let token = $(this).data('token');
      
        console.log(token);

        $.ajax({
            url: "/document/generate/proposal/form",
            type: 'POST',
            data: {'token': token},
            success: function(resp) {
                Swal.fire("Generated!", "Proposal Form successfully", "success");
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                // Handle error
            }
        });
    });

    $(document).on('click', '#generateMandateForm', function(e) {
        let token = $(this).data('token');
      
        console.log(token);

        $('.rightbar-overlay').css('display', 'block');
        $.ajax({
            url: "/document/generate/mandate/form",
            type: 'POST',
            data: {'token': token},
            success: function(resp) {
                $('.rightbar-overlay').css('display', 'none');
                Swal.fire("Generated!", "Manate Form successfully", "success");
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                // Handle error
            }
        });
    });

    $(document).on('click', '#delete-document', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
            swal.fire({
                title: 'Delete Document?',
                text: "You are about to delete this document, Are you certain?",
                type: 'warning',
                icon:"warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete Document!'
            }).then(function(t) {
                if(t.value) {
                    $.ajax({
                        url: "/document/delete/attached",
                        type: 'POST',
                        data: {'id':id, 'del_type': 'document applications id'},
                        success: function(resp) {
                            if(resp.status == 'success'){
                                Swal.fire("Deleted!",resp.message,resp.status);
                                window.location.reload();
                            }
                            if(resp.status == 'error'){
                                Swal.fire("Failed!",resp.message,resp.status);
                            }
                            
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
    }); 


</script>
@stop