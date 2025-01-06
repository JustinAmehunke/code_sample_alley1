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
                            <h4 class="card-title">Duration : {{$start_date}} - {{$end_date}}</h4>
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
                    {{-- <table class="table invoice-items"> --}}
                    <table id="datatable" class="table table-bordered dt-responsive wrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr class="">
                                <th>No.</th>
                                <th>Assigned To.</th>
                                <th>Request No.</th>
                                <th>Customer Name.</th>
                                <th>Phone Number</th>
                                <th>Email Address</th>
                                <th>Policy Number</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Classification</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Method of Contact</th>
                                <th>Notified By</th>
                                <th>Product Type</th>
                                <th>Process</th>
                                <th>Level</th>
                                <th>How Resolved</th>
                                <th>Note</th>
                                <th>Reported Date</th>
                                <th>Completed Date</th>
                                <th>Last Updated Date</th>
                                <th>Name of Capturer</th>
                                <th>Branch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($results)
                                @php
                                    $ttl_record = 0;
                                @endphp
                                @foreach ($results as $record)
                                    @php
                                        $ttl_record++;
                                    @endphp
                                    <tr>
                                        <td>{{ $ttl_record }}</td>
                                        <td>{{ getUserName($record->assigned_to) }}</td>
                                        <td>{{ $record->request_no }}</td>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->phone_number }}</td>
                                        <td>{{ $record->email }}</td>
                                        <td>{{ $record->policy_number }}</td>
                                        <td>{{ $record->tbl_complaints_categories?->name }}</td>
                                        <td>{{ $record->description }}</td>
                                        <td>{{ $record->tbl_complaint_classifications?->name }}</td>
                                        <td>{{ $record->tbl_application_status?->status_name }}</td>
                                        <td>{{ $record->reporting_channel ?? $record->tbl_complaint_reporting_channel?->name }}</td>
                                        <td>{{ $record->tbl_method_of_contact?->name }}</td>
                                        <td>{{ $record->tbl_notified_by?->name }}</td>
                                        <td>{{ $record->tbl_product_type?->name }}</td>
                                        <td>{{ $record->tbl_process?->name }}</td>
                                        <td>{{ $record->tbl_level?->name }}</td>
                                        <td>{{ $record->tbl_how_resolved?->name }}</td>
                                        <td>{{ $record->note }}</td>
                                        <td>{{ $record->createdon }}</td>
                                        <td>{{ $record->completed_date }}</td>
                                        <td>{{ $record->last_updated_date }}</td>
                                        <td>{{ $record->tbl_users?->full_name }}</td>
                                        <td>{{ $record->tbl_branch?->branch_name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="d-flex mr-lg" style="justify-content: space-between; margin-top: 40px;">
                       <div class="d-flex">
                        <p class="" style="margin-right: 5px;" colspan="3"><strong>TOTAL: </strong></p>
                        <p class=""><strong>{{ $ttl_record }}</strong></p>
                       </div>
                        <!-- <a href="#" class="btn btn-default">Submit Invoice</a> -->
                      <div>
                        <a href="#" target="_blank"
                        class="btn btn-info ml-sm"><i class="fa fa-print"></i> Print</a>
                        <a href="#" target="_blank"
                            class="btn btn-danger ml-sm"><i class="fa fa-file-excel-o"></i> Save As Excel</a>
                      </div>
    
                    </div>

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
    <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script>

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