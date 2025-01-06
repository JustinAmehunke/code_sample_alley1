@extends('layouts.main-master')
@section('content')

<style>
    .list-nostyled{
        list-style: none;
    }
</style>
<form class="custom-validation" action="{{route('update-company-profile')}}" method="post" enctype="multipart/form-data">
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
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Company Profile</h5>
                <div class="card-body">
                   <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Company Code</label>
                                <input type="text" name="company_code" id="company_code" value="{{$company_profile->code}}" class="form-control" readonly/>
                                @error('company_code')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Company Name</label>
                                <input type="text" name="company_name" id="company_name" value="{{$company_profile->company_name}}" required class="form-control"  placeholder=""/>
                                @error('company_name')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Short Name</label>
                                <input type="text" name="short_name" id="short_name" value="{{$company_profile->company_shortname}}" required class="form-control"  placeholder=""/>
                                <input type="hidden" id="org_id" name="org_id" value="{{$company_profile->id}}" required />
                                @error('short_name')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Vat Reg. Number</label>
                                <input type="text" name="vat_number" id="vat_number" value="{{$company_profile->vat_reg_no}}" required class="form-control"  placeholder=""/>
                                @error('vat_number')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                           
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Contact/Company Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" value="{{$company_profile->company_contact_no}}" required class="form-control"  placeholder=""/>
                                @error('contact_number')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Contact/Company Contact Email</label>
                                <input type="email" name="contact_email" id="contact_email" value="{{$company_profile->company_contact_email}}" required class="form-control"  placeholder=""/>
                                @error('contact_name')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Company Contact Number</label>
                                <input type="text" name="contact_name" id="contact_name" value="{{$company_profile->company_contact}}" required class="form-control"  placeholder=""/>
                                @error('contact_name')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Company Logo</label>
                                <input type="file" name="company_logo" id="company_logo" onchange="displayImg(this,$(this))" value="{{ old('company_logo') }}" class="form-control"  placeholder=""/>
                                @error('company_logo')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                   </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Company Address</label>
                                <textarea name="company_address" id="company_address" cols="30" rows="3" required class="form-control" >
                                    {{trim($company_profile->company_address)}}
                                </textarea>
                                @error('company_address')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                          
                        </div>
                        <div class="col-md-6">
                            <div style="margin-top: 30px;">
                                 <img id="logo-field" class="logo-field" style="max-height: 120px; width: 200px" src="/storage/company_profile/{{$company_profile->company_logo_path}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button style="margin-top: 20px" type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                Submit
                            </button>
                        </div>
                       
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Bank Details</h5>
                <div class="card-body">
                    @if (count($bank_details) <= 1)
                        <div id="firstbank">
                            <hr>
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Account Name</label>
                                        <input type="text" name="account_name[]" id="account_name" value="{{count($bank_details) ? $bank_details[0]->account_name:''}}" required="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Bank</label>
                                        <input type="text" name="bank_name[]" id="bank_name" value="{{count($bank_details) ? $bank_details[0]->bank:''}}" required="" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Account No.</label>
                                        <input type="text" name="account_number[]" id="account_number" value="{{count($bank_details) ? $bank_details[0]->account_no:''}}" required="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Branch</label>
                                        <input type="text" name="bank_branch[]" id="bank_branch" value="{{count($bank_details) ? $bank_details[0]->branch:''}}" required="" class="form-control" placeholder="">
                                    </div>
                                
                                </div>
                            
                            </div>
                        </div>
                    @endif
                    @if (count($bank_details) > 1)
                        @foreach ($bank_details as $key => $bank_detail)
                            @if ($key > 0)
                                <div id="firstbank" class="cloned">
                                    <hr>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Account Name</label>
                                                <input type="text" name="account_name[]" id="account_name" value="{{$bank_detail->account_name}}" required="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Bank</label>
                                                <input type="text" name="bank_name[]" id="bank_name" value="{{$bank_detail->bank}}" required="" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Account No.</label>
                                                <input type="text" name="account_number[]" id="account_number" value="{{$bank_detail->account_no}}" required="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Branch</label>
                                                <input type="text" name="bank_branch[]" id="bank_branch" value="{{$bank_detail->branch}}" required="" class="form-control" placeholder="">
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                
                                    <div style="margin-bottom: 40px;">
                                        <button type="button" class="btn btn-danger waves-effect btn-sm waves-light removebank" style="float: right; margin-bottom: 10px;">
                                            <i class="ri-delete-bin-line align-middle"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <div id="hostmore">

                    </div>
                    <div>
                        <button type="button" id="add_row" class="btn btn-primary waves-effect btn-sm waves-light" style="float: right;">
                            <i class="ri-add-fill align-middle"></i> Add Another
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
    
@section('new-menu-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>

    <script>
    $(document).ready(function() {
    setTimeout(function() {
        $('.parsley-danger').fadeOut('slow');
        $('.alert-success').fadeOut('slow');
    }, 4000); // 500 milliseconds = 0.5 seconds
    });
    </script>

    <script>
        $(document).ready(function() {
            $('input[type="checkbox"][name="departments[]"]').on('change', function() {
                var departmentId = $(this).attr('id').replace('department', '');
                var designationCheckboxes = $('.department' + departmentId);

                var allChecked = $(this).prop('checked');
                designationCheckboxes.prop('checked', allChecked);
            });

            $('input[type="checkbox"][name="designations[]"]').on('change', function() {
                var departmentId = $(this).attr('class').replace('department', '');
                var departmentCheckbox = $('#department' + departmentId);

                var anyUnchecked = $('.department' + departmentId + ':not(:checked)').length > 0;

                departmentCheckbox.prop('checked', !anyUnchecked);
            });

                // Toggle expand/collapse
            $('#tree').on('click', '.toggle', function(){
                $(this).toggleClass('fa-minus fa-plus');
                $(this).siblings('ul').toggle();
            });
        });

        // Check/uncheck top-level parent
        $('#tree').on('change', '#top-level-checkbox', function(){
            $('#tree :checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script>
        $(document).on('change', 'input#hidden_menu', function() {
                var ele = $(this);
                var div = $('div.hidden_div');
                if (ele.is(":checked")) {
                    div.show();
                } else {
                    div.hide();
                }
        });

        $(document).on('change', 'select#sub_page', function() {
            var ele = $(this);
            var parent = $("select#parent_page");
            if (ele.val() != '') {
                var parentid = ele.find("option:selected").data("parent");
                console.log(parentid);
                parent.val(parentid).change();
            }
        });
</script>

<script>
     $(document).ready(function() {

        $(document).on('click', '#add_row', function(e) {
            console.log('1');
            var clonediv = $('div#firstbank').first().clone();
            clonediv.find('input').val(''); // Clear input value
            $('#hostmore').append(clonediv);
            // Add class for future clones
            clonediv.addClass('cloned'); 
            clonediv.append(`
            <div style="margin-bottom: 40px;">
                <button type="button" class="btn btn-danger waves-effect btn-sm waves-light removebank" style="float: right; margin-bottom: 10px;">
                    <i class="ri-delete-bin-line align-middle"></i>
                </button>
            </div>
            `)
        });
    });
    //
    $(document).on('click', '.removebank', function(){
        // Remove closest div with class 'clonable'
        $(this).closest('.cloned').remove(); 
    });

    //display logo
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#logo-field").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
   
</script>

@stop