@extends('layouts.main-master')
@section('content')
<!-- Sweet Alert-->
<link href="{{asset('/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Font Awesome -->
<link href="{{asset('/assets/libs/font-awesome/css/font-awesome.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    Application Modules
                    <button type="button" id="new_view_types" style="float: right;" data-complaint="" class="btn btn-primary btn-sm waves-effect waves-light new_view_types">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-bordered mb-2">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Complaint Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tr-cont-ep" id="tr-cont-ep">
                            @foreach ($complaintTypes as $key => $complaintType)
                                <tr>
                                    <td>
                                        {{++$key}}
                                        <input type="hidden" name="" value="{{$complaintType->id}}">
                                    </td>
                                    <td>
                                        {{$complaintType->complaint_name}}
                                        <input type="hidden" name="complaint_name" value="{{$complaintType->complaint_name}}">
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <button type="button"  data-complaint="{{$complaintType->id}}" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <form id="deleteForm{{$complaintType->id}}" action="/admin/complaints/types/delete" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$complaintType->id}}">
                                                <button type="button"  data-complaint="{{$complaintType->id}}" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                           <div class="showajaxfeed" id="showajax_feed_{{$complaintType->id}}_delete"></div>
                                        </div>
                                        {{-- <div class="btn-group me-2" role="group" aria-label="Second group">
                                            <button type="button"  data-tr="${x}" class="btn btn-success btn-sm waves-effect waves-light">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button type="button"  data-tr="${x}" class="btn btn-danger btn-sm waves-effect waves-light">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                          
                        </tbody>
                    </table>
                    <form id="form_endpoint_details" action="/admin/save/update/complaint/types" method="POST">
                        @csrf 
                       
                        <div id="ep_ajax-loading" style="display: none;">
                            <div style="display: flex; justify-content: center; position: relative; top: -8px; background: #f8f9fa;">
                                 {{-- <div class="spinner-border text-primary m-1" role="status">
                                     <span class="sr-only">Loading...</span>
                                 </div> --}}
                                 <div class="spinner-grow text-primary m-1" role="status">
                                     <span class="sr-only">Loading...</span>
                                 </div>
                            </div>
                        </div>
                        <div id="ep_ajax-nodata" style="display: none;">
                            <div style="display: flex; justify-content: center; position: relative; top: -8px; background: #f8f9fa;">
                                 {{-- <div class="spinner-border text-primary m-1" role="status">
                                     <span class="sr-only">Loading...</span>
                                 </div> --}}
                                 <div class="text- m-1" role="status">
                                     <span class="">No data found.</span>
                                 </div>
                            </div>
                         </div>
                        {{-- <div>
                            <button type="button" id="add_eprow" class="btn btn-primary waves-effect btn-sm waves-light modal-btns" style="float: right;">
                                <i class="ri-add-fill align-middle"></i> Add Another
                            </button>
                        </div> --}}
                        {{-- <div style="margin-top:30px;">
                            <button class="btn btn-primary modal-btns " style="margin-right: 20px;" type="submit" id="btn_details_sub-module">Save/Update</button>
                            <span class="showajaxfeed" id="showajax_feed_endpoint_details"></span>
                           <input type="hidden" id="ep_module_id" name="module_id">
                           <input type="hidden" id="ep_submodule_id" name="submodule_id">
                        </div> --}}
                    </form>
                </div>
            </div>
           
        </div>
       
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="new_view_types_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_1_types"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn_new_view_types" class="btn btn-primary waves-effect waves-light all-btns">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection
    
@section('application-status-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{asset('/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Sweet alert init js-->
    <script src="{{asset('/assets/js/pages/sweet-alerts.init.js')}}"></script>
  
    <script src="{{asset('/assets/js/ajax-utils.js')}}"></script>

    <script>
         $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $('.new_view_types').on('click', function(){
            $('#new_view_types_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let type_id = $(this).data('complaint');
           
            //append content into modal
            if(type_id){
                let row = $(this).closest('tr');
                // var num = row.find('td:eq(0)').text();
                let complaintId = row.find('td:eq(0) input:hidden').val();
                let complaint_name = row.find('td:eq(1) input[name="complaint_name"]').val();
                
                $('#modalLabel').html('Updating Complaint Type');
                $('#form').attr('action', '/admin/complaints/types/update');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Complaint Type</label>
                        <input type="text" class="form-control" name="complaint_name" value="${complaint_name}" id="complaint_name"  required="">
                        <input type="hidden" name="id" value="${complaintId}">
                    </div>
                `);
            }else{
                $('#modalLabel').html('Adding New Complaint Type');
                $('#form').attr('action', '/admin/complaints/types/save');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Complaint Type</label>
                        <input type="text" class="form-control" name="complaint_name" id="complaint_name"  required="">
                    </div>
                `);
            }
        });
        $('.all-btns').on('click', function(){
            $('.all-btns').prop('disabled', true);
            showAjaxLoading('Loading', 'showfeed_1_types', status = true);
            $('#form').submit();
        });
        $('.delete-btns').on('click', function(){
            $('.delete-btns').prop('disabled', true);
            let type_id = $(this).data('complaint');
            Swal.fire({
                title: "Are you sure?",
                text: "You are deleting this Complaint Type",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#1cbb8c",
                cancelButtonColor: "#f32f53",
                confirmButtonText: "Yes, delete it!",
            }).then(function (t) {
                if (t.value == true) {
                    showAjaxLoading('Loading', 'showfeed_'+type_id+'_delete', status = true);
                    $('#deleteForm'+type_id).submit();
                }else{
                    $('.delete-btns').prop('disabled', false);
                }
            });
        });
    </script>
@stop