@extends('layouts.main-master')
@section('content')

<style>
    .list-nostyled{
        list-style: none;
    }
</style>
<form class="custom-validation" action="{{route('create-menu')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Add Menu Item</h5>
                @if(session('error_message'))
    <div class="alert alert-danger">
        {{ session('error_message') }}
    </div>
@endif
                <div class="card-body">
                   <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Menu Label</label>
                                <input type="text" name="menu_label" id="menu_label" value="{{ old('menu_label') }}" required class="form-control"  placeholder=""/>
                                @error('menu_label')
                                    <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Icon</label>
                                <select class="select2 form-select " name="menu_icon" id="menu_icon" required="">
                                    @foreach ($faicons as $value)
                                    <option value="{{$value}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Sort</label>
                                <input data-parsley-type="number" type="number" name="sort" id="sort" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" style="padding-top: 39px;">
                                <input class="form-check-input" type="checkbox" name="is_subpage" value="1" id="hidden_menu" >
                                <label class="form-check-label" for="hidden_menu">
                                    Is Sub Page?
                                </label>
                            </div>
                            {{-- <div class="col-md-4" >
                             
                            </div> --}}
                        </div>
                   </div>
                    <div class=" mb-3">
                      <div class=" hidden_div" style="display: none">
                        <label class="form-check-label">Which Page?</label>
                        <select class="form-select select2" name="sub_page" id="sub_page">
                            <option selected="" disabled="" value="">Select Parent Page...</option>
                            @foreach ($parentsMenu as $pmenu)
                               <option value="{{$pmenu->id}}" data-parent="{{$pmenu->parent}}">{{$pmenu->label}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="mb-3">
                        <label>Parent</label>
                        <select class="form-select select2" name="parent_page" id="parent_page" >
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($childrenMenu as $chmenu)
                               <option value="{{$chmenu->id}}" >{{$chmenu->label}}</option>
                            @endforeach
                        </select>
                    </div>
                    

                    <div class="mb-3">
                        <label>Link</label>
                        <input  type="text" class="form-control" value="{{old('page_url')}}" name="page_url" id="page_url" required="" placeholder="URL">
                        @error('page_url')
                            <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="visible_to_all" id="visible_to_all"  value="1" id="invalidCheck">
                        <label class="form-check-label" for="invalidCheck">
                            Visible to All
                        </label>
                    </div>
                    
                   
                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                        Submit
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Allocate to Profiles</h5>
                <div class="card-body">
                    <div class="card">
                         <div class="card-header mb-3">
                                Select Profiles
                            </div>
                            <ul id="tree" class="list-unstyled">
                                <li>
                                    <i class="fas fa-minus toggle" style="cursor: pointer"></i>
                                    <input type="checkbox" id="top-level-checkbox">
                                    <label for="top-level-checkbox" class="btn btn-outline-light waves-effect">All Profiles</label>
                                    <ul class="list-nostyled">
                                        @foreach ($departmentsdata as $data)
                                            <li class="mb-2">
                                                <i class="fas fa-minus toggle" style="cursor: pointer"></i>
                                                <input type="checkbox" value="{{$data['id']}}" name="departments[]" id="department{{$data['id']}}">
                                                <label for="department{{$data['id']}}" class="btn btn-outline-light waves-effect" style="padding:.17rem .5rem;">
                                                    {{$data['department_name']}}
                                                </label>
                                                <ul class="list-nostyled">
                                                    @if ($data['items'])
                                                        @foreach ($data['items'] as $item)
                                                            <li>
                                                                <input type="checkbox" value="{{$item['id']}}" name="designations[]" class="department{{$data['id']}}"  id="designation{{$item['id']}}">
                                                                <label for="designation{{$item['id']}}" class="btn btn-outline-light waves-effect" style="padding:.17rem .5rem;">{{$item['designations']}}</label>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </li>
                                        @endforeach
                                        
                                    </ul>
                                </li>
                            </ul>
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
    }, 500); // 500 milliseconds = 0.5 seconds
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
@stop