@php
    $lastParam = collect(request()->segments())->last(); // Get the last segment of the URL
    $paramValue = request()->query('id'); 
@endphp
<div class="col-md-3">
    <div class="card">
        <h5 class="card-header">
            Product Options
        </h5>
        @php
            $doc_appl_id = 0 ;
            $quote_menu = [];
            $quote_menu['Customer-Info'] = true;
            $quote_menu['Request-Profile'] = true;

            $int_logs = \App\Models\IntegrationLog::where('tbl_document_applications_id', $doc_appl_id)
                                        ->where('deleted', 0)
                                        ->count()?? 0;
            if ($int_logs > 0) {
                $quote_menu['SLAMS-Logs'] = true;
            }

            $all_docs_count = \App\Models\Document::where('tbl_document_applications_id', $doc_appl_id)
                                        ->where('deleted', 0)
                                        ->count()?? 0;
            if(isset($requesteds)){
                if ($all_docs_count >= ($requesteds ? count($requesteds) : 0)) {
                    $quote_menu['Checklist'] = true;
                }
            }

            $documents_count = \App\Models\Document::where('tbl_document_applications_id', $doc_appl_id)
                                        ->where('deleted', 0)
                                        ->count()?? 0;
            if ($documents_count > 0) {
                $quote_menu['Attached-Documents'] = true;
            }

        @endphp
        {{-- {{json_encode($quote_menu)}} --}}
        <div class="card-body">
            <div class="d-grid mb-3">
                <a href="{{route('customer-info', ['section' => $param_section, 'id' => $param_id])}}" type="button" class="btn {{ $lastParam == "customer-info" ? "btn-primary":"btn-outline-primary" }}  waves-effect waves-light d-flex justify-content-between">
                   <span>
                    <i class="ri-radio-button-line align-middle me-2"></i>
                    <span>Customer Info</span>
                   </span>
                   @if (isset($quote_menu['Customer-Info']))
                        <div class="btn-groupp" role="group" aria-label="Second group">
                                @isset($record['policy_no'])
                                    <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                        <i class="ri-check-line align-middle ms-1 me-1"></i>
                                    </span>
                                @else
                                    <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                        <i class="ri-close-line align-middle ms-1 me-1"></i>
                                    </span>
                               @endisset
                               
                           
                        </div>
                   @else
                        <div class="btn-group" role="group" aria-label="Second group">
                            <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                <i class="ri-close-line align-middle ms-1 me-1"></i>
                            </span>
                        </div>
                   @endif
                    {{-- <i class="ri-arrow-right-line align-middle ms-2"></i> --}}
                </a>
            </div>
            @isset($record['policy_no'])
                <div class="d-grid mb-3">
                    <a href="{{route('product-checklist', ['section' => $param_section, 'id' => $param_id])}}" type="button" class="btn {{ $lastParam == "product-checklist" ? "btn-primary":"btn-outline-primary" }} waves-effect waves-light d-flex justify-content-between">
                    <span>
                        <i class="ri-radio-button-line align-middle me-2"></i>
                        <span>Checklist</span>
                    </span>
                    @if (isset($quote_menu['Checklist']))
                            <div class="btn-groupp" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                    <i class="ri-check-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @else
                            <div class="btn-group" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                    <i class="ri-close-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @endif
                    </a>
                </div>
                <div class="d-grid mb-3">
                    <a href="{{route('attached-documents', ['section' => $param_section, 'id' => $param_id])}}" type="button" class="btn {{ $lastParam == "attached-documents" ? "btn-primary":"btn-outline-primary" }} waves-effect waves-light d-flex justify-content-between">
                    <span class="d-flex">
                        <i class="ri-radio-button-line align-middle me-2"></i>
                        <span class="text-start">Attached Documents</span>
                    </span>
                    @if (isset($quote_menu['Attached-Documents']))
                            <div class="btn-groupp" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                    <i class="ri-check-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @else
                            <div class="btn-groupp" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                    <i class="ri-close-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @endif
                    </a>
                </div>
                <div class="d-grid mb-3">
                    <a href="{{route('request-profile', ['section' => $param_section, 'id' => $param_id])}}" type="button" class="btn {{ $lastParam == "request-profile" ? "btn-primary":"btn-outline-primary" }} waves-effect waves-light d-flex justify-content-between">
                    <span class="d-flex">
                        <i class="ri-radio-button-line align-middle me-2"></i>
                        <span class="text-start">Request Profile</span>
                    </span>
                    @if (isset($quote_menu['Request-Profile']))
                            <div class="btn-groupp" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                    <i class="ri-check-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @else
                            <div class="btn-group" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                    <i class="ri-close-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @endif
                    </a>
                </div>
                <div class="d-grid mb-3">
                    <a href="{{route('slams-logs', ['section' => $param_section, 'id' => $param_id])}}" type="button"  class="btn {{ $lastParam == "slams-logs" ? "btn-primary":"btn-outline-primary" }} waves-effect waves-light d-flex justify-content-between">
                    <span>
                        <i class="ri-radio-button-line align-middle me-2"></i>
                        <span>SLAMS Logs</span>
                    </span>
                    @if (isset($quote_menu['SLAMS-Logs']))
                            <div class="btn-groupp" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                    <i class="ri-check-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @else
                            <div class="btn-group" role="group" aria-label="Second group">
                                <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                    <i class="ri-close-line align-middle ms-1 me-1"></i>
                                </span>
                            </div>
                    @endif
                    </a>
                </div>
            @endisset
           
        </div>
    </div>

    @isset($record)
        @if ($record['form_filled'] == 1)
            <div class="button-items mt-3 d-grid">
                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light">Complete</button>
            </div>
            {{-- @if ($record['tbl_documents_products_id'] == 14)
                <div class="button-items mt-3 d-grid">
                    <button type="button" class="btn btn-warning btn-sm waves-effect waves-light">Generate Corporate Due Diligence Form</button>
                </div>
            @else
                <div class="button-items mt-3 d-grid">
                    <button type="button" id="generateProposalForm" data-token="{{$record->token}}" class="btn btn-success btn-sm waves-effect waves-light">Generate Proposal Form</button>
                    <button type="button" id="generateMandateForm" data-token="{{$record->token}}" class="btn btn-warning btn-sm waves-effect waves-light"> Generate Mandate Form</button>
                </div>
            @endif --}}
        @endif
    @endisset
   
</div>

