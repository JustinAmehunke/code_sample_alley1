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
                    <form class="" action="{{route('customer.complaints.init.search')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                @php
                                    $complaint_categories = App\Models\ComplaintCategory::where('deleted', 0)->get();
                                @endphp
                                <div class="mb-3">
                                    <label for="categ" class="form-label">Complaint Category</label>
                                    <select class="form-control select2" name="categ" id="categ" >
                                        <option disabled="" value="">Choose...</option>
                                        @foreach ($complaint_categories as $complaint_category)
                                            <option value="{{$complaint_category->id}}">{{$complaint_category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Assigned To</label>

                                    @php
                                        $users = App\Models\User::whereNotIn('id', [Auth()->id()])->get();
                                    @endphp
                                    <select class="select2 form-control select2-multiple" name="assigned_to[]" id="assigned_to" 
                                            multiple="multiple" data-placeholder="Choose ...">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        {{-- <option value="">ALL</option> --}}
                                        <option value="{{Auth()->id()}}">Me</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->full_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="reqnum" class="form-label">Request Number</label>
                                    <input type="text" id="reqnum" name="reqnum" class="form-control" placeholder="" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Name</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="" >
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
                        
                            {{-- @foreach($records as $key => $glrow)
                        
                                <tr class="">
                                    <td>{{ ++$key }}</td>
                        
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ getUserName($glrow['assigned_to']) }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['request_no'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['name'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['phone_number'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['email'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['policy_number'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow->tbl_complaints_categories['name'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow->tbl_users['full_name'] ?? '' }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow->tbl_branch['branch_name'] }}</strong>
                                        </a>
                                    </td>
                        
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['description'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['assign_comment'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ date('d-M-Y H:i:s', strtotime($glrow['createdon'])) }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow->tbl_application_status['status_name'] }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#viewStock" data-toggle="modal" data-id="{{ $glrow['id'] }}" class="open-viewRequest">
                                            <strong>{{ $glrow['reporting_channel'] ?? $glrow->tbl_complaint_reporting_channel['name'] }}</strong>
                                        </a>
                                    </td>
                        
                                    <td>
                                        <div class="btn-group me-2 mb-2 mb-sm-0">
                                            <button type="button" class="btn btn-primary btn-sm waves-light waves-effect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-list-settings-line align-middle"></i> Action <i class="mdi mdi-chevron-down ms-1 align-middle"></i>
                                            </button>
                                            <div class="dropdown-menu" style="">
                                                <a href="#viewStock"  data-id="{{ $glrow['id'] }}" class="open-viewAuditTrail dropdown-item"><i class="fa fa-history"></i> Audit Trail</a>
                                                <a href="#modal-dialog" data-id="{{ $glrow['id'] }}" class="open-assign dropdown-item"><i class="fa fa-share-alt"></i> Assign Request</a>
                                                <a href="#modal-dialog" data-id="{{ $glrow['id'] }}" class="complete-request dropdown-item" ><i class="fa fa-file-o"></i> Complete Request</a>
                                                <a href="{{ url('assigned-complaints?id=' . base64_encode($glrow['id']) . '&action=' . base64_encode('delete') . '') }}" id="deleteca" class="dropdown-item"><i class="fa fa-trash-o"></i>
                                                    Delete</a>
                                                <a href="{{ url('assigned-complaints?id=' . base64_encode($glrow['id']) . '&action=' . base64_encode('pend') . '') }}" id="pendca" class="dropdown-item"><i class="fa fa-file-o"></i> Pend
                                                    Request</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach --}}
                        
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


    <div id="actionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actionModalLabel">Modal Heading</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="action-ontent">
                  
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
                    data.complaint_type = 'assigned-admin';
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
                            date.getFullYear() + ' ' 
                            // + date.getHours() + ':' +
                            // date.getMinutes() + ':' +
                            // date.getSeconds()
                            ;
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
                                    <a href="#viewStock"  data-id="${btoa(id)}" data-action="${btoa('audit')}" class="open-viewAuditTrail dropdown-item init-action"><i class="fa fa-history"></i>Audit Trail</a>
                                    <a href="#viewStock"  data-id="${btoa(id)}" data-action="${btoa('assign')}" class="init-action dropdown-item"><i class="fas fa-share"></i>Assign Request</a>
                                    <a href="#modal-dialog" data-id="${btoa(id)}" data-action="${btoa('complete')}" class="init-action dropdown-item"><i class="far fa-check-circle"></i> Complete Request</a>
                                    <a href="#" data-id="{${btoa(id)}" data-action="${btoa('delete')}" id="delete-action" class="dropdown-item delete-action"><i class="fa fa-trash-o"></i> Delete</a>
                                    <a href="#" data-id="{${btoa(id)}" data-action="${btoa('pend')}" id="pend-action" class="dropdown-item pend-action"><i class="far fa-dot-circle"></i> Pend Request</a>
                                </div>
                            </div>
                        `;
                    }
                }
            ]
        });
    });

  
    $(document).on("click", ".init-action", function(e) {
        e.preventDefault()

        var action = $(this).data('action');
        var id = $(this).data('id');

        $.ajax({
            type: 'POST',
            url: "{{route('customer.complaints.action.init')}}", //complete.php //assign.php
            data: {
                action: action,
                id: id
            },
            success: function(data) {
                $("#action-ontent").html(data);
                $('#actionModal').modal('show');

            }
        });
    });

    $(document).on('click', '.delete-action', function(e) {
        e.preventDefault();

        var action = $(this).data('action');
        var id = $(this).data('id');

        swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this record!",
            type: 'warning',
            icon:"warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete Complaint!'
        }).then(function(t) {
            if(t.value) {
                $.ajax({
                    url: "{{route('customer.complaints.handle.del-pend')}}",
                    type: 'POST',
                    data: {
                        action: action,
                        id: id
                    },
                    success: function(resp) {
                        if(resp.status == 'success'){
                            Swal.fire("Deleted!","Record deleted successfully.","success");
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    }); 

    $(document).on('click', '.pend-action', function(e) {
        e.preventDefault();

        var action = $(this).data('action');
        var id = $(this).data('id');

        swal.fire({
            title: 'Are you sure ?',
            text: "You are about to pend this record!",
            type: 'warning',
            icon:"warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Pend Complaint!'
        }).then(function(t) {
            if(t.value) {
                $.ajax({
                    url: "{{route('customer.complaints.handle.del-pend')}}",
                    type: 'POST',
                    data: {
                        action: action,
                        id: id
                    },
                    success: function(resp) {
                        if(resp.status == 'success'){
                            Swal.fire("Pended!","Record pended successfully.","success");
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    }); 

    $(document).on('submit','#assignForm', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "{{route('customer.complaints.handle.assign')}}", 
            method: 'POST',
            data: formData,
            success: function(resp) {
                // Handle the success response here
                if(resp.status == "success"){
                    toastr.success('Complaint assigned successfully');
                    $('#actionModal').modal('hide'); 
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle any errors here
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('submit','#completeForm', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "{{route('customer.complaints.handle.complete')}}", 
            method: 'POST',
            data: formData,
            success: function(resp) {
                // Handle the success response here
                if(resp.status == "success"){
                    toastr.success('Complaint closed successfully');
                    $('#actionModal').modal('hide'); 
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle any errors here
                console.error(xhr.responseText);
            }
        });
    });
</script>
  
@stop