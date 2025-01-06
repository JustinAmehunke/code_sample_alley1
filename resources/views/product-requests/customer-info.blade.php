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
    @if (session('message'))
        <div class="alert alert-{{ session('message')['type'] }}">
            <h4 class="card-title">{{ session('message')['type'] }}</h4>
            {{ session('message')['message'] }}
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
                    Customer Details 
                </h5> 

                <div class="card-body">
                    <form class="custom-validation" action="{{route('save-customer-info')}}" method="POST" novalidate="">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="policy_no" class="form-label">Policy Number</label>
                                    <input type="text" class="form-control" id="policy_no" name="policy_no"
                                     value="{{isset($record['policy_no']) ? $record['policy_no'] : 'Auto Generated' }}"  readonly>
                                   
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product" class="form-label">Product</label>

                                    @php
                                        $document_products = App\Models\DocumentProduct::where('deleted', 0)->get();
                                    @endphp
                                    <select class="form-select" id="tbl_documents_products_id" name="tbl_documents_products_id" required="">
                                        <option {{ !isset($record['tbl_documents_products_id']) ? "selected" : ""}}  disabled="" value="">Choose...</option>
                                        @foreach ($document_products as $document_product)
                                            <option value="{{$document_product->id}}"

                                                @if (isset($record['tbl_documents_products_id']) && $document_product->id == $record['tbl_documents_products_id'])
                                                    selected=""
                                                @endif
                                                >{{$document_product->product_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="override_status" class="form-label">Status</label>
                                    @php
                                        $appstatus = new App\Http\CustomClasses\ApplicationStatusClass(11, 16);
                                    @endphp
                                    <select class="form-select" name="override_status" id="override_status" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        @foreach ($appstatus->getStatusList() as $status)
                                            <option value="{{$status->id}}">{{$status->status_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Full Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    value="{{isset($record['customer_name']) ? $record['customer_name'] : ""}}"
                                     required="">
                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_mobile_no" class="form-label">Customer Mobile Number *</label>
                                    <input type="text" class="form-control phone-num" name="customer_mobile_no" id="customer_mobile_no" 
                                    pattern="[0]{1}[0-9]{9}" 
                                    title="Invalid phone number"
                                    value="{{isset($record['sms']) ? $record['sms'] : ''}}"
                                    required="">
                                    <span class="phone_number_invalid error-message">Invalid phone number</span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="text" class="form-control" name="email_address" id="email_address"
                                    value="{{isset($record['email']) ? $record['email'] : ''}}" 
                                    >
                                  
                                </div>
                            </div>
                            
                           
                        </div>
                       
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Save & Proceed</button>
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
        $(function(){
            $(document).on('input', '.phone-num', function() {
                const input = $(this);
                let inputValue = input.val().replace(/[^0-9]/g, "");
                
                if (inputValue.length > 10) {
                    inputValue = inputValue.slice(0, 10); // Truncate to 10 characters if longer
                }

                const isValid = inputValue.match(/^0[0-9]{9}$/);

                const errorMessage = input.next('.error-message');
                errorMessage.css('display', isValid && inputValue.length === 10 ? 'none' : 'block');

                input.val(inputValue); // Update the input value with the truncated value
            });
        })
    </script>
  
@stop