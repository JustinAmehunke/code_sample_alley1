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
                    <button type="button" id="view_branch" style="float: right;" data-branch="" class="btn btn-primary btn-sm waves-effect waves-light view_branch">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-bordered mb-2">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Organisation</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tr-cont-ep" id="tr-cont-ep">
                            @foreach ($branches as $key => $branch)
                                <tr>
                                    <td>
                                        {{++$key}}
                                        <input type="hidden" name="" value="{{$branch->id}}">
                                    </td>
                                    <td>
                                        {{$branch->branch_code}}
                                        <input type="hidden" name="branch_code" value="{{$branch->branch_code}}">
                                    </td>
                                    <td>
                                        {{$branch->branch_name}}
                                        <input type="hidden" name="branch_name" value="{{$branch->branch_name}}">
                                    </td>
                                    <td>
                                        {{$branch->organisation->org_name}}
                                        <input type="hidden" name="org_name" value="{{$branch->organisation->org_name}}">
                                        <input type="hidden" name="branch_org_id" value="{{$branch->organisation->id}}">
                                        <input type="hidden" name="branch_region" value="{{$branch->esu_region_id}}">
                                        <input type="hidden" name="branch_city" value="{{$branch->esu_city_id}}">
                                    </td>
                                    <td>
                                        {{$branch->branch_contact_no}}
                                        <input type="hidden" name="branch_contact_no" value="{{$branch->branch_contact_no}}">
                                    </td>
                                    <td>
                                        {{$branch->branch_contact_email}}
                                        <input type="hidden" name="branch_contact_email" value="{{$branch->branch_contact_email}}">
                                        <input type="hidden" name="branch_address" value="{{$branch->branch_addr}}">
                                    </td>
                                   
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <button type="button"  data-branch="{{$branch->id}}" class="btn btn-success btn-sm modal-btns waves-effect waves-light view_branch">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Second group">
                                    
                                            <button type="button"  data-branch="{{$branch->id}}" class="btn btn-danger btn-sm modal-btns waves-effect waves-light">
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
<div id="view_branch_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_view_branch" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                                
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_view_branch"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_view_branch" class="btn btn-primary waves-effect waves-light">Save changes</button>
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
            localStorage.removeItem('partnersData');
            let partnersData = @json($partners);
            let regionsData = @json($regions);
            let citiesData = @json($cities);

            // Store in local storage
            localStorage.setItem('partnersData', JSON.stringify(partnersData));
            localStorage.setItem('regionsData', JSON.stringify(regionsData));
            localStorage.setItem('citiesData', JSON.stringify(citiesData));

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
        $('.view_branch').on('click', function(){
            $('#view_branch_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let branch_id = $(this).data('branch');
            //Get category data
            let storedCategoryData = localStorage.getItem('categoryData');
            let categoryData = JSON.parse(storedCategoryData);
            // console.log(categoryData);
            
           
            //append content into modal
            if(branch_id){
                let row = $(this).closest('tr');
                // var num = row.find('td:eq(0)').text();
                let branchId = row.find('td:eq(0) input:hidden').val();
                let branch_code = row.find('td:eq(1) input[name="branch_code"]').val();
                let branch_name = row.find('td:eq(2) input[name="branch_name"]').val();
                let branch_org_id = row.find('td:eq(3) input[name="branch_org_id"]').val();

                let branch_contact = row.find('td:eq(4) input[name="branch_contact_no"]').val();
                let branch_email = row.find('td:eq(5) input[name="branch_contact_email"]').val();
                let branch_address = row.find('td:eq(5) input[name="branch_address"]').val();
                let branch_region = row.find('td:eq(3) input[name="branch_region"]').val();
                let branch_city = row.find('td:eq(3) input[name="branch_city"]').val();

                
                $('#modalLabel').html('Updating Branch');
                $('#form_view_branch').attr('action', '/admin/branches/update');
                
                $('#modal_concent').append(`
                   
                   <div class="row">
                       <div class="mb-3 col-md-6">
                           <label for="branch_code" class="form-label">Branch Code</label>
                           <input type="text" class="form-control" name="branch_code" id="branch_code" value="${branch_code}" required="" readonly >
                           <input type="hidden" name="branch_id" value="${branchId}">
                       </div>
                       <div class="mb-3 col-md-6">
                           <label for="branch_name" class="form-label">Branch Name</label>
                           <input type="text" class="form-control" name="branch_name" id="branch_name" value="${branch_name}" required="">
                       </div>
                   </div>
                   <div class="mb-3 position-relative" >
                       <label for="branch_type" class="form-label">Organisation</label>
                       <select class="form-select" name="branch_type" id="branch_type"  required="">
                         
                       </select>
                   </div>
                   <div class="mb-3 position-relative">
                       <label for="branch_address" class="form-label">Address</label>
                       <textarea class="form-control" rows="3" name="branch_address" id="branch_address"  required="">${branch_address}</textarea>
                   </div>

                    <div class="row">
                       <div class="mb-3 col-md-6">
                           <label for="branch_contact_no" class="form-label">Branch Contact Number</label>
                           <input type="text" class="form-control" name="branch_contact_no" id="branch_contact_no" value="${branch_contact}" required="">
                       </div>
                       <div class="mb-3 col-md-6">
                           <label for="branch_contact_email" class="form-label">Branch Contact Email</label>
                           <input type="text" class="form-control" name="branch_contact_email" id="branch_contact_email" value="${branch_email}" required="">
                       </div>
                   </div>
                   <div class="row">
                       <div class="mb-3 col-md-6">
                           <label for="branch_region" class="form-label">Branch Region</label>
                           <select class="form-select" name="branch_region" id="branch_region" required="">
                           
                           </select>
                       </div>
                       <div class="mb-3 col-md-6">
                           <label for="branch_city" class="form-label">Branch City</label>
                           <select class="form-select" name="branch_city" id="branch_city" required="">
                           
                           </select>
                       </div>
                   </div>
               `);
               
               $('#branch_type').empty();
               let storedPartners = localStorage.getItem('partnersData');
               let partners = JSON.parse(storedPartners);
               if(partners){
                   let option = `<option value="" selected="">Select..</option>`;
                   $('#branch_type').append(option);
                   // let selected = 
                   partners.forEach(partner => {
                    if(partner.id == branch_org_id){
                        option = `<option value="${partner.id}" selected="">${partner.org_name}</option>`;
                    }else{
                        option = `<option value="${partner.id}" >${partner.org_name}</option>`;
                    }
                    $('#branch_type').append(option);
                   });
               }

               $('#branch_region').empty();
               let storedRegions = localStorage.getItem('regionsData');
               let regions = JSON.parse(storedRegions);
               if(regions){
                   let option = `<option value="" selected="">Select..</option>`;
                   $('#branch_region').append(option);
                   // let selected = 
                   regions.forEach(region => {
                    if(region.id == branch_region){
                        option = `<option value="${region.id}" selected="">${region.region_name}</option>`;
                    }else{
                        option = `<option value="${region.id}">${region.region_name}</option>`;
                    }
                    $('#branch_region').append(option);
                   });
               }
                let storedCities = localStorage.getItem('citiesData');
                let cities = JSON.parse(storedCities);
                if(cities){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#branch_city').append(option);
                    // let selected = 
                    cities.forEach(city => {
                        if(city.id == branch_city){
                            option = `<option value="${city.id}" selected="">${city.city_name}</option>`;
                        }else{
                            option = `<option value="${city.id}">${city.city_name}</option>`;
                        }
                        $('#branch_city').append(option);
                    });
                }
            }else{
                $('#modalLabel').html('Adding New Branch');
                $('#form_view_branch').attr('action', '/admin/branches/save');
               
                $('#modal_concent').append(`
                   
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="branch_code" class="form-label">Branch Code</label>
                            <input type="text" class="form-control" name="branch_code" id="branch_code"  required="" readonly placeholder="Auto Generated">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="branch_name" class="form-label">Branch Name</label>
                            <input type="text" class="form-control" name="branch_name" id="branch_name"  required="">
                        </div>
                    </div>
                    <div class="mb-3 position-relative" >
                        <label for="branch_type" class="form-label">Organisation</label>
                        <select class="form-select" name="branch_type" id="branch_type" required="">
                          
                        </select>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="branch_address" class="form-label">Address</label>
                        <textarea class="form-control" rows="3" name="branch_address" id="branch_address"  required=""></textarea>
                    </div>

                     <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="branch_contact_no" class="form-label">Branch Contact Number</label>
                            <input type="text" class="form-control" name="branch_contact_no" id="branch_contact_no"  required="">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="branch_contact_email" class="form-label">Branch Contact Email</label>
                            <input type="text" class="form-control" name="branch_contact_email" id="branch_contact_email"  required="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="branch_region" class="form-label">Branch Region</label>
                            <select class="form-select" name="branch_region" id="branch_region" required="">
                            
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="branch_city" class="form-label">Branch City</label>
                            <select class="form-select" name="branch_city" id="branch_city" required="">
                            
                            </select>
                        </div>
                    </div>
                `);
                
                $('#branch_type').empty();
                let storedPartners = localStorage.getItem('partnersData');
                let partners = JSON.parse(storedPartners);
                if(partners){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#branch_type').append(option);
                    // let selected = 
                    partners.forEach(partner => {
                        option = `<option value="${partner.id}">${partner.org_name}</option>`;
                        $('#branch_type').append(option);
                    });
                }

                $('#branch_region').empty();
                let storedRegions = localStorage.getItem('regionsData');
                let regions = JSON.parse(storedRegions);
                if(regions){
                    let option = `<option value="" selected="">Select..</option>`;
                    $('#branch_region').append(option);
                    // let selected = 
                    regions.forEach(region => {
                        option = `<option value="${region.id}">${region.region_name}</option>`;
                        $('#branch_region').append(option);
                    });
                }
            }

        });

        let storedCities = localStorage.getItem('citiesData');
        let cities = JSON.parse(storedCities);
        $(document).on('change', '#branch_region', function(){
            $('#branch_city').empty();
            let selectedRegion = $('#branch_region').val();
            if(cities){
                let option = `<option value="" selected="">Select..</option>`;
                $('#branch_city').append(option);
                // let selected = 
                cities.forEach(city => {
                   if(city.tbl_region_id == selectedRegion){
                        option = `<option value="${city.id}">${city.city_name}</option>`;
                        $('#branch_city').append(option);
                   }
                });
            }
        });
    </script>
 

@stop