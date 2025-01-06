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
                <h4 class="mb-sm-0">Report</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Complaints</a></li>
                        <li class="breadcrumb-item active">Report</li>
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
                    Set Report Criteria
                </h5> 

                <div class="card-body">
                    <form class="" action="{{route('search.customer-complaints')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                @php
                                    $complaint_categories = App\Models\ComplaintCategory::where('deleted', 0)->get();
                                @endphp
                                <div class="mb-3">
                                    <label for="categ" class="form-label">Complaint Category</label>
                                    <select class="select2 form-control select2-multiple" name="categ" id="categ"
                                    multiple="multiple" data-placeholder="Choose ..." >
                                        <option disabled="" value="">Choose...</option>
                                        @foreach ($complaint_categories as $complaint_category)
                                            <option value="{{$complaint_category->id}}">{{$complaint_category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Complaints Between</label>
                                    <div class="input-daterange input-group" id="datepicker6" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                        <span class="input-group-text"><i class="mdi mdi-calendar me-1"></i></span>
                                        <input type="text" class="form-control from-to-datepicker" id="start_date" name="start_date" placeholder="Start Date">
                                        <span class="input-group-text">To</span> 
                                        <input type="text" class="form-control from-to-datepicker" id="end_date" name="end_date" placeholder="End Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="doc_status" class="form-label">Status</label>

                                    @php
                                        $document_products = App\Models\DocumentProduct::all();

                                        $appstatus = new App\Http\CustomClasses\ApplicationStatusClass(2, 2);
                                    @endphp
                                    <select class="select2 form-control select2-multiple" name="status[]" id="doc_status"
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
                                    <label for="reqnum" class="form-label">Request Number</label>
                                    <input type="text" id="reqnum" name="reqnum" class="form-control" placeholder="" >
                                </div>
                            </div>
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
                           
                        </div>

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
@stop