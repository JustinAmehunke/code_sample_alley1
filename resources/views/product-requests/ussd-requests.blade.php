@extends('layouts.main-master')
@section('content')

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
                <h4 class="mb-sm-0">USSD Requests</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product Request</a></li>
                        <li class="breadcrumb-item active">USSD Requests</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        @php
            $firstCol = $incomplete;
            $secondCol = $pending_reqs;
            $thirdCol = $payment_reqs;
            $fourthCol = $paid_reqs;
        @endphp
        @include('product-requests.includes.requests-dashboard-header')
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- <h5 class="card-header">
                    SLAMS Logs
                </h5>  --}}

                <div class="card-body">
        
                    <h4 class="card-title">USSD Requests</h4>
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
                           
                        </tbody>
                    </table>

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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('document.get-ussd.requests') }}",
                    type: "POST",
                    data: function (data) {
                        data.search = $('input[type="search"]').val();
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
                                        <a class="dropdown-item"  href="#${data}"><i class="ri-history-line align-middle me-2"></i>Audit Trial</a>

                                        ${form_filled == 1 ? `
                                            <a class="dropdown-item"  href="#${token}"><i class="ri-file-list-line align-middle me-2"></i>Digital Form</a>
                                            <a class="dropdown-item"  href="#${id}"><i class="ri-share-fill align-middle me-2"></i>Share</a>`: ''}

                                        ${((createdby == user_id) || [324, 75, 273, 288, 1, 85, 269, 270, 78].includes(user_id)) ? 
                                            `<a class="dropdown-item"  href="#${btoa(id)}"><i class="ri-file-edit-line align-middle me-2"></i>Edit Request</a>` : ''}
                                        
                                        @if (auth()->user()?->delete_documents_yn)
                                            ${[75].includes(tbl_application_status_id) ? 
                                                `<a class="dropdown-item"  href="#${btoa(id)}"><i class="ri-delete-bin-line align-middle me-2"></i>Delete</a>` : ''}
                                        @endif

                                        @if (auth()->user()?->override_status_yn)
                                            <a class="dropdown-item"  href="#${token}"><i class="ri-swap-line align-middle me-2"></i>Override Status</a>
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