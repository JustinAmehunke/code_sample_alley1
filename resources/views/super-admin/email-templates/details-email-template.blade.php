@extends('layouts.main-master')
@section('content')

<style>
    .inline-table{
        display: inline-table !important;
    }
</style>
<form class="custom-validation" action="{{route('update-email-template')}}" method="post" enctype="multipart/form-data">
    @csrf
    @if(session('success_message'))
        <div class="alert alert-success success-notification">
            {{ session('success_message') }}
        </div>
    @endif
    @if(session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif
    {{-- @if($errors->any())
        <div class="alert alert-danger parsley-danger">
            <ul> 
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Edit Email Template</h5>
                <div class="card-body">

                    {{-- <div class="btn-group me-1 mt-2 mb-3">
                        <button class="btn btn-primary waves-effect btn-sm waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-application-cog align-middle"></i> Choose Category <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                            @if ($categories)
                                @foreach ($categories as $key => $category)
                                    <a class="dropdown-item" href="{{ route('category-email-template', ['category' => encrypt($category->category)]) }}"><b>{{$category->category}}</b></a>
                                @endforeach
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('new-email-template')}}">Create New</a>
                        </div>
                    </div> --}}

                    <form class="custom-validation" action="{{route('update-email-template')}}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Email Variable</label>
                                    <input type="text" name="email_variable" id="email_variable" value="{{$viewEmailTemplate->email_group}}" required class="form-control"  placeholder="No_spaces"/>
                                    @error('email_variable')
                                        <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Email Group</label>
                                    <input type="text" name="email_group" id="email_group" value="{{$viewEmailTemplate->category}}" required class="form-control"  placeholder=""/>
                                    @error('email_group')
                                        <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="" style="margin-top: 48px;">
                                    <input class="form-check-input" name="default_item" id="default_item" type="checkbox" {{$viewEmailTemplate->default_template ? "checked":""}} id="formCheck1">
                                    <label>Default Item</label>
                                    @error('default_item')
                                        <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Subject</label>
                                    <input type="text" name="subject" id="subject" value="{{$viewEmailTemplate->subject}}" required class="form-control"  placeholder=""/>
                                    <input type="hidden" value="{{$viewEmailTemplate->id}}" name="id" id="">
                                    @error('subject')
                                        <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Message</label>
                                    @error('template_body')
                                        <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                    <textarea id="elm1" name="template_body">{{$viewEmailTemplate->template_body}}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                            Update
                        </button>
                    </form>


                    
            </div>
        </div>
    </div>
</form>
@endsection
    
@section('email-templates-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>

    <!--tinymce js-->
    <script src="{{asset('/assets/libs/tinymce/tinymce.min.js')}}"></script>

    <!-- init js -->
    <script src="{{asset('/assets/js/pages/form-editor.init.js')}}"></script>

   
<script>
    $(document).ready(function() {
    setTimeout(function() {
        $('.success-notification').fadeOut('slow');
    }, 3000); // 500 milliseconds = 0.5 seconds
});
</script>
@stop