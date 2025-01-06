{{-- @extends('layouts.main-master')
@section('content') --}}
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
.btn-successs {
    color: #fff;
    background-color: #6fd088;
    border-color: #6fd088;
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
    /* border: 1px solid #e8e8e8; */
    padding: 20px;
    background-color: #fff;
}
.card-header-b{
    border-bottom: 1px solid #dad3d3;
}
.card-body-grey{
    /* background-color: #f1f5f7; */
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
    <div class="row" style="margin-top: 100px;">
        <div class="col-md-12">
            <p class="text-center">Please complete the form below. Please provide the correct information.</p>
        
            <div class="card">
                {{-- <h5 class="card-header request-header">
                    Policy Number: <span id="policy-number">{{$policy_no}}</span> 
                </h5> --}}
                @if ($record->tbl_documents_products['product_name'] == "CLAIM REQUEST" || $record->tbl_documents_products['product_name'] == "DEATH CLAIM")
                    <h5 class="card-header request-header">{{ $record->tbl_documents_products['product_name'] }} >> Claim Number : {{ $record['policy_no'] }}</h5>
                @else
                    <h5 class="card-header request-header">{{ $record->tbl_documents_products['product_name'] }} >> Policy Number : {{ $record['policy_no'] }}</h5>
                @endif
                
                <div class="card-body">
                    <div class="">
                        <h5 class="">
                           {{-- <span class="mb-3">Form Progress: </span> --}}
                            <span id="step_header"></span>
                        </h5>
                        <div class="card-body card-body-grey">
                            {{-- Start --}}
                              <!--
                                ----------------------------------------------------------------------------------------------------------------
                                SECTION START OF FORM
                                ----------------------------------------------------------------------------------------------------------------
                            -->

                            <form id="form_step_1" style="display: none" class="step custom-validation" action="{{route('save-update-deathclaim-request')}}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <input type="hidden" name="doc_appl_id" value="{{$document_applications_id}}">
                                <input type="hidden" name="rec_id" value="{{$existing_record[0]->id}}">
                                <input type="hidden" name="product_name" value="EDUCATOR" />
                                <div class="d-flex justify-content-center">
                                    <div class="col-md-8 form-content"  id="panel_for_stage_8">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Claimant</h4>
                                        </div>
                                        <div class="panel-body panel-form">
                                            <div class="form-group">
                                                <label class="form-label" for="policy_number">Policy Number *:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                    <input class="form-control" type="text" id="policy_number" name="policy_number" value="{{ $record['policy_no'] }}" readonly required />
                                                    <input class="form-control" type="hidden" style="display: none;" min="1" max="100" id="claim_percentage" name="claim_percentage" value="100" readonly required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="claim_type">Claim Type * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <select name="claim_type" id="claim_type" class="form-select" required>
                                                    <option value=""></option>
                                                    <option value="Death">Death</option>
                                                    <option value="Personal Accident">Personal Accident</option>
                                                    <option value="Travel">Travel</option>
                                                </select>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="reason_for_claim">Reason For Claim * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <select name="reason_for_claim" id="reason_for_claim" class="form-select" required>
                                                    <option value=""></option>
                                                    <option value="ACCIDENT">Accident</option>
                                                    <option value="HEART ATTACK">Heart Attack</option>
                                                    <option value="MALARIA">Malaria</option>
                                                    <option value="RETRENCHED">Retrenched</option>
                                                    <option value="NATURAL DEATH">Natural Death</option>
                                                    <option value="H.I.V AIDS">H.I.V AIDS</option>
                                                    <option value="CARDIO PULMONARY">Cardio Pulmonary</option>
                                                    <option value="PTB">PTB</option>
                                                    <option value="STROKE">Stroke</option>
                                                    <option value="KIDNEY FAILURE">Kidney Failure</option>
                                                    <option value="CANCER">Cancer</option>
                                                    <option value="PARAPLEGIA">Paraplegia</option>
                                                    <option value="DIABETES">Diabetes</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="name_of_claimant">Name Of Claimant *:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="name_of_claimant" name="name_of_claimant" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                               <div class="col-md-6">
                                                    <label class="form-label" for="mobile">Mobile Number * :</label>
                                                    <div class="col-md-12 col-sm-12 mb-3">
                                                        <input type="text" name="mobile_no" class="form-control phone-num" id="mobile_no" 
                                                        pattern="[0]{1}[0-9]{9}" 
                                                        title="Invalid phone number"
                                                        required  />
                                                        <span class="phone_number_invalid error-message">Invalid phone number</span>
                                                        <ul class="parsley-errors-list filled" id="verify-mobile-error" style="display: none;" aria-hidden="false">
                                                            <li class="parsley-required verify-mobile-error-msg">This value is required.</li>
                                                        </ul>
                                                    </div>
                                               </div>
                                               <div class="form-group col-md-4">
                                                    <div class="" style="margin-top: 30px;">
                                                        <button class="btn btn-primary" id="init-verify-phone-number" type="button">Verify Number</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="mobile">Digital Address* :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input type="text" name="digital_address" class="form-control" id="digital_address" value="" required />
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                                <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Proceed</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--
                                ----------------------------------------------------------------------------------------------------------------
                                SECTION START OF FORM
                                ----------------------------------------------------------------------------------------------------------------
                                -->
                            <form id="form_step_2" style="display: none" class="step custom-validation" action="{{route('save-update-deathclaim-request')}}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <input type="hidden" name="doc_appl_id" value="{{$document_applications_id}}">
                                <input type="hidden" name="rec_id" value="{{$existing_record[0]->id}}">
                                <input type="hidden" name="product_name" value="EDUCATOR" />
                                <div class="d-flex justify-content-center">
                                    <div class="col-md-8 form-content"  id="panel_for_stage_8">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Particulars of Deceased</h4>
                                        </div>
                                        <div class="panel-body panel-form">
                                            <div class="form-group">
                                                <label class="form-label" for="name_of_deceased">Name *:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="name_of_deceased" name="name_of_deceased" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="date_of_birth_of_deceased">Date of Birth * :</label>
                                                <div class="col-md-12 mb-3">
                                                <input type="date" name="date_of_birth_of_deceased" min="<?php echo change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); ?>" max="<?php echo change_date(date('Y-m-d H:i:s'), '-18 years', 'Y-m-d'); ?>" class="form-control" id="date_of_birth_of_deceased" value="" required />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="relationship_to_claimant">Relationship to Claimant * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <select name="relationship_to_claimant" id="relationship_to_claimant" class="form-select" required>
                                                    <option value=""></option>
                                                    <option value="Mother">Spouse</option>
                                                    <option value="Son">Son</option>
                                                    <option value="Son">Daughter</option>
                                                    <option value="Mother">Mother</option>
                                                    <option value="Father">Father</option>
                                                    <option value="Sister">Sister</option>
                                                    <option value="Brother">Brother</option>
                                                    <option value="Auntie">Auntie</option>
                                                    <option value="Uncle">Uncle</option>
                                                    <option value="Nephew">Nephew</option>
                                                    <option value="Niece">Niece</option>
                                                    <option value="Grandmother">Grandmother</option>
                                                    <option value="Grandfather">Grandfather</option>

                                                </select>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="mobile_of_deceased">Mobile Number * :</label>
                                                <div class="col-md-12 mb-3">
                                                <input type="text" name="mobile_of_deceased" class="form-control phone-num" id="mobile_of_deceased" 
                                                pattern="[0]{1}[0-9]{9}" 
                                                title="Invalid phone number"
                                                    required />
                                            </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="home_tel_of_deceased">Home Telephone :</label>
                                                <div class="col-md-12 mb-3">
                                                <input type="text" name="home_tel_of_deceased" class="form-control phone-num" id="home_tel_of_deceased" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="work_tel_of_deceased">Work Telephone :</label>
                                                <div class="col-md-12 mb-3">
                                                    <input type="text" name="work_tel_of_deceased" class="form-control phone-num" id="work_tel_of_deceased" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="address_of_deceased">Physical Address *:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                    <input class="form-control" type="text" id="address_of_deceased" name="address_of_deceased" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="area_of_deceased">Area * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="area_of_deceased" name="area_of_deceased" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="house_number_of_deceased">House Number :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="house_number_of_deceased" name="house_number_of_deceased" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="landmark_to_house_of_deceased">Notable Landmark to the House *:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="landmark_to_house_of_deceased" name="landmark_to_house_of_deceased" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="occupation_of_deceased">Occupation * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="occupation_of_deceased" name="occupation_of_deceased" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="employer_of_deceased">Employer * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="employer_of_deceased" name="employer_of_deceased" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="employer_location_of_deceased">Employer's Location * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="employer_location_of_deceased" name="employer_location_of_deceased" value="" required />
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                                <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button> 
                                                <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Save & Proceed</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--
                                ----------------------------------------------------------------------------------------------------------------
                                SECTION START OF FORM
                                ----------------------------------------------------------------------------------------------------------------
                            -->
                            <form id="form_step_3" style="display: none" class="step custom-validation" action="{{route('save-update-deathclaim-request')}}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <input type="hidden" name="doc_appl_id" value="{{$document_applications_id}}">
                                <input type="hidden" name="rec_id" value="{{$existing_record[0]->id}}">
                                <input type="hidden" name="product_name" value="EDUCATOR" />
                                <div class="d-flex justify-content-center">
                                    <div class="col-md-8 form-content"  id="panel_for_stage_8">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Death Details</h4>
                                        </div>
                                        <div class="panel-body panel-form">
                                            <div class="form-group">
                                                <label class="form-label" for="place_of_death">Place of Death (Home/Hospital/Other) *:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="place_of_death" name="place_of_death" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="name_of_hospital">Name of Hospital/Other :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="name_of_hospital" name="name_of_hospital" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="date_of_death">Date Of Death * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="date" min="<?php echo change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" id="date_of_death" name="date_of_death" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="cause_of_death">Cause of Death * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="cause_of_death" name="cause_of_death" value="" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="place_of_accident">Place of Accident(If Cause of Death is Accidental) :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="place_of_accident" name="place_of_accident" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="name_of_police_station">Name Of Police Station :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="name_of_police_station" name="name_of_police_station" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="address_of_police_station">Address Of Police Station :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="address_of_police_station" name="address_of_police_station" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="cause_of_death">Summary of Accident :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <textarea id="cause_of_death" name="cause_of_death" class="form-control" rows="2"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="body_deposited">Was the body deposited in a Motuary/Funeral Home * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <select id="body_deposited" name="body_deposited" class="form-select" required>
                                                    <option value=""></option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="motuary_name">If yes, state the name of motuary/funeral home :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="motuary_name" name="motuary_name" value="" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="body_buried">Has the body been buried * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <select id="body_buried" name="body_buried" class="form-select" required>
                                                    <option value=""></option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="cemetery_name">If yes, state the name of the cemetery:</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="cemetery_name" name="cemetery_name" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="burial_date">Burial Date :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="date" min="<?php echo change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); ?>" id="burial_date" name="burial_date" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="name_of_entity_handled_burial_service">Name of Church/Funeral Home/Motuary that Handled Burial Service :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="name_of_entity_handled_burial_service" name="name_of_entity_handled_burial_service" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="name_of_entity_handled_burial_service">Contact Details of person to confirm information provided</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                <input class="form-control" type="text" id="contact_details_confirmation" name="contact_details_confirmation" value="" required />
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                                <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button> 
                                                <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Save & Proceed</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>


                            <!--
                            ----------------------------------------------------------------------------------------------------------------
                            SECTION START OF FORM
                            ----------------------------------------------------------------------------------------------------------------
                            -->

                            <form id="form_step_4" style="display: none" class="step custom-validation" action="{{route('save-update-deathclaim-request')}}" method="POST" >
                                @csrf
                                <input type="hidden" name="doc_appl_id" value="{{$document_applications_id}}">
                                <input type="hidden" name="rec_id" value="{{$existing_record[0]->id}}">
                                <input type="hidden" name="product_name" value="EDUCATOR" />
                                <div class="d-flex justify-content-center">
                                    <div class="col-md-8 form-content" id="panel_for_stage_3">
                                        <div class="panel-heading">

                                            <h4 class="panel-title">Claim Payment Option</h4>
                                        </div>
                                        <div class="panel-body panel-form">

                                            <div class="form-group">
                                                <label class="form-label" for="payment_option">How do you want to receive
                                                    the claim * :</label>
                                                <div class="col-md-12 col-sm-12 mb-3">
                                                    <select id="payment_method" 
                                                        name="payment_method" class="form-select" required>
                                                        <option value=""></option>
                                                        <option value="Mobile Money">Mobile Money</option>
                                                        <option value="Bank">Bank</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="payment_option">
                                       
                                            </div>
                                            

                                            <div id="express"></div>

                                            <div class="modal-footer">
                                                <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                                <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button> 
                                                <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Save & Proceed</button>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </form>

                            <!--
                            ----------------------------------------------------------------------------------------------------------------
                            SECTION START OF FORM
                            ----------------------------------------------------------------------------------------------------------------
                            -->


                            <form id="form_step_5" style="display: none" class="step custom-validation" action="{{route('save-update-deathclaim-request')}}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <input type="hidden" name="doc_appl_id" value="{{$document_applications_id}}">
                                <input type="hidden" name="rec_id" value="{{$existing_record[0]->id}}">
                                <input type="hidden" name="product_signature" value="product_signature">
                                <div class="d-flex justify-content-center">
                                    <div class="col-md-8 form-content"  id="panel_for_stage_8">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Disclaimer & Declaration</h4>
                                            <p>Old Mutual Life shall not be held liable for wrong account provided resulting in non-payment of claim</p>
                                        </div>
                    
                                        <div class="panel-body panel-form">
                                            <div class="col-md-12">
                                                {{-- <div class="col-md-12">
                                                    <div class="form-group">
                                                        <span class="control-label col-md-12 col-sm-12" style="text-align: center;"
                                                            for="how_did_you_hear">How did you hear about this product ?</span>
                                                        <div class="col-md-12 col-sm-12">
                                                            <select id="how_did_you_hear" name="how_did_you_hear"
                                                                onchange="set_agent_name_input(this)" required class="form-select">
                                                                <option value=""></option>
                                                                <option value="Agent">Through An Agent</option>
                                                                <option value="Self-Discovery">Self-Discovery</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div> --}}
                                               
                                                <div class="col-md-12" id="agent_code_or_name_holder" style="display: none;">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-12 col-sm-12" style="text-align: center;"
                                                            for="agent_code_or_name">Agent Code :</label>
                                                        <div class="col-md-12 col-sm-12">
                                                            <input class="form-control flexdatalist" type="text" id="agent_code"
                                                                name="agent_code" value="" />
                                                        </div>
                                                    </div>
                    
                                                    <br>
                                                </div>
                    
                                                <div id="modalBasic" class="modal-block mfp-hide">
                                                    <section class="panel">
                                                        <header class="panel-heading">
                                                            {{-- <h2 class="panel-title">Terms & Conditions</h2> --}}
                                                        </header>
                                                        <div class="panel-body">
                                                            <div class="modal-wrapper">
                                                                <div class="modal-text">
                                                                    {{-- <?= $record->tbl_documents_products['terms_and_conditions'] ?> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <footer class="panel-footer">
                                                            <div class="row">
                                                                <div class="col-md-12 text-right">
                                                                    <label for="">Agree</label>
                                                                    <input class="form-check-input" id="agree-term-no-popup" type="checkbox" onclick="enable_submit_btn(this)" id="formCheck2" >
                                                                    {{-- <button class="btn btn-default modal-dismiss"
                                                                        onclick="enable_submit_btn(this)">Agree</button> --}}
                                                                </div>
                                                            </div>
                                                        </footer>
                                                    </section>
                                                </div>
                    
                    
                                                <div class="form-group">
                                                    <div class="col-md-1">
                                                        {{-- <input class="form-control" type="checkbox" value="" id="declarationtext" required> --}}
                                                    </div>
                                                    <div class="col-md-11">
                                                        <label class="control-label modal-basic" id="model_link" href="#modalBasic"
                                                            style="text-align: left;" for="declarationtext">
                                                            I, the undersigned, hereby declare that the information provided by me and required for me on this application is both correct and accurate, and the option selected herein is clear
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="tin">Your Name *:</label>
                                                    <div class="col-md-12 col-sm-12 mb-3">
                                                        <input class="form-control" type="text" id="my_name" name="my_name" readonly />
                                                    </div>
                                                </div>
                    
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <span class="control-label col-md-12 col-sm-12" style="text-align: center;">Signature
                                                            Options</span>
                                                        <div class="col-md-12 col-sm-12">
                                                            <select id="signopt" name="signopt" onchange="setsignature(this)" required
                                                                class="form-select">
                                                                <option value="1">Sign Signature</option>
                                                                <option value="2">Upload Signature Image</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br><br><br><br>
                                                <div class="col-md-12" id="choose_signing_holder" style="display: none;margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <span class="control-label col-md-12 col-sm-12" style="text-align: center;"
                                                            for="sign_img">Choose Signature Image (PNG Format)</span>
                                                        <div class="col-md-12 col-sm-12">
                                                            <input class="form-control" type="file" id="sign_img" name="sign_img"
                                                                accept="image/png" />
                                                        </div>
                                                    </div>
                                                </div><br>
                    
                                                <span id="signing_holder">
                                                    <span class="control-label col-md-12 col-sm-12" style="text-align: center;">
                                                        Sign your signature below
                                                    </span>
                    
                                                    <div id="signature"
                                                        style="min-height: 350px; width:100%; color: darkblue; background-color: lightgrey">
                    
                                                    </div>
                                                    <div id="final_signature" style="display: none;" class="col-md-offset-4 col-md-3">
                    
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                        <div class="col-md-offset-5 col-md-12">
                                                            <button id="re_sign" class="btn btn-info">Re-Sign</button>
                                                        </div>
                                                    </div>
                                                </span>
                    
                    
                                                <input class="form-control" type="hidden" id="final_signature_base64_image_svg"
                                                    name="final_signature_base64_image_svg" readonly required style="display: none;" />
                    
                                                <div class="modal-footer">
                                                    <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                                    <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button>
                                                    <button type="submit" id="finish_appication" class="oml-btn oml-btn-success">Finish Application</button>
                                                </div>
                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                

                            <div id="process-completed" style="display: none">
                                <div>
                                    <h5>All Records Saved Successfully</h5>
                                </div>
                                <div class="button-items mt-3">
                                    <button type="button" data-token="{{$token}}" class="btn btn-successs btn-sm waves-effect waves-light previewProposal">VIEW DIGITAL PROPOSAL FORM</button>
                                    <button type="button" data-token="{{$token}}" class="btn btn-warning btn-sm waves-effect waves-light previewMandate">VIEW MANDATE FORM</button>
                                    <a href="{{route('request-profile', ['section' => $param_section, 'id' => $param_id])}}" type="button" class="btn btn-primary btn-sm waves-effect waves-light">GO BACK TO APPLICATION</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Terms & Conditions</h5>
                                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                            </div>
                            <div class="modal-body">
                                {!!$record->tbl_documents_products->terms_and_conditions!!}
                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="oml-btn btn-light waves-effect" data-bs-dismiss="modal">Close</button> --}}
                                <button type="button" class="oml-btn oml-btn-success" data-bs-dismiss="modal">Agree</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $('input[name="annual_premium_update"]').change(function(){
            // Get the selected value
            let selectedValue = $('input[name="annual_premium_update"]:checked').val();
            $('#annual_premium-selected').empty();
            if(selectedValue == 'YES'){
                $('#yes-selected').css('display', 'block');
                $('#annual_premium-selected').append(`
                    <select name="annual_premium" id="annual_premium" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="20">20%</option>
                        <option value="25">25%</option>
                        <option value="30">30%</option>
                    </select>
                `)

            }else{
                $('#annual_premium-selected').append(`
                    <select name="annual_premium" id="annual_premium" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="15">15%</option>
                    </select>
                `);
            }
        });

        $('select[name="payment_method"]').change(function(){
             // Get the selected value
             let selectedValue = $('select[name="payment_method"]').val();
             console.log(selectedValue);
            $('#payment_option').empty();
            if(selectedValue == "Mobile Money"){
                $('#payment_option').append(`
                    <span id="momo_payment" class="form-group" >
                        <div class="form-group">
                            <label class="form-label" for="telco_name">Telco Operator *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <select class="form-select" type="text" id="telco_name" name="telco_name" value="">
                                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                                    <option value="Vodafone Cash">Vodafone Cash</option>
                                    <option value="Airtel Tigo Money">Airtel Tigo Money</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class=" col-md-6">
                                <label class="form-label" for="wallet_number">Phone Number *:</label>
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <input class="form-control phone-num" type="text" id="wallet_number"
                                    pattern="[0]{1}[0-9]{9}" 
                                    title="Invalid phone number"
                                        name="wallet_number" value="" />
                                    <span class="phone_number_invalid error-message">Invalid phone number</span>
                                    <ul class="parsley-errors-list filled" id="verify-wallet_number-error" style="display: none;" aria-hidden="false">
                                        <li class="parsley-required verify-wallet_number-error-msg">This value is required.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="" style="margin-top: 30px;">
                                    <button class="btn btn-primary" id="init-verify-payment-phone-number" type="button">Verify Number</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="wallet_name">Name On Number *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <input class="form-control" type="text" id="wallet_name" name="wallet_name" readonly />
                            </div>
                        </div>
                    </span>
                `);
            }
            if(selectedValue == "Bank" ){
                $('#payment_option').append(`
                    <span id="bank_payment" class="form-group" >
                        <div class="form-group">
                            <label class="form-label" for="payment_bank_name">Bank Name *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                @php
                                    $banks = App\Models\Bank::where('deleted', 0)->get();
                                @endphp
                                <select name="payment_bank_name" id="tbl_banks_id" class="form-select"
                                    onchange="get_branches_list(this)">
                                    <option value="0">N/Ao</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="payment_bank_branch">Bank Branch
                                *:</label>
                            <div class="col-md-12 col-sm-12 mb-3 select-container">
                                <img src="/assets/images/loader-sm.gif" width="30px" alt="" style="width: 20px; margin-right: 10px; display: none;">
                                <select class="form-select" type="text" id="payment_bank_branch"
                                    name="payment_bank_branch" value="">

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-label" for="payment_account_number">Account Number
                                *:</label>
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <input class="form-control" type="text" id="payment_account_number"
                                        name="account_number" value="" />
                                    <ul class="parsley-errors-list filled" id="payment_account_number-error" style="display: none;" aria-hidden="false">
                                        <li class="parsley-required">This value is required.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="" style="margin-top: 30px;">
                                    <button class="btn btn-primary" id="init-verify-acc" type="button">Verify Account</button>
                                </div>
                            </div>
                           
                        </div>

                        <div class="form-group" style=" margin-bottom: 10px;">
                            <label class="form-label" for="payment_account_holder_name">Account
                                Holder Name *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <input class="form-control" type="text" id="payment_account_holder_name"
                                    name="account_holder_name" readonly />
                            </div>
                        </div>
                    </span>
                `);
            }
            if(selectedValue == "CAG Deductions" || selectedValue == "Stop Order"){
                $('#payment_option').append(`
                    <span id="cag_payment" class="form-group" >
                        <div class="form-group">
                            <label class="form-label" for="employer">Employer *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <input class="form-control flexdatalist" type="text" id="employer" name="employer" value="" />
                            </div>
                        </div>
                        <div class="form-group" style=" margin-bottom: 10px;">
                            <label class="form-label" for="staff_id">Staff ID *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <input class="form-control" type="text" id="staff_id" name="staff_id" value="" />
                            </div>
                        </div>
                        <div class="form-group" style=" margin-bottom: 10px;">
                            <label class="form-label" for="staff_id">Office Building Location
                                *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <input class="form-control" type="text" id="office_building_location"
                                    name="office_building_location" value="" />
                            </div>
                        </div>
                    </span>
                `);
            }
            if(selectedValue == "Cash/Cheque"){
                $('#payment_option').empty();
            }
        });
        // Handle all form submission on this page
        $(document).ready(function() {
            $('#id-verificationModal').modal('show');

           for (let index = 0; index < $('.step').length; index++) {
            let i = index
            let status = 'Step '+ ++i;
            let current_step = `<span class="badge rounded-pill mb-1 badge-soft-primary mr-2" id="step_header_${i}"><i class="ri-focus-fill align-middle me-1"></i><span class="black">${status}: </span>Active</span>`;
            let pending_step = `<span class="badge rounded-pill mb-1 badge-light mr-2" id="step_header_${i}"><i class="ri-focus-2-fill align-middle me-1"></i>${status}</span>`;
            let success_step =`<span class="badge rounded-pill mb-1 badge-soft-success mr-2" id="step_header_${i}"><i class="ri-checkbox-circle-line align-middle me-1"></i><span class="black">${status}:</span> Success</span>`;
            let failed_step =`<span class="badge rounded-pill mb-1 badge-soft-danger mr-2" id="step_header_${i}"><i class="ri-error-warning-line align-middle me-1"></i><span class="black">${status}:</span> Failed</span>`;
            if(index == 0){
                $('#step_header').append(`${current_step}`);
               }else{
                $('#step_header').append(`${pending_step}`);
               }
            }
            console.log($('.step').length);
            let currentStep = 1;
            let trustee = false;
            // Hide all steps and show current
            $('.step').hide();
            $('#form_step_' + currentStep).show();

            $('.prev').click(function() {
                if(trustee){
                    console.log('2bf-'+currentStep);
                    currentStep = currentStep-2;
                    console.log('2-'+currentStep);
                    $('.step').hide();
                    $('#form_step_' + currentStep).show();
                }else{
                    currentStep--;
                    console.log('1-'+currentStep);
                    $('.step').hide();
                    $('#form_step_' + currentStep).show();
                }
                
            });

            

            $(document).on('submit', 'form', function(e) {
                e.preventDefault(); // Prevent the default form submission
                $('.modal-btns').prop('disabled', true); //disable buttons

                console.log('Submitted');

                // Get the form element that triggered the submit event
                let form = $(this);
               
                let fullid = form.attr('id');
                let type = fullid.split('_')[2];
                let isValid = false;

                if(fullid){
                    console.log(fullid);
                    isValid = validateStep(fullid);
                }else{
                    console.log('Ooops!');
                }

                if(isValid){
                    //If no error, submit form and move next
                    $('#step_header_'+currentStep).removeClass('badge-soft-primary');
                    $('#step_header_'+currentStep).addClass('badge-light');
                    $('#step_header_'+currentStep).empty();
                    $('#step_header_'+currentStep).append(`
                        <div class="spinner-grow text-secondary mm-1" role="status" style="width: 10px; height: 10px;"><span class="sr-only">Loading...</span></div>
                        Step ${currentStep}: Submitting
                    `);
                    currentStep++;
                    $('.step').hide();
                    $('#form_step_' + currentStep).show();
                    $('#step_header_'+currentStep).removeClass('badge-light');
                    $('#step_header_'+currentStep).addClass('badge-soft-primary');
                    $('#step_header_'+currentStep).empty();
                    $('#step_header_'+currentStep).append(`<i class="ri-focus-fill align-middle me-1"></i><span class="black">Step ${currentStep}: </span>Active`);
                    
                    submitform(form, currentStep);

                    //if signature step is next, init signature
                    if(fullid == 'form_step_4'){ //can be modified based on form steps
                        //disable button untill T&C agree
                        $('#finish_appication').attr('disabled', 'disabled');

                        if (signature_canvas_has_not_been_set) {
                            signature_canvas_has_not_been_set = false;
                            var $sigdiv = $("#signature");

                            //$('#signature').jSignature({'width': '100%', 'height': 400 });
                            $sigdiv.jSignature({
                                'width': '100%',
                                'height': 400
                            }); // inits the jSignature widget.
                            console.log("sigdiv: " + $sigdiv);
                            // after some doodling...
                            $sigdiv.jSignature("reset"); // clears the canvas and rerenders the decor on it.

                            // Getting signature as SVG and rendering the SVG within the browser. 
                            // (!!! inline SVG rendering from IMG element does not work in all browsers !!!)
                            // this export plugin returns an array of [mimetype, base64-encoded string of SVG of the signature strokes]
                            var sig_not_generated = true;

                            $("#signature").on('click touch touchstart', function(e) {
                                // 'e.target' will refer to div with "#signature" 
                                var datapair = $sigdiv.jSignature("getData");
                                var i = new Image();
                                i.src = datapair;
                                //i.src = "data:" + datapair[0] + "," + datapair[1];
                                $("#final_signature").html("");
                                $(i).appendTo($("#final_signature")); // append the image (SVG) to DOM.
                                $("#final_signature_base64_image_svg").val(datapair);

                                // Getting signature as "base30" data pair
                                // array of [mimetype, string of jSIgnature"s custom Base30-compressed format]
                                //datapair = $sigdiv.jSignature("getData", "base30");
                                // reimporting the data into jSignature.
                                // import plugins understand data-url-formatted strings like "data:mime;encoding,data"
                                //$sigdiv.jSignature("setData", "data:" + datapair.join(","));
                            });


                            $("#re_sign").on('click', function(e) {
                                e.preventDefault();
                                $sigdiv.jSignature("reset");
                                $("#final_signature").html("");
                            });
                        }
                    }

                    let underage_beneficiaries_exist = false;
                    if(fullid == 'form_step_8'){
                        m = document.getElementById(fullid);
                        dobs = m.querySelectorAll("input[name='beneficiary_dob\\[\\]']");

                        for (i = 0; i < dobs.length; i++) {
                            var today = new Date();
                            var birthDate = new Date(dobs[i].value);
                            var age = today.getFullYear() - birthDate.getFullYear();
                            var m = today.getMonth() - birthDate.getMonth();
                            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }
                            console.log(age);
                            if (age < 18) {
                                underage_beneficiaries_exist = true;
                            }
                          
                        } 
                        console.log(underage_beneficiaries_exist);
                        trustee = false;
                        if(!underage_beneficiaries_exist){
                            $('.step').hide();
                            $('#form_step_10').show();

                            $('#step_header_'+currentStep).removeClass('badge-light');
                            $('#step_header_'+currentStep).addClass('badge-soft-success');
                            $('#step_header_'+currentStep).empty();
                            $('#step_header_'+currentStep).append(`<i class="ri-checkbox-circle-line align-middle me-1"></i><span class="black">Step ${currentStep}:</span> Success`);
                            trustee = true;
                            currentStep++;

                            //disable button untill T&C agree
                            $('#finish_appication').attr('disabled', 'disabled');

                            if (signature_canvas_has_not_been_set) {
                            signature_canvas_has_not_been_set = false;
                            var $sigdiv = $("#signature");

                            //$('#signature').jSignature({'width': '100%', 'height': 400 });
                            $sigdiv.jSignature({
                                'width': '100%',
                                'height': 400
                            }); // inits the jSignature widget.
                            console.log("sigdiv: " + $sigdiv);
                            // after some doodling...
                            $sigdiv.jSignature("reset"); // clears the canvas and rerenders the decor on it.

                            // Getting signature as SVG and rendering the SVG within the browser. 
                            // (!!! inline SVG rendering from IMG element does not work in all browsers !!!)
                            // this export plugin returns an array of [mimetype, base64-encoded string of SVG of the signature strokes]
                            var sig_not_generated = true;

                            $("#signature").on('click touch touchstart', function(e) {
                                // 'e.target' will refer to div with "#signature" 
                                var datapair = $sigdiv.jSignature("getData");
                                var i = new Image();
                                i.src = datapair;
                                //i.src = "data:" + datapair[0] + "," + datapair[1];
                                $("#final_signature").html("");
                                $(i).appendTo($("#final_signature")); // append the image (SVG) to DOM.
                                $("#final_signature_base64_image_svg").val(datapair);

                                // Getting signature as "base30" data pair
                                // array of [mimetype, string of jSIgnature"s custom Base30-compressed format]
                                //datapair = $sigdiv.jSignature("getData", "base30");
                                // reimporting the data into jSignature.
                                // import plugins understand data-url-formatted strings like "data:mime;encoding,data"
                                //$sigdiv.jSignature("setData", "data:" + datapair.join(","));
                            });


                            $("#re_sign").on('click', function(e) {
                                e.preventDefault();
                                $sigdiv.jSignature("reset");
                                $("#final_signature").html("");
                            });
                        }
                        }
                    }
                   
                }

            });

            function submitform(form, currentStep){
                let message = 'loading';
                let fullid = form.attr('id');
                let id = fullid.split('_')[1];
                let type = fullid.split('_')[2];
                let action = form.attr('action');

                showAjaxLoading(message, fullid, status = true);
                $('#btn_'+ id + '_' + type).prop('disabled', true);

                // Get the form data
                // let formData = form.serialize();
                let formEl = $('#'+fullid)[0];
                let formData = new FormData(formEl); 
                // Send the form data using AJAX
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    processData: false, 
                    contentType: false, 
                    success: function(resp) {
                        showAjaxLoading(message = null , fullid, status = false);
                        
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        $('.modal-btns').prop('disabled', false);
                        if(resp.status == 'success'){
                            // Request was successful
                            let currstep =  --currentStep;
                            $('#step_header_'+currentStep).removeClass('badge-light');
                            $('#step_header_'+currentStep).addClass('badge-soft-success');
                            $('#step_header_'+currentStep).empty();
                            $('#step_header_'+currentStep).append(`<i class="ri-checkbox-circle-line align-middle me-1"></i><span class="black">Step ${currentStep}:</span> Success`);
                          
                            // Select all elements with class name "step"
                            var stepss = $('.step');
                            // Filter the selected elements to find those with inline style "display: none;"
                            var hiddenSteps = stepss.filter(function() {
                                return $(this).css('display') === 'none';
                            });
                            // Count the number of hidden steps
                            var hiddenStepsCount = hiddenSteps.length;
                            // console.log("Total steps:", stepss.length);
                            // console.log("Hidden steps:", hiddenStepsCount);
                            if(hiddenStepsCount==5){
                                $('#process-completed').css('display', 'block');

                                $('#loading-msg').html('Almost through! Generating Documents...');
                                $('.rightbar-overlay').css('display', 'block');

                                let token = resp.token;
                                $.ajax({
                                    url: "/document/generate/mandate/proposal/forms",
                                    type: 'POST',
                                    data: {'token': token},
                                    success: function(resp) {
                                        if(resp.status == 'success'){
                                            Swal.fire("Generated!", "Proposal and Mandate Forms generated successfully", "success");
                                        }else{
                                            Swal.fire("Failed!", "Unable to generate documents. Generate from Dashboard", "error");
                                        }
                                        $('.rightbar-overlay').css('display', 'none');
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText);
                                        // Handle error
                                    }
                                });
                            }
                           
                            showAjaxSuccess(message = resp.message , fullid);
                            
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
            }
            // function validateStep1(){
            //     // if($('#name1').val()){
            //         // console.log($('#name1').val());
            //         return true;
            //     // }
            // }
            
            function validateStep(fullId) {
                let x, y, i, valid = true;

                x = document.getElementById(fullId);
                y = x.querySelectorAll("input, select");

                function handleInputChange() {
                    if (this.value.trim() !== "") {
                        this.classList.remove("error");
                    } else if (this.hasAttribute('required')) {
                        this.classList.add("error");
                    }
                }
            
                for (i = 0; i < y.length; i++) {
                    if (y[i].value.trim() === "") {
                        if (y[i].hasAttribute('required')) {
                            y[i].classList.add("parsley-error");
                            valid = false;
                        }
                    } else {
                        y[i].classList.remove("parsley-error");
                    }
                    
                    y[i].addEventListener('change', handleInputChange);
                }
                // console.log(valid);
                // if(valid){return true;}else{return false;}

                console.log('PROCEED '+proceed);
                let curr_number = $('#mobile').val() ?? $('#mobile_no').val();
                if(fullId == 'form_step_1'){
                    
                    if(proceed == 1){
                        valid = true;
                    }else if(proceed == 2){
                        valid = false;
                        toastr.error('Information on ID Card and Phone Number does not match. Please re-enter details', 'Verification Failed!');
                    }else{
                        valid = false;
                        toastr.error('Please verify your Mobile Number to proceed', 'Verification Required!');
                    }

                    if (localStorage.getItem('phone_num')) {
                        let verified_number = localStorage.getItem('phone_num');
                        if(verified_number != curr_number){
                            valid = false;
                            toastr.error('Please verify your Phone Number again to proceed.', 'Verification Failed!');    
                        }
                    }
                   
                }

                let wallet_number = $('#wallet_number').val();
                let account_number = $('#account_number').val() ?? $('#payment_account_number').val();
                let payment_method = $('#payment_method').val();

                if(fullId == 'form_step_4'){
                    if(payment_method == "Mobile Money" || payment_method == "Bank"){
                        if(payment_proceed == 1){
                            valid = true;
                        }else if(payment_proceed == 2){
                            valid = false;
                            toastr.error('Information on ID Card and Momo Number do not match.. Please re-enter details', 'Verification Failed!');
                        }else{
                            valid = false;
                            toastr.error('Please verify your Momo Number to proceed', 'Verification Required!');
                        }

                        if (localStorage.getItem('wallet_number')) {
                            let verified_number = localStorage.getItem('wallet_number');
                            if(verified_number != wallet_number){
                                valid = false;
                                toastr.error('Please verify your Momo Number again to proceed.', 'Verification Failed!');    
                            }
                        }

                        if (localStorage.getItem('account_number')) {
                            let verified_number = localStorage.getItem('account_number');
                            if(verified_number != account_number){
                                valid = false;
                                toastr.error('Please verify your Account Number again to proceed.', 'Verification Failed!');    
                            }
                        }
                    }
                }
                // Unique verification per step
                if(valid){
                    if(fullId == 'form_step_8'){
                        j = document.getElementById(fullId);
                        k = j.querySelectorAll("input[name='beneficiary_percentage\\[\\]']");
                        
                        total_percentage = 0;

                        for (i = 0; i < k.length; i++) {
                            if (k[i].value.trim()) {
                                console.log(k[i].value.trim());
                                total_percentage +=parseInt(k[i].value);
                            }
                        }

                        console.log(total_percentage);

                        // if(total_percentage == 100){
                        //     return true;
                        // }else{
                        //     return false;
                        // }
                        // check beneficiaries age

                        if(total_percentage == 100){
                            return true;
                        }else{
                            $('.beneficiary-percentage').addClass('parsley-error');
                            return false;
                        }
                       
                       
                    }else{
                        return true;
                    }
                }
               



                
            }
          

            $(document).on('input', '.phone-num', function() {
                const input = $(this);
                let inputValue = input.val().replace(/[^0-9]/g, "");
                
                if (inputValue.length > 10) {
                    inputValue = inputValue.slice(0, 10); // Truncate to 10 characters if longer
                }

                const isValid = inputValue.match(/^0[0-9]{9}$/);

                const errorMessage = input.next('.error-message');
                errorMessage.css('display', isValid && inputValue.length === 10 ? 'none' : 'block');

                input.val(inputValue); // Update the input value with the truncated value
            });
            $(document).on("change touchleave touchcancel", "input#date_of_birth", function() {
                var age = $("input#age").val();
                var date_of_birth = $(this).val();
                var newdate = date_of_birth.split("/").reverse().join("-");
                dob = new Date(newdate);
                var today = new Date();
                var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
                $("input#age").val(age);
                $('div#show_age').show();

            });

            
            const depdropurl = "/document/ajax_calls/dependent_dropdowns?";
            $("#agent_code").flexdatalist({
            selectionRequired: true,
            removeOnBackspace: true,
            //redoSearchOnFocus: true,
            valueProperty: "agent_code",
            minLength: 1,
            searchByWord: true,
            searchIn: ["agent_name", "agent_code"],
            visibleProperties: ["agent_name", "agent_code"],
            textProperty: ['agent_name'],
            url: depdropurl +  
                $.param({
                action: "getAgentList",
                retrieve_type: "agent_code",
                })
            });

            $("#occupation").flexdatalist({
            selectionRequired: true,
            removeOnBackspace: true,
            redoSearchOnFocus: true,
            valueProperty: "slams_id",
            minLength: 1,
            searchByWord: true,
            searchIn: ["occupation_name"],
            visibleProperties: ["occupation_name"],
            textProperty: ['occupation_name'],
            url: depdropurl +
                $.param({
                action: "getOccupationList",
                retrieve_type: "occupation_name",
                }),

            });
            $("#employer").flexdatalist({
                selectionRequired: true,
                removeOnBackspace: true,
                valueProperty: "emp_code",
                minLength: 1,
                searchByWord: true,
                searchIn: ["name"],
                visibleProperties: ["name"],
                textProperty: ['name'],
                url: depdropurl +
                    $.param({
                    action: "getEmployerList",
                    retrieve_type: "name",
                    }),

            });
        });  
    </script>

    <script>
        // sum assured calulation update
        function calculateSumAssured(){
            let frequency = $('#payment_frequency').val();
            let premium = parseInt($('#contribution_amount').val());

            if(premium){
                if(frequency == "MONTHLY"){
                    sum_assured = premium/1 * 120;
                    if (sum_assured > 10000){sum_assured = 10000}
                    $('#sum_assured').val(sum_assured.toFixed());
                    $(".btn-success").prop("disabled", false);
                }else if(frequency == "QUATERLY"){
                    sum_assured = premium/3 * 120;
                    if (sum_assured > 10000){sum_assured = 10000}
                    $('#sum_assured').val(sum_assured.toFixed());
                    $(".btn-success").prop("disabled", false);
                }else if(frequency == "HALF YEARLY"){
                    sum_assured = premium/6 * 120;
                    if (sum_assured > 10000){sum_assured = 10000}
                    $('#sum_assured').val(sum_assured.toFixed());
                    $(".btn-success").prop("disabled", false);
                }else if(frequency == "YEARLY"){
                    sum_assured = premium/12 * 120;
                    if (sum_assured > 10000){sum_assured = 10000}
                    $('#sum_assured').val(sum_assured.toFixed());
                    $(".btn-success").prop("disabled", false);
                }else{
                    alert('Please select a payment frequency.');
                    $('#payment_frequency').addClass('error');
                    setTimeout(() => {
                        $('#payment_frequency').removeClass('error');
                    }, 5000);
                    $(".btn-success").prop("disabled", true);
                }
            }
        }
        $('#payment_frequency').on('change', function(){
            calculateSumAssured();
        });

        $('#payment_term').on('change', function(){
            let age = $('#age').val();
            let payment_term = $('#payment_term').val();
            let limit = parseInt(age) + parseInt(payment_term);

            if(limit>65){
                alert('Please select a lower payment term.');
                $('#payment_term').addClass('error');
                setTimeout(() => {
                    $('#payment_term').removeClass('error');
                }, 5000);
                $(".btn-success").prop("disabled", true);
                
            }else{
                $(".btn-success").prop("disabled", false);
            }
        });
    </script>

    <script>
        $(document).on('click', 'button#privacy', function() {

            $('div#priv_show').hide();
            $('div#show_form').show();
            $('div#panel_for_stage_1').show();

        });

        // function setIDRestriction(x, input_id) {
        //     document.getElementById(input_id).value = "";
        //     if (x.value == "Drivers License") {
        //         document.getElementById(input_id).setAttribute('maxLength', '18');
        //     } else if (x.value == "Passport") {
        //         document.getElementById(input_id).setAttribute('maxLength', '8');
        //     } else if (x.value == "Voter ID") {
        //         document.getElementById(input_id).setAttribute('maxLength', '10');
        //     } else if (x.value == "SSNIT") {
        //         document.getElementById(input_id).setAttribute('maxLength', '20');
        //     }
        // }

        var terms_shown = 0;
        $("#declarationtext").change(function() {
            if (terms_shown == 0) {
                $('#model_link').click();
                terms_shown = 1;
            }
        });
    
        // function get_branches_list(x) {
        //     // console.log('here');
        //     var ele = $(this);
        //     var row = ele.closest('tr');
        //     var organ = document.getElementById("payment_bank_branch");

        //     //console.log(ele.val());
        //     $('#ajax-loader').css('display', 'block');
        //     // organ.find('option:not(:first)').remove();
        //     var postData = {
        //         'type_id': x.value,
        //         'action': "bank_branch",
        //     };
        //     $.ajax({
        //         url: '../ajax_calls/dependent_dropdowns',
        //         type: 'GET',
        //         data: postData,
        //         dataType: 'json',

        //         success: function(res) {
        //             //console.log(res);
        //             $('#ajax-loader').css('display', 'none');
        //             if (res.state == 1) {
        //                 organ.innerHTML = "";
        //                 // console.log(res.data.Banks, true);
        //                 $.each(res.data, function(e, u) {
        //                     // var option = "";
        //                     //select_item.removeChild(options.u);

        //                     organ.innerHTML += "<option value=" + u.id + "_" + u.bankBranchName +
        //                         " selected>" + u.bankBranchName +
        //                         "</option>";


        //                 });

        //             }
        //         }
        //     });

        // }
        $(document).on('click', '#agree-term-no-popup', function(){
            if($('#agree-term-no-popup').prop('checked')){
                $('#finish_appication').removeAttr("disabled");
            }else{
                $('#finish_appication').attr('disabled', 'disabled');
            }
        });

        function enable_submit_btn() {
            
            if($('#agree-term').prop('checked')){
                $('#staticBackdrop').modal('show');
                $('#finish_appication').removeAttr("disabled");
            }else{
                $('#finish_appication').attr('disabled', 'disabled');
            }
        

            // $('#modalBasic').addClass("mfp-hide");
            // $('.mfp-ready').remove();
            // $('#finish_appication').removeAttr("disabled");
            // $("#declarationtext").prop('checked', true);
        }
        signature_canvas_has_not_been_set = true;

        added = 1;

        function add_more_beneficiaries() {
            if (added < 10) {
                if (added == 1) {
                    position = "Second Beneficiary";
                    position_num = "two";
                } else if (added == 2) {
                    position = "Third Beneficiary";
                    position_num = "three";
                } else if (added == 3) {
                    position = "Fourth Beneficiary";
                    position_num = "four";
                } else if (added == 4) {
                    position = "Fifth Beneficiary";
                    position_num = "five";
                } else if (added == 5) {
                    position = "Sixth Beneficiary";
                    position_num = "six";
                } else if (added == 6) {
                    position = "Seventh Beneficiary";
                    position_num = "seven";
                } else if (added == 7) {
                    position = "Eighth Beneficiary";
                    position_num = "eight";
                } else if (added == 8) {
                    position = "Nineth Beneficiary";
                    position_num = "nine";
                } else if (added == 9) {
                    position = "Tenth Beneficiary";
                    position_num = "ten";
                }



                added++;

                $('#beneficiaries_holder').append('<div class="col-md-12" style="margin-top: 10px" id="holder_beneficiary_' +
                    added + '"><div class="row bb"><label class="form-label"><h6><i class="ri-focus-fill align-middle me-1"></i>' + position +
                    '</h6></label></div><div class="row"><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_full_name">Full Name *:</label><div class="col-md-12 col-sm-12 mb-3"><input class="form-control" required type="text" id="beneficiary_' +
                    position_num + '_full_name" name="beneficiary' +
                    '_full_name[]" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_dob">Date Of Birth *:</label><div class="col-md-12 col-sm-12 mb-3"><input class="form-control"  min="<?php echo change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); ?>"  max="<?php echo date('Y-m-d'); ?>" type="date" required id="beneficiary_' +
                    position_num + '_dob" name="beneficiary_dob[]" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_relationship">Relationship *:</label><div class="col-md-12 col-sm-12 mb-3"><select name="beneficiary' +
                    '_relationship[]"  onchange="set_real_rela_input(this)" data-bentype="' + position_num +
                    '"  id="beneficiary_' + position_num +
                    '_relationship"  required class="form-select"><option value=""></option><option value="SON">SON</option><option value="WIFE">WIFE</option><option value="HUSBAND">HUSBAND</option><option value="MOTHER">MOTHER</option><option value="FATHER">FATHER</option><option value="DAUGHTER">DAUGHTER</option><option value="BROTHER">BROTHER</option><option value="SISTER">SISTER</option><option value="FATHER-IN-LAW">FATHER-IN-LAW</option><option value="MOTHER-IN-LAW">MOTHER-IN-LAW</option></select></div></div></div><div class="col-md-12" id="beneficiary_' +
                    position_num +
                    '_real_relationship_holder" style="display: none;"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_real_relationship">State Relationship *:</label><div class="col-md-12 col-sm-12 mb-3"><input class="form-control" type="text" id="beneficiary_' +
                    position_num + '_real_relationship" name="beneficiary' +
                    '_real_relationship[]" value="" /></div></div></div>' +
                    `<div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label"
                                for="beneficiary_gendee">Gender *:</label>
                            <div class="col-md-12 col-sm-12 mb-3">
                                <select name="beneficiary_gender[]" data-bentype="one"
                                    id="beneficiary_gender" required class="form-select">
                                    <option value=""></option>
                                    <option value="M">MALE</option>
                                    <option value="F">FEMALE</option>

                                </select>
                            </div>
                        </div>
                    </div>`+
                    '<div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_percentage">Percentage(%) *:</label><div class="col-md-12 col-sm-12 mb-3"><input class="form-control beneficiary-percentage" type="number"  min="0" id="beneficiary_' +
                    position_num + '_percentage" name="beneficiary_percentage[]" value="" required /></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num + '_id_type">ID Type :</label><div class="col-md-12 col-sm-12 mb-3"><select id="beneficiary_' +
                    position_num + '_id_type" name="beneficiary' +
                    '_id_type[]" class="form-control" '+ `onchange="setIDRestriction(this, 'beneficiary_${position_num}_id_number')"`+ '><option value=""></option><option value="Drivers License">Drivers License</option><option value="Passport">Passport</option> <option value="Ghana Card">Ghana Card</option><option value="Voter ID">Voter ID</option><option value="SSNIT">SSNIT</option></select></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_id_number">ID Number :</label><div class="col-md-12 col-sm-12 mb-3"><input class="form-control" type="text" id="beneficiary_' +
                    position_num + '_id_number" name="beneficiary' +
                    '_id_number[]" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                    position_num +
                    '_phone_no">Phone Number :</label><div class="col-md-12 col-sm-12 mb-3"><input class="form-control phone-num" type="text" id="beneficiary_' +
                    position_num + '_phone_no" pattern="[0]{1}[0-9]{9}" title="Invalid phone number" name="beneficiary' + 
                    '_phone_no[]" value="" /></div></div></div></div></div></div></div>');
                $('#remove_beneficiary_btn').fadeIn();
            }
        }


        function remove_beneficiary(x) {
            holderid = "holder_beneficiary_" + added;
            if (added > 1) {
                console.log("holderid: " + holderid);
                $('#' + holderid).remove();
                added--;
                if (added < 2) {
                    $('#remove_beneficiary_btn').hide();
                }
            } else {
                $('#remove_beneficiary_btn').hide();
            }
        }

        function check_trustee(x, panel) {

            this_dob = document.getElementById('trustee_dob').value;

            var today = new Date();
            var birthDate = new Date(this_dob);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            console.log('TRUSTEE AGE IS: ' + age);

            if (age < 18 || isNaN(age)) {
                swal("Oops!", "The trustee cannot be less than 18", "error");
            } else {
                change_form_stage(x, 'panel_for_stage_8');
            }
        }

        function set_agent_name_input(x) {
            if (x.value == "Agent") {
                $('#agent_code_or_name_holder').fadeIn();
                //$("#agent_code_or_name").prop('required', true);
            } else {
                $('#agent_code_or_name_holder').hide();
                //$("#agent_code_or_name").prop('required', false);
            }
        }

        function select_income_type(x) {
            if (x.value == "Other") {
                $("#other_income_sources_label").html('State All Income Sources * :');
                $("#other_income_sources").prop('required', true);
            } else {
                $("#other_income_sources_label").html('Other Income Sources :');
                $("#other_income_sources").prop('required', false);
            }
        }

        function set_other_title_field(x) {
            if (x.value == "Other") {
                $('#other_title_holder').fadeIn();
                $("#other_title").prop('required', true);
            } else {
                $('#other_title_holder').hide();
                $("#other_title").prop('required', false);
            }
        }
        function set_other_reason_for_claim(x) {
            if (x.value == "OTHER") {
                $('#other_reason_for_claim_holder').fadeIn();
                $("#other_reason_for_claim").prop('required', true);
            } else {
                $('#other_reason_for_claim_holder').hide();
                $("#other_reason_for_claim").prop('required', false);
            }
        }


        function check_beneficiaries(id, error_message, next_panel, next_btn, check_type, checks_age_limits) {
            // var error = check_date_input(id, error_message, next_panel, next_btn, check_type, checks_age_limits, true);
            // if (error) {
            //     return;
            // }
            var total_percentage = 0;
            var no_underage_beneficiaries_exist = true;

            for (let index = 1; index <= added; index++) {
                if (index == 1) {
                    this_position_num = "one";
                } else if (index == 2) {
                    this_position_num = "two";
                } else if (index == 3) {
                    this_position_num = "three";
                } else if (index == 4) {
                    this_position_num = "four";
                } else if (index == 5) {
                    this_position_num = "five";
                } else if (index == 6) {
                    this_position_num = "six";
                } else if (index == 7) {
                    this_position_num = "seven";
                } else if (index == 8) {
                    this_position_num = "eight";
                } else if (index == 9) {
                    this_position_num = "nine";
                } else if (index == 10) {
                    this_position_num = "ten";
                }
                console.log(index);
                this_percentage = document.getElementById('beneficiary_' + this_position_num + '_percentage').value;
                console.log(this_percentage);
                this_dob = document.getElementById('beneficiary_' + this_position_num + '_dob').value;
                console.log(this_dob);


                var today = new Date();
                var birthDate = new Date(this_dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 18) {
                    no_underage_beneficiaries_exist = false;
                }


                if (!isNaN(this_percentage)) {
                    total_percentage = +total_percentage + +this_percentage;
                } else {
                    total_percentage = 0;
                }
            }

            //console.log("FINAL total_percentage: " + total_percentage);
            if (total_percentage == 100) {
                if (no_underage_beneficiaries_exist) {
                    document.getElementById("beneficiary_panel_proceed_btn").setAttribute("panel", "panel_for_stage_8");
                    change_form_stage(document.getElementById("beneficiary_panel_proceed_btn"), 'panel_for_stage_8');
                    $("#trustee_full_name ").prop('required', false);
                    $("#trustee_dob ").prop('required', false);
                    $("#trustee_gender ").prop('required', false);
                    $("#trustee_relationship ").prop('required', false);
                } else {
                    document.getElementById("beneficiary_panel_proceed_btn").setAttribute("panel", "panel_for_stage_9");
                    change_form_stage(document.getElementById("beneficiary_panel_proceed_btn"), 'panel_for_stage_9');
                    $("#trustee_full_name ").prop('required', true);
                    $("#trustee_dob ").prop('required', true);
                    $("#trustee_gender ").prop('required', true);
                    $("#trustee_relationship ").prop('required', true);
                }
            } else {
                swal("Oops!", "The total percentage for all the beneficiaries must be 100!", "error");
            }
        }

        function setsignature(x) {
            if (x.value == "2") {
                $('#signing_holder').hide();
                $('#choose_signing_holder').fadeIn();
                $("#sign_img").attr('required', '');
                $('#final_signature_base64_image_svg').removeAttr('required');
            } else {
                $('#choose_signing_holder').hide();
                $('#signing_holder').fadeIn();
                $("#sign_img").removeAttr('required');
                $('#final_signature_base64_image_svg').attr('required', '');
            }
        }

        function set_real_rela_input(x) {
            bentype = x.getAttribute("data-bentype");
            console.log("bentype: " + bentype);
            if (x.value == "Other") {
                $('#beneficiary_' + bentype + '_real_relationship_holder').fadeIn();
                $('#beneficiary_' + bentype + '_real_relationship').prop('required', true);
            } else {
                $('#beneficiary_' + bentype + '_real_relationship_holder').hide();
                $('#beneficiary_' + bentype + '_real_relationship').prop('required', false);
            }
        }

        function select_income_type(x) {
            if (x.value == "Other") {
                $("#other_income_sources_label").html('State All Income Sources * :');
                $("#other_income_sources").prop('required', true);
            } else {
                $("#other_income_sources_label").html('Other Income Sources :');
                $("#other_income_sources").prop('required', false);
            }
        }

        function set_other_payment_term(x) {
            if (x.value == "Other") {
                $('#other_payment_term_holder').fadeIn();
                $("#other_payment_term").prop('required', true);
            } else {
                $('#other_payment_term_holder').hide();
                $("#other_payment_term").prop('required', false);
            }
        }

        $('#contribution_amount').on('input', function() {
            $('#sum_assured').val("");
            calculateSumAssured();
        });

        function toggle_region_in_ghana_field_display(x) {
            if (x.value == "1") {
                $('#region_in_ghana_holder').fadeIn();
                $("#region_in_ghana").prop('required', true);
            } else if (x.value == "0") {
                $('#region_in_ghana_holder').hide();
                $("#region_in_ghana").prop('required', false);
            }
        }

        function toggle_illment_details_field_display(x) {
            if (x.value == "Yes") {
                $('#illment_description_holder').fadeIn();
                $("#illment_description").prop('required', true);
            } else if (x.value == "No") {
                $('#illment_description_holder').hide();
                $("#illment_description").prop('required', false);
            }
        }


        function check_premium(x, panel, id, error_message, next_panel, next_btn, check_type, checks_age_limits, return_error) {
            error_exists = check_date_input(id, error_message, next_panel, next_btn, check_type, checks_age_limits,
                return_error)

            if ($('#contribution_amount').val() < 50) {
                swal("Oops!", "The premium cannot be less then Gh¢50", "error");
            } else {
                if (!error_exists) {
                    change_form_stage(x, panel);
                }
            }
        }

        function check_date_input(id, error_message, next_panel, next_btn, check_type, checks_age_limits, return_error) {
            var today = new Date();
            var error = false;

            for (let index = 0; index < id.length; index++) {
                const element = id[index];

                if (document.getElementById(element) == null) {
                    break;
                }
                this_date = document.getElementById(element).value;
                if (this_date.trim() == "") {
                    error = true;
                    break;
                }
                var birthDate = new Date(this_date);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                console.log('entered date: ' + this_date);
                console.log('age: ' + age);
                console.log('first_checking_greater_than: ' + check_type[index][0]);
                console.log('first_check_age_limit: ' + checks_age_limits[index][0]);
                console.log('second_checking_greater_than: ' + check_type[index][1]);
                console.log('second_check_age_limit: ' + checks_age_limits[index][1]);

                if (check_type[index][0] == "1") {
                    if (age > checks_age_limits[index][0]) {
                        error = true;
                        break;
                    }
                } else if (check_type[index][0] == "2") {
                    if (age < checks_age_limits[index][0]) {
                        error = true;
                        break;
                    }
                } else if (check_type[index][0] == "3") {
                    var now = new Date();
                    console.log("checking if past");
                    now.setHours(0, 0, 0, 0);
                    if (birthDate < now) {
                        console.log("Selected date is in the past");
                        error = true;
                        break;
                    }
                }

                if (check_type[index][1] == "1") {
                    if (age > checks_age_limits[index][1]) {
                        error = true;
                        break;
                    }
                } else if (check_type[index][1] == "2") {
                    if (age < checks_age_limits[index][1]) {
                        error = true;
                        break;
                    }
                }
            }


            if (error) {
                swal("Oops!", error_message, "error");
                if (return_error) {
                    return error
                } else {
                    return;
                }

            }


            if (return_error) {
                return error
            } else {
                change_form_stage(next_btn, next_panel);
            }
        }
    </script>
@stop