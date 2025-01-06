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
                <h4 class="mb-sm-0">My Requests</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product Request</a></li>
                        <li class="breadcrumb-item active">My Requests</li>
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
        
                    <h4 class="card-title">My Requests</h4>
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
                                <th>Status</th>
                                <th>Date</th>
                                <th>Branch</th>
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
                    url: "{{ route('document.user.requests') }}",
                    type: "POST",
                    data: function (data) {
                        data.search = $('input[type="search"]').val();
                    }
                },
                order: ['1', 'DESC'],
                pageLength: 100,
                searching: true,
                aoColumns: [
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                        width: "3%",
                    },
                    {
                        data:'request_no',
                        render: function(data, type, row) {
                            return `<a href="/product-request/request-profile?section='UmVxdWVzdC1Qcm9maWxl'&id='${btoa(row.id)}'" data-id="${btoa(row.id)}" class="request-overview">${data}</a>`;
                        }
                    },
                    {
                        data: 'customer_name',
                        // render: function(data, type, row) {
                        //     return `<a href="#" data-id="${btoa(row.id)}" class="request-overview">${data}</a>`;
                        // }
                    },
                    {
                        data: 'policy_no',
                        // render: function(data, type, row) {
                        //     return `<a href="#" data-id="${btoa(row.id)}" class="request-overview">${data}</a>`;
                        // }
                    },
                    {
                        data: 'document_product.product_name',
                    },
                    {
                        data: 'user.full_name',
                        render: function(data, type, row) {
                            return row.user.firstname +' '+ row.user.lastname;
                        }
                    },
                    {
                        data: 'application_status.status_name',
                    },
                    {
                        data: 'createdon',
                    },
                   
                    {
                        data: 'branch.branch_name',
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
                                        <button class="dropdown-item audit" data-id="${btoa(id)}"  href="#${data}"><i class="ri-history-line align-middle me-2"></i>Audit Trial</button>

                                        ${form_filled == 1 ? `
                                            <a class="dropdown-item digital-form" data-token="${token}"  href="#${btoa(id)}"><i class="ri-file-list-line align-middle me-2"></i>Digital Form</a>
                                            <a class="dropdown-item share" data-id="${btoa(id)}"  href="#${id}"><i class="ri-share-fill align-middle me-2"></i>Share</a>`: ''}

                                        ${((createdby == user_id) || [324, 75, 273, 288, 1, 85, 269, 270, 78].includes(user_id)) ? 
                                            `<a class="dropdown-item" href="/product-request/request-profile?section='UmVxdWVzdC1Qcm9maWxl'&id='${btoa(id)}'"><i class="ri-file-edit-line align-middle me-2"></i>Edit Request</a>` : ''}
                                        
                                        @if (auth()->user()?->delete_documents_yn)
                                            ${[75].includes(tbl_application_status_id) ? 
                                                `<a class="dropdown-item" href="#${btoa(id)}"><i class="ri-delete-bin-line align-middle me-2"></i>Delete</a>` : ''}
                                        @endif

                                        @if (auth()->user()?->override_status_yn)
                                            <a class="dropdown-item" data-id="${btoa(id)}"  href="#${token}"><i class="ri-swap-line align-middle me-2"></i>Override Status</a>
                                        @endif
                                    </div>
                                </div>
                            `;
                        }
                    }
                ]
            });
        });

        $(document).on('click', '.digital-form', function(e){
            e.preventDefault();
            let token = $(this).data('token');
            $.ajax({
                url: '/document/preview-proposal/' + token,
                type: 'GET',
                success: function(response) {
                    // Update modal content with the retrieved preview content
                    $('#previewContent').html(response.previewContent);
                    // Show the modal
                    $('.digital-form-modal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error if any
                    console.error(error);
                }
            });
        })

        $(document).on('click', '.audit', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
        
            console.log(id);

            $.ajax({
                url: "/document/audit/" + id,
                type: 'GET',
                success: function(resp) {
                    $('#render-audit').html(resp);
                    $('.bs-example-modal-lg').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                }
            });
        });

        $(document).on('click', '.share', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
            //  $('#smallModal').modal('show');
            $.ajax({
                url: "/document/share/" + id,
                type: 'GET',
                success: function(resp) {
                    $('#render-share').html(resp);
                    $('#smallModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                }
            });
            
        }); 

        $(document).on('submit', '#share-doc', function(e) {
            e.preventDefault();
            let form = new FormData(this);

            $('.rightbar-overlay').css('display', 'block');

            $.ajax({
                url: "/document/share/via-email",
                type: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function(resp) {
                    console.log(resp);
                    // Handle success
                    $('#smallModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Document shared successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('.rightbar-overlay').css('display', 'none');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                    $('.rightbar-overlay').css('display', 'none');
                }
            });
        }); 

        $(document).on('change', 'select#delivery', function () {
            console.log('here');
            var delivery = $(this).val();
            if(delivery == 'SMS'){
                $('div#live1').show('slow');
                $('div#live2').hide('slow');
            } else if(delivery == 'EMAIL'){
                $('div#live2').show('slow');
                $('div#live1').hide('slow');

            }else{
                $('div#live1').hide('slow');
                $('div#live2').hide('slow');
            }
        });

        $(document).on('click', '.request-overview', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
        
            console.log(id);
            $('#previewContent').empty();
            $.ajax({
                type: 'GET',
                url: '/document/request/overview/' + id,
                success: function(resp) {
                    $('#previewContent').html(resp.html);
                    // Show the modal
                    $('.digital-form-modal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                }
            });
        });
        
    </script>
  
@stop