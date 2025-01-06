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
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Validation type</h4>
                    <p class="card-title-desc">Parsley is a javascript form validation
                        library. It helps you provide your users with feedback on their form
                        submission before sending it to your server.</p>

                    <form class="custom-validation" action="#" novalidate="">
                        <div class="mb-3">
                            <label>Required</label>
                            <input type="text" class="form-control" required="" placeholder="Type something">
                        </div>

                        <div class="mb-3">
                            <label>Equal To</label>
                            <div>
                                <input type="password" id="pass2" class="form-control" required="" placeholder="Password">
                            </div>
                            <div class="mt-2">
                                <input type="password" class="form-control" required="" data-parsley-equalto="#pass2" placeholder="Re-Type Password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>E-Mail</label>
                            <div>
                                <input type="email" class="form-control" required="" parsley-type="email" placeholder="Enter a valid e-mail">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>URL</label>
                            <div>
                                <input parsley-type="url" type="url" class="form-control" required="" placeholder="URL">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Digits</label>
                            <div>
                                <input data-parsley-type="digits" type="text" class="form-control" required="" placeholder="Enter only digits">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Number</label>
                            <div>
                                <input data-parsley-type="number" type="text" class="form-control" required="" placeholder="Enter only numbers">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Alphanumeric</label>
                            <div>
                                <input data-parsley-type="alphanum" type="text" class="form-control" required="" placeholder="Enter alphanumeric value">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Textarea</label>
                            <div>
                                <textarea required="" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Range validation</h4>
                    <p class="card-title-desc">Parsley is a javascript form validation
                        library. It helps you provide your users with feedback on their form
                        submission before sending it to your server.</p>

                    <form action="#" class="custom-validation" novalidate="">

                        <div class="mb-3">
                            <label>Min Length</label>
                            <div>
                                <input type="text" class="form-control" required="" data-parsley-minlength="6" placeholder="Min 6 chars.">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Max Length</label>
                            <div>
                                <input type="text" class="form-control" required="" data-parsley-maxlength="6" placeholder="Max 6 chars.">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Range Length</label>
                            <div>
                                <input type="text" class="form-control" required="" data-parsley-length="[5,10]" placeholder="Text between 5 - 10 chars length">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Min Value</label>
                            <div>
                                <input type="text" class="form-control" required="" data-parsley-min="6" placeholder="Min value is 6">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Max Value</label>
                            <div>
                                <input type="text" class="form-control" required="" data-parsley-max="6" placeholder="Max value is 6">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Range Value</label>
                            <div>
                                <input class="form-control" required="" type="text range" min="6" max="100" placeholder="Number between 6 - 100">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Regular Exp</label>
                            <div>
                                <input type="text" class="form-control" required="" data-parsley-pattern="#[A-Fa-f0-9]{6}" placeholder="Hex. Color">
                            </div>
                        </div>

                        <div class="mb-0">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="view_types_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_view_types" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_view_types"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_view_types" class="btn btn-primary waves-effect waves-light">Save changes</button>
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

        $(function(){
           
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
                            // $('#view_types_modal').modal('show');

                            showAjaxSuccess(message = resp.message , fullid);
                            
                            // console.log(resp.document_types);
                            reloadTable(resp.document_types);
                            
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
                            if (errors.hasOwnProperty('document_name')) {
                                $('#document_name').addClass('is-invalid');
                            }
                            if (errors.hasOwnProperty('require_camera')) {
                                $('#require_camera').addClass('is-invalid');
                            }
                            if (errors.hasOwnProperty('badge')) {
                                $('#badge').addClass('is-invalid');
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
                    data.forEach(document_type => {
                        $('#tr-cont-ep').append(`
                        <tr>
                            <td>
                                ${++key}
                                <input type="hidden" name="document_type_id" value="${document_type.id}">
                            </td>
                            <td>
                                ${document_type.document_name}
                                <input type="hidden" name="document_name" value="${document_type.document_name}">
                            </td>
                            <td>
                                ${document_type.require_camera > 0 ? 'YES' : 'NO'}
                                <input type="hidden" name="require_camera" value="${document_type.require_camera}">
                            </td>
                            <td>
                                ${document_type.badge.badge}
                                <input type="hidden" name="badge" value="${document_type.badge.id}">
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="button"  data-doc_type="${document_type.id}" class="btn btn-success btn-sm modal-btns waves-effect waves-light view_types">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="button"  data-doc_type="${document_type.id}" class="btn btn-danger btn-delete btn-sm modal-btns waves-effect waves-light">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        `);
                    });

                    $('#view_types_modal').modal('hide');
                }
            }

            $(document).on('click', '.btn-delete', function(){
                let id = $(this).data('doc_type');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You are deleting this Document Type",
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#1cbb8c",
                    cancelButtonColor: "#f32f53",
                    confirmButtonText: "Yes, delete it!",
                }).then(function (t) {
                    if (t.value == true) {
                        $('.rightbar-overlay').css('display', 'block');
                        $.ajax({
                            url: "/admin/documents/types/delete/"+id,
                            type: "POST",
                            dataType: "JSON",
                            success: function (resp) {
                                if(resp.status == "success"){
                                    $('.rightbar-overlay').css('display', 'none');
                                    Swal.fire("Deleted!", resp.message, "success");
                                    reloadTable(resp.document_types);
                                }
                            }
                        });
                        
                    }
                });
            });
        });
       
    </script>
    <script>
        $(document).on('click', '.view_types', function(){
            $('#view_types_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let type_id = $(this).data('doc_type');
           
            //append content into modal
            if(type_id){
                let row = $(this).closest('tr');
                // var num = row.find('td:eq(0)').text();
                let document_type_id = row.find('td:eq(0) input:hidden').val();
                let document_name = row.find('td:eq(1) input[name="document_name"]').val();
                let require_camera = row.find('td:eq(2) input[name="require_camera"]').val();
                let badge_id = row.find('td:eq(3) input[name="badge"]').val();
                
                $('#modalLabel').html('Updating Document Type');
                $('#form_view_types').attr('action', '/admin/documents/types/update');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Document Type Name</label>
                        <input type="text" class="form-control" name="document_name" value="${document_name}" id="document_name"  required="">
                        <input type="hidden" name="id" value="${document_type_id}">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Require Camera</label>
                        <select class="form-select" name="require_camera" id="require_camera" required="">
                          
                        </select>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Badge</label>
                        <select class="form-select" name="badge" id="badge" required="">
                          
                        </select>
                    </div>
                `);

                let badgesAll = localStorage.getItem('badgesData');
                let badges = JSON.parse(badgesAll);
                if(badges){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#badge').append(option);
                    // let selected = 
                    badges.forEach(badge => {
                        console.log(badge);
                        if(badge.id == badge_id){
                            option = `<option value="${badge.id}" selected="">${badge.badge}</option>`;
                        }else{
                            option = `<option value="${badge.id}">${badge.badge}</option>`;
                        }
                        $('#badge').append(option);
                    });
                }

                array = {"Yes":1, "No":0};
                for(let key in array ){
                    if(array[key] == require_camera){
                        $('#require_camera').append(`<option value="${array[key]}" selected="">${key}</option>`);
                    }else{
                        $('#require_camera').append(`<option value="${array[key]}" >${key}</option>`);
                    }
                }
            }else{
                $('#modalLabel').html('Adding New Document Type');
                $('#form_view_types').attr('action', '/admin/documents/types/save');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Document Type Name</label>
                        <input type="text" class="form-control" name="document_name" id="document_name"  required="">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Require Camera</label>
                        <select class="form-select" name="require_camera" id="require_camera" required="">
                          
                        </select>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Badge</label>
                        <select class="form-select" name="badge" id="badge" required="">
                          
                        </select>
                    </div>
                `);

                let badgesAll = localStorage.getItem('badgesData');
                let badges = JSON.parse(badgesAll);
                if(badges){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#badge').append(option);
                    // let selected = 
                    badges.forEach(badge => {
                        option = `<option value="${badge.id}">${badge.badge}</option>`;
                        $('#badge').append(option);
                    });
                }

                array = {"Yes":1, "No":0};
                for(let key in array ){
                    $('#require_camera').append(`<option value="${array[key]}" >${key}</option>`);
                }
            }
        });
        // $('#new_sub-module').on('click', function(){
        //     $('#new_sub-module_modal').modal('show');
        // });
    </script>

@stop