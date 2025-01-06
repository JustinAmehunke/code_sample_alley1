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
                    <div id="accordion" class="custom-accordion">
                        @php
                            $action_modules = App\Models\ActionModule::where('status', 0)->orderBy('id', 'Asc')->get();
                        @endphp
                        @if ($action_modules)
                            @foreach ($action_modules as $action_module)
                            <div class="card mb-1 shadow-none">
                                <a href="#collapse{{$action_module->id}}" class="text-dark collapsed" data-bs-toggle="collapse"
                                                aria-expanded="true"
                                                aria-controls="collapseOne">
                                    <div class="card-header tab-primary" id="headingOne">
                                        <h6 class="m-0 tab-white">
                                            <i class="ri-star-s-fill align-middle me-2"></i>
                                             <span id="module_title_{{$action_module->id}}">{{$action_module->module_name}}</span> ID: {{$action_module->id}}
                                            <i class="mdi mdi-minus float-end accor-plus-icon"></i>
                                        </h6>
                                    </div>
                                </a>
    
                                <div id="collapse{{$action_module->id}}" class="collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <div class="card">
                                            <h5 class="card-header">Job Grade Approvals for Module -> <span id="module_{{$action_module->id}}_name_title">{{$action_module->module_name}}</span></h5>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <form id="form_{{$action_module->id}}_module" action="/super-admin/update/action-module/" method="POST" >
                                                                @csrf
                                                                <div class="col-md-6">
                                                                    <div class="mb-3 position-relative">
                                                                        <label for="validationTooltip04" class="form-label">Module Name</label>
                                                                        <input type="text" class="form-control" name="module_name" id="module_name" value="{{$action_module->module_name}}" required="">
                                                                        <input type="hidden" name="mod_id" value="{{$action_module->id}}">
                                                                        <div class="invalid-tooltip">
                                                                            Please provide a valid state.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <button class="btn btn-primary" style="margin-right: 20px;" type="submit" id="btn_{{$action_module->id}}_module">Update</button>
                                                                    <span class="showajaxfeed" id="showajax_feed_{{$action_module->id}}_module">
                                                                   </span>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <form id="form_{{$action_module->id}}_sub-module" action="/super-admin/save/update/job/approval/limit" method="POST">
                                                                    @csrf
                                                                    <table class="table table-bordered mb-3">
            
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th>Grade</th>
                                                                                <th>Can Approve</th>
                                                                                <th>Same User Approval <i class="fa fa-exclamation"></i></th>
                                                                                <th>Approval Value Limit (LC)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $jobgrades = App\Models\JobGrade::all();
                                                                            @endphp
                                                                            @if ($jobgrades)
                                                                                @foreach ($jobgrades as $jobgrade)
                                                                                @php
                                                                                    $jobgradeapproval = App\Models\JobGradeApprovalLimit::where('tbl_job_grades_id', $jobgrade->id)->where('tbl_actions_module_id', $action_module->id)->get();
                                                                                @endphp
                                                                                <tr>
                                                                                    <th scope="row">{{$jobgrade->job_grade}}
                                                                                        <input type="hidden" name="grade_id[]" value="{{$jobgrade->id}}">
                                                                                        <input type="hidden" name="perm_id[]" value="{{count($jobgradeapproval)?$jobgradeapproval[0]->id:0}}">
                                                                                    </th>
                                                                                    <td>
                                                                                        {!! yesNoOptions('can_approve[]', count($jobgradeapproval)?$jobgradeapproval[0]->can_approve:0) !!}
                                                                                    </td>
                                                                                    <td>
                                                                                        {!! yesNoOptions('same_user_approval[]', count($jobgradeapproval)?$jobgradeapproval[0]->same_user_approval:0) !!}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="approval_limit[]" class="form-control" value="{{count($jobgradeapproval)?$jobgradeapproval[0]->approval_limit:0}}"   readonly>
                                                                                        <input type="hidden"  name="module_id" value="{{$action_module->id}}">
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            @endif
                                                                           
                                                                        </tbody>
                                                                    </table>
                                                                    <div>
                                                                        <button class="btn btn-primary" style="margin-right: 20px;" type="submit" id="btn_{{$action_module->id}}_sub-module">Save/Update</button>
                                                                        <span class="showajaxfeed" id="showajax_feed_{{$action_module->id}}_sub-module">
                                                                       </span>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card">
                                                            <h5 class="card-header">Sub Modules  
                                                                <button type="button" id="new_sub-module" data-module="{{$action_module->id}}" class="btn btn-primary btn-sm waves-effect waves-light new_module mt-1">
                                                                    <i class="ri-add-fill align-middle me-2"></i> Add New Sub Module
                                                                </button>
                                                            </h5>
                                                            <div class="card-body">
                                                                <div>
                                                                    @php
                                                                        $actionSubModule = App\Models\ActionSubModule::where('tbl_actions_module_id', $action_module->id)->where('deleted', 0)->orderBy('id', 'Asc')->get();
                                                                    @endphp
                                                                    @if ($actionSubModule)
                                                                        @foreach ($actionSubModule as $action_sub_module)
                                                                            <button type="button" data-module="{{$action_module->id}}" data-submodule="{{$action_sub_module->id}}" 
                                                                                data-module_name="{{$action_module->module_name}}" data-submodule_name="{{$action_sub_module->sub_module}}"
                                                                                data-use_module="{{$action_sub_module->use_module}}" id="action_submodule_{{$action_sub_module->id}}" 
                                                                                {{-- btn btn-primary waves-effect waves-light --}}
                                                                                class="btn btn-outline-primary waves-effect waves-light w-lg mb-1 action_submodule submodule_module_data_{{$action_module->id}}">
                                                                                <i class="ri-star-s-fill align-middle me-2"></i>
                                                                                <span id="action_submodule_{{$action_sub_module->id}}_html">{{$action_sub_module->sub_module}}</span>
                                                                                <span class="align-middle ms-2">ID: {{$action_sub_module->id}}</span> 
                                                                            </button>
                                                                        @endforeach
                                                                    @endif
                                                                        {{-- <button type="button" class="btn btn-outline-light waves-effect w-lg mb-1">
                                                                            <i class="ri-star-s-fill align-middle me-2"></i>
                                                                            Light
                                                                            <span class="align-middle ms-2">ID: 2</span> 
                                                                        </button> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <h5 class="card-header">EndPoint Management
                                                                <button type="button" id="endpoint_global" data-module="{{$action_module->id}}" 
                                                                    data-submodule="null"
                                                                    data-endpoint="global"
                                                                    data-submodule_name="Global"
                                                                     class="btn btn-primary btn-sm waves-effect waves-light mt-1 action_endpoint">
                                                                    <i class="ri-add-fill align-middle me-2"></i> Global Endpoints
                                                                </button>
                                                            </h5>
                                                            <div class="card-body">
                                                                <div>
                                                                    @if ($actionSubModule)
                                                                        @foreach ($actionSubModule as $action_sub_module)
                                                                            <button type="button" data-module="{{$action_module->id}}" data-submodule="{{$action_sub_module->id}}" 
                                                                                data-module_name="{{$action_module->module_name}}" data-submodule_name="{{$action_sub_module->sub_module}}"
                                                                                data-endpoint="{{$action_sub_module->id}}" id="action_submodule_{{$action_sub_module->id}}_ep" 
                                                                                class="btn btn-outline-primary waves-effect waves-light w-lg mb-1 action_endpoint">
                                                                                <i class="ri-star-s-fill align-middle me-2"></i>
                                                                                <span id="action_submodule_{{$action_sub_module->id}}_html_ep">{{$action_sub_module->sub_module}}</span>
                                                                                <span class="align-middle ms-2">ID: {{$action_sub_module->id}}</span> 
                                                                            </button>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
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
{{-- Edit Sub Module Modal --}}
<div id="submodule_details" class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Sub Module Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <h5 class="card-header">Create/Edit Status for Sub Module -&gt; <span id="module_name_title"></span> : <span id="submodule_name_title"></span></h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <form id="form_update_sub-module" action="/super-admin/update/action-submodule/" method="POST">
                                        @csrf          
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative">
                                                <label for="validationTooltip04" class="form-label">Sub Module Name</label>
                                                <input type="text" class="form-control" name="submodule_name" id="submodule_name" value="" required="">
                                                <input type="hidden" name="submod_id" id="submod_id">
                                                <input type="hidden" name="trigger_btn" id="trigger_btn">
                                                
                                                <div class="invalid-tooltip">
                                                    Please provide a valid state.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="use_module" value="1" id="use_module">
                                                    <label class="form-check-label" for="formCheck2">
                                                        Use Module Approval Rules
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary" style="margin-right: 20px;" type="submit" id="btn_update_sub-module">Update</button>
                                            <span class="showajaxfeed" id="showajax_feed_update_sub-module">
                                           </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="form_details_sub-module" action="/super-admin/save/update/action-submodule/details" method="POST">
                                            @csrf 
                                            <table class="table table-bordered mb-2">
            
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ID</th>
                                                        <th>Status Name</th>
                                                        <th>Workflow no.</th>
                                                        <th>Stage</th>
                                                        <th>EndPoint</th>
                                                        {{-- <th>Actions</th> --}}
                                                        <th><i class="ri-delete-bin-line"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tr-cont" id="tr-cont">
                                                   
                                                </tbody>
                                            </table>
                                            <div id="ajax-loading" style="display: none;">
                                                <div style="display: flex; justify-content: center; position: relative; top: -8px; background: #f8f9fa;">
                                                     {{-- <div class="spinner-border text-primary m-1" role="status">
                                                         <span class="sr-only">Loading...</span>
                                                     </div> --}}
                                                     <div class="spinner-grow text-primary m-1" role="status">
                                                         <span class="sr-only">Loading...</span>
                                                     </div>
                                                </div>
                                            </div>
                                            <div id="ajax-nodata" style="display: none;">
                                                <div style="display: flex; justify-content: center; position: relative; top: -8px; background: #f8f9fa;">
                                                     {{-- <div class="spinner-border text-primary m-1" role="status">
                                                         <span class="sr-only">Loading...</span>
                                                     </div> --}}
                                                     <div class="text- m-1" role="status">
                                                         <span class="">No data found.</span>
                                                     </div>
                                                </div>
                                             </div>
                                            <div>
                                                <button type="button" id="add_row" class="btn btn-primary waves-effect btn-sm waves-light modal-btns" style="float: right;">
                                                    <i class="ri-add-fill align-middle"></i> Add Another
                                                </button>
                                            </div>
                                            <div style="margin-top:30px;">
                                                <button class="btn btn-primary modal-btns" style="margin-right: 20px;" type="submit" id="btn_details_sub-module">Save/Update</button>
                                                <span class="showajaxfeed" id="showajax_feed_details_sub-module"></span>
                                               <input type="hidden" id="module_id" name="module_id">
                                               <input type="hidden" id="submodule_id" name="submodule_id">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- Edit Endpoint --}}
<div id="endpoint_details" class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Endpoint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <h5 class="card-header">EndPoints for -&gt; <span id="ep_module_name_title"></span> : <span id="ep_submodule_name_title"></span></h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="form_endpoint_details" action="/super-admin/save/update/endpoint/details" method="POST">
                                            @csrf 
                                            <table class="table table-bordered mb-2">
            
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Endpoint Name</th>
                                                        <th>Endpoint Type</th>
                                                        <th>Endpoint ID</th>
                                                        {{-- <th>EndPoint</th> --}}
                                                        <th>Actions</th>
                                                        <th><i class="ri-delete-bin-line"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tr-cont-ep" id="tr-cont-ep">
                                                   
                                                </tbody>
                                            </table>
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
                                            <div>
                                                <button type="button" id="add_eprow" class="btn btn-primary waves-effect btn-sm waves-light modal-btns" style="float: right;">
                                                    <i class="ri-add-fill align-middle"></i> Add Another
                                                </button>
                                            </div>
                                            <div style="margin-top:30px;">
                                                <button class="btn btn-primary modal-btns" style="margin-right: 20px;" type="submit" id="btn_details_sub-module">Save/Update</button>
                                                <span class="showajaxfeed" id="showajax_feed_endpoint_details"></span>
                                               <input type="hidden" id="ep_module_id" name="module_id">
                                               <input type="hidden" id="ep_submodule_id" name="submodule_id">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
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