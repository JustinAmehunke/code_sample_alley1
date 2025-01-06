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
                    <button type="button" id="user_partner" style="float: right;" data-module="" class="btn btn-primary btn-sm waves-effect waves-light user_partner">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <div class="col-12">
                        @if(session('success_message'))
                          <div class="alert alert-success alert-dismissible fade show success-notification" role="alert">
                            {{ session('success_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                        @endif
                        <table id="datatable-buttons" class="table table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Partner Code</th>
                                <th>Partner Name</th>
                                <th>Type</th>
                                <th>Sub Type</th>
                                <th>Contact</th>
                                <th>Contact No</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
    
    
                            <tbody class="tr-cont-ep" id="tr-cont-ep">
                                @foreach ($partners as $key => $partner)
                                    <tr>
                                        <td>{{++$key}}
                                            <input type="hidden" name="branch_id" value="{{$partner->id}}">
                                        </td>
                                        <td>{{$partner->code}}
                                            <input type="hidden" name="org_code" value="{{$partner->code}}">
                                            <input type="hidden" name="org_id" value="{{$partner->id}}">
                                        </td>
                                        <td>{{$partner->org_name}}
                                            <input type="hidden" name="org_name" value="{{$partner->org_name}}">
                                        </td>
                                        <td>{{isset($partner->usercategory)?$partner->usercategory->user_category:''}}
                                            <input type="hidden" name="org_type" value="{{$partner->tbl_user_category_id}}">
                                        </td>
                                        <td>{{isset($partner->usersubcategory)?$partner->usersubcategory->category_name:''}}
                                            <input type="hidden" name="sub_org_type" value="{{$partner->tbl_sub_user_category_id}}">
                                        </td>
                                        <td>{{$partner->org_contact}}
                                            <input type="hidden" name="org_contact" value="{{$partner->org_contact}}">
                                            <input type="hidden" name="org_contact_email" value="{{$partner->org_contact_email}}">
                                            <input type="hidden" name="org_address" value="{{$partner->org_address}}">
                                        </td>
                                        <td>{{$partner->org_contact_no}}
                                            <input type="hidden" name="org_contact_no" value="{{$partner->org_contact_no}}">
                                            <input type="hidden" name="org_alt_contact_no" value="{{$partner->org_alt_contact_no}}">
                                            <input type="hidden" name="org_nic_no" value="{{$partner->org_nic_no}}">
                                            <input type="hidden" name="org_is_local" value="{{$partner->org_is_local}}">
                                            <input type="hidden" name="deleted" value="{{$partner->deleted}}">
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Second group">
                                                <button type="button"  data-partner="{{$partner->id}}" class="btn btn-success btn-sm waves-effect waves-light user_partner">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group" aria-label="Second group">
                                                <button type="button"  data-partner="{{$partner->id}}" class="btn btn-danger btn-delete btn-sm waves-effect waves-light ">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
    
                        <div id="ajax-loading" style="display: none;">
                            <div style="display: flex; justify-content: center; position: relative; top: -102px; background: #f8f9fa;">
                                    {{-- <div class="spinner-border text-primary m-1" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div> --}}
                                    <div class="spinner-grow text-primary m-1" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
           
        </div>
       
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="user_partner_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_user_partner" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_user_partner"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_user_partner" class="btn btn-primary waves-effect waves-light">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection
    
@section('application-status-script')
    <!-- Required datatable js -->
    <script src="{{asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    
    <!-- Responsive examples -->
    <script src="{{asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Datatable init js -->
    {{-- <script src="{{asset('/assets/js/custom/datatable.init.js')}}"></script> --}}
     <!-- Datatable init js -->
     <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script>
      <!-- Buttons examples -->
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
      <script src="{{asset('/assets/libs/jszip/jszip.min.js')}}"></script>
      <script src="{{asset('/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
      <script src="{{asset('/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

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
            localStorage.removeItem('typesData');
            localStorage.removeItem('branchesData');
            let typesData = @json($types);
            let branchesData = @json($branches);
            let userCategoriesData =  @json($user_categories);
            // Store in local storage
            localStorage.setItem('typesData', JSON.stringify(typesData));
            localStorage.setItem('branchesData', JSON.stringify(branchesData));
            localStorage.setItem('userCategoriesData', JSON.stringify(userCategoriesData));

            // console.log(JSON.stringify(typesData));
           
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
                        // console.log(resp.partners);
                        reloadTable(resp.partners);
                        
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
        // reload function
        function reloadTable(data){
            $('#tr-cont-ep').empty();
            if(data){
                let key = 0;
                data.forEach(partner => {
                    $('#tr-cont-ep').append(`
                    <tr>
                        <td>${++key}
                            <input type="hidden" name="branch_id" value="${partner.id}">
                        </td>
                        <td>${partner.code}
                            <input type="hidden" name="org_code" value="${partner.code}">
                            <input type="hidden" name="org_id" value="${partner.id}">
                        </td>
                        <td>${partner.org_name}
                            <input type="hidden" name="org_name" value="${partner.org_name}">
                        </td>
                        <td>${partner.usercategory?partner.usercategory.user_category:''}
                            <input type="hidden" name="org_type" value="${partner.tbl_user_category_id}">
                        </td>
                        <td>${partner.usersubcategory?partner.usersubcategory.category_name:''}
                            <input type="hidden" name="sub_org_type" value="${partner.tbl_sub_user_category_id}">
                        </td>
                        <td>${partner.org_contact}
                            <input type="hidden" name="org_contact" value="${partner.org_contact}">
                            <input type="hidden" name="org_contact_email" value="${partner.org_contact_email}">
                            <input type="hidden" name="org_address" value="${partner.org_address}">
                        </td>
                        <td>${partner.org_contact_no}
                            <input type="hidden" name="org_contact_no" value="${partner.org_contact_no}">
                            <input type="hidden" name="org_alt_contact_no" value="${partner.org_alt_contact_no}">
                            <input type="hidden" name="org_nic_no" value="${partner.org_nic_no}">
                            <input type="hidden" name="org_is_local" value="${partner.org_is_local}">
                            <input type="hidden" name="deleted" value="${partner.deleted}">
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Second group">
                                <button type="button"  data-partner="${partner.id}" class="btn btn-success btn-sm waves-effect waves-light user_partner">
                                    <i class="ri-edit-line"></i>
                                </button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Second group">
                                <button type="button"  data-partner="${partner.id}" class="btn btn-danger btn-delete btn-sm waves-effect waves-light ">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    `);
                });

                $('#user_partner_modal').modal('hide');
            }
        }

        $(document).on('click', '.btn-delete', function(){
            let id = $(this).data('partner');
            Swal.fire({
                title: "Are you sure?",
                text: "You are deleting this partner",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#1cbb8c",
                cancelButtonColor: "#f32f53",
                confirmButtonText: "Yes, delete it!",
            }).then(function (t) {
                if (t.value == true) {
                    $('.rightbar-overlay').css('display', 'block');
                    $.ajax({
                        url: "/admin/users/partners/delete/"+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function (resp) {
                            if(resp.status == "success"){
                                $('.rightbar-overlay').css('display', 'none');
                                Swal.fire("Deleted!", resp.message, "success");
                                reloadTable(resp.partners);
                            }
                        }
                    });
                    
                }
            });
        });
        });
       
    </script>
    
    <script>
        $(document).on('click', '.user_partner', function(){
            $('#user_partner_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let type_id = $(this).data('partner');           
            //append content into modal
            if(type_id){
                let row = $(this).closest('tr');
                //
                let branch_id = row.find('td:eq(0) input[name="branch_id"]').val();
                let org_code = row.find('td:eq(1) input[name="org_code"]').val();
                let org_id = row.find('td:eq(1) input[name="org_id"]').val();
                let org_name = row.find('td:eq(2) input[name="org_name"]').val();
                let org_type = row.find('td:eq(3) input[name="org_type"]').val();
                let sub_org_type = row.find('td:eq(4) input[name="sub_org_type"]').val();
                let org_contact = row.find('td:eq(5) input[name="org_contact"]').val();
                let org_contact_email = row.find('td:eq(5) input[name="org_contact_email"]').val();
                let org_address = row.find('td:eq(5) input[name="org_address"]').val();
                let org_contact_no = row.find('td:eq(6) input[name="org_contact_no"]').val();
                let org_alt_contact_no = row.find('td:eq(6) input[name="org_alt_contact_no"]').val();
                let org_nic_no = row.find('td:eq(6) input[name="org_nic_no"]').val();
                let org_is_local =row.find('td:eq(6) input[name="org_is_local"]').val();
                let deleted = row.find('td:eq(6) input[name="deleted"]').val();
                
                //
                
                $('#modalLabel').html('Updating Complaint Type');
                $('#form_user_partner').attr('action', '/admin/users/partners/update');
                
                $('#modal_concent').append(`
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_code" class="form-label">Partner Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="${org_code}" id="org_code" name="org_code" required="" readonly>
                                <input type="hidden" class="form-control" value="${org_id}" id="org_id" name="org_id" required="">
                                <input type="hidden" class="form-control" value="${org_id}" id="org_id" name="org_id" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_name" class="form-label">Partner Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="${org_name}" id="org_name" name="org_name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_type" class="form-label">Partner type <span class="text-danger">*</span></label>
                                <select name="org_type" id="org_type" class="form-select" required="">
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sub_org_type" class="form-label">Sub Partner type <span class="text-danger">*</span></label>
                                <select name="sub_org_type" id="sub_org_type" class="form-select" required="">
                                    <option value="0" >N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_contact" class="form-label">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="${org_contact}" id="org_contact" name="org_contact" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_contact_no" class="form-label">Contact/Org Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="${org_contact_no}" id="org_contact_no" name="org_contact_no" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_alt_contact_no" class="form-label">Alternate Contact Number </label>
                                <input type="text" class="form-control" value="${org_alt_contact_no}" id="org_alt_contact_no" name="org_alt_contact_no" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_contact_email" class="form-label">Contact/Org Contact Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="${org_contact_email}" id="org_contact_email" name="org_contact_email" required="">
                               
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_nic_no" class="form-label">Contact/Org NIC No </label>
                                <input type="text" class="form-control" value="${org_nic_no}" id="org_nic_no" name="org_nic_no" placeholder="NIC Assigned No" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_is_local" class="form-label">Is Local? <span class="text-danger">*</span></label>
                                <select name="org_is_local" id="org_is_local" class="form-select" required="">
                                    <option value="0" ${org_is_local == 0 ? 'selected':''}>Local</option>
                                    <option value="1" ${org_is_local == 1 ? 'selected':''}>Foreign</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="org_address" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="org_address" name="org_address" required="" rows="2">${org_address}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label">Managing Branch</label>
                                <select name="branch_id" id="branch_id" class="form-select">
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status_s" class="form-select">
                                    <option value="0" ${deleted == 0 ? 'selected':''}>Active</option>
                                    <option value="1" ${deleted == 1 ? 'selected':''}>Diabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `);

                let storedbranchesData = localStorage.getItem('branchesData');
                let $branches = JSON.parse(storedbranchesData);
                if($branches){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#branch_id').append(option);
                    // let selected = 
                    $branches.forEach(branch => {
                        // console.log(branch);
                        if(branch.id == branch_id){
                            option = `<option value="${branch.id}" selected="">${branch.branch_name}</option>`;
                        }else{
                            option = `<option value="${branch.id}">${branch.branch_name}</option>`;
                        }
                        $('#branch_id').append(option);
                    });
                }

                let storedtypesData = localStorage.getItem('typesData');
                let types = JSON.parse(storedtypesData);
                if(types){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#org_type').append(option);
                    // let selected = 
                    types.forEach(type => {
                        console.log(org_type);
                        if(type.id == org_type){
                            option = `<option value="${type.id}" selected="">${type.user_category}</option>`;
                        }else{
                            option = `<option value="${type.id}">${type.user_category}</option>`;
                        }
                        $('#org_type').append(option);
                    });
                }

            }else{
                $('#modalLabel').html('Adding New Complaint Type');
                $('#form_user_partner').attr('action', '/admin/users/partners/save');
               
                $('#modal_concent').append(`
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_code" class="form-label">Partner Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="org_code" name="org_code" placeholder="Auto Generated" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_name" class="form-label">Partner Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  id="org_name" name="org_name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_type" class="form-label">Partner type <span class="text-danger">*</span></label>
                                <select name="org_type" id="org_type" class="form-select" required="">
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sub_org_type" class="form-label">Sub Partner type <span class="text-danger">*</span></label>
                                <select name="sub_org_type" id="sub_org_type" class="form-select" required="">
                                    <option value="0" >N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_contact" class="form-label">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  id="org_contact" name="org_contact" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_contact_no" class="form-label">Contact/Org Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  id="org_contact_no" name="org_contact_no" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_alt_contact_no" class="form-label">Alternate Contact Number </label>
                                <input type="text" class="form-control" id="org_alt_contact_no" name="org_alt_contact_no" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_contact_email" class="form-label">Contact/Org Contact Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="org_contact_email" name="org_contact_email" required="">
                               
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_nic_no" class="form-label">Contact/Org NIC No </label>
                                <input type="text" class="form-control" id="org_nic_no" name="org_nic_no" placeholder="NIC Assigned No" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="org_is_local" class="form-label">Is Local? <span class="text-danger">*</span></label>
                                <select name="org_is_local" id="org_is_local" class="form-select" required="">
                                    <option value="0" >Local</option>
                                    <option value="1" >Foreign</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="org_address" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="org_address" name="org_address" required="" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label">Managing Branch</label>
                                <select name="branch_id" id="branch_id" class="form-select">
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status_s" class="form-select">
                                    <option value="0" >Active</option>
                                    <option value="1" >Diabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `);

                let storedbranchesData = localStorage.getItem('branchesData');
                let $branches = JSON.parse(storedbranchesData);
                if($branches){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#branch_id').append(option);
                    // let selected = 
                    $branches.forEach(branch => {
                        // console.log(branch);
                        option = `<option value="${branch.id}">${branch.branch_name}</option>`;
                        $('#branch_id').append(option);
                    });
                }

                let storedtypesData = localStorage.getItem('typesData');
                let types = JSON.parse(storedtypesData);
                if(types){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#org_type').append(option);
                    // let selected = 
                    types.forEach(type => {
                        // console.log(org_type);
                        option = `<option value="${type.id}">${type.user_category}</option>`;
                        $('#org_type').append(option);
                    });
                }

            }
        });
    </script>
    <script>
       $(document).on('change', '#org_type', function(){
        let id = $('#org_type').val();
        let user_categories = JSON.parse(localStorage.getItem('userCategoriesData'));

        $('#sub_org_type').empty();
        user_categories.forEach(subcategory => {
            if(subcategory.tbl_user_category_id && subcategory.tbl_user_category_id == id){
                $('#sub_org_type').append(`<option value="${subcategory.id}">${subcategory.category_name}<option/>`);
            }
        });

        // $.ajax({
        //     url: "/admin/users/partners/sub/category/"+id,
        //     type: "GET",
        //     dataType: "JSON",
        //     success: function (resp) {
        //         if(resp.status == "success"){
        //             if(resp.subcategory){
        //                 resp.subcategory.forEach(subcategory => {
        //                     $('#sub_org_type').append(`<option value="${subcategory.id}">${subcategory.id}<option/>`);
        //                 });
        //             }
        //         }
        //     }
        // });
       });
    </script>
@stop