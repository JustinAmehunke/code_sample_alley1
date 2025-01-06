@extends('layouts.main-master')
@section('content')

<style>
    .inline-table{
        display: inline-table !important;
    }
</style>
@if ($caategory)
<style>
    .bttn-light {
        border: 1px solid #d0d2d6 !important;
    }
</style>  
@endif

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
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Bank Details</h5>
                <div class="card-body">

                    <div class="btn-group me-1 mt-2 mb-3">
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
                    </div>

                    <div class="btn-group mt-2 inline-table mb-3"  role="group" aria-label="Basic example">
                        @if ($emailTemplates)
                            @foreach ($emailTemplates as $key => $emailTemplate)
                                @if ($key == 0)
                                    @if ($emailTemplate->category == $caategory)
                                        <a href="{{ route('view-email-template', ['id' => encrypt($emailTemplate->id)]) }}" class="btn btn-light bttn-light waves-effect">{{$emailTemplate->email_group}}</a>
                                    @else
                                        <a href="{{ route('view-email-template', ['id' => encrypt($emailTemplate->id)]) }}" class="btn btn-outline-light waves-effect">{{$emailTemplate->email_group}}</a>
                                    @endif
                                    
                                @else
                                    @if ($emailTemplate->category == $caategory)
                                        <a href="{{ route('view-email-template', ['id' => encrypt($emailTemplate->id)]) }}" class="btn btn-light bttn-light waves-effect">{{$emailTemplate->email_group}} - {{$caategory}}</a>
                                    @else
                                        <a href="{{ route('view-email-template', ['id' => encrypt($emailTemplate->id)]) }}" class="btn btn-outline-light waves-effect">{{$emailTemplate->email_group}} - {{$caategory}}</a>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        {{-- <button type="button" class="btn btn-outline-light waves-effect">Right</button> --}}
                    </div>

                   

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label>Subject</label>
                            <input type="text" name="subject" id="subject" value="{{$viewEmailTemplate->subject}}" required class="form-control"  placeholder=""/>
                            @error('subject')
                                <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label>Message</label>
                            <textarea id="elm1" name="area">{{$viewEmailTemplate->template_body}}</textarea>
                            @error('subject')
                                <ul class="parsley-danger parsley-errors-list filled" ><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                       
                    </div>

                    <div class="btn-group me-1 mt-2 mb-3" style="float: right;">
                        <a href="{{ route('details-email-template', ['id' => encrypt($viewEmailTemplate->id)]) }}" class="btn btn-primary waves-effect btn-sm waves-light" type="button">
                             Updape This Template <i class="ri-arrow-right-line align-middle ms-2"></i>
                        </a>
                    </div>


                    
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

   

@stop