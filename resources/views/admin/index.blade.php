@extends('layouts.main-master')
@section('content')

    <div class="row">
       <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Document Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Document</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        @if ($document_products)
            @foreach ($document_products as $document_product)
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        @php
                             $count = \App\Models\DocumentApplication::where('tbl_documents_products_id', $document_product->id)
                                ->where('createdby', Auth()->id())
                                ->count();
                        @endphp 
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">{{$document_product->product_name}}</p>
                               
                                <div class="d-flex justify-content-between">
                                    <h4 class="mb-2">{{$count}}</h4>
                                    <span class="avatar-title bg-light text-primary rounded-3" style="width: 35px">
                                        <i class="ri-inbox-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-inbox-line font-size-24"></i>
                                </span>
                            </div> --}}
                        </div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div><!-- end col -->
            @endforeach
        @endif
    </div>

    {{-- // --}}
    <div class="row">
        <div class="col-md-12">
            <h4 class="card-title mb-3 mt-3">Go to</h4>
            <div class="row">
                <div class="col-md-3">
                    <a href="{{route('pending-bin-dashboard')}}">
                        <button id="position-4-notice" class="mt-sm mb-sm btn btn-warning">My Pending Request Bin</button>
                    </a>
               </div>
               <div class="col-md-3">
                    <a href="{{route('requested-documents')}}">
                        <button id="position-4-notice" class="mt-sm mb-sm btn btn-warning">Requested Documents</button>
                    </a>
               </div>
               <div class="col-md-3">
                    <a href="{{route('fa-dashboard')}}">
                        <button id="position-4-notice" class="mt-sm mb-sm btn btn-warning">FA Dashboard</button>
                    </a>
               </div>
               <div class="col-md-3">
                    <a href="{{route('team-lead-dashboard')}}">
                        <button id="position-4-notice" class="mt-sm mb-sm btn btn-warning">Team Lead Dashboard</button>
                    </a>
               </div>
            </div>
        </div>
    </div>
 
    {{-- // --}}
    
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Requests</h4>
                    <p class="card-title-desc"> </p>

                    
                </div>
            </div>
        </div>
    </div> --}}
    {{-- // --}}
    

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
                    <h5 class="modal-title" id="smallModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="render-share"> 
                    <form class="custom-validation" id="modalForm" action="#" method="post" novalidate="">
                     
                    </form>
                </div>
               
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade digital-form-modal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="print-cont">
                <div class="modal-header">
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewContent">
    
                </div>
                <div class="d-print-none">
                    <div class="float-end">
                        
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
    <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script>

    {{-- <script src="{{asset('/assets/js/app.js')}}"></script> --}}


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
        $(document).on('click', '.approve', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
            swal.fire({
                title: 'Approve Request?',
                text: "You are about to Approve Request, Are you certain?",
                type: 'warning',
                icon:"warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Approve Request!'
            }).then(function(t) {
                if(t.value) {
                    $.ajax({
                        url: "/document/approve",
                        type: 'POST',
                        data: {'id':id},
                        success: function(resp) {
                            // console.log(resp.status);
                            if(resp.status == 'success'){
                                Swal.fire("Approved!","Your file has been approved successfully.","success");
                            }
                           window.location.reload();
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

            // t("#sa-warning").click(function(){
            //     Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",
            //     icon:"warning",
            //     showCancelButton:!0,
            //     confirmButtonColor:"#1cbb8c",
            //     cancelButtonColor:"#f32f53",
            //     confirmButtonText:"Yes, delete it!"
            // }).then(function(t){t.value&&Swal.fire("Deleted!","Your file has been deleted.","success")})}),
        }); 


        $(document).on('click', '.review', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let proposal = $(this).data('proposal');
            $.ajax({
                url: "/document/initiate-review",
                type: 'POST',
                data: {'id':id},
                success: function(resp) {
                    $('#smallModalLabel').html('Review');
                    $('#modalForm').empty();
                    $('#modalForm').append(`
                        <div class="mb-3">
                            <label class="mb-2" id="title">You are about to review the Request with Policy Number <b>${proposal}</b></label>
                            <div>
                                <textarea required="" name="comments" class="form-control" rows="5" placeholder="Write comment"></textarea>
                                <input type="hidden" id="review_id" value="${id}" name="id">
                            </div>
                        </div>
                        <div class="mb-3">
                            ${resp}
                        </div>
                        <div class="mb-0">
                            <div style="float: right;">
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    `);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
           
            
            // departments.forEach(element => {
            //     let option = `<option value="${element.id}">${element.name}</option>`;
            //     $('#department_id').append(option);
            // });
            
            

            $('#modalForm').attr('action', '/document/review');
            $('#smallModal').modal('show');
        }); 
        $(document).on('submit', '#modalForm', function(e) {
            e.preventDefault();
            let form = new FormData(this);
            let url = $('#modalForm').attr('action');
            console.log(url);
            $.ajax({
                url: url,
                type: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function(resp) {
                    console.log(resp);
                    if(resp.status == 'success'){
                         // Handle success
                    $('#smallModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Document shared successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error
                }
            });
        }); 

        $(document).on('click', '.decline', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let proposal = $(this).data('proposal');
            $('#smallModalLabel').html('Decline');
            $('#modalForm').empty();
            $('#modalForm').append(`
                <div class="mb-3">
                    <label class="mb-2" id="title">You are about to decline the Request with Policy Number <b>${proposal}</b></label>
                    <div>
                        <textarea required="" name="comments" class="form-control" rows="5" placeholder="Write comment"></textarea>
                        <input type="hidden" id="id" value="${id}" name="id">
                    </div>
                </div>
                <div class="mb-0">
                    <div style="float: right;">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect">
                            Cancel
                        </button>
                    </div>
                </div>
            `);

            $('#modalForm').attr('action', '/document/decline');
            $('#smallModal').modal('show');
        }); 
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
            swal.fire({
                title: 'Remove from Tray ?',
                text: "You are about to remove this record from your tray, Are you certain?",
                type: 'warning',
                icon:"warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete Request!'
            }).then(function(t) {
                if(t.value) {
                    $.ajax({
                        url: "/document/delete",
                        type: 'POST',
                        data: {'id':id},
                        success: function(resp) {
                            if(resp.status == 'success'){
                                Swal.fire("Deleted!","Record deleted successfully.","success");
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        }); 

        $(document).on('click', '.override', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
            //  $('#smallModal').modal('show');
            $.ajax({
                url: "/document/override",
                type: 'POST',
                data: {'id':id},
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
    </script>

@stop