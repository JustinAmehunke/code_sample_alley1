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
                    <button type="button" id="user_subcategory" style="float: right;" data-complaint="" class="btn btn-primary btn-sm waves-effect waves-light user_subcategory">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-bordered mb-2">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Main Category</th>
                                <th>Sub Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tr-cont-ep" id="tr-cont-ep">
                            @foreach ($user_subcategories as $key => $user_subcategory)
                                <tr>
                                    <td>
                                        {{++$key}}
                                        <input type="hidden" name="" value="{{$user_subcategory->id}}">
                                    </td>
                                    <td>
                                        {{$user_subcategory->usercategory->user_category}}
                                        <input type="hidden" name="category_name" value="{{$user_subcategory->usercategory->user_category}}">
                                        <input type="hidden" name="category_id" value="{{$user_subcategory->tbl_user_category_id}}">
                                    </td>
                                    <td>
                                        {{$user_subcategory->category_name}}
                                        <input type="hidden" name="sub_category_name" value="{{$user_subcategory->category_name}}">
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <button type="button"  data-subcategory="{{$user_subcategory->id}}" class="btn btn-success btn-sm waves-effect waves-light user_subcategory">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                    
                                            <button type="button"  data-subcategory="{{$user_subcategory->id}}" class="btn btn-danger btn-delete btn-sm waves-effect waves-light">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                          
                        </tbody>
                    </table>
                    <form id="form_endpoint_details" action="/admin/save/update/user/sub-category" method="POST">
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
                        </div>
                        <div style="margin-top:30px;">
                            <button class="btn btn-primary modal-btns" style="margin-right: 20px;" type="submit" id="btn_details_sub-module">Save/Update</button>
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
<div id="user_subcategory_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_user_subcategory" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_user_subcategory"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_user_subcategory" class="btn btn-primary waves-effect waves-light">Save changes</button>
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

        $(function(){
            localStorage.removeItem('categoryData');
            let categoryData = @json($user_categories);
            // Store in local storage
            localStorage.setItem('categoryData', JSON.stringify(categoryData));
            // console.log(categoryData);

            // category_name,
            // tbl_user_category_id
        });

        function reloadTable(data){
            $('#tr-cont-ep').empty();
            if(data){
                let key = 0;
                data.forEach(subcategory => {
                    $('#tr-cont-ep').append(`
                    <tr>
                        <td>
                            ${++key}
                            <input type="hidden" name="" value="${subcategory.id}">
                        </td>
                        <td>
                            ${subcategory.usercategory.user_category}
                            <input type="hidden" name="category_name" value="${subcategory.usercategory.user_category}">
                            <input type="hidden" name="category_id" value="${subcategory.tbl_user_category_id}">
                        </td>
                        <td>
                            ${subcategory.category_name}
                            <input type="hidden" name="sub_category_name" value="${subcategory.category_name}">
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Second group">
                                <button type="button"  data-subcategory="${subcategory.id}" class="btn btn-success btn-sm waves-effect waves-light user_subcategory">
                                    <i class="ri-edit-line"></i>
                                </button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Second group">
                        
                                <button type="button"  data-subcategory="${subcategory.id}" class="btn btn-danger btn-delete btn-sm waves-effect waves-light">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    `);
                });

                $('#user_subcategory_modal').modal('hide');
            }
        }
    </script>

    <script>
        $(document).on('click', '.user_subcategory', function(){
            $('#user_subcategory_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let type_id = $(this).data('subcategory');
            //Get category data
            let storedCategoryData = localStorage.getItem('categoryData');
            let categoryData = JSON.parse(storedCategoryData);
            // console.log(categoryData);
            
           
            //append content into modal
            if(type_id){
                let row = $(this).closest('tr');
                // var num = row.find('td:eq(0)').text();
                let subcategoryId = row.find('td:eq(0) input:hidden').val();
                let category_name = row.find('td:eq(1) input[name="category_name"]').val();
                let categoryId = row.find('td:eq(1) input[name="category_id"]').val();
                let sub_category_name = row.find('td:eq(2) input[name="sub_category_name"]').val();
                
                $('#modalLabel').html('Updating User Sub Category');
                $('#form_user_subcategory').attr('action', '/admin/users/sub-category/update');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Main Category</label>
                        <select class="form-select" name="tbl_user_category_id" id="main_category">
                          
                        </select>
                        <input type="hidden" name="id" value="${subcategoryId}">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Sub Category</label>
                        <input type="text" class="form-control" name="subcategory_name" value="${sub_category_name}" id="sub_category"  required="">
                    </div>
                `);
                //
                $('#main_category').empty();
                // console.log(categoryData);
                if(categoryData){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#main_category').append(option);
                    categoryData.forEach(category => {
                        if(category.id == categoryId){
                            option = `<option value="${category.id}" selected="">${category.user_category}</option>`;
                        }else{
                            option = `<option value="${category.id}">${category.user_category}</option>`;
                        }
                        $('#main_category').append(option);
                    });
                }
            }else{
                $('#modalLabel').html('Adding New User Sub Category');
                $('#form_user_subcategory').attr('action', '/admin/users/sub-category/save');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Main Category</label>
                        <select class="form-select" name="tbl_user_category_id" id="main_category" required="">
                          
                        </select>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Sub Category</label>
                        <input type="text" class="form-control" name="subcategory_name" id="sub_category"  required="">
                    </div>
                `);
                $('#main_category').empty();
                if(categoryData){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#main_category').append(option);
                    categoryData.forEach(category => {
                        option = `<option value="${category.id}">${category.user_category }</option>`;
                        $('#main_category').append(option);
                    });
                }
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

                // console.log(action);
                // return ;
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
                        reloadTable(resp.subcategory);
                        
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

        //

        $(document).on('click', '.btn-delete', function(){
            let id = $(this).data('subcategory');
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
                        url: "/admin/users/sub-category/delete/"+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function (resp) {
                            if(resp.status == "success"){
                                $('.rightbar-overlay').css('display', 'none');
                                Swal.fire("Deleted!", resp.message, "success");
                                reloadTable(resp.subcategory);
                            }
                        }
                    });
                    
                }
            });
        });
    </script>
   

@stop