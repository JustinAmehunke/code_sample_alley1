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
                    <button type="button" id="new_workflow" style="float: right;" data-complaint="" class="btn btn-primary btn-sm waves-effect waves-light new_workflow">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <div class="btn-group me-1 mt-2 mb-3">
                        <button class="btn btn-primary waves-effect btn-sm waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-application-cog align-middle"></i> Choose Category <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                        @if ($document_setups)
                            @foreach ($document_setups as $key => $document_setup)
                                <a class="dropdown-item" href="{{ route('admin-document-workflow-setup-details', ['workflow' => base64_encode($document_setup->id)]) }}"><b>{{$document_setup->name}}</b></a>
                            @endforeach
                        @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item new_workflow" href="#">Create New</a>
                        </div>
                    </div>
                    <form id="form" action="/admin/documents/save/update/workflow-setup-details" method="Post">
                        @csrf
                        <table class="table table-bordered mb-2">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Workflow Type</th>
                                    <th>Reference</th>
                                    <th>Require Evidence</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tr-cont-ep" id="tr-cont-ep">
                                @if (isset($document_setup_details))
                                    @foreach ($document_setup_details as $key => $document_setup_detail)
                                            <tr class="ep-tr" id="ep-tr-{{++$key}}">
                                                <td>
                                                    {{$key}}
                                                    <input type="hidden" name="document_setup_detail_id[]" value="{{$document_setup_detail->id}}">
                                                    <input type="hidden" name="document_setup_id" value="{{isset($document_setup_id)?$document_setup_id:''}}">
                                                </td>
                                                <td>
                                                    <select name="workflow_type_id[]" id="workflow_{{$key}}" class="form-select workflow" readonly>
                                                        @foreach ($workflow_types as $workflow_type)
                                                            @if ($workflow_type->id == $document_setup_detail->tbl_document_workflow_type_id)
                                                                <option value="{{$workflow_type->id}}" selected>{{$workflow_type->name}}</option>
                                                            @else
                                                                <option value="{{$workflow_type->id}}">{{$workflow_type->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    {{-- tbl_document_workflow_type_id --}}
                                                    <select class="form-select reference" name="reference[]" id="reference_{{$key}}">
                                                        @if ($document_setup_detail->tbl_document_workflow_type_id == 1)
                                                            @foreach ($departments as $department) 
                                                                @if($department->id == $document_setup_detail->reference) 
                                                                    <option value="{{$department->id}}" selected="">{{$department->department_name}}</option>
                                                                @else
                                                                    <option value="{{$department->id}}">{{$department->department_name}}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif ($document_setup_detail->tbl_document_workflow_type_id == 2)
                                                            @foreach ($document_types as $document_type) 
                                                                @if($document_type->id == $document_setup_detail->reference) 
                                                                    <option value="{{$document_type->id}}" selected="">{{$document_type->document_name}}</option>
                                                                @else
                                                                    <option value="{{$document_type->id}}">{{$document_type->document_name}}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif ($document_setup_detail->tbl_document_workflow_type_id == 3)
                                                            @foreach ($users as $user) 
                                                                @if($user->id == $document_setup_detail->full_name) 
                                                                    <option value="{{$user->id}}" selected="">{{$user->full_name}}</option>
                                                                @else
                                                                    <option value="{{$user->id}}">{{$user->full_name}}</option>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <select name="reference[]" id="reference" class="form-control" readonly>
                                                                <option value=""></option>
                                                            </select>
                                                        @endif
                                                    </select>
                                                    {{-- {{$document_setup_detail}} --}}
                                                    {{-- <input type="hidden" name="document_name" value="{{$document_setup_detail->document_name}}"> --}}
                                                </td>
                                                <td>
                                                    {!! yesNoOptions('require_evidence[]', $document_setup_detail ? $document_setup_detail->require_evidence:0) !!}
                                                </td>
                                                {{-- <td>
                                                -
                                                </td> --}}
                                                <td>
                                                    <button type="button"  data-tr="{{$key}}" data-type="ep" class="btn btn-danger btn-remove all-btns btn-sm waves-effect waves-light">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    {{-- <div class="btn-group" role="group" aria-label="Second group">
                                                        <button type="button"  data-complaint="{{$document_setup_detail->id}}" class="btn btn-success btn-sm waves-effect waves-light new_view_types">
                                                            <i class="ri-edit-line"></i>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group" role="group" aria-label="Second group">
                                                
                                                        <button type="button"  data-complaint="{{$document_setup_detail->id}}" class="btn btn-danger btn-sm waves-effect waves-light">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div> --}}
                                                </td>
                                            </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if (isset($document_setup_details))
                            <div>
                                <button type="button" id="add_eprow" class="btn btn-primary all-btns waves-effect btn-sm waves-light modal-btns" style="float: right;">
                                    <i class="ri-add-fill align-middle"></i> Add Another
                                </button>
                            </div>
                            <div style="margin-top:30px;">
                                <button class="btn btn-primary modal-btns new-update-btns" style="margin-right: 20px;" type="submit" id="btn_details_sub-module">Save/Update</button>
                                <span class="showajaxfeed" id="showajax_feed_0_workflow"></span>
                            <input type="hidden" id="ep_module_id" name="module_id">
                            <input type="hidden" id="ep_submodule_id" name="submodule_id">
                            </div>
                        @endif
                    </form>
                   
                </div>
            </div>
           
        </div>
       
    </div>

    <div id="new_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Adding New Document Workflow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_new_workflow" action="/admin/documents/save/workflow" method="POST" >
                    <div class="modal-body">
                            @csrf
                            <div class="col-md-12">
                                <div id="modal_concent">
                                  
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <span class="showajaxfeed" id="showajax_feed_new_workflow"></span>
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btn_new_view_types" class="btn btn-primary new-btn waves-effect waves-light">Save changes</button>
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
        $(function(){
            // localStorage.removeItem('categoryData');
            //set local storage
            let documentproducts_Data = @json($document_products);
            // Store in local storage
            localStorage.setItem('documentproductsData', JSON.stringify(documentproducts_Data));
            
            // console.log(documentproductsData);
        });
    </script>
    <script>
        $(document).ready(function() {
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
            $('.alert-danger').fadeOut('slow');
        }, 3000); // 500 milliseconds = 0.5 seconds
    });
    </script>
    @if (isset($document_setup_id))
        <script>
            $(function(){
                // localStorage.removeItem('categoryData');
                //set local storage
                let departments_Data = @json($departments);
                let documents_Data = @json($document_types);
                let users_Data = @json($users);
                let workflow_Data = @json($workflow_types);
                let documentproducts_Data = @json($document_products);
                // Store in local storage
                localStorage.setItem('departmentsData', JSON.stringify(departments_Data));
                localStorage.setItem('documentsData', JSON.stringify(documents_Data));
                localStorage.setItem('usersData', JSON.stringify(users_Data));
                localStorage.setItem('workflowData', JSON.stringify(workflow_Data));
                localStorage.setItem('documentproductsData', JSON.stringify(documentproducts_Data));
                // console.log(departmentsData);
                // console.log(documentsData);
                // console.log(usersData);
                // console.log(workflowData);
                // console.log(documentproductsData);
            });
        </script>
    @endif

    <script>
        $('.new-update-btns').on('click', function(){
            $('.all-btns').prop('disabled', true);
            showAjaxLoading('Loading', 'showfeed_0_workflow', status = true);
            $('#form').submit();
        });
        $('.new-btn').on('click', function(){
            $('.new-btn').prop('disabled', true);
            showAjaxLoading('Loading', 'showfeed_new_workflow', status = true);
            $('#form_new_workflow').submit();
        });

        

        $(document).on('click', '.btn-remove', function(){
            let num = $(this).data('tr');
            if($(this).data('type')){
                $('#ep-tr-'+num).remove();
            }else{
                $('#tr-'+num).remove();
            }
            
        });
        $('.new_workflow').on('click', function(){
            $('#new_modal').modal('show');
            $('#modal_concent').empty();

            $('#modal_concent').append(`
                <div class="mb-3 position-relative">
                    <label for="validationTooltip04" class="form-label">Document Workflow Name</label>
                    <input type="text" class="form-control" name="name" value="" id="name"  required="">
                </div>
                <div class="mb-3 position-relative">
                    <label for="validationTooltip04" class="form-label">Product</label>
                    <select class="form-select" name="product" id="product">
                        
                    </select>
                </div>
            `);

            let documentproductsData = localStorage.getItem('documentproductsData');
            let document_productsData = JSON.parse(documentproductsData );
            console.log(document_productsData);
            if(document_productsData){
                let option = `<option value="" selected="">Select..</option>`;
                $('#product').append(option);
                document_productsData.forEach(document_product => {
                    option = `<option value="${document_product.id}">${document_product.product_name}</option>`;
                    $('#product').append(option);
                });
            }
        });

    </script>

    {{-- EndPoint --}}
    <script>
        let count = $('.ep-tr').length;
        $('#add_eprow').on('click', function(){
            let x = ++count;
            let data = JSON.parse(localStorage.getItem('workflowData'));

            $('#tr-cont-ep').append(`
                <tr class="ep-tr" id="ep-tr-${x}">
                    <th scope="row">
                        ${x}
                        <input type="hidden" name="endpoint_details_id[]" value="">
                    </th>
                    <td>
                        <select class="form-select workflow" name="workflow_type_id[]" id="workflow_${x}">
                            
                        </select>
                    </td>
                    <td>
                        <select class="form-select reference" name="reference[]" id="reference_${x}">
                            
                        </select>
                    </td>
                    <td>
                        <select class="form-select evidence" name="require_evidence[]" id="require_evidence_${x}">
                            <option value="0">No</option>
                            <option value="1">Yes</option> 
                        </select>
                    </td>
                    <td>
                        <button type="button"  data-tr="${x}" data-type="ep" class="btn btn-danger all-btns btn-remove btn-sm waves-effect waves-light">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            if(data){
                data.forEach(workflow_type => {
                    let option;
                    option = `<option value="${workflow_type.id}">${workflow_type.name}</option>`;
                    $('#workflow_'+x).append(option);
                });
            }
           
        });

        $(document).on('change', '.workflow', function(){
            let data = JSON.parse(localStorage.getItem('workflowData'));
            let departmentsData = JSON.parse(localStorage.getItem('departmentsData'));
            let document_typesData =  JSON.parse(localStorage.getItem('documentsData'));
            let usersData = JSON.parse(localStorage.getItem('usersData'));

            let module_id = $(this).val();
            let id = this.id.split('_')[1];
            $('#reference_'+id).empty();
            let option;
            if (module_id == 1) {
                departmentsData.forEach(department => {
                   option =`<option value="${department.id}" >${department.department_name}</option>`;
                   $('#reference_'+id).append(option);
                });
            }else if(module_id == 2){
                document_typesData.forEach(document_type => {
                   option =`<option value="${document_type.id}" >${document_type.document_name}</option>`;
                   $('#reference_'+id).append(option);
                });
            }else if(module_id == 3){
                usersData.forEach(user => {
                   option =`<option value="${user.id}">${user.full_name}</option>`;
                   $('#reference_'+id).append(option);
                });
            }else{
                option = `<option value=""></option>`;
                $('#reference_'+id).append(option);
            }
                                               
        });
    </script>
   

@stop