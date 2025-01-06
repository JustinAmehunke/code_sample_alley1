@extends('layouts.main-master')
@section('content')
<link href="{{asset('/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        .from-to-datepicker{
            padding: 0.47rem 0.1rem !important;
        }
        .table.dataTable.dtr-inline.collapsed > tbody > tr > td, table.dataTable.dtr-inline.collapsed > tbody > tr > td {
            font-size: 13px;
        }
    </style>
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
       <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Register Complaints</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Complaints</a></li>
                        <li class="breadcrumb-item active">Register Complaints</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    @if(session('form-params'))
        <p>{{json_encode( session('form-params')) }}</p>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    Register Complaints
                </h5> 

                <div class="card-body">
                    <form class="" action="{{route('complaints-register-save')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label"> Name</label>
                                    <input type="text" value="" name="customer_name" required="" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label"> Phone Number</label>
                                    <input type="text" value="" name="contact_details" required="" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label"> Email Address</label>
                                    <input type="text" value="" name="email_address" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label" for="id_type">Classification * :</label>
                                    <select name="class_id" class="form-control" id="">
                                        <!-- <option value=''></option> -->
                                        <option>
                                            <?php $classifications = \App\Models\ComplaintClassification::orderBy("name")->get();
                                            foreach ($classifications as $classification) : ?>
                                        <option value="<?= $classification['id'] ?>">
                                            <?= $classification['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label"> Policy Number</label>
                                    <input type="text" value="" name="policy_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label" for="id_type">Complaint Category * :</label>
                                    <select name="cat_id" class="form-control" id="">
                                        <!-- <option value=''></option> -->
                                        <option>
                                            <?php $categories = \App\Models\ComplaintCategory::orderBy("name")->get();
                                            foreach ($categories as $category) : ?>
                                        <option value="<?= $category['id'] ?>">
                                            <?= $category['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label" for="customer_name"> Details of Complaints :</label>
                                    <textarea name="description" rows="3" class="form-control" type="text"
                                        required=""></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label" for="customer_name"> Method of Contact :</label>
                                    <select name="method_of_contact_id" id="" class="form-control">
                                        <!-- <option value=''></option> -->
                                        <option>
                                            <?php $methods = \App\Models\MethodOfContact::orderBy("name")->get();
                                            foreach ($methods as $method) : ?>
                                        <option value="<?= $method['id'] ?>">
                                            <?= $method['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label" for="customer_name"> Reporting Channel :</label>
                                    <select name="reporting_channel" id="" class="form-control">
                                        <option value=''></option>
                                        <option value="Email">Email</option>
                                        <option value="Walk-in">Walk-in</option>
                                        <option value="Phone">Phone</option>
                                        <option value="Regulator">Regulator</option>
                                        <option value="Third Parties">Third Parties</option>
                                        <option value="Contact Centre">Contact Centre</option>
                                        <option value="Retention team">Retention Team</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="control-label" for="customer_name"> Received Date :</label>
                                    <input type="date" name="received_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <label class="control-label col-sm-2 col-md-2">Add Attachment(s)</label>
                                    <div class="col-md-10 col-sm-10">
                                        <table id="technical_requirement"
                                            class="table table-striped no-wrap table-bordered responsive"
                                            style="font-size: 13px;color: #000; padding: 5px 10px !important; ">

                                            <thead>
                                                <tr>
                                                    <th class="col-md-2">Name of Document</th>
                                                    <th class="col-md-2">File</th>
                                                    <th class="col-md-1">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- <?php for ($i = 0; $i < $size_subs; $i++) { ?>
                                                <tr id="custom_tr">

                                                    <td><input type="text" name="doc_name[]" class="form-control"
                                                            value="<?= ucwords($subs['subject_name'][$i]) ?>"></td>

                                                    <td><input type="file" id="file" name="file[]"
                                                            class="form-control" />
                                                    </td>

                                                    <td class="text-center">
                                                        <a href="#" id="cmdRemoveCustomTr" class="btn btn-xs btn-danger"
                                                            data-toggle="tooltip" data-placement="top" title="Remove">
                                                            <i class="fa fa-minus"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <?php  } ?> --}}
                                                <tr id="custom_tr" class="hide">

                                                    <td><input type="text" name="doc_name[]" class="form-control"
                                                            value="">
                                                    </td>

                                                    <td><input type="file" id="file" name="file[]" value=""
                                                            class="form-control" /></td>

                                                    <td class="text-center">
                                                        <a href="#" id="cmdRemoveCustomTr" class="btn btn-xs btn-danger"
                                                            data-toggle="tooltip" data-placement="top" title="Remove">
                                                            <i class="fa fa-minus"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <tfoot>

                                                <tr>
                                                    <td colspan="7">
                                                        <div class="col-md-5">
                                                            <a href="#" id="add_row"
                                                                class="btn btn-xs btn-danger pull-left"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Add"><i class="fa fa-plus"></i> Add File</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                       
                        {{-- <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Save & Proceed</button>
                        </div> --}}
                        <div class="d-flex justify-content-end mb-3">
                            <div>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Reset
                                </button>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
           
        </div>
    </div>
   

@endsection
    
@section('application-status-script')
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/libs/signature/js/jSignature.min.js')}}"></script>
    <script src="{{asset('/assets/libs/flexdatalist/js/jquery.flexdatalist.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{asset('/assets/js/ajax-utils.js')}}"></script>

    <!-- Required datatable js -->
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
    {{-- <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script> --}}

    <!-- Select2 js -->
    <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <!-- Date Picker js -->
    <script src="{{asset('/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

    <script src="{{asset('/assets/js/app.js')}}"></script>


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

<script>
  $(document).ready(function() {

    var clonetr = $('tr#custom_tr').first().removeClass('hide').clone();
    $('tr#custom_tr').first().remove();
    var rowcount = 0;

    $(document).on('click', '#add_row', function(e) {
        e.stopImmediatePropagation();
        var newtr1 = clonetr.clone();
        //newtr = checklist(newtr);
        $('table#technical_requirement').append(newtr1);
        reindex(this, 'tr#custom_tr');
        return false;
    });

    $(document).on('click', '#cmdRemoveCustomTr', function() {
        var count = $('tr#custom_tr').length;
        console.log(count);
        var element1 = $(this);
        // console.log(count);
        $(this).closest('tr').remove();

        reindex(this, 'tr#custom_tr');
        return false;
    });

    function reindex(t, b) {
        rowcount = 0;
        $(b).closest('table#technical_requirement').find('tr').each(function() {
            rowcount += 1;
            $(this).find('label#rowno').text(rowcount);
        });
    }

});
</script>
  
@stop