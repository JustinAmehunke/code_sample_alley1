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
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    @if ($document_product->id)
                        <h4 class="card-title">Edit Product</h4>
                    @else
                        <h4 class="card-title">Add Product</h4>
                    @endif

                    <form class="custom-validation" action="{{route('admin-document-creatte-update-product')}}" method="POST" novalidate="">
                        @csrf
                        <div class="mb-3">
                            <label class="">Document Type Name</label>
                            <div class="">
                                <input type="text" value="{{ $document_product->product_name ?? '' }}" name="product_name" required class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="">Template Link</label>
                            <div class="">
                                <input type="text" value="{{ $document_product->template_link ?? '' }}" name="template_link" required class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="">Website Link</label>
                            <div class="">
                                <input type="text" value="{{ $document_product->website_link ?? 'N/A' }}" name="website_link" class="form-control" readonly>
                            </div>
                        </div>
                      
                        <div class="form-check form-switch mb-3" dir="ltr">
                            <input  type="checkbox" name="product_document_yn" class="form-check-input" id="customSwitch1" {{ $document_product->require_product > 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="customSwitch1">Generate Product Document</label>
                        </div>
                       
                        <div class="form-check form-switch mb-3" dir="ltr">
                            <input type="checkbox" name="mandate_document_yn" class="form-check-input" id="customSwitch1" {{ $document_product->require_mandate > 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="customSwitch1">Generate Mandate Document</label>
                        </div>
                        <div>
                            <label class="mb-2">Require Documents Checklist</label>
                            <input type="hidden" name="id" value="<?= isset($document_product->id) ? $document_product->id : 0 ?>">
                            <table id="technical_requirement1" class="table table-striped no-wrap table-bordered responsive" style="font-size: 13px;color: #000; padding: 5px 10px !important;">
                                <thead>
                                    <tr>
                                        <th class="col-md-3">Document Name</th>
                                        <th class="col-md-2">Mandatory</th>
                                        <th class="col-md-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="custom_tr1" class="hide">
                                        <td>
                                            {!! comboBuilderNotRequired('tbl_document_type', 'tbl_document_type_id[]', 'document_name', 'id', isset($document_products_checklist->tbl_document_type_id) ? $document_products_checklisttbl_document_type_id : '', 'where deleted = 0 ') !!}
                                        </td>
                                        <td>
                                            {!! yesNoOptions("mandatory[]") !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="#" id="cmdRemoveCustomTr1" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remove">
                                                <i class="fa fa-minus"></i>
                                            </a> --}}

                                            <button type="button" id="cmdRemoveCustomTr1" class="btn btn-danger waves-effect btn-sm waves-light removebank" style="float: right; margin-bottom: 10px;">
                                                <i class="ri-delete-bin-line align-middle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @foreach ( $document_products_checklists as  $document_products_checklist)
                                    <tr id="custom_tr1">
                                        <td>
                                            {!! comboBuilderNotRequired('tbl_document_type', 'tbl_document_type_id[]', 'document_name', 'id', isset($document_products_checklist->tbl_document_type_id) ? $document_products_checklist->tbl_document_type_id : '', 'where deleted = 0 ') !!}
                                        </td>
                                        <td>
                                            {!! yesNoOptions("mandatory[]", $document_products_checklist->mandatory_yn) !!}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="#" id="cmdRemoveCustomTr1" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remove">
                                                <i class="fa fa-minus"></i>
                                            </a> --}}
                                            <button type="button" id="cmdRemoveCustomTr1" class="btn btn-danger waves-effect btn-sm waves-light removebank" style="float: right; margin-bottom: 10px;">
                                                <i class="ri-delete-bin-line align-middle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="col-md-5">
                                                {{-- <a href="#" id="add_row1" class="btn btn-xs btn-danger pull-left" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus"></i> Add Another</a> --}}
                                                <button type="button" id="add_row1" title="Add" class="btn btn-primary waves-effect btn-sm waves-light" style="float: right;">
                                                    <i class=" ri-add-fill align-middle"></i> Add Another
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                       
                        
                        
                      
                        <div class="mb-0">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                    @if ($document_product->id)
                                    Update
                                    @else
                                    Save
                                    @endif
                                </button>
                               
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->

      
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="view_types_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_view_types" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_view_types"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_view_types" class="btn btn-primary waves-effect waves-light">Save changes</button>
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
            var clonetr = $('tr#custom_tr').first().clone().removeClass('hide');
            var clonetr1 = $('tr#custom_tr1').first().clone().removeClass('hide');
            //  var clonetr1 = $('tr#custom_tr1').first().removeClass('hide').clone();
            $('tr#custom_tr1').first().remove();
            var rowcount = 0;
            var rowcount1 = 0;

            $(document).on('click', '#add_row1', function(e) {
                e.stopImmediatePropagation();
                var newtr1 = clonetr1.clone();
                //newtr = checklist(newtr);
                $('table#technical_requirement1').append(newtr1);
                reindex(this, 'tr#custom_tr1');
                return false;
            });

            $(document).on('click', '#cmdRemoveCustomTr1', function() {
                var count = $('tr#custom_tr1').length;
                var element1 = $(this);
                console.log(count);
                if (count > 0) {
                    $(this).closest('tr').remove();
                } else {
                    var heading = 'Confirm Removal of last item';
                    var question = 'Please confirm that you wish to delete this row';
                    var cancelButtonTxt = 'Cancel';
                    var okButtonTxt = 'Confirm';
                    var callback = function() {
                    $(element1).closest('div').remove();
                    };
                    confirmer(heading, question, cancelButtonTxt, okButtonTxt, callback);
                }
                reindex(this, 'div#custom_tr1');
                return false;
            });

            function reindex(t, b) {
                rowcount = 0;
                $(b).closest('table#technical_requirement1').find('tr').each(function() {
                    rowcount += 1;
                    $(this).find('label#rowno1').text(rowcount);
                });
            }
        });
    </script>
     <script>
      
    </script>

@stop