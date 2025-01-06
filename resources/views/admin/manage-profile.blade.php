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
                    <button type="button" id="new_module" style="float: right;" data-module="" class="btn btn-primary btn-sm waves-effect waves-light new_module">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                   
                </div>
            </div>
           
        </div>
       
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="new_module_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_new_module" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_new_module"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_new_module" class="btn btn-primary waves-effect waves-light">Save changes</button>
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
    <script>
         $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $('.new_module').on('click', function(){
            $('#new_module_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let mod_id = $(this).data('module');

            //append content into modal
            if(id == 'new_module'){
                $('#modalLabel').html('Adding New Module');
                $('#form_new_module').attr('action', '/super-admin/create/action-module/');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Module Name</label>
                        <input type="text" class="form-control" name="module_name" id="module_name"  required="">
                    </div>
                `);
            }
            if(id == 'new_sub-module'){
                $('#modalLabel').html('Adding New Sub Module');
                $('#form_new_module').attr('action', '/super-admin/create/action-submodule/');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Sub Module Name</label>
                        <input type="text" class="form-control" name="module_name" id="module_name"  required="">
                        <input type="hidden" name="module_id" id="module_id" value="${mod_id}"  required="">
                    </div>
                `);
            }
        });
        // $('#new_sub-module').on('click', function(){
        //     $('#new_sub-module_modal').modal('show');
        // });
    </script>
    <script>
        $('.action_submodule').on('click', function(){
            $('#submodule_details').modal('show');
            $('#tr-cont').empty();
            // $('.modal-btns').css('cursor', 'not-allowed');
            $('.modal-btns').prop('disabled', true);
            
            // get data from on button element
            let id = this.id;
            let module_id = $(this).data('module');
            let submodule_id = $(this).data('submodule');
            let submodule_name = $(this).data('submodule_name');
            let module_name = $(this).data('module_name');
            let use_module = $(this).data('use_module');
            // add module and sub module id to sub module details the form in modal
            $('#module_id').val(module_id);
            $('#submodule_id').val(submodule_id);
            $('#submod_id').val(submodule_id);
            $('#submodule_name').val(submodule_name);
            $('#trigger_btn').val(id);
            if(use_module){$('#use_module').prop('checked', true);}
            $('#submodule_name_title').html(submodule_name);
            $('#module_name_title').html(module_name);
            // clear local storage
            localStorage.removeItem('ajax-data');
            // show loader
            $('#ajax-loading').css('display', 'block');
            //get submodule details data
            $.ajax({
                url: '/super-admin/view/action-submodule/'+module_id+'/'+submodule_id,
                type: 'POST',
                data: 'nth',
                success: function(resp) {
                    localStorage.setItem('ajax-data', JSON.stringify(resp));
                    let count = $('.tr').length;
                    $('#ajax-loading').css('display', 'none');
                    $('.modal-btns').prop('disabled', false);
                    // $('.modal-btns').css('cursor', 'pointer');
                    if(resp){
                        if(resp.custom_list && resp.custom_list.length > 0){
                            resp.custom_list.forEach(list => {
                                let x = ++count
                                $('#tr-cont').append(`
                                    <tr class="tr" id="tr-${x}">
                                        <th scope="row">
                                            ${x}
                                            <input type="hidden" name="application_status_id[]" value="${list.id}">
                                        </th>
                                        <td>
                                            ${list.id}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="status_name[]" id="" value="${list.status_name}" >
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="workflow_no[]" id="" value="${list.workflow_no}" style="width: 80px;">
                                        </td>
                                        <td>
                                            <select class="form-select" name="stage[]" id="stage_${x}">
                                            
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select" name="endpoint[]" id="endpoint_${x}">
                                                
                                            </select>
                                        </td>
                                       
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="delete[]" value="${list.id}" id="formCheck2">
                                        </td>
                                    </tr>
                                `);

                                if( resp.stages){
                                    resp.stages.forEach(stage => {
                                        let option;
                                        if(stage.id == list.tbl_application_status_stage_id){
                                            option = `<option value="${stage.id}" selected="">${stage.stage_name}[${stage.id}]</option>`;
                                        }else{
                                            option = `<option value="${stage.id}">${stage.stage_name}[${stage.id}]</option>`;
                                        }
                                        $('#stage_'+x).append(option);
                                    });
                                }
                            
                                if(resp.endpoints){
                                    resp.endpoints.forEach(endpoint => {
                                        let option;
                                        if(endpoint.id == list.tbl_application_status_endpoints_id){
                                            option = `<option value="${endpoint.id}" selected="">${endpoint.endpoint_name}[${endpoint.id}]</option>`;
                                        }else{
                                            option = `<option value="${endpoint.id}">${endpoint.endpoint_name}[${endpoint.id}]</option>`;
                                        }
                                        $('#endpoint_'+x).append(option);
                                    });
                                }
                            });
                        }else{
                            $('#ajax-nodata').css('display', 'block');
                            setTimeout(() => {
                                $('#ajax-nodata').css('display', 'none');
                            }, 2000);
                        }
                        
                    }else{
                        $('#ajax-nodata').css('display', 'block');
                        setTimeout(() => {
                            $('#ajax-nodata').css('display', 'none');
                        }, 2000);
                    }
                   
                }
            });
        });

        $('#add_row').on('click', function(){
            let count = $('.tr').length;
            let x = ++count;
            let data = JSON.parse(localStorage.getItem('ajax-data'));
           
            $('#tr-cont').append(`
                <tr class="tr" id="tr-${x}">
                    <th scope="row">
                        ${x}
                        <input type="hidden" name="application_status_id[]" value="">
                    </th>
                    <td>
                        
                    </td>
                    <td>
                        <input type="text" name="status_name[]" id="" class="form-control" >
                    </td>
                    <td>
                        <input type="number" name="workflow_no[]" id="" class="form-control" value="" style="width: 80px;">
                    </td>
                    <td>
                        <select class="form-select" name="stage[]" id="stage_${x}">
                           
                        </select>
                    </td>
                    <td>
                        <select class="form-select" name="endpoint[]" id="endpoint_${x}">
                          
                        </select>
                    </td>
                    <td>

                    </td>
                    <td>
                        <button type="button"  data-tr="${x}" class="btn btn-danger btn-remove btn-sm waves-effect waves-light">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `);

            if(data.stages){
                data.stages.forEach(stage => {
                    let option = `<option value="${stage.id}">${stage.stage_name}[${stage.id}]</option>`;
                    $('#stage_'+x).append(option);
                });
            }
            
            if(data.endpoints){
                data.endpoints.forEach(endpoint => {
                    let option = `<option value="${endpoint.id}">${endpoint.endpoint_name}[${endpoint.id}]</option>`;
                    $('#endpoint_'+x).append(option);
                });
            }
           
        });

        $(document).on('click', '.btn-remove', function(){
            let num = $(this).data('tr');
            if($(this).data('type')){
                $('#ep-tr-'+num).remove();
            }else{
                $('#tr-'+num).remove();
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
                    
                    if(resp.status == 'success'){
                        // Request was successful
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        $('.modal-btns').prop('disabled', false);
                        showAjaxSuccess(message = resp.message , fullid);
                        if(id == 'new'){
                            $('#new_module_modal').modal('hide');
                            location.reload();
                        }
                        if(id == 'details'){
                            // $('#submodule_details').modal('hide');
                        }
                        if(id == 'update'){
                            // $('#submodule_details').modal('hide');
                            let trigger_btn = $('#trigger_btn').val();
                            let updated_submodule_name = $('#submodule_name').val();
                            console.log(trigger_btn);
                            // update data params on trigger button
                            $('#'+trigger_btn).data('submodule_name', updated_submodule_name);
                            if($('#use_module').prop('checked')){$('#'+trigger_btn).data('use_module', 1);}else{$('#'+trigger_btn).data('use_module', 0);}
                            // update button text
                            $('#'+trigger_btn+'_html').html(updated_submodule_name);
                            // update endpoint button text
                            $('#'+trigger_btn+'_ep').data('submodule_name', updated_submodule_name)
                            $('#'+trigger_btn+'_html_ep').html(updated_submodule_name);

                            // update modal title
                            $('#submodule_name_title').html(updated_submodule_name);
                        }

                        if(type == 'module'){
                            //get updated module name
                            let updated_module_name = form.find('#module_name').val();
                            //update the title
                            $('#modue_title_'+id).html(updated_module_name);
                            $('#module_'+id+'_name_title').html(updated_module_name);
                            //update the data params of submodule buttons under this module
                            $('.submodule_module_data_'+id).attr("data-module_name", updated_module_name);
                        }
                        
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

    {{-- EndPoint --}}
    <script>
        $('.action_endpoint').on('click', function(){
            $('#endpoint_details').modal('show');
            $('#tr-cont-ep').empty();
            // $('.modal-btns').css('cursor', 'not-allowed');
            $('.modal-btns').prop('disabled', true);
            
            // get data from on button element
            let id = this.id;
            let module_id = $(this).data('module');
            let submodule_id = $(this).data('submodule');
            let submodule_name = $(this).data('submodule_name');
            let module_name = $(this).data('module_name');
            let endpoint = $(this).data('endpoint');
            // add module and sub module id to sub module details the form in modal
            $('#ep_module_id').val(module_id);
            $('#ep_submodule_id').val(submodule_id);
            // $('#ep_submod_id').val(submodule_id);
            // $('#ep_submodule_name').val(submodule_name);
            // $('#ep_trigger_btn').val(id);
            // if(use_module){$('#use_module').prop('checked', true);}
            $('#ep_submodule_name_title').html(submodule_name);
            $('#ep_module_name_title').empty();
            $('#ep_module_name_title').html(module_name);
            // clear local storage
            localStorage.removeItem('ajax-data');
            // show loader
            $('#ep_ajax-loading').css('display', 'block');
            //get submodule details data
            $.ajax({
                url: '/super-admin/view/endpoint/details/'+module_id+'/'+submodule_id+'/'+endpoint,
                type: 'POST',
                data: 'nth',
                success: function(resp) {
                    localStorage.setItem('ajax-data', JSON.stringify(resp));
                    let count = $('.tr').length;
                    $('#ep_ajax-loading').css('display', 'none');
                    $('.modal-btns').prop('disabled', false);
                    // $('.modal-btns').css('cursor', 'pointer');
                    if(resp){
                        if(resp.endpoints && resp.endpoints.length > 0){
                            resp.endpoints.forEach(list => {
                                let x = ++count
                                $('#tr-cont-ep').append(`
                                    <tr class="ep-tr" id="ep-tr-${x}">
                                        <th scope="row">
                                            ${x}
                                            <input type="hidden" name="endpoint_details_id[]" value="${list.id}">
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="endpoint_name[]" id="" value="${list.endpoint_name}" >
                                        </td>
                                        <td>
                                            <select class="form-select" name="endpoint_type[]" id="endpoint_${x}">
                                                
                                            </select>
                                        </td>
                                        <td>
                                            ${list.id}
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="delete[]" value="${list.id}" id="formCheck2">
                                        </td>
                                    </tr>
                                `);
                            
                                if(resp.endpoint_status){
                                    resp.endpoint_status.forEach(endpoint => {
                                        let option;
                                        if(endpoint.value == list.endpoint_id){
                                            option = `<option value="${endpoint.value}" selected="">${endpoint.label}</option>`;
                                        }else{
                                            option = `<option value="${endpoint.value}">${endpoint.label}</option>`;
                                        }
                                        $('#endpoint_'+x).append(option);
                                    });
                                }
                            });
                        }else{
                            $('#ep_ajax-nodata').css('display', 'block');
                            setTimeout(() => {
                                $('#ep_ajax-nodata').css('display', 'none');
                            }, 2000);
                        }
                        
                    }else{
                        $('#ep_ajax-nodata').css('display', 'block');
                        setTimeout(() => {
                            $('#ep_ajax-nodata').css('display', 'none');
                        }, 2000);
                    }
                   
                }
            });
        });

        $('#add_eprow').on('click', function(){
            let count = $('.ep-tr').length;
            let x = ++count;
            let data = JSON.parse(localStorage.getItem('ajax-data'));
           
            $('#tr-cont-ep').append(`
                <tr class="ep-tr" id="ep-tr-${x}">
                    <th scope="row">
                        ${x}
                        <input type="hidden" name="endpoint_details_id[]" value="">
                    </th>
                    <td>
                        <input type="text" class="form-control" name="endpoint_name[]" id="" value="" >
                    </td>
                    <td>
                        <select class="form-select" name="endpoint_type[]" id="endpoint_${x}">
                            
                        </select>
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        <button type="button"  data-tr="${x}" data-type="ep" class="btn btn-danger btn-remove btn-sm waves-effect waves-light">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                    <td>
                        <input type="checkbox" class="form-check-input" name="delete[]" value="" id="formCheck2">
                    </td>
                </tr>
            `);
            
            if(data.endpoint_status){
                data.endpoint_status.forEach(endpoint => {
                    let option;
                    option = `<option value="${endpoint.value}">${endpoint.label}</option>`;
                    $('#endpoint_'+x).append(option);
                });
            }
           
        });
    </script>
   

@stop