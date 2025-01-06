@extends('layouts.main-master')
@section('content')
    <style>
        .from-to-datepicker{
            padding: 0.47rem 0.1rem !important;
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
    <link href="{{asset('/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <div class="row">
        <div class="col-12">
             <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                 <h4 class="mb-sm-0">Proposals Rejected Report</h4>
 
                 <div class="page-title-right">
                     <ol class="breadcrumb m-0">
                         <li class="breadcrumb-item"><a href="javascript: void(0);">Product Request</a></li>
                         <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                         <li class="breadcrumb-item active">Proposals Rejected Report</li>
                     </ol>
                 </div>
 
             </div>
         </div>
     </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    Search Parameters
                </h5> 

                <div class="card-body">
                    <form class="" action="{{route('rejected-proposals-report')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product" class="form-label">Proposals Received By</label>
                                    @php
                                        $departments = App\Models\Department::where('deleted', 0)->get();
                                    @endphp
                                    <select class="form-select select2 select2-multiple" multiple="multiple" data-placeholder="Choose ..." id="dept" name="dept[]">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        @foreach ($departments as $department)
                                            <option value="{{$department->id}}">{{$department->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Date Range *</label>
                                    <div class="input-daterange input-group" id="datepicker6" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                        <span class="input-group-text"><i class="mdi mdi-calendar me-1"></i></span>
                                        <input type="text" class="form-control from-to-datepicker" name="start" placeholder="Start Date" required>
                                        <span class="input-group-text">To</span> 
                                        <input type="text" class="form-control from-to-datepicker" name="end" placeholder="End Date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product" class="form-label">Branch</label>

                                    @php
                                        $branches = App\Models\Branch::where('deleted', 0)->get();
                                    @endphp
                                    <select class="form-select select2 select2-multiple" multiple="multiple" data-placeholder="Choose ..." id="bn" name="bn[]">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value=''>ALL BRANCHES</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->branch_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product" class="form-label">Product *</label>

                                    @php
                                        $document_products = App\Models\DocumentProduct::all();
                                    @endphp
                                    <select class="form-select select2 select2-multiple" multiple="multiple" data-placeholder="Choose ..." id="prod"  name="prod[]">
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product" class="form-label">Uploaded By</label>

                                    @php
                                        $users = App\Models\User::whereNotIn('id', [Auth()->id()])->get();
                                    @endphp
                                    <select class="form-select select2 select2-multiple" multiple="multiple" data-placeholder="Choose ..." id="cby" name="cby[]">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value="">ALL</option>
                                        <option value="{{Auth()->id()}}">Me</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->full_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product" class="form-label">Document Status</label>

                                    @php
                                        $document_products = App\Models\DocumentProduct::all();

                                        $appstatus = new App\Http\CustomClasses\ApplicationStatusClass(11, 16)
                                    @endphp
                                    <select class="form-select select2 select2-multiple" multiple="multiple" data-placeholder="Choose ..." id="dtus" name="dtus[]">
                                        {{-- <option selected="" disabled="" value="">Choose...</option> --}}
                                        <option value=''>ALL</option>
                                        @foreach ($appstatus->getStatusList() as $status)
                                            <option value="{{$status->id}}">{{$status->status_name}}</option>
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

    <script src="{{asset('/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
   


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