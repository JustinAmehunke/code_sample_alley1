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
                    <button type="button" id="user_category" style="float: right;" data-complaint="" class="btn btn-primary btn-sm waves-effect waves-light user_category">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-bordered mb-2">

                        <thead class="table-light">
                            <tr>
                                <th>#</th> 
                                <th>Main Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tr-cont-ep" id="tr-cont-ep">
                            @foreach ($user_categories as $key => $user_category)
                                <tr>
                                    <td>
                                        {{++$key}}
                                        <input type="hidden" name="id" value="{{$user_category->id}}">
                                        <input type="hidden" name="prefix" value="{{$user_category->prefix}}">
                                    </td>
                                    <td>
                                        {{$user_category->user_category}}
                                        <input type="hidden" name="user_category" value="{{$user_category->user_category}}">
                                        <input type="hidden" name="service_provider" value="{{$user_category->service_provider}}">
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <button type="button"  data-category="{{$user_category->id}}" class="btn btn-success btn-sm waves-effect waves-light user_category">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <button type="button"  data-category="{{$user_category->id}}" class="btn btn-danger btn-delete btn-sm waves-effect waves-light ">
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
<div id="user_category_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_user_category" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_user_category"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_user_category" class="btn btn-primary waves-effect waves-light">Save changes</button>
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
        $(document).on('click', '.user_category', function(){
            $('#user_category_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let type_id = $(this).data('category');
           
            //append content into modal
            if(type_id){
                let row = $(this).closest('tr');
                // var num = row.find('td:eq(0)').text();
                let user_cat_Id = row.find('td:eq(0) input[name="id"]').val();
                let prefix = row.find('td:eq(0) input[name="prefix"]').val();

                let user_category = row.find('td:eq(1) input[name="user_category"]').val();
                let service_provider = row.find('td:eq(1) input[name="service_provider"]').val();
                
                $('#modalLabel').html('Updating Complaint Type');
                $('#form_user_category').attr('action', '/admin/users/main-category/update');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">2 Letter Category Prefix</label>
                        <input type="text" class="form-control" name="prefix" value="${prefix}" id="module_name"  required="">
                        <input type="hidden" name="id" value="${user_cat_Id}">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Main Category</label>
                        <input type="text" class="form-control" name="user_category" value="${user_category}" id="module_name"  required="">
                      
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Partner?</label>
                        <select name="service_provider" id="service_provider" class="form-select">
                           
                        </select>
                    </div>
                `);
                array = {"Yes":1, "No":0};
               for(let key in array ){
                    if(array[key] == service_provider){
                        $('#service_provider').append(`<option value="${array[key]}" selected="">${key}</option>`);
                    }else{
                        $('#service_provider').append(`<option value="${array[key]}" >${key}</option>`);
                    }
               }
            }else{
                $('#modalLabel').html('Adding New Complaint Type');
                $('#form_user_category').attr('action', '/admin/users/main-category/save');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">2 Letter Category Prefix</label>
                        <input type="text" class="form-control" name="prefix" id="prefix"  required="">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Main Category</label>
                        <input type="text" class="form-control" name="user_category"  id="user_category"  required="">
                      
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Partner?</label>
                        <select name="service_provider" id="service_provider" class="form-select">
                            <option value="0" >No</option>
                            <option value="1" >Yes</option>
                        </select>
                    </div>
                `);
            }
        });
    </script>
    <script>
        function reloadTable(data){
            $('#tr-cont-ep').empty();
            if(data){
                let key = 0;
                data.forEach(category => {
                    $('#tr-cont-ep').append(`
                        <tr>
                            <td>
                                ${++key}
                                <input type="hidden" name="id" value="${category.id}">
                                <input type="hidden" name="prefix" value="${category.prefix}">
                            </td>
                            <td>
                                ${category.user_category}
                                <input type="hidden" name="user_category" value="${category.user_category}">
                                <input type="hidden" name="service_provider" value="${category.service_provider}">
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="button"  data-category="${category.id}" class="btn btn-success btn-sm waves-effect waves-light user_category">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                            
                                    <button type="button"  data-category="${category.id}" class="btn btn-danger btn-delete btn-sm waves-effect waves-light">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                });

                $('#user_category_modal').modal('hide');
            }
        }

        $(document).on('click', '.btn-delete', function(){
            let id = $(this).data('category');
            Swal.fire({
                title: "Are you sure?",
                text: "You are deleting User Category",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#1cbb8c",
                cancelButtonColor: "#f32f53",
                confirmButtonText: "Yes, delete it!",
            }).then(function (t) {
                if (t.value == true) {
                    $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: "/admin/users/main-category/delete/"+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function (resp) {
                            if(resp.status == "success"){
                                $('.rightbar-overlay').css('display', 'none');
                                Swal.fire("Deleted!", resp.message, "success");
                                reloadTable(resp.category);
                            }
                        }
                    });
                    
                }
            });
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
                    
                    if(resp.status == 'success'){
                        // Request was successful
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        $('.modal-btns').prop('disabled', false);
                        showAjaxSuccess(message = resp.message , fullid);
                        
                        //
                        reloadTable(resp.category);
                        
                    }else{
                        // Request was unsuccessful
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        showAjaxError(message = resp.message , fullid);
                    }
                   
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Error occurred during the request
                    console.error('Error:', textStatus, errorThrown);
                    showAjaxError(message = "Something went wrong" , fullid);
                }
                });
            });
        });
    </script>

   

@stop