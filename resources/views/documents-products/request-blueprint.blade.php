<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>{{ $dynamicTitle }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Old Mutual Insurance" name="description" />
        <meta content="ShrinQ" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}"  />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
         <!-- Sweet Alert-->
         <link href="{{asset('/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
         <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/toastr/build/toastr.min.css')}}">

        <style>
            .bg-color-white{
                background-color: white !important;
            }
            .oml-btn {
                padding: 8px 20px;
                font-size: 15px;
                position: relative !important;
                font-family: 'Montserrat', sans-serif;
                cursor: pointer;
                font-weight: 500;
                line-height: 1.2;
                transition: all 0.3s ease 0s;
                border-radius: 23px;
            }
            .oml-btn-success {
                border: 0;
                background-color: #049774;
                color: #fff;
                background: linear-gradient(270deg, #60B848 1.64%, #009677 98.36%);
            }
            .oml-btn-success:hover {
                color: #fff;
                background-image: linear-gradient(to left, #049774, #44a858 22%, #7db840);
            }

            .oml-btn-success:hover {
                color: #fff;
                background-color: #3bb347;
                border-color: #38a943;
            }
            .request-header{
                color: #ffff;
                background-image: url('/assets/images/old-mutual-header.png')
            }
            .bg-overlay{
                color: #ffff;
                background-image: url('/assets/images/form_background.jpg');
                background-color: #fff !important;
                background-size: contain;
                background-repeat: no-repeat;
                /* background-size: 15px 15px; */
            }
            .card-shadow{
                margin-bottom: 24px;
                -webkit-box-shadow: 1px 0 20px rgba(0,0,0,.05);
                box-shadow: 1px 0 20px rgba(0,0,0,.05);
            }

        </style>

        <style>
            .form-group input:focus,.form-group select:focus  {
                border-bottom: 3px solid;
                border-bottom-color: #50b848;
                outline: none;
            }
            .form-group input, .form-group select  {
                border-bottom: 1px solid;
                border-bottom-color:  #b8bdc2; /* #ced4da */
                
            }
            .form-group input, .form-group select, .col-md-12 select, .mb-3 select, .mb-3 input {
                border: none;
                border-bottom: 1px solid #b8bdc2; /* #ced4da */
            }
            .form-check-input{
                border: 1px solid rgba(0, 0, 0, .25) !important;
            }
            /* .form-label {
                margin-bottom: .3rem !important;
            } */

            .select-container {
                position: relative;
                display: inline-block;
            }

            .select-container img {
                position: absolute;
                left: 5px; /* Adjust the left position as needed */
                top: 50%;
                transform: translateY(-50%);
            }

            .select-container select {
                padding-left: 40px; /* Adjust the padding to make space for the image */
                /* Add any other styling for the select input */
            }
            .parsley-error {
                border-bottom: 2px solid #f32f53 !important;
            }
        </style>
    </head>

    <body class="bg-color-white">
        <div class="bg-overlay"></div>
        <span class="logo-lg" style=" margin-top: 200px;
        position: absolute;">
            <img src="/assets/images/paralax_image_1.png" alt="logo-dark" height="250">
        </span>
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('/assets/images/om-cust-logo.PNG') }}" alt="logo-sm" height="90">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('/assets/images/om-cust-logo.PNG') }}" alt="logo-dark" height="90">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('/assets/images/om-cust-logo.PNG') }}" alt="logo-sm-light" height="90">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('/assets/images/om-cust-logo.PNG') }}" alt="logo-light" height="90">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
       <div class="d-flex justify-content-center">
        <div class="col-md-8">
            <div class="container-fluid p-0">
                @if ($record)
                    @php
                        // $param_id =  request()->query('id');
                        $param_id =  base64_encode($record->id);
                        $param_section = request()->query('section');
                        $token = request()->query('token');
                        //
                        $document_product_id = ($mandate == 'Mandate Request') ? 5 : $record->tbl_documents_products_id;
                        $document_applications_id = $record->id;
                        $policy_no = $record->policy_no;
                        $existing_record = $product_model::where('tbl_document_applications_id', $document_applications_id)->get();
                    @endphp
                     {{-- Production DB Structure
                     1|EDUCATOR PLAN          
                     2|DEATH CLAIM            
                     3|CLAIM REQUEST          
                     4|TERM ASSURANCE         
                     5|MANDATE REQUEST        
                     6|PERSONAL ACCIDENT      
                     7|REFUND REQUEST         
                     8|SPECIAL INVESTMENT PLAN
                     9|TRANSITION PLUS PLAN   
                    10|TRANSITION             
                    11|TRAVEL INSURANCE       
                    12|KEY MAN                
                    13|NIC COMMUNICATIONS     
                    14|RETAIL SALES COMPLAINT 
                    15|FIDO SIP               
                    16|CORPORATE DUE DILIGENCE --}}

                    @switch($document_product_id)

                    @case(1) {{-- EDUCATOR PLAN --}}
                        @include('documents-products.products.educator')
                            @break
                    @case(2) {{-- DEATH CLAIM --}}
                        @include('documents-products.products.death-claim')
                            @break
                    @case(3) {{-- CLAIM REQUEST --}}
                        @include('documents-products.products.claim-request')
                            @break
                    @case(4) {{-- TERM ASSURANCE --}}
                        @include('documents-products.products.term-assurance')
                            @break
                    @case(5) {{-- MANDATE REQUEST --}}
                        @include('documents-products.products.mandate')
                            @break
                    @case(6) {{-- PERSONAL ACCIDENT --}}
                        @include('documents-products.products.personal-accident')
                            @break
                    @case(7) {{-- REFUND REQUEST --}}
                        @include('documents-products.products.refund-request')
                            @break
                    @case(8) {{-- SPECIAL INVESTMENT PLAN --}}
                        @include('documents-products.products.sip')
                            @break
                    @case(9) {{-- TRANSITION PLUS PLAN --}}
                        @include('documents-products.products.tpp')
                            @break
                    @case(10) {{-- TRANSITION --}}
                        @include('documents-products.products.transition')
                            @break
                    @case(11) {{-- TRAVEL INSURANCE --}}
                        @include('documents-products.products.travel-insurance')
                            @break
                    @case(12) {{-- KEY MAN --}}
                        @include('documents-products.products.keyman')
                            @break
                    @case(15) {{-- FIDO SIP --}}
                        @include('documents-products.products.fidosip')
                            @break
                    @case(16) {{-- CORPORATE DUE DILIGENCE --}}
                        @include('documents-products.products.corporate')
                            @break
                    @default
                        @include('documents-products.products.invalid-product')
                    @endswitch
                @else
                    @include('documents-products.products.invalid-product')
                @endif
            </div>
            <!-- end container -->
        </div>
       </div>
        <!-- end -->
         <!-- Modal -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="print-cont">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title" id="myLargeModalLabel">Large modal</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="$('.bs-example-modal-lg').modal('hide');" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="previewContent">
        
                    </div>
                    <div class="d-print-none" style="text-align: center; ">
                        <div class="" style="padding: 5px;">
                            {{-- <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a> --}}
                            <a href="javascript:void(0)" onclick="printContent()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
        
                            {{-- <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Send</a> --}}
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- Right bar overlay-->
        <div class="rightbar-overlay">
            <div  style="  position: fixed; top: 0; left: 0; width: 100%;height: 100%; display: flex;
            justify-content: center;
            align-items: center;">
                <div style="text-align: center">
                    <div class="spinner-border text-primary m-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div style="color: white;" >
                        <span id="loading-msg"></span>
                    </div>
                </div>
            </div>
        </div>
         <!-- Verify Modal -->
        <div class="modal fade" id="verificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verificationModalLabel">Customer Verification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <form class="custom-validation" id="verifyPhoneNumber" novalidate=""> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone-number-verify" name="phone_number"  required="" readonly
                                        pattern="[0]{1}[0-9]{9}" 
                                        title="Invalid phone number"
                                        >
                                        <ul class="parsley-errors-list filled" id="verify-phone-error" style="display: none;" aria-hidden="false">
                                            <li class="parsley-required">This value is required.</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="form-label">Network</label>
                                        <select class="form-select" id="network-verify" name="operator" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option value="mtn">MTN</option>
                                            <option value="vodafone">Vodafone</option>
                                            <option value="airteltigo">AirtelTigo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary" id="verify-phone-number" type="button">Verify</button>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-danger" id="manually_flag_verify-phone-number" type="button">Manually Flag & Skip</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                          
                            {{-- <div >
                                <button class="btn btn-primary" type="submit">Verify</button>
                            </div> --}}
                        {{-- </form> --}}
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light">Verify</button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="modal fade" id="momoVerificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="momoVerificationModalLabel">Customer Verification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <form class="custom-validation" id="verifyPhoneNumber" novalidate=""> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="wallet_number-verify" name="phone_number"  required="" readonly
                                        pattern="[0]{1}[0-9]{9}" 
                                        title="Invalid phone number"
                                        >
                                        <ul class="parsley-errors-list filled" id="verify-wallet_number-error" style="display: none;" aria-hidden="false">
                                            <li class="parsley-required">This value is required.</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="form-label">Network</label>
                                        <select class="form-select" id="wallet_network-verify" name="operator" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option value="mtn">MTN</option>
                                            <option value="vodafone">Vodafone</option>
                                            <option value="airteltigo">AirtelTigo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary" id="verify-wallet_number" type="button">Verify</button>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-danger" id="manually_flag_verify-wallet_number" type="button">Manually Flag & Skip</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                          
                            {{-- <div >
                                <button class="btn btn-primary" type="submit">Verify</button>
                            </div> --}}
                        {{-- </form> --}}
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light">Verify</button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="modal fade" id="id-verificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="id-verificationModalLabel">Customer Verification</h5>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        {{-- <form class="custom-validation" id="verifyPhoneNumber" novalidate=""> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="form-label">ID Type</label>
                                        <select class="form-select validate-id" name="id-type-verify" data-idnum="id-number-verify" id="id-type-verify" required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option value="voter-id">Voter ID</option>
                                            <option value="ghana-card">Ghana Card</option>
                                            <option value="passport">Passport</option>
                                            <option value="voter-old">Old Voter ID</option>
                                            <option value="ssnit">SSNIT</option>
                                            <option value="drivers-license">Driver's License</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">ID Number</label>
                                        <input type="text" class="form-control" id="id-number-verify" name="id_number"  required=""
                                        title="Invalid ID number"
                                        >
                                        <ul class="parsley-errors-list filled" id="verify-id-num-error" style="display: none;" aria-hidden="false">
                                            <li class="parsley-required" id="verify-id-num-error-msg">This value is required.</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary" id="verify-id-number" type="button">Verify</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            {{-- <div >
                                <button class="btn btn-primary" type="submit">Verify</button>
                            </div> --}}
                        {{-- </form> --}}
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light">Verify</button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="modal fade" id="upload-verified-id" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="upload-verified-idLabel">Upload Verified ID</h5>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <form class="custom-validation" id="id-file-form" novalidate="" enctype="multipart/form-data">
                            <div class="row">
                               
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Verified ID</label>
                                        <input type="file" class="form-control" id="id-file" name="id_file"
                                        accept=".pdf, image/*" required=""
                                        title="Invalid ID number"
                                        >
                                        <ul class="parsley-errors-list filled" id="id-file-error" style="display: none;" aria-hidden="false">
                                            <li class="parsley-required" id="id-file-error-msg">This value is required.</li>
                                        </ul>
                                        <input type="hidden" name="token" value="{{$record->token}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary" id="upload-id" type="button">Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light">Verify</button>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="modal fade" id="acc_bank-verified" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acc_bank-verifiedLabel">Bank Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="custom-validation" id="id-file-form" novalidate="" enctype="multipart/form-data">
                            <div class="row">
                               
                            <div class="form-group">
                                <label class="form-label" for="payment_bank_name">Select Bank *:</label>
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <select name="acc_bank_code" id="acc_bank_code" class="form-select">
                                        <option value="0">N/Ao</option>
                                        <option value="ACB">ACCESS BANK (ACB)</option>
                                        <option value="ADB">ADB (ADB)</option>
                                        <option value="BOA">BANK OF AFRICA (BOA)</option>
                                        <option value="BBG">ABSA (BBG)</option>
                                        <option value="CAL">CAL BANK (CAL)</option>
                                        <option value="ECB">ECOBANK (ECB)</option>
                                        <option value="ENE">ENERGY BANK (ENE)</option>
                                        <option value="FBN">FBN BANK (FBN)</option>
                                        <option value="FID">FIDELITY BANK (FID)</option>
                                        <option value="FAB">FIRST ATLANTIC BANK (FAB)</option>
                                        <option value="FNB">FIRST NATIONAL BANK (FNB)</option>
                                        <option value="GCB">GCB BANK (GCB)</option>
                                        <option value="GTB">GUARANTEE TRUST BANK (GTB)</option>
                                        <option value="REP">REPUBLIC BANK (REP)</option>
                                        <option value="NIB">NATIONAL INVESTMENT BANK (NIB)</option>
                                        <option value="PRU">PRUDENTIAL BANK (PRU)</option>
                                        <option value="SOC">SOCIETE GENERAL (SOC)</option>
                                        <option value="STA">STANBIC BANK (STA)</option>
                                        <option value="STB">STANDARD CHATERED BANK (STB)</option>
                                        <option value="UBA">UNITED BANK FOR AFRICA (UBA)</option>
                                        <option value="UMB">UNIVERSAL MERCHANT BANK (UMB)</option>
                                        <option value="ZEN">ZENITH BANK (ZEN)</option>
                                        <option value="OMN">OMNI BSIC BANK (OMN)</option>
                                        <option value="BOG">BANK OF GHANA (BOG)</option>
                                        <option value="ARB">ARB APEX BANK (ARB)</option>
                                        <option value="CBG">CBG (CBG)</option>
                                        <option value="GHL">GHL BANK (GHL)</option>                                    
                                    </select>
                                    <ul class="parsley-errors-list filled" id="verify-id-num-error" style="display: none;" aria-hidden="false">
                                        <li class="parsley-required" id="verify-id-num-error-msg">This value is required.</li>
                                    </ul>
                                    <input type="hidden" id="acc_bank_number" name="acc_bank_number">
                                </div>
                            </div>
                                <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary" id="verify-acc-number" type="button">Verify</button>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="mt-3 text-center">
                                            <button class="btn btn-danger" id="manually_flag_verify-acc-number" type="button">Manually Flag & Skip</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light">Verify</button>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

          <!-- Sweet Alerts js -->
          <script src="{{asset('/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
           <!-- toastr plugin -->
        <script src="{{asset('/assets/libs/toastr/build/toastr.min.js')}}"></script>
        <!-- toastr init -->
        {{-- <script src="{{asset('/assets/js/pages/toastr.init.js')}}"></script> --}}

        <script src="{{asset('assets/js/app.js')}}"></script>

        @yield('application-status-script')

        <script>
            $(document).on('click', '.previewProposal', function(){
                let token = $(this).data('token');
                $('#previewContent').empty();

                $.ajax({
                    url: '/document/preview-proposal/' + token,
                    type: 'GET',
                    success: function(response) {
                        // Update modal content with the retrieved preview content
                        $('#previewContent').html(response.previewContent);
                        let privacy_disclosure = $("#privacy_disclosure").clone().html();
                        $('#previewContent').append(privacy_disclosure);
                        // Show the modal
                        $('.bs-example-modal-lg').modal('show');
                    },
                    error: function(xhr, status, error) {
                        // Handle error if any
                        console.error(error);
                    }
                });
            })
            $(document).on('click', '.previewMandate', function(){
                let token = $(this).data('token')
                $('#previewContent').empty();

                $.ajax({
                    url: '/document/preview-mandate/' + token,
                    type: 'GET',
                    success: function(response) {
                        // Update modal content with the retrieved preview content
                        $('#previewContent').html(response.previewContent);
                        // Show the modal
                        $('.bs-example-modal-lg').modal('show');
                    },
                    error: function(xhr, status, error) {
                        // Handle error if any
                        console.error(error);
                    }
                });
            })

            $(document).on('change', '.validate-id', function(){
                let input_id = $(this).data('idnum');
                let val = $(this).val();

                console.log(input_id + val);
                document.getElementById(input_id).value = "";

                if (val == "drivers-license") {
                    document.getElementById(input_id).setAttribute('maxLength', '18');
                } else if (val == "ghana-card") {
                    document.getElementById(input_id).setAttribute('maxLength', '15');
                } else if (val == "passport") {
                    document.getElementById(input_id).setAttribute('maxLength', '8');
                } else if (val == "voter-id") {
                    document.getElementById(input_id).setAttribute('maxLength', '10');
                } else if (val == "voter-old") {
                    document.getElementById(input_id).setAttribute('maxLength', '10');
                } else if (val == "ssnit") {
                    document.getElementById(input_id).setAttribute('maxLength', '20');
                }
            })

            function printContent() {
                var printDiv = document.getElementById("print-cont");
                var printContents = printDiv.innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>

        <script>
            $(function(){
                // $('#verificationModal').modal('show');
                // Swal.fire("Generated!", "Proposal and Mandate Forms generated successfully", "success");
                // Swal.fire("Failed!", "Unable to generate documents. Generate from Dashboard", "error");

                // $('input[readonly]').hover(function() {
                // // Change cursor to not-allowed when hovering over the readonly input
                //     $(this).css('cursor', 'not-allowed');
                // }, function() {
                //     // Restore cursor to default when leaving the readonly input
                //     $(this).css('cursor', 'default');
                // });
                $(document).on('change', '.watch', function() {
                    //save the last verified number in storage and compare with the modified if not the same use must run check 
                    // Get the value of the changed input
                    var newValue = $(this).val();
                    console.log('Input value changed to: ' + newValue);
                    // You can perform any actions you want based on the new value here
                });

                
                
            })
            let proceed = 3;
            let payment_proceed = 3;

            let id_checked = false;

            @if(isset($record))
                let doc_appl_id = @json(base64_encode($record->id));
            @endif
            let attempt = 0;
            $(document).on('click', '#verify-id-number', function(){
                let id_type = $('#id-type-verify').val();
                let id_number = $('#id-number-verify').val();

                console.log(id_type);
                if (typeof id_type == 'undefined' || id_type == null ) {
                    $('#id-type-verify').css('border-color', 'red');
                    $('#id_type-error').css('display', 'block');

                    setTimeout(() => {
                        $('#id-type-verify').css('border-color', '#ced4da');
                        $('#id_type-error').css('display', 'none');
                    }, 3000);
                    return;
                }

                let err = false;
                const idLengthMap = {
                    "drivers-license": 18,
                    "ghana-card": 15,
                    "passport": 8,
                    "voter-id": 10,
                    "voter-old": 10,
                    "ssnit": 20
                };
                const expectedLength = idLengthMap[id_type];
                if (id_number.length !== expectedLength) {
                    err = true;
                }

                if (id_number == '') {
                    $('#id-number-verify').css('border-color', 'red');
                    $('#verify-id-num-error-msg').html('This value is required.');
                    $('#verify-id-num-error').css('display', 'block');

                    setTimeout(() => {
                        $('#id-number-verify').css('border-color', '#ced4da');
                        $('#verify-id-num-error').css('display', 'none');
                    }, 3000);
                    return;
                }

                if (err) {
                    $('#id-number-verify').css('border-color', 'red');
                    $('#verify-id-num-error-msg').html('Invalid ID Number.');
                    $('#verify-id-num-error').css('display', 'block');

                    setTimeout(() => {
                        $('#id-number-verify').css('border-color', '#ced4da');
                        $('#verify-id-num-error').css('display', 'none');
                    }, 3000);
                    return;
                }

                if(id_number && typeof id_type !== 'undefined' && id_type !== null){
                 $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: '/document/product/request/verify/id-number',
                        type: 'POST',
                        data: {'id_type': id_type, 'id_number': id_number},
                        dataType: 'json',
                        success: function(resp) {
                            console.log(resp);
                            if(resp.data.code == 200){    //  if(resp.data.data.name){ //bring back after temporary solution
                                id_checked = true;
                                // bring back after temporary solution
                                let full_name = resp.data.firstName +' '+resp.data.middleName+' '+resp.data.lastName;
                                let first_name = resp.data.firstName;
                                let last_name = resp.data.lastName;
                                let middle_name = resp.data.middleName;
                                let id_gender = resp.data.gender;
                                let idNumber = resp.data.idNumber;
                                
                                dateOfBirth = resp.data.dateOfBirth.replace(/\\+/g, '');
                                let parts = dateOfBirth.split("/");
                                let reformattedDate = parts[2] + "-" + parts[1] + "-" + parts[0]; // Reformat to YYYY-MM-DD


                                ////////////////// switch to above after temporarly fix
                                // let full_name = resp.data.data.name.trim(); // Remove leading and trailing spaces
                                // // Split the full name into parts
                                // let name_parts = full_name.split(' ');
                                // // Extract the first name
                                // let first_name = name_parts[0];
                                // // Extract the last name
                                // let last_name = name_parts.pop();
                                // // Check if a middle name exists
                                // let middle_name = '';
                                // if (name_parts.length > 1) {
                                //     // Join the remaining parts to get the middle name
                                //     middle_name = name_parts.slice(1).join(' ');
                                // }
                                //
                                // let id_gender =  resp.data.data.gender;
                                //end of temp fix
                                // let idNumber = id_number;
                                // 
                                //format dob
                                // Input date string
                                // let dateString = resp.data.data.dateOfBirth;

                                // Split the date string into parts
                                // let parts = dateString.split(' ');

                                // Extract year, month, and day
                                // let year = parts[0];
                                // let month = parts[1];
                                // let day = parts[2];
                                // // Convert month to numeric value
                                // let monthNumber = new Date(Date.parse(month + " 1, 2000")).getMonth() + 1; // Adding 1 since months are zero-based
                                // // Pad day with leading zero if needed
                                // day = day.length === 1 ? '0' + day : day;
                                // // Format the date as YYYY-MM-DD
                                // let reformattedDate = `${year}-${monthNumber.toString().padStart(2, '0')}-${day}`;
                                
                                ////////////////end

                                $('#my_name').val(full_name);
                                $('#prefill-firstname').val(first_name);
                                $('#prefill-surname').val(last_name);
                                $('#prefill-othernames').val(middle_name);
                               

                                $('#gender-cont').empty();
                                $('#gender-cont').append(`
                                    <input class="form-control" type="text" id="prefill-gender" name="gender" readonly />
                                `);
                                $('#id-type_cont').empty();
                                $('#id-type_cont').append(`
                                    <input class="form-control" type="text" id="prefill-id_type" name="id_type" value="" readonly
                                                    required />
                                    <ul class="parsley-errors-list filled" id="id_type-error" style="display: none;" aria-hidden="false">
                                        <li class="parsley-required">This value is required.</li>
                                    </ul>
                                 `);

                                $('#prefill-id_type').val(resp.id_selected);
                                $('#prefill-id_number').val(idNumber);

                                if(id_gender == 'male'  || id_gender == 'Male'){
                                    $('#prefill-gender').val('Male');
                                }else{
                                    $('#prefill-gender').val('Female');
                                }

                                //Use formatted dob
                                $('#date_of_birth').val(reformattedDate);
                                //
                                let date_of_birth = $('#date_of_birth').val();
                                if(date_of_birth && typeof date_of_birth !== 'undefined' && date_of_birth !== null){
                                    var newdate = date_of_birth.split("/").reverse().join("-");
                                    dob = new Date(newdate);
                                    var today = new Date();
                                    var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
                                    $("input#age").val(age);
                                }
                                $('div#show_age').show();

                                $('.rightbar-overlay').css('display', 'none');
                                $('#id-verificationModal').modal('hide');
                                toastr.success('ID Verified successfully!');
                                //show upload
                                // $('#upload-verified-id').modal('show');
                                // id_checked = true;
                            }else{
                                attempt++;
                                console.log('attempt '+ attempt);
                                if(attempt >= 3){
                                    id_checked = true;
                                    $('#gender-cont').empty();
                                    $('#gender-cont').append(`
                                        <select id="manual-gender" name="gender" class="form-select" required>
                                            <option value=""></option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    `);
                                    $('#id-type_cont').empty();
                                    $('#id-type_cont').append(`
                                        <select id="id_type" name="id_type" class="form-select" required readonly
                                            onchange="setIDRestriction(this, 'id_number')">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option value="Ghana Card">Ghana Card</option>
                                            <option value="Drivers License">Driver's License</option>
                                            <option value="Passport">Passport</option>
                                            <option value="Voter ID">Voter ID</option>
                                            <option value="SSNIT">SSNIT</option>
                                        </select> 
                                    `);
                                   
                                    //claim
                                    $('#my_name').removeAttr('readonly');
                                    //
                                    $('#prefill-firstname').removeAttr('readonly');
                                    $('#prefill-surname').removeAttr('readonly');
                                    $('#prefill-othernames').removeAttr('readonly');
                                    // $('#prefill-id_type').removeAttr('readonly');
                                    $('#prefill-id_number').removeAttr('readonly');
                                    // $('#prefill-gender').removeAttr('readonly');
                                    $('#date_of_birth').removeAttr('readonly');

                                    $('.rightbar-overlay').css('display', 'none');
                                    $('#id-verificationModal').modal('hide');
                                    toastr.error('Unable to verify ID. Please continue manually.', 'Sorry!');
                                    $('#upload-verified-id').modal('show');
                                    // Pass the variable to JavaScript
                                    let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                   
                                    $.ajax({
                                        url: '/document/product/request/flag',
                                        type: 'POST',
                                        data: {'token': token, 'message': " Unable to verify ID,"},
                                        dataType: 'json',
                                        success: function(resp) {
                                            
                                        }
                                    });
                                }else{
                                    $('#id_number').val('');
                                    $('.rightbar-overlay').css('display', 'none');
                                    id_checked = false;
                                    toastr.error('Unable to verify ID! Please try again.1');
                                }         
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            attempt++;
                            console.log('attempt '+ attempt);
                            if(attempt >= 3){
                                id_checked = true;
                                $('#gender-cont').empty();
                                $('#gender-cont').append(`
                                    <select id="manual-gender" name="gender" class="form-select" required>
                                        <option value=""></option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                `);
                                $('#id-type_cont').empty();
                                    $('#id-type_cont').append(`
                                        <select id="id_type" name="id_type" class="form-select" required readonly
                                            onchange="setIDRestriction(this, 'id_number')">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option value="Ghana Card">Ghana Card</option>
                                            <option value="Drivers License">Driver's License</option>
                                            <option value="Passport">Passport</option>
                                            <option value="Voter ID">Voter ID</option>
                                            <option value="SSNIT">SSNIT</option>
                                        </select> 
                                `);
                                $('#my_name').removeAttr('readonly');
                                $('#prefill-firstname').removeAttr('readonly');
                                $('#prefill-surname').removeAttr('readonly');
                                $('#prefill-othernames').removeAttr('readonly');
                                // $('#prefill-id_type').removeAttr('readonly');
                                $('#prefill-id_number').removeAttr('readonly');
                                // $('#prefill-gender').removeAttr('readonly');
                                $('#date_of_birth').removeAttr('readonly');

                                $('.rightbar-overlay').css('display', 'none');
                                $('#id-verificationModal').modal('hide');
                                toastr.error('Unable to verify ID. Please continue manually.', 'Sorry!');
                                $('#upload-verified-id').modal('show');

                                 // Pass the variable to JavaScript
                                 let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                   
                                $.ajax({
                                    url: '/document/product/request/flag',
                                    type: 'POST',
                                    data: {'token': token, 'message': " Unable to verify ID,"},
                                    dataType: 'json',
                                    success: function(resp) {
                                        proceed = 1;
                                        toastr.error('Request flagged. Please proceed','Verification Failed');
                                    }
                                });
                            }else{
                                $('#id_number').val('');
                                $('.rightbar-overlay').css('display', 'none');
                                id_checked = false;
                                toastr.error('Unable to verify ID! Please try again.');
                            }

                          
                        }
                    });
                } 
                // else {
                //     $('#network-verify').css('border-color', 'red');
                //     $('#phone-number-verify').css('border-color', 'red');
                // }
            });

            $(document).on('click', '#init-verify-phone-number', function(){
                let val = $('#mobile').val() ?? $('#mobile_no').val(); 
                let pattern = /^[0-9]{10}$/

                if (!pattern.test(val)) {
                    $('#mobile').css('border-color', 'red');
                    $('.verify-mobile-error-msg').html('Invalid Mobile Number.');
                    $('#verify-mobile-error').css('display', 'block');

                    setTimeout(() => {
                        $('#mobile').css('border-color', '#ced4da');
                        $('#verify-mobile-error').css('display', 'none');
                    }, 3000);

                    return;
                }

                if(val){
                    $('#phone-number-verify').val(val);
                    $('#verificationModal').modal('show');
                }
               
            });

            $(document).on('click', '#init-verify-payment-phone-number', function(){
                let val = $('#wallet_number').val();
                let pattern = /^[0-9]{10}$/

                if (!pattern.test(val)) {
                    $('#wallet_number').css('border-color', 'red');
                    $('.verify-wallet_number-error-msg').html('Invalid Momo Number.');
                    $('#verify-wallet_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#wallet_number').css('border-color', '#ced4da');
                        $('#verify-wallet_number-error').css('display', 'none');
                    }, 3000);

                    return;
                }

                if(val){
                    $('#wallet_number-verify').val(val);
                    $('#momoVerificationModal').modal('show');
                }
               
            });

            let phone_attempt = 0;
            $(document).on('click', '#verify-phone-number', function(){
                phone_attempt++;
                let operator = $('#network-verify').val();
                let phone_num = $('#phone-number-verify').val();
                // let pattern = /[0]{1}[0-9]{9}/;
                let pattern = /^[0-9]{10}$/
                console.log(operator);
                if(typeof operator == 'undefined' || operator == null){
                  $('#network-verify').css('border-color', 'red');
                  return ;
                }

                if (!pattern.test(phone_num)) {
                    $('#phone-number-verify').css('border-color', 'red');
                    $('#verify-phone-error').css('display', 'block');

                    setTimeout(() => {
                        $('#phone-number-verify').css('border-color', '#ced4da');
                        $('#verify-phone-error').css('display', 'none');
                    }, 3000);

                    return;
                }

                

                $('#phone-number-verify').css('border-color', '#ced4da');
                $('#verify-phone-error').css('display', 'none');

                if(phone_num && typeof operator !== 'undefined' && operator !== null){
                 $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: '/document/product/request/verify/phone-number',
                        type: 'POST',
                        data: {'phone_number': phone_num, 'operator': operator},
                        dataType: 'json',
                        success: function(resp) {
                            $('#verificationModal').modal('hide');
                            $('.rightbar-overlay').css('display', 'none');

                            if(resp.status == 'success'){
                                proceed = 1;
                                toastr.success('Phone number verified successfully!');
                                //
                                localStorage.setItem('phone_num', phone_num);
                            }else if(resp.data.status == "success" && attempt >= 3){
                                proceed = 1
                                toastr.success('Phone number verified successfully! Could not verify against ID');
                                //
                                localStorage.setItem('phone_num', phone_num);
                            }
                            // else if(attempt >= 3){
                            //     proceed = 1
                            //     toastr.success('Phone number verified successfully!');
                            //     //
                            //     localStorage.setItem('phone_num', phone_num);
                            // }
                            else{
                                proceed = 2;
                                if(phone_attempt >=3){
                                    $('#verificationModal').modal('hide');
                                    // Pass the variable to JavaScript
                                    let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                    
                                    $.ajax({
                                        url: '/document/product/request/flag',
                                        type: 'POST',
                                        data: {'token': token, 'message': " Unable to verify Phone number,"},
                                        dataType: 'json',
                                        success: function(resp) {
                                            proceed = 1;
                                            localStorage.setItem('phone_num', phone_num);
                                            toastr.error('Request flagged. Please proceed','Verification Failed');
                                        }
                                    });
                                }else{
                                    toastr.error('Unable to verify Phone number! Please try again.');
                                }
                               
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            $('#verificationModal').modal('hide');
                            $('.rightbar-overlay').css('display', 'none');
                            proceed = 2;
                            if(phone_attempt >=3){

                                // Pass the variable to JavaScript
                                let token = @json(isset($record) && isset($record->token) ? $record->token : null);

                                $.ajax({
                                    url: '/document/product/request/flag',
                                    type: 'POST',
                                    data: {'token': token, 'message': " Unable to verify Phone number,"},
                                    dataType: 'json',
                                    success: function(resp) {
                                        proceed = 1;
                                        localStorage.setItem('phone_num', phone_num);
                                        toastr.error('Request flagged. Please proceed','Verification Failed');
                                    }
                                });
                            }else{
                                toastr.error('Unable to verify Phone number! Please try again.');
                            }
                        }
                    });
                } 
                // else {
                //     $('#network-verify').css('border-color', 'red');
                //     $('#phone-number-verify').css('border-color', 'red');
                // }
            });

            let momo_attempt = 0;
            $(document).on('click', '#verify-wallet_number', function(){
                momo_attempt++;

                if (localStorage.getItem('account_number')) {
                    localStorage.removeItem('account_number');
                }
                let wallet_network = $('#wallet_network-verify').val();
                let wallet_number = $('#wallet_number-verify').val();
                // let pattern = /[0]{1}[0-9]{9}/;
                let pattern = /^[0-9]{10}$/
                console.log(wallet_network);
                if(typeof wallet_network == 'undefined' || wallet_network == null){
                  $('#network-verify').css('border-color', 'red');
                  return ;
                }

                if (!pattern.test(wallet_number)) {
                    $('#wallet_number-verify').css('border-color', 'red');
                    $('#verify-wallet_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#wallet_number-verify').css('border-color', '#ced4da');
                        $('#verify-wallet_number-error').css('display', 'none');
                    }, 3000);

                    return;
                }

                

                $('#wallet_number-verify').css('border-color', '#ced4da');
                $('#verify-wallet_number-error').css('display', 'none');

                if(wallet_number && typeof wallet_network !== 'undefined' && wallet_network !== null){
                 $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: '/document/product/request/verify/phone-number',
                        type: 'POST',
                        data: {'phone_number': wallet_number, 'operator': wallet_network, 'doc_appl_id' : doc_appl_id},
                        dataType: 'json',
                        success: function(resp) {
                            $('#momoVerificationModal').modal('hide');
                            $('.rightbar-overlay').css('display', 'none');

                            if(resp.status == 'success' || resp.data.status == 'success'){
                                console.log('yes yes');
                                // proceed = 1;
                                payment_proceed = 1;
                                // id_checked = true;
                                $('#wallet_name').val(resp.data.name);
                                toastr.success('Momo number verified successfully!');
                                //
                                localStorage.setItem('wallet_number', wallet_number);
                            }else{
                                // proceed = 2;
                                payment_proceed = 2;

                                if(momo_attempt >= 3){
                                    // Pass the variable to JavaScript
                                    let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                    
                                    $.ajax({
                                        url: '/document/product/request/flag',
                                        type: 'POST',
                                        data: {'token': token, 'message': " Unable to verify Momo number,"},
                                        dataType: 'json',
                                        success: function(resp) {
                                            payment_proceed == 1
                                            toastr.error('Request flagged. Please proceed','Verification Failed');
                                        }
                                    });
                                }else{
                                    toastr.error('Unable to verify Momo number! Please try again.');
                                }
                               
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            $('#momoVerificationModal').modal('hide');
                            $('.rightbar-overlay').css('display', 'none');
                            // proceed = 2;
                            payment_proceed = 2;
                           
                            if(momo_attempt >= 3){
                                // Pass the variable to JavaScript
                                let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                
                                $.ajax({
                                    url: '/document/product/request/flag',
                                    type: 'POST',
                                    data: {'token': token, 'message': " Unable to verify Momo number,"},
                                    dataType: 'json',
                                    success: function(resp) {
                                        payment_proceed == 1
                                        toastr.error('Request flagged. Please proceed','Verification Failed');
                                    }
                                });
                            }else{
                                toastr.error('Unable to verify Momo number! Please try again.');
                            }
                        }
                    });
                } 
                // else {
                //     $('#network-verify').css('border-color', 'red');
                //     $('#phone-number-verify').css('border-color', 'red');
                // }
            });

            $(document).on('click', '#upload-id', function(){
                let id_file = $('#id-file').val();

                if (typeof id_file == 'undefined' || id_file == null || id_file === '') {
                    $('#id-file').css('border-color', 'red');
                    $('#id-file-error').css('display', 'block');

                    setTimeout(() => {
                        $('#id-file').css('border-color', '#ced4da');
                        $('#id-file-error').css('display', 'none');
                    }, 3000);
                }

                if(typeof id_file !== 'undefined' && id_file !== null && id_file !==''){
                    let formData = new FormData($('#id-file-form')[0]);
                 $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: '/document/product/request/id/upload',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(resp) {
                            if(resp.status == 'success'){
                                id_checked = true;
                                
                                $('#upload-verified-id').modal('hide');
                                $('.rightbar-overlay').css('display', 'none');
                                toastr.success('ID uploaded successfully!');
                            }else{
                                $('#id_number').val('')
                                $('.rightbar-overlay').css('display', 'none');
                                toastr.error('Unable to upload ID! Try again.');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            $('.rightbar-overlay').css('display', 'none');
                            toastr.error('Unable to upload ID! Try again.');
                        }
                    });
                } 
                // else {
                //     $('#network-verify').css('border-color', 'red');
                //     $('#phone-number-verify').css('border-color', 'red');
                // }
            });
                
            $(document).on('click', '#init-verify-acc', function(){
                
                let acc_number = $('#payment_account_number').val();

                if (typeof acc_number == 'undefined' || acc_number == null ) {
                    $('#payment_account_number').css('border-color', 'red');
                    $('#payment_account_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#payment_account_number').css('border-color', '#ced4da');
                        $('#payment_account_number-error').css('display', 'none');
                    }, 3000);
                    return ;
                }

                if (acc_number == '') {
                    $('#payment_account_number').css('border-color', 'red');
                    $('#payment_account_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#payment_account_number').css('border-color', '#ced4da');
                        $('#payment_account_number-error').css('display', 'none');
                    }, 3000);
                    return ;
                }

                $('#acc_bank-verified').modal('show');
                $('#acc_bank_number').val($('#payment_account_number').val());
            });

            let bank_attempt = 0;
            $(document).on('click', '#verify-acc-number', function(){
                bank_attempt++;

                if (localStorage.getItem('wallet_number')) {
                    localStorage.removeItem('wallet_number');
                }
                let acc_number = $('#acc_bank_number').val();
                let acc_bank_code = $('#acc_bank_code').val();

                console.log(acc_number);
                if (typeof acc_number == 'undefined' || acc_number == null ) {
                    $('#payment_account_number').css('border-color', 'red');
                    $('#payment_account_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#payment_account_number').css('border-color', '#ced4da');
                        $('#payment_account_number-error').css('display', 'none');
                    }, 3000);
                    return ;
                }

                if (acc_number == '') {
                    $('#payment_account_number').css('border-color', 'red');
                    $('#payment_account_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#payment_account_number').css('border-color', '#ced4da');
                        $('#payment_account_number-error').css('display', 'none');
                    }, 3000);
                    return ;
                }

                if (typeof acc_bank_code == 'undefined' || acc_bank_code == null || acc_bank_code == '') {
                    $('#payment_account_number').css('border-color', 'red');
                    $('#payment_account_number-error').css('display', 'block');

                    setTimeout(() => {
                        $('#payment_account_number').css('border-color', '#ced4da');
                        $('#payment_account_number-error').css('display', 'none');
                    }, 3000);

                    return ;
                }

                if(acc_number && typeof acc_number !== 'undefined' && acc_number !== null){
                 $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: '/document/product/request/verify/acc-number',
                        type: 'POST',
                        data: {'acc_number': acc_number, 'acc_bank_code' : acc_bank_code, 'doc_appl_id' : doc_appl_id},
                        dataType: 'json',
                        success: function(resp) {
                            console.log(resp.data.status);
                            $('#acc_bank-verified').modal('hide');
                            if(resp.data.status == 'success'){
                                // proceed = 1;
                                payment_proceed = 1;
                                // id_checked = true;
                                $('#payment_account_holder_name').val(resp.data.name);
                                $('.rightbar-overlay').css('display', 'none');
                                toastr.success('Account Number verified successfully!');
                                localStorage.setItem('account_number', acc_number)
                            }else{
                                // proceed = 1;
                                payment_proceed = 2;
                                // id_checked = true;
                                $('#id_number').val('')
                                $('.rightbar-overlay').css('display', 'none');
                               
                                if(bank_attempt >= 3){
                                    // Pass the variable to JavaScript
                                    let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                    
                                    $.ajax({
                                        url: '/document/product/request/flag',
                                        type: 'POST',
                                        data: {'token': token, 'message': " Unable to verify Account Number,"},
                                        dataType: 'json',
                                        success: function(resp) {
                                            payment_proceed == 1
                                            toastr.error('Request flagged. Please proceed','Verification Failed');
                                        }
                                    });
                                }else{
                                    toastr.error('Unable to verify Account Number!');
                                }
                                
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            payment_proceed = 2;
                            $('#id_number').val('')
                            $('.rightbar-overlay').css('display', 'none');
                            
                            if(bank_attempt >= 3){
                                // Pass the variable to JavaScript
                                let token = @json(isset($record) && isset($record->token) ? $record->token : null);
                                $('#acc_bank-verified').modal('hide');

                                $.ajax({
                                    url: '/document/product/request/flag',
                                    type: 'POST',
                                    data: {'token': token, 'message': " Unable to verify Account Number,"},
                                    dataType: 'json',
                                    success: function(resp) {
                                        payment_proceed == 1
                                        toastr.error('Request flagged. Please proceed','Verification Failed');
                                    }
                                });
                            }else{
                                toastr.error('Unable to verify Account Number!');
                            }
                        }
                    });
                } 
                // else {
                //     $('#network-verify').css('border-color', 'red');
                //     $('#phone-number-verify').css('border-color', 'red');
                // }
            });

            $(document).on('change', '#payer_relationship_to_policy_holder', function(){
                if($(this).val() == 'Self'){
                    $('.payer-hide').css('display', 'none');

                    $('#payer_name').removeAttr('required');
                    $('#payer_id_type').removeAttr('required');
                    $('#payer_id_number').removeAttr('required');
                } else {
                    $('#payer_name').attr('required', 'required');
                    $('#payer_id_type').attr('required', 'required');
                    $('#payer_id_number').attr('required', 'required');
                    
                    $('.payer-hide').css('display', 'block');
                }
            })

            //
           
            $(document).on('click', '#manually_flag_verify-phone-number', function(){
                //imcomplete pass request token or id
                $.ajax({
                    url: '/document/product/request/flag',
                    type: 'POST',
                    data: {'token': token, 'message': " Manually flagged Phone number,"},
                    dataType: 'json',
                    success: function(resp) {
                        proceed = 1;
                        localStorage.setItem('phone_num', phone_num);
                        toastr.error('Request flagged. Please proceed','Verification Failed');
                    }
                });
            })
            $(document).on('click', '#manually_flag_verify-wallet_number', function(){
               //imcomplete pass request token or id
                $.ajax({
                    url: '/document/product/request/flag',
                    type: 'POST',
                    data: {'token': token, 'message': "Manually flagged Momo number,"},
                    dataType: 'json',
                    success: function(resp) {
                        payment_proceed == 1
                        toastr.error('Request flagged. Please proceed','Verification Failed');
                    }
                });
            });
            $(document).on('click', '#manually_flag_verify-acc-number', function(){
                 //imcomplete pass request token or id
                $.ajax({
                    url: '/document/product/request/flag',
                    type: 'POST',
                    data: {'token': token, 'message': " Manually flagged Account Number,"},
                    dataType: 'json',
                    success: function(resp) {
                        payment_proceed == 1
                        toastr.error('Request flagged. Please proceed','Verification Failed');
                    }
                });
            });
          
        </script>

        <script>
            function setIDRestriction(x, input_id) {
                document.getElementById(input_id).value = "";
                if (x.value == "Drivers License") {
                    document.getElementById(input_id).setAttribute('maxLength', '18');
                } else if (x.value == "Passport") {
                    document.getElementById(input_id).setAttribute('maxLength', '8');
                } else if (x.value == "Voter ID") {
                    document.getElementById(input_id).setAttribute('maxLength', '10');
                } else if (x.value == "SSNIT") {
                    document.getElementById(input_id).setAttribute('maxLength', '20');
                }else if (x.value == "Ghana Card") {
                    document.getElementById(input_id).setAttribute('maxLength', '15');
                }
            }

            function get_branches_list(x) {
            
                var organ = $('#payment_bank_branch');
                organ.empty();

                $('#ajax-loader').css('display', 'block');

                var postData = {
                    'type_id': x.value,
                    'action': "bank_branch",
                };
                $.ajax({
                    url: '/document/ajax_calls/dependent_dropdowns',
                    type: 'GET',
                    data: postData,
                    dataType: 'json',
                    success: function(res) {
                        $('#ajax-loader').css('display', 'none');

                        // From db
                        // if (res) {
                        //     $.each(res, function(e, u) {
                        //         organ.append("<option value=" + u.id + "_" + u.branch_name +
                        //             " selected>" + u.branch_name +
                        //             "</option>");
                        //     });
                        // }

                        // From Slams
                        if (res) {
                            $.each(res.data, function(e, u) {
                                organ.append("<option value=" + u.id + "_" + u.bankBranchName +
                                    " selected>" + u.bankBranchName +
                                    "</option>");
                            });
                        }

                        //USE this if the branches are fetched from the slams
                        // if (res.state == 1) {
                        //     organ.innerHTML = "";
                        //     // console.log(res.data.Banks, true);
                        //     $.each(res.data, function(e, u) {
                        //         // console.log(u);
                        //         organ.innerHTML += "<option value=" + u.id + "_" + u.bankBranchName +
                        //             " selected>" + u.bankBranchName +
                        //             "</option>";
                        //     });

                        // }
                    }
                });

            }
        </script>
        
    </body>
</html>

