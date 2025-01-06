@extends('layouts.main-master')
@section('content')
{{-- flexdatalist --}}
<link href="{{asset('/assets/libs/flexdatalist/css/jquery.flexdatalist.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
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
    margin: 0.15rem!important;
}
.black{
    color: #000 !important;
}

.mr-2{
    margin-right: 4px;
}
.form-content{
    border: 1px solid #e8e8e8;
    padding: 20px;
    background-color: #fff;
}
.card-header-b{
    border-bottom: 1px solid #dad3d3;
}
.card-body-grey{
    background-color: #f1f5f7;
}
.bb{
    border-bottom: 1px solid #5b5757;
    margin-bottom: 6px;
    margin-top: 20px;
}
.phone_number_invalid{
    font-size: 12px; 
    color: rgb(243, 47, 83); 
    margin-top: 5px; 
    display: none;
}
.alert-dismissible .btn-close {
    padding: 0.7rem 1.25rem !important;
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
    @if (session('message'))
        <div class="alert alert-{{ session('message')['class'] }} alert-dismissible fade show" role="alert">
            <i class="mdi mdi-block-helper me-2"></i> 
            {{ session('message')['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    Product Checklist 
                </h5>

                <div class="card-body">
                    {{-- <table id="technical_requirement1" class="table table-striped no-wrap table-bordered responsive"
                    style="font-size: 13px;color: #000; padding: 5px 10px !important; "> --}}
                   <form action="{{route('save-checklist-documents')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="col-md-1"></th>
                                    <th class="col-md-3">Document Name</th>
                                    <th class="col-md-3"></th>
                        
                                    <th class="col-md-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" id="xx_id" name="xx_id">
                                @foreach ($requesteds as $requested)
                                    @if ($requested->tbl_document_type->id !==3)
                                        <tr id="custom_{{$requested->id}}" class="custom_tr" data-id="{{$requested->id}}">
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Second group">
                                                    @php
                                                        $rs = App\Models\Document::where([
                                                            'tbl_document_type_id' => $requested->tbl_document_type_id,
                                                            'tbl_document_applications_id' => $requested->tbl_document_applications_id,
                                                        ])->get();
                                                    @endphp   
                                                    
                                                    @if (count($rs)>0)
                                                        <span data-complaint="1" style="padding: 1px 4px;" class="btn btn-success btn-sm waves-effect waves-light delete-btns">
                                                            <i class="ri-check-line align-middle ms-1 me-1"></i>
                                                        </span>
                                                    @else
                                                        <span  data-complaint="1" style="padding: 1px 4px;" class="btn btn-danger btn-sm waves-effect waves-light delete-btns">
                                                            <i class="ri-close-line align-middle ms-1 me-1"></i>
                                                        </span>
                                                    @endif
                                                
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#loadsource" data-toggle="modal" data-id="{{$requested->id}}" class="open-viewRequest">
                                                    <font color="black">
                                                        <strong>{{$requested->tbl_document_type->document_name }}</strong>
                                                    </font>
                                                </a>
                                            </td>
                                            <input type="hidden" name="tbl_document_checklist_id[]" value="{{$requested->id}}">
                                            <input type="hidden" name="tbl_document_type_id[]" value="1">
                                
                                            <td>
                                                @if ($requested->tbl_document_type_id != 2 || $record->tbl_documents_products_id == 5)
                                                    <select id="proposal_mode" name="proposal_mode[]" class="form-select">
                                                        <option {{ (isset($requested->mode) && $requested->mode > 0) ? '' : 'selected'}} disabled="" value="">Choose...</option>
                                                        {{-- <option value="1" {{$requested->mode == 1 ? 'selected':''}}>Take Picture</option> --}}
                                                        {{-- <option value="2" {{$requested->mode == 2 ? 'selected':''}}>Attach</option> --}}
                                                        <option value="3" {{$requested->mode == 3 ? 'selected':''}}>Send Digital Request</option>
                                                        @if (in_array($requested->tbl_document_type['id'], [1,2,21]))
                                                            <option value="4" {{$requested->mode == 4 ? 'selected':''}}>Fill Digital Request</option>
                                                        @endif
                                                    
                                                    </select>
                                                @endif
                                            </td>
                                            <td>
                                                <div id="proposal_file_yn" class="col-md-12" style="display:{{$requested->mode == 2 ? 'show':'none'}};">
                                                    <input type="file" class="form-control" id="proposal_file" name="proposal_file[]" accept=".pdf, image/*">
                                                </div>
                                                <div id="proposal_file_no" class="col-md-12" style="display:{{$requested->mode == 1 ? 'show':'none'}};">
                                                    <a href="#viewWebcam" type="button" data-id="{{$requested->id}}" data-toggle="modal"
                                                        class="mb-xs mt-xs mr-xs btn btn-info open-viewWebcam"><i class="fa fa-camera"></i> </a>

                                                        @if ($requested->mode == 1 && $requested->tbl_checklist_status_id == 2)
                                                            <div class="text-danger" id="img_notif"><strong>Image Captured</strong></div>
                                                        @else
                                                            <div class="text-danger" id="img_notif"><strong>No Image Captured</strong></div>
                                                        @endif

                                                        <input type="hidden" class="form-control" name="proposal_image[]" id="webcam_image">
                                
                                                </div>
                                                <div id="digital" class="col-md-12" style="display:{{$requested->notification_type <> '' ? '':'none'}};">
                                                    <select name="notification_type[]" id="notification_type" class="form-select">
                                                        <option value="" >Notification Type</option>
                                                        <option value="SMS" {{$requested->notification_type == 'SMS' ? 'selected':''}}>SMS</option>
                                                        <option value="EMAIL" {{$requested->notification_type == 'EMAIL' ? 'selected':''}}>EMAIL</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="SMS" style="display:{{($requested->sms <> '') ? '':'none'}};">
                                                    <label class="col-md-12 control-label" for="profileAddress">Mobile
                                                        Number</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="sms[]" value="{{($requested->sms <> '') ? $requested->sms :''}}">
                                                    </div>
                                
                                                </div>
                                                <div class="form-group" id="EMAIL" style="display:{{($requested->email <> '') ? '':'none'}};">
                                                    <label class="col-md-12 control-label" for="profileAddress">Email
                                                        Address</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="email[]" value="{{($requested->email <> '') ? $requested->email :''}}">
                                                    </div>
                                
                                                </div>
                                            </td>
                                            <div id="viewWebcam" class="modal fade viewWebcam" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <form id="decline_form" action="" method="POST" autocomplete="off">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" align="center">Image Capture</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{-- <h1 class="text-center">Image Capture</h1> --}}
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div id="my_camera"></div>
                                                                        <br>
                                                                        <input type="button" value="Take Snapshot" onclick="take_snapshot()">
                                                                        <input type="hidden" name="image" class="image-tag">
                                                                        <input type="hidden" name="id" id="id" class="image-id" value="{{$requested->id}}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div id="results">Your image will appear here...
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 text-center">
                                                                        <br>
                                                                        <button name="bntCapture" type="submit" data-id="{{$requested->id}}"
                                                                            class="btn btn-success bntCapture" disabled="">Submit</button>
                                                                    </div>
                                                                </div>
                                        
                                                            </div>
                                                        </form>
                                                        <div class="modal-footer">
                                        
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                        
                                                    </div>
                                        
                                                </div>
                                            </div>
                                        </tr>            
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            <input type="hidden" name="record_id" value="{{$record->id > 0 ? $record->id : 0}}">
                            <button class="btn btn-primary" type="submit" name="bntsubmitChecklist" >Save & Proceed</button>
                        </div>
                   </form>
                </div>
            </div>
           
        </div>
    </div>

   
{{-- </form> --}}

@endsection
    
@section('application-status-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/libs/signature/js/jSignature.min.js')}}"></script>
    <script src="{{asset('/assets/libs/flexdatalist/js/jquery.flexdatalist.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{asset('/assets/js/ajax-utils.js')}}"></script>

    {{-- <!-- Required datatable js -->
    <script src="{{asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

    <script src="{{asset('/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>
    
    <!-- Responsive examples -->
    <script src="{{asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script>

    <script src="{{asset('/assets/js/app.js')}}"></script> --}}


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
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script language="JavaScript">
        function initWebCam() {
          Webcam.set({
            width: 340,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
          });
    
          Webcam.attach('#my_camera');
        }
    
        function take_snapshot() {
          Webcam.snap(function(data_uri) {
            $(".image-tag").val(data_uri);
            // console.log(data_uri);
            document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
            $('button.bntCapture').prop('disabled', false);
          });
        }
    
        $(document).on('click', '.bntCapture', function(e) {
          e.preventDefault();
    
          var mode = $(this);
          var xx_id = $('input#xx_id').val();
          var row = $('tr#custom_tr' + xx_id);
          // console.log('row', row);
    
          var data_uri = row.find('.image-tag').val();
          var data_id = row.find('.image-id').val();
          // console.log(data_uri);
          $.ajax({
            type: 'POST',
            url: 'storeImage-camera.php',
            data: {
              image: data_uri,
              id: data_id
            },
            success: function(data) {
    
              //var res = $.parseJSON(data);
              //console.log(data);
              //var base = $('#id').eq(res.id).closest('tr#custom_tr1');
              row.find("input#webcam_image").val(data);
              row.find('div#img_notif').html('<font color="green">Image Captured</font>');
              $('#viewWebcam').modal('hide');
    
            }
          });
        });

        $(document).on("click", ".open-viewWebcam", function() {
          initWebCam();
          var id = $(this).data('id');
          $('input#xx_id').val(id);
          console.log('xx_id', $('input#xx_id'));
          try {
            console.log('open la');
            $('#viewWebcam').modal('show');
          } catch (err) {}
    
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
        
            var clonetr = $('div#custom_tr').first().removeClass('hide').clone();
            //  $('div#custom_tr').first().remove();
            var rowcount = 0;
        
            $(document).on('click', '#add_row', function(e) {
                e.stopImmediatePropagation();
                var newtr = clonetr.clone();
                //newtr = checklist(newtr);
                $('table#technical_requirement').append(newtr);
                reindex(this, 'div#custom_tr');
                return false;
            });
            $(document).on("click", ".open-viewRequest", function() {
                var id = $(this).data('id');
                //  console.log(id);
                //$(".modal-body div#orderNo").html(orderNo);
                $.ajax({
                    type: 'GET',
                    url: 'view-checklist.php',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        //  console.log(data);
                        $(".modal-body").html(data);
                        try {
                            $('#modal-dialog').modal('open');
                        } catch (err) {}
                    }
                });
            });
        
        
            $(document).on('click', '#cmdRemoveCustomTr', function() {
                var count = $('div#custom_tr').length;
                var element = $(this);
                //console.log(count);
                if (count > 1) {
                    $(this).closest('div').remove();
                } else {
                    var heading = 'Confirm Removal of last item';
                    var question = 'Please confirm that you wish to delete this row';
                    var cancelButtonTxt = 'Cancel';
                    var okButtonTxt = 'Confirm';
                    var callback = function() {
                        $(element).closest('div').remove();
                    };
                    confirmer(heading, question, cancelButtonTxt, okButtonTxt, callback);
                }
                reindex(this, 'div#custom_tr');
                return false;
            });
        
            function reindex(t, b) {
                rowcount = 0;
                $(b).closest('div#custom_tr').find(b).each(function() {
                    console.log(this);
                    rowcount += 1;
                    $(this).find('span#rowno').text(rowcount);
                });
            }
        
            $(document).on('click', 'a#previewloader', function(e) {
                e.preventDefault();
        
                var ele = $(this);
                var base = ele.parentsUntil("tr").parent();
                var id = $(this).data('id');
                console.log(id);
        
                if (id > 0) {
                    var postData = {
                        id: id
                    };
                    var opts = {
                        title: 'Preview Document',
                        url: "/document-rendering/view-document.php",
                        persistent: false,
                        print: {
                            paper: 'A4',
                            orientation: 'portrait'
                        },
                        params: postData,
                        size: "lg", //lg, md, sm
                        type: 'iframe' // iframe, page
                    }
                    //console.log(opts);
                    LoadModal(opts);
                } else {
                    swal('Please select the neccessary paramaters first');
                }
                return false;
            });
        
            $(document).on('change', 'select#proposal_mode', function() {
                event.preventDefault();
                var mode = $(this);
                var row = mode.closest('.custom_tr');
        
                var proposal_file_yn = $('div#proposal_file_yn').find(':selected');
                if (mode.val() > 0) {
                    if (mode.val() == 1) {
                        row.find('div#digital').hide('slow');
                        row.find('div#proposal_file_no').show('slow');
                        row.find('div#proposal_file_yn').hide('slow');
                        row.find('div#EMAIL').hide('slow');
                        row.find('div#SMS').hide('slow');
                    } else if (mode.val() == 2) {
                        row.find('div#proposal_file_yn').show('slow');
                        row.find('div#proposal_file_no').hide('slow');
                        row.find('div#digital').hide('slow');
                        row.find('div#EMAIL').hide('slow');
                        row.find('div#SMS').hide('slow');
                    } else if (mode.val() == 3) {
                        row.find('div#digital').show('slow');
                        row.find('div#proposal_file_no').hide('slow');
                        row.find('div#proposal_file_yn').hide('slow');
                        row.find('div#EMAIL').hide('slow');
                        row.find('div#SMS').hide('slow');
                    } else {
                        row.find('div#proposal_file_yn').hide('slow');
                        row.find('div#proposal_file_no').hide('slow');
                        row.find('div#digital').hide('slow');
                        row.find('div#EMAIL').hide('slow');
                        row.find('div#SMS').hide('slow');
        
                    }
                } else {
                    row.find('div#proposal_file_yn').hide('slow');
                    row.find('div#proposal_file_no').hide('slow');
                    row.find('div#digital').hide('slow');
                    row.find('div#EMAIL').hide('slow');
                    row.find('div#SMS').hide('slow');
                }
        
            });
            $(document).on('change', 'select#notification_type', function() {
                event.preventDefault();
                var mode = $(this);
                //  var ele = $(this);
                var row = mode.closest('.custom_tr');
        
                if (mode.val() == 'SMS') {
                    row.find('div#SMS').show('slow');
                    row.find('div#EMAIL').hide('slow');
                } else if (mode.val() == 'EMAIL') {
                    row.find('div#EMAIL').show('slow');
                    row.find('div#SMS').hide('slow');
                } else {
                    row.find('div#EMAIL').hide('slow');
                    row.find('div#SMS').hide('slow');
                }
        
            });
        
        
        });
    </script>
  
@stop