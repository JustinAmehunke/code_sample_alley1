@extends('layouts.main-master')
@section('content')

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
                    <button type="button" id="view_restriction" style="float: right;" data-restriction="" class="btn btn-primary btn-sm waves-effect waves-light view_restriction">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-bordered mb-2">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Restriction</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tr-cont-ep" id="tr-cont-ep">
                            @foreach ($restrictions as $key => $restriction)
                                <tr>
                                    <td>
                                        {{++$key}}
                                        <input type="hidden" name="id" value="{{$restriction->id}}">
                                    </td>
                                    <td>
                                        {{$restriction->restriction_name}}
                                        <input type="hidden" name="restriction_name" value="{{$restriction->restriction_name}}">
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <button type="button"  data-restriction="{{$restriction->id}}" class="btn btn-success btn-sm modal-btns waves-effect waves-light view_restriction">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                    
                                            <button type="button"  data-restriction="{{$restriction->id}}" class="btn btn-danger btn-delete btn-sm modal-btns waves-effect waves-light">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                          
                        </tbody>
                    </table>
                   
                </div>
            </div>
           
        </div>
       
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="view_restriction_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_view_restriction" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_view_restriction"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_view_restriction" class="btn btn-primary waves-effect waves-light">Save changes</button>
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
        // Handle all form submission on this page
        $(document).ready(function() {
            $(document).on('submit', 'form', function(e) {
                e.preventDefault(); // Prevent the default form submission
                $('.modal-btns').prop('disabled', true); //disable buttons

                // Get the form element that triggered the submit event
                let form = $(this);

                // console.log(form.attr('id').split('_')[1]);
                let message = 'loading';
                let fullid = form.attr('id');
                let id = fullid.split('_')[1];
                let type = fullid.split('_')[2];
                let action = form.attr('action');
                // console.log(action);
                // return;

                showAjaxLoading(message, fullid, status = true);
                $('#btn_'+ id + '_' + type).prop('disabled', true);

                // Get the form data
                let formData = form.serialize();

                // Send the form data using AJAX
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    success: function(resp) {
                        showAjaxLoading(message = null , fullid, status = false);
                        
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        $('.modal-btns').prop('disabled', false);
                        if(resp.status == 'success'){
                            // Request was successful
                            $('#view_restriction_modal').modal('hide');

                            showAjaxSuccess(message = resp.message , fullid);
                            
                            // console.log(resp.restrictions);
                            reloadTable(resp.restrictions);
                            
                        }else{
                            // Request was unsuccessful
                            $('#btn_'+ id + '_' + type).prop('disabled', false);
                            showAjaxError(message = resp.message , fullid);
                        }
                    
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Error occurred during the request
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        $('.modal-btns').prop('disabled', false);
                        //
                        showAjaxError(message = "Something went wrong" , fullid);
                        if (xhr.status === 422) { // 422 is the status code for validation errors
                            var errors = xhr.responseJSON.errors;

                            // Clear previous error styles
                            $('.form-control').removeClass('is-invalid');

                            // Apply error styles to specific fields
                            if (errors.hasOwnProperty('department_name')) {
                                $('#department_name').addClass('is-invalid');
                            }
                            if (errors.hasOwnProperty('mailing_list')) {
                                $('#mailing_list').addClass('is-invalid');
                            }
                            setTimeout(() => {
                                $('.form-control').removeClass('is-invalid');
                            }, 3000);
                            // Add error messages if needed
                            // $('#department_name_error').text(errors.department_name[0]);
                            // $('#mailing_list_error').text(errors.mailing_list[0]);
                            // ...
                        }
                    }
                });
            });
            // reload function
            function reloadTable(data){
                $('#tr-cont-ep').empty();
                if(data){
                    let key = 0;
                    data.forEach(restriction => {
                        $('#tr-cont-ep').append(`
                        <tr>
                            <td>
                                ${++key}
                                <input type="hidden" name="id" value="${restriction.id}">
                            </td>
                            <td>
                                ${restriction.restriction_name}
                                <input type="hidden" name="restriction_name" value="${restriction.restriction_name}">
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="button"  data-restriction="${restriction.id}" class="btn btn-success btn-sm modal-btns waves-effect waves-light view_restriction">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                            
                                    <button type="button"  data-restriction="${restriction.id}" class="btn btn-danger btn-delete btn-sm modal-btns waves-effect waves-light">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        `);
                    });

                    $('#view_restriction_modal').modal('hide');
                }
            }

            $(document).on('click', '.btn-delete', function(){
                let id = $(this).data('restriction');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You are deleting this restriction",
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#1cbb8c",
                    cancelButtonColor: "#f32f53",
                    confirmButtonText: "Yes, delete it!",
                }).then(function (t) {
                    if (t.value == true) {
                        $('.rightbar-overlay').css('display', 'block');
                        $.ajax({
                            url: "/admin/documents/restriction/delete/"+id,
                            type: "POST",
                            dataType: "JSON",
                            success: function (resp) {
                                if(resp.status == "success"){
                                    $('.rightbar-overlay').css('display', 'none');
                                    Swal.fire("Deleted!", resp.message, "success");
                                    reloadTable(resp.restrictions);
                                }
                            }
                        });
                        
                    }
                });
            });
        });
       
    </script>
    <script>
        $(document).on('click', '.view_restriction', function(){
            $('#view_restriction_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let type_id = $(this).data('restriction');
           
            //append content into modal
            if(type_id){
                let row = $(this).closest('tr');
                // var num = row.find('td:eq(0)').text();
                let restrictionId = row.find('td:eq(0) input:hidden').val();
                let restriction_name = row.find('td:eq(1) input[name="restriction_name"]').val();
                
                $('#modalLabel').html('Updating Restriction');
                $('#form_view_restriction').attr('action', '/admin/documents/restriction/update');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="restriction_name" class="form-label">Restriction</label>
                        <input type="text" class="form-control" name="restriction_name" value="${restriction_name}" id="restriction_name"  required="">
                        <input type="hidden" name="id" value="${restrictionId}">
                    </div>
                `);
            }else{
                $('#modalLabel').html('Adding New Restriction');
                $('#form_view_restriction').attr('action', '/admin/documents/restriction/save');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="restriction_name" class="form-label">Restriction</label>
                        <input type="text" class="form-control" name="restriction_name" id="restriction_name"  required="">
                    </div>
                `);
            }
        });
    </script>
   

   
   

@stop