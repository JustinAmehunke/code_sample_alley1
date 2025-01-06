@extends('layouts.main-master')
@section('content')
<link href="{{asset('/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        .from-to-datepicker{
            padding: 0.47rem 0.1rem !important;
        }
        .table.dataTable.dtr-inline.collapsed > tbody > tr > td, table.dataTable.dtr-inline.collapsed > tbody > tr > td {
            font-size: 13px;
        }
    </style>
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
    <div class="row">
       <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Search Request(s)</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product Request</a></li>
                        <li class="breadcrumb-item active">Search Request(s)</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    @if(session('form-params'))
        <p>{{json_encode( session('form-params')) }}</p>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    Search Parameters
                </h5> 

                <div class="card-body">
                    <form class="" action="{{route('document.search.requests')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="policy_number" class="form-label">Policy Number</label>
                                    <input type="text" id="policy_number" name="policy_number" class="form-control" placeholder="" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Date Range </label>
                                    <div class="input-daterange input-group" id="datepicker6" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                        <span class="input-group-text"><i class="mdi mdi-calendar me-1"></i></span>
                                        <input type="text" class="form-control from-to-datepicker" id="start_date" name="start_date" placeholder="Start Date">
                                        <span class="input-group-text">To</span> 
                                        <input type="text" class="form-control from-to-datepicker" id="end_date" name="end_date" placeholder="End Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Client</label>
                                    <select type="text" class="select2 form-control select2-multiple" name="client_id[]" id="client_id" placeholder=""
                                        multiple="multiple" data-placeholder="Choose ...">
                                         <option selected="" disabled="" value="">Choose...</option>
                                         <option value="1">1</option>
                                         <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">Branch</label>

                                    @php
                                        $branches = App\Models\Branch::where('deleted', 0)->get();
                                    @endphp
                                    {{-- <select class="form-select"  > --}}
                                    <select class="select2 form-control select2-multiple" name="branch_id[]" id="branch_id"
                                        multiple="multiple" data-placeholder="Choose ...">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value=''>ALL BRANCHES</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->branch_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>

                                    @php
                                        $document_products = App\Models\DocumentProduct::all();
                                    @endphp
                                    <select class="select2 form-control select2-multiple" name="product_id[]" id="product_id" 
                                        multiple="multiple" data-placeholder="Choose ...">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value="">ALL BUSINESS</option>
                                        <option value="ALL PRODUCTS">ALL PRODUCTS</option>
                                        <option value="ALL CLAIMS">ALL CLAIMS</option>
                                        @foreach ($document_products as $document_product)
                                            <option value="{{$document_product->id}}">{{$document_product->product_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="uploaded_by" class="form-label">Uploaded By</label>

                                    @php
                                        $users = App\Models\User::whereNotIn('id', [Auth()->id()])->get();
                                    @endphp
                                    <select class="select2 form-control select2-multiple" name="uploaded_by[]" id="uploaded_by" 
                                            multiple="multiple" data-placeholder="Choose ...">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value="">ALL</option>
                                        <option value="{{Auth()->id()}}">Me</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->full_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="doc_status" class="form-label">Document Status</label>

                                    @php
                                        $document_products = App\Models\DocumentProduct::all();

                                        $appstatus = new App\Http\CustomClasses\ApplicationStatusClass(11, 16);
                                    @endphp
                                    <select class="select2 form-control select2-multiple" name="doc_status[]" id="doc_status"
                                            multiple="multiple" data-placeholder="Choose ...">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value=''>ALL</option>
                                        @foreach ($appstatus->getStatusList() as $status)
                                            <option value="{{$status->id}}">{{$status->status_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="req_number" class="form-label">Request Number</label>
                                    <input type="text" class="form-control" name="req_number" id="req_number" placeholder=""  >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="dynamic_search" class="form-label">Dynamic Search</label>
                                    <input type="text" class="form-control" name="dynamic_search" id="dynamic_search" placeholder=""  >
                                </div>
                            </div>
                        </div>
                       
                        {{-- <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Save & Proceed</button>
                        </div> --}}
                        <div class="d-flex justify-content-end mb-3">
                            <div>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Reset
                                </button>
                                <button class="btn btn-primary" type="submit">Perform Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
           
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- <h5 class="card-header">
                    SLAMS Logs
                </h5>  --}}

                <div class="card-body">
        
                    <h4 class="card-title">Search Results</h4>
                    <p class="card-title-desc"> </p>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Assigned To</th>
                                <th>Request No.</th>
                                <th>Customer Name.</th>
                                <th>Phone Number</th>
                                <th>Email Address</th>
                                <th>Policy Number</th>
                                <th>Category</th>
                                <th>Capturer</th>
                                <th>Branch</th>
                                <th>Description</th>
                                <th>Assign Note</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        
                         
                        </tbody>
                        
                    </table>

                </div>
            </div>
           
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Audit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="render-audit">
                
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade override-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Override</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="override-body">
                
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="smallModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallModalLabel">Share</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="render-share">
                  
                </div>
               
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade digital-form-modal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="print-cont">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="myLargeModalLabel">Large modal</h5> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewContent">
    
                </div>
                <div class="d-print-none">
                    <div class="float-end">
                        {{-- <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a> --}}
                        <a href="javascript:void(0)" onclick="printContent()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
    
                        <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Send</a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

@endsection
    
@section('application-status-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/libs/signature/js/jSignature.min.js')}}"></script>
    <script src="{{asset('/assets/libs/flexdatalist/js/jquery.flexdatalist.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{asset('/assets/js/ajax-utils.js')}}"></script>

    <!-- Required datatable js -->
    <script src="{{asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

    <script src="{{asset('/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>
    
    <!-- Responsive examples -->
    <script src="{{asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    {{-- <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script> --}}

    <!-- Select2 js -->
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <!-- Date Picker js -->
    <script src="{{asset('/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

    <script src="{{asset('/assets/js/app.js')}}"></script>


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
    $(document).ready(function() {
        // bs-example-modal-lg
        // smallModal
       
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            error: function () { },
            language: {
                emptyTable: ''
            },
            ajax: {
                url: "{{ session('form-params') ? route('customer.complaints.custom.search-results') : route('customer.complaints.search-results') }}",
                type: "POST",
                data: function (data) {
                    data.search = $('div.dataTables_filter input').val();
                    data.form_params = @json(session('form-params') ?: null);
                    data.complaint_type = 'search';
                }
            },
            order: ['1', 'DESC'],
            pageLength: 10,
            searching: true,
            aoColumns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    width: "3%",
                },
                {
                    data:'id',
                    render: function(data,type,row){
                        let assigned_to =  row.assigned ? row.assigned.full_name : 'Not Assigned';
                        return `<span>${assigned_to}</span>`;
                    }
                },
                {
                    data: 'request_no',
                },
                {
                    data: 'name',
                },
                {
                    data: 'phone_number',
                },
                    {
                    data: 'email',
                },
                {
                    data: 'policy_number',
                },
                {
                    data: 'tbl_complaints_categories.name',
                },
                {
                    data: 'tbl_users.full_name',
                },
                {
                    data: 'tbl_branch.branch_name',
                },
                {
                    data: 'description',
                },
                {
                    data: 'assign_comment',
                },
                {
                    data: 'createdon',
                    render: function(data, type, row) {
                        // Assuming the date format is YYYY-MM-DD HH:MM:SS
                        var date = new Date(data);
                        // Array to map month numbers to abbreviated month names
                        var monthAbbreviations = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];
                        // Format the date with abbreviated month name, e.g., "DD MMM YYYY HH:MM:SS"
                        var formattedDate = date.getDate() + ' ' +
                            monthAbbreviations[date.getMonth()] + ' ' +
                            date.getFullYear() + ' ' +
                            date.getHours() + ':' +
                            date.getMinutes() + ':' +
                            date.getSeconds();
                        return formattedDate;
                    }
                },

                {
                    data: 'tbl_application_status.status_name',
                },
                {
                    data: 'id',
                    render: function(data, type, row){
                        let clannel =  row.reporting_channel ?? (row.tbl_complaint_reporting_channel ? row.tbl_complaint_reporting_channel.name : '');
                        return `<span>${clannel}</span>`;
                    }
                },
                {
                    data: 'id',
                    width: "20%",
                    render: function(data, type, row) {
                        let id = row.id;
                        let user_id = @json(optional(auth()->user())->id); // Use optional() to avoid errors if the user is not authenticated
                        
                        return `
                            <div class="btn-group me-2 mb-2 mb-sm-0">
                                <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a href="#viewStock"  data-id="${btoa(id)}" class="open-viewAuditTrail dropdown-item"><i class="fa fa-history"></i> Audit Trail</a>
                                    <a href="#modal-dialog" data-id="${btoa(id)}" class="open-assign dropdown-item"><i class="fa fa-share-alt"></i> Assign Request</a>
                                    <a href="#modal-dialog" data-id="{${btoa(id)}" class="complete-request dropdown-item" ><i class="fa fa-file-o"></i> Complete Request</a>
                                    <a href="#" data-id="{${btoa(id)}" id="deleteca" class="dropdown-item"><i class="fa fa-trash-o"></i>
                                        Delete</a>
                                    <a href="#" data-id="{${btoa(id)}" id="pendca" class="dropdown-item"><i class="fa fa-file-o"></i> Pend
                                        Request</a>
                                </div>
                            </div>
                        `;
                    }
                }
            ]
        });
    });

</script>
  
@stop