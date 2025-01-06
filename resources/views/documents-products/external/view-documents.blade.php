
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>{{ $dynamicTitle }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="View Request" name="description" />
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
       <div class="d-flex justify-content-center">
        <div class="col-md-8">
            <div class="container-fluid p-0">
                <div class="card" style="padding-top: 100px;">
                    <div class="card-body">
                        {{-- @if ($previewContent)
                             {!! $previewContent !!}
                        @else
                            @include('documents-products.products.invalid-product')
                        @endif --}}

                        @php
                            $token = request()->query('token');
                            $record = \App\Models\DocumentApplication::with('tbl_application_status')->where(['token' => $token, 'deleted' => 0])->first();
                            $documents = \App\Models\Document::with('createdby')->with('tbl_document_type')->where(['tbl_document_applications_id' => optional($record)->id, 'deleted' => 0])->get();
                        @endphp

                        @if (!$record)
                            @include('documents-products.products.invalid-product')
                        @else
                            <div class="content">
                                <!-- Start container-fluid -->
                                <div class="container-fluid">
                                    <div class="col-md-12 mb-3">
                                        {{-- {!! message_box(true) !!} --}}
                                        <h4 class="header-title mb-3">Document Request Details for #{{ $record['request_no'] }}</h4>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success btn-rounded btn-sm"><strong>{{ optional($record->tbl_application_status)['status_name'] }}</strong></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="header-title mb-3">Documents Attached to Request</h4>
                                            @if ($documents->isNotEmpty())
                                               

                                                <table id="technical_requirement" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document</th>
                                                            <th>Document Type</th>
                                                            <th>Processed By</th>
                                                            <th>Processed On</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($documents as $document)
                                                            @php
                                                                $url = '';
                                                                $s3FileUrl = Storage::disk('s3')->url('documents/'.$document->document);
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td><a href="{{$s3FileUrl}}" id="previewProposal" data-id="{{ $document['id'] }}"><img src="{{ asset('/assets/images/doc_logos/' . $document->tbl_document_images?->images) }}"></a></td>
                                                                <td>{{ optional($document->tbl_document_type)['document_name'] }}</td>
                                                                <td>{{ $document->tbl_users->full_name }}</td>
                                                                <td>{{ $document->createdon }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <strong>No Document(s) attached to request</strong>
                                            @endif
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <!-- end container-fluid -->
                            </div>
                        @endif
                        
                    </div>
                </div>
               
            </div>
            <!-- end container -->
        </div>
       </div>

       <div id="modal-dialog" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" align="center">Message</h4>
            </div>
    
            <div class="modal-body">
              <p class="text-center msg-holder">
                <span class="fa fa-exclamation"></span>&nbsp;&nbsp;Kindly select a <span class="label label-info" style="font-size: 13px;">Sub Risk Code</span>
              </p>
            </div>
    
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

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
       
        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{asset('assets/js/app.js')}}"></script>

        <script type="text/javascript">

            $(document).ready(function() {



                $(document).on('click', '.previewProposal-l', function(){
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

            });
        </script>

        @yield('application-status-script')
    </body>
</html>

