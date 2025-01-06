
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
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-sm" height="90">
                            </span>
                            <span class="logo-lg">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-dark" height="90">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-sm-light" height="90">
                            </span>
                            <span class="logo-lg">
                                <img src="/assets/images/om-cust-logo.PNG" alt="logo-light" height="90">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        @php
            $token = request()->query('token');
            $key = request()->query('key') ? base64_decode(request()->query('key')) : null;
            $uid = request()->query('uid') ? base64_decode(request()->query('uid')) : null;

            if ($token && $key && request()->query('auth') == 1) {
                $record = \App\Models\DocumentApplication::where(['token' => $token, 'deleted' => 0])->first();

                if ($key == 'approved' || $key == 'approved-review') {
                    $message = 'You have requested to <strong>Approve</strong> this request. Kindly validate your response by providing us with your <strong>Email Address or Mobile Number</strong>?';
                } elseif ($key == 'declined' || $key == 'declined-review') {
                    $message = 'You have requested to <strong>Decline</strong> this request. Kindly validate your response by providing us with your <strong>Email Address or Mobile Number</strong>?';
                } elseif ($key == 'reviewed') {
                    $message = 'You have requested to  <strong>Review</strong>. Kindly validate your response by providing us with your <strong>Email Address or Mobile Number</strong>?';
                }
            }
        @endphp

       <div class="d-flex justify-content-center">
        <div class="col-md-8">
            <div class="container-fluid p-0">
                <div class="card" style="padding-top: 100px;">
                    <div class="card-body">

                        @if ($token)
                            <div class="alert alert-success" role="alert">
                                {!!$message!!}
                            </div>
                              
                              <form name="login" id="actionForm" action="{{ route('document-action-action') }}" method="post">
                                @csrf <!-- CSRF Token for security -->
                            
                                <div class="form-group mb-3">
                                    <label class="form-label">Enter Email Address/Mobile No.</label>
                                    <div class="input-group input-group-icon">
                                        <input name="username" type="text" class="form-control input-lg" />
                                    </div>
                                </div>
                            
                                @if ($key == 'declined' || $key == 'reviewed' || $key == 'approved-review')
                                <div class="form-group mb-3">
                                    <label class="form-label" for="password">Comments *</label>
                                    <textarea name="comments" class="form-control" required></textarea>
                                </div>
                                @endif
                            
                                @if ($key == 'reviewed')
                                <div class="form-group mb-3">
                                    <label class="form-label" for="password"><strong>Sending Request to {{ $record->tbl_users->full_name }}</strong></label>
                                    <select name="tbl_departments_id" class="form-control">
                                        @php
                                            $recs = \App\Models\DocumentWorkflow::with('tbl_document_setup_details')->where('tbl_document_applications_id', $record->id)
                                                                                ->where('tbl_document_workflow_type_id', 1)
                                                                                ->where('deleted', 0)
                                                                                ->get();
                                        @endphp

                                        <option value=""></option>
                                        <option value="0">Requester - {{ $record->tbl_users->full_name }}</option>
                                        @foreach ($recs as $rec)
                                            @php $depts = \App\Models\Department::find($rec->tbl_document_setup_details['reference']) @endphp
                                        <option value="{{ $depts['id'] }}">{{ $depts['department_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            
                                @if ($record['tbl_documents_products_id'] == 2)
                                <div class="form-group mb-3">
                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="bypass_check">
                                                &nbsp;ByPass Security &amp; Forensics
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            
                                <div class="col-md-12 text-right">
                                    <input type="hidden" name="token" value="{{ request()->query('token') }}">
                                    <input type="hidden" name="key" value="{{ request()->query('key') }}">
                                    <input type="hidden" name="uid" value="{{ request()->query('uid') }}">
                                    <button type="submit" name="btnAuthenticate" class="btn btn-success">Validate Response</button>
                                    {{-- <button type="submit" name="btnAuthenticate" class="btn btn-success btn-block btn-lg visible-xs mt-sm">Validate Response</button> --}}
                                </div>
                            </form>
                        @else
                            @include('documents-products.products.invalid-product')
                        @endif

                       


                        

                    </div>
                </div>
               
            </div>
            <!-- end container -->
        </div>
       </div>
        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('assets/js/app.js')}}"></script>
        <script>
            $.ajaxSetup({
               headers:{
                   'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
               }
           });
       </script>
       
       <script src="{{asset('/assets/libs/toastr/build/toastr.min.js')}}"></script>


        <script>
            $(document).ready(function(){
                $('#actionForm').submit(function(e){
                    e.preventDefault(); // Prevent the default form submission

                    // Serialize the form data
                    var formData = $(this).serialize();

                    // AJAX request
                    $.ajax({
                        url: '/document/action/request/action', // Replace 'your-backend-url.php' with the URL of your backend script
                        type: 'POST',
                        data: formData,
                        success: function(resp){
                            // Handle the response from the server
                            toastr.success(resp.message.message);
                            // window.location.href="/";
                        },
                        error: function(xhr, status, error){
                            // Handle errors
                            console.error(error);
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message.message);
                            } else {
                                console.error(error);
                                toastr.error('Sorry! Something went wrong.');
                            }
                        }
                    });
                });
            });

        </script>

        @yield('application-status-script')
    </body>
</html>

