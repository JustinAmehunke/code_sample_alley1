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
                                <th>Request No</th>
                                <th>Customer Name</th>
                                <th>Policy Number</th>
                                <th>Product</th> 
                                <th>Processed By</th>
                                <th>Branch</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- <tr>
                                <td>1</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>
                                    <div class="btn-group me-2 mb-2 mb-sm-0">
                                        <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                        </button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item" href="#">Updates</a>
                                            <a class="dropdown-item" href="#">Social</a>
                                            <a class="dropdown-item" href="#">Team Manage</a>
                                        </div>
                                    </div>
                                </td> 
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>63</td>
                                <td>2011/07/25</td>
                                <td>$170,750</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>
                                    <div class="btn-group me-2 mb-2 mb-sm-0">
                                        <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                        </button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item" href="#">Updates</a>
                                            <a class="dropdown-item" href="#">Social</a>
                                            <a class="dropdown-item" href="#">Team Manage</a>
                                        </div>
                                    </div>
                                </td> 
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Junior Technical Author</td>
                                <td>San Francisco</td>
                                <td>66</td>
                                <td>2009/01/12</td>
                                <td>$86,000</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>
                                    <div class="btn-group me-2 mb-2 mb-sm-0">
                                        <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                        </button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item" href="#">Updates</a>
                                            <a class="dropdown-item" href="#">Social</a>
                                            <a class="dropdown-item" href="#">Team Manage</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Senior Javascript Developer</td>
                                <td>Edinburgh</td>
                                <td>22</td>
                                <td>2012/03/29</td>
                                <td>$433,060</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>
                                    <div class="btn-group me-2 mb-2 mb-sm-0">
                                        <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                        </button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item" href="#">Updates</a>
                                            <a class="dropdown-item" href="#">Social</a>
                                            <a class="dropdown-item" href="#">Team Manage</a>
                                        </div>
                                    </div>
                                </td> 
                            </tr> --}}
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
            ajax: {
                url: "{{ session('form-params') ? route('document.custom.search-results') : route('document.search-results') }}",
                type: "POST",
                data: function (data) {
                    data.search = $('div.dataTables_filter input').val();
                    data.form_params = @json(session('form-params') ?: null);
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
                    data:'request_no',
                },
                {
                    data: 'customer_name',
                },
                {
                    data: 'policy_no',
                },
                {
                    data: 'document_product.product_name',
                },
                    {
                    data: 'user.full_name',
                },
                {
                    data: 'branch.branch_name',
                },
                {
                    data: 'createdon',
                },
                {
                    data: 'application_status.status_name',
                },
                {
                    data: 'source',
                },
                {
                    data: 'id',
                    width: "20%",
                    render: function(data, type, row) {
                        let id = row.id;
                        let user_id = @json(optional(auth()->user())->id); // Use optional() to avoid errors if the user is not authenticated
                        let token = row.token;
                        let request_no = row.request_no;
                        let form_filled = row.form_filled;
                        let createdby = row.createdby;
                        let tbl_application_status_id = row.tbl_application_status_id;

                        return `
                            <div class="btn-group me-2 mb-2 mb-sm-0">
                                <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                </button>
                                <div class="dropdown-menu" style="">
                                    <button class="dropdown-item audit" data-id="${btoa(id)}"  href="#${data}"><i class="ri-history-line align-middle me-2"></i>Audit Trial</button>

                                    ${form_filled == 1 ? `
                                        <a class="dropdown-item digital-form" data-token="${token}"  href="#${btoa(id)}"><i class="ri-file-list-line align-middle me-2"></i>Digital Form</a>
                                        <a class="dropdown-item share" data-id="${btoa(id)}"  href="#${id}"><i class="ri-share-fill align-middle me-2"></i>Share</a>`: ''}

                                    ${((createdby == user_id) || [324, 75, 273, 288, 1, 85, 269, 270, 78].includes(user_id)) ? 
                                        `<a class="dropdown-item" href="/product-request/request-profile?section='UmVxdWVzdC1Qcm9maWxl'&id='${btoa(id)}'"><i class="ri-file-edit-line align-middle me-2"></i>Edit Request</a>` : ''}
                                    
                                    @if (auth()->user()?->delete_documents_yn)
                                        ${[75].includes(tbl_application_status_id) ? 
                                            `<a class="delete dropdown-item" data-id="${btoa(id)}" href="#"><i class="ri-delete-bin-line align-middle me-2"></i>Delete</a>` : ''}
                                    @endif

                                    @if (auth()->user()?->override_status_yn)
                                        <a class="override dropdown-item" data-id="${btoa(id)}"  href="#${token}"><i class="ri-swap-line align-middle me-2"></i>Override Status</a>
                                    @endif
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