@extends('layouts.main-master')
@section('content')
{{-- flexdatalist --}}
<link href="{{asset('/assets/libs/flexdatalist/css/jquery.flexdatalist.min.css')}}" id="bootstrap-style"
    rel="stylesheet" type="text/css" />
<style>
    .flexdatalist-results li span.highlight {
        font-weight: 700;
        text-decoration: underline;
    }

    .highlight {
        background-color: #CCC;
        color: #FFF;
        padding: 3px 6px;
    }

    .flexdatalist-results li.active {
        background: #2B82C9;
        color: #fff;
        cursor: pointer;
    }
</style>
<style>
    .list-nostyled {
        list-style: none;
    }

    .tab-primary {
        color: #fff;
        background-color: #0f9cf3 !important;
        border-color: #0f9cf3 !important;
    }

    .tab-white {
        color: #fff;
    }

    .card .collapsed .card-header {
        background-color: #f1f5f7 !important;
        border-bottom: 0 solid #f1f5f7 !important;
    }

    .card .collapsed .card-header .tab-white {
        color: #0a1832 !important;
    }

    element.style {}

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

    .badge-soft-success {
        color: #169e38 !important;
    }

    .badge-light {
        /* color: #000; */
        color: #817b7b;
        background-color: #d8dce1;
        /* hide upcoming steps */
        /* color: #817b7b00;
    background-color: #d8dce100; */
    }

    .mm-1 {
        margin: 0.15rem !important;
    }

    .black {
        color: #000 !important;
    }

    .mr-2 {
        margin-right: 4px;
    }

    .form-content {
        border: 1px solid #e8e8e8;
        padding: 20px;
        background-color: #fff;
    }

    .card-header-b {
        border-bottom: 1px solid #dad3d3;
    }

    .card-body-grey {
        background-color: #f1f5f7;
    }

    .bb {
        border-bottom: 1px solid #5b5757;
        margin-bottom: 6px;
        margin-top: 20px;
    }

    .phone_number_invalid {
        font-size: 12px;
        color: rgb(243, 47, 83);
        margin-top: 5px;
        display: none;
    }
</style>
{{-- <form class="custom-validation" action="{{route('update-company-profile')}}" method="post"
    enctype="multipart/form-data"> --}}
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
    @php
        $param_id =  request()->query('id');
        $param_section = request()->query('section');

        if (request()->has('id')) {
            $id = base64_decode(request('id'));
            $record = App\Models\DocumentApplication::find($id);
            $requesteds = App\Models\DocumentChecklist::where(['tbl_document_applications_id' => $id, 'deleted' => 0])->get();
        }
    @endphp
    <div class="row">
        @include('product-requests.includes.request-side-menu')
        <div class="col-md-9">
            <div class="card">
                <h5 class="card-header">
                    Attached Documents To Request
                </h5>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Uploaded Documents</h4>  
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
    
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Document</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($documents) > 0)
                                                    @foreach ($documents as $key=>$document)
                                                        <tr>
                                                            <th scope="row">{{++$key}}</th>
                                                            <td>{{$document->tbl_document_type->document_name}}</td>
                                                            <td>
                                                                @php
                                                                    $url = '';
                                                                    $s3FileUrl = Storage::disk('s3')->url('documents/'.$document->document);
                                                                @endphp
                                                                 <a href="{{$s3FileUrl}}" target="_blank" id="previewID" data-id="" data-token="">
                                                                    <img src="{{ asset('/assets/images/doc_logos/' . $document->tbl_document_images->images) }}">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="custom-validation" action="{{route('save-attached-documents')}}" method="POST" enctype="multipart/form-data"> 
                        @csrf
                        <div class="row" id="first-doc">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="reference_name" class="form-label">Reference Name</label>
                                    <input type="text" class="form-control" id="reference_name" name="reference_name[]" required="">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tbl_document_type_id" class="form-label">Document Type *</label>
                                    @php
                                    $document_types = App\Models\DocumentType::all();
                                    @endphp
                                    <select class="form-select" id="tbl_document_type_id" name="tbl_document_type_id[]" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        @foreach ($document_types as $document_type)
                                        <option value="{{$document_type->id}}">{{$document_type->document_name}}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Attach Document</label>
                                    <input type="file" class="form-control" id="file" name="file[]" accept=".pdf, image/*" required="">
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div id="more-doc">

                        </div>
                        <div>
                            <button type="button" id="add_row" class="btn btn-primary waves-effect btn-sm waves-light">
                                <i class="ri-close-line align-middle"></i> Add Another
                            </button>
                        </div>
                        <input type="hidden" name="id" value="{{isset($record->id) ? $record['id'] : 0 }}">
                        <input type="hidden" name="policy_no" value="{{isset($record->policy_no) ? $record->policy_no : 0}}">

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Save & Proceed</button>
                        </div>
                    </form> 
                </div>
            </div>

        </div>
    </div>
    {{--
</form> --}}

@endsection

@section('application-status-script')
<script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
<script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('/assets/libs/signature/js/jSignature.min.js')}}"></script>
<script src="{{asset('/assets/libs/flexdatalist/js/jquery.flexdatalist.min.js')}}"></script>
<script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
<script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('/assets/js/ajax-utils.js')}}"></script>


<!-- Sweet Alerts js -->
<script src="{{asset('/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Sweet alert init js-->
<script src="{{asset('/assets/js/pages/sweet-alerts.init.js')}}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#add_row').on('click', function () {
            // Clone the first document and append it to #more-doc
            var clonedDoc = $('#first-doc').clone();
            clonedDoc.find('.col-md-4').last().append(`
                <div style="margin-bottom: 2px;">
                    <button type="button" class="btn btn-danger waves-effect btn-sm waves-light removedoc" style="float: right; margin-bottom: 10px;">
                        <i class="ri-delete-bin-line align-middle"></i>
                    </button>
                </div>
            `);

            $('#more-doc').append(clonedDoc);
        });

        $('#more-doc').on('click', '.removedoc', function () {
            $(this).closest('.row').remove(); // Remove the closest .row element
        });
    });
</script>

@stop