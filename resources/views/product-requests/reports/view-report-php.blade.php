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
                <h4 class="mb-sm-0">Reports</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product Request</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
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
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Submissions Report</h4>
                            <h4 class="card-title">Duration : 2024-01-05 - 2024-01-16</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <p>No. 4 Dr. Paul A. Acquah Street Airport Residential Area Accra </p>
                                <p>Phone: 0307000600 </p>
                                <p>admin@oldmutual.com.gh </p>
                                <img src="/assets/images/logo_small.png" alt="logo-light" height="40">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="card-title-desc"> </p>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            {{-- <tr class="h6 text-dark">
                                <th class="text-weight-semibold">No.</th>
                                <th class="text-weight-semibold">Branch</th>
                                <th class="text-weight-semibold">Product</th>
                                <th class="text-weight-semibold">Last Updated Date</th>
                                <th class="text-weight-semibold">Proposal Number</th>
                                <th class="text-weight-semibold">Client Name</th>
                                <th class="text-weight-semibold">Client Phone</th>
                                <th class="text-weight-semibold">Created On</th>
                                <th class="text-weight-semibold">F.A Name</th>
                                <th class="text-weight-semibold">Report To</th>
                                <th class="text-weight-semibold">Pending Bin</th>
                                <th class="text-weight-semibold">Status</th>
                                <th class="text-weight-semibold">Mode</th>
                                <th class="text-weight-semibold">Last Updated Date</th>
                            </tr> --}}
                            <tr class="h6 text-dark">
                                <th id="cell-id" class="text-weight-semibold">No.</th>
                                <th id="cell-id" class="text-weight-semibold">Branch</th>
                                <th id="cell-id" class="text-weight-semibold">Product</th>
                                <th id="cell-id" class="text-weight-semibold">Last Updated Date</th>
                                <th id="cell-id" class="text-weight-semibold">Proposal Number</th>
                                <th id="cell-id" class="text-weight-semibold">Client Name</th>
                                <th id="cell-id" class="text-weight-semibold">Client Phone</th>
                                <th id="cell-item" class="text-weight-semibold">Created On</th>
                                <th id="cell-item" class="text-weight-semibold">F.A Name</th>
                                <th id="cell-item" class="text-weight-semibold">Report To</th>
                                <th id="cell-item" class="text-weight-semibold">Pending Bin</th>
                                <th id="cell-id" class="text-weight-semibold">Status</th>
                                <th id="cell-id" class="text-weight-semibold">Mode</th>
                                <th id="cell-id" class="text-weight-semibold">Last Updated Date</th>
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
        $(document).ready(function() {
            $('#datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('document.all.submissions.requests') }}",
                    type: "POST",
                    data: function (data) {
                        data.search = @json($search)
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
                        data:'branch_name',
                    },
                    {
                        data: 'product_name',
                    },
                    {
                        data: 'last_updated_date',
                        render: function(data, type, row, meta){
                            var lastUpdatedDate = row.last_updated_date;
                            var formattedDate = new Date(lastUpdatedDate);
                            // Convert to desired format
                            var formattedDateString = formattedDate.getDate() + '-' + 
                                                    formattedDate.toLocaleString('default', { month: 'short' }) + '-' + 
                                                    formattedDate.getFullYear() + ' ' + 
                                                    formattedDate.getHours() + ':' + 
                                                    formattedDate.getMinutes() + ':' + 
                                                    formattedDate.getSeconds();

                            return formattedDateString;
                        }
                    },
                    {
                        data: 'policy_no',
                    },
                    {
                        data: 'customer_name',
                    },
                    {
                        data: 'sms',
                    },
                    {
                        data: 'createdon',
                    },
                        {
                        data: 'full_name',
                    },
                    {
                        data: 'reports_full_name',
                    },
                    {
                        data: 'pending_dept',
                    },
                    {
                        data: 'status_name',
                    },
                    {
                        data: 'checklist_status_id',
                        render: function (data, type, row, meta){
                            if (row.checklist_mode) {
                                if ((row.checklist_mode === 1 || row.checklist_mode === 2) && row.checklist_status_id === 2) {
                                    return 'Manual Upload';
                                } else if ((row.checklist_mode === 3 || row.checklist_mode === 4) && row.checklist_status_id === 2) {
                                    return 'Online';
                                }
                                return 'Not Uploaded';
                            }
                            return 'Manual Upload';
                        }
                    },
                    {
                        data: 'last_updated_dat',
                    },
                ]
            });
        });

        
    </script>
  
@stop