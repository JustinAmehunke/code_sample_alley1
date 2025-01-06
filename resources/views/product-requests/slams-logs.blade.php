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
                    SLAMS Logs
                </h5> 

                <div class="card-body">
        
                    <h4 class="card-title">Default Datatable</h4>
                    <p class="card-title-desc"> </p>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Endpoint</th>
                                <th>Passed Info</th>
                                <th>SLAMS Response</th>
                                <th>Date/Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slamlogs as $key => $slamlog)
                                @php
                                    $response = json_decode($slamlog['response_message'], true);
                                @endphp
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ $slamlog['status'] }}</td>
                                    <td>{{ $slamlog['endpoint_url'] }}</td>
                                    <td>{{ $slamlog['request_message'] }}</td>
                                    <td>{{ isset($response['message']) && $response['message'] !== '' ? $response['message'] : $slamlog['response_message'] }}</td>
                                    <td>{{ date('d-M-Y H:i:s', strtotime($slamlog['createdon'])) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
           
        </div>
    </div>
   
{{-- </form> --}}

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