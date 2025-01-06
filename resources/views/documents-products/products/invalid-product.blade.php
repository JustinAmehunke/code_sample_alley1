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
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header request-header">
                    OLD MUTUAL : <span id="policy-number">Digital Form</span> 
                </h5>
                
                <div class="card-body d-flex justify-content-center">
                    <div class="col-md-8">
                        <div class="card border border-danger">
                            <div class="card-header bg-transparent border-danger">
                                <h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i>Invalid Request</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Product not found</h5>
                                <p class="card-text">Something went wrong with the request you have initiated. Kindly try again or contact support.</p>
                                <div style="display: block">
                                    <div>
                                    </div>
                                    <div class="button-items mt-3">
                                        <a href="{{route('document-my-requests')}}" type="button" class="btn btn-success btn-sm waves-effect waves-light">GO BACK TO APPLICATION</a>
                                        {{-- <button type="button" class="btn btn-warning btn-sm waves-effect waves-light">VIEW MANDATE FORM</button>
                                        <button type="button" class="btn btn-primary btn-sm waves-effect waves-light">GO BACK TO APPLICATION</button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Static backdrop</h5>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <p>Display T&C here. I will not close if you click outside me. Don't even try to press escape key.</p>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="oml-btn btn-light waves-effect" data-bs-dismiss="modal">Close</button> --}}
                        <button type="button" class="oml-btn oml-btn-success" data-bs-dismiss="modal">Agree</button>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
{{-- </form> --}}

{{-- @endsection --}}
    
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
        // Handle all form submission on this page
        $(document).ready(function() {
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
            // Hide all steps and show current
            $('.step').hide();
            $('#form_step_' + currentStep).show();

            $('.prev').click(function() {
                currentStep--;
                $('.step').hide();
                $('#form_step_' + currentStep).show();
            });

            

            $(document).on('submit', 'form', function(e) {
                e.preventDefault(); // Prevent the default form submission
                $('.modal-btns').prop('disabled', true); //disable buttons

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
                    if(fullid == 'form_step_8'){ //can be modified based on form steps
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
                    if(fullid == 'form_step_7'){
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
                            if (age > 18) {
                                underage_beneficiaries_exist = true;
                            }
                          
                        } 
                        console.log(underage_beneficiaries_exist);
                        if(underage_beneficiaries_exist){
                            $('.step').hide();
                            $('#form_step_9').show();

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
                            --currentStep;
                            $('#step_header_'+currentStep).removeClass('badge-light');
                            $('#step_header_'+currentStep).addClass('badge-soft-success');
                            $('#step_header_'+currentStep).empty();
                            $('#step_header_'+currentStep).append(`<i class="ri-checkbox-circle-line align-middle me-1"></i><span class="black">Step ${currentStep}:</span> Success`);
                  
                            $('#view_department_modal').modal('hide');

                            showAjaxSuccess(message = resp.message , fullid);
                            
                            // console.log(resp.departments);
                            reloadTable(resp.departments);
                            
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

                // Unique verification per step
                if(valid){
                    if(fullId == 'form_step_7'){
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

            
            const depdropurl = "/ajax_calls/dependent_dropdowns?";
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
            //redoSearchOnFocus: true,
            valueProperty: "slams_id",
            minLength: 1,
            searchByWord: true,
            searchIn: ["occupation_name"],
            visibleProperties: ["occupation_name"],
            textProperty: ['occupation_name'],
            url: depdropurl +
                $.param({
                action_page: "getOccupationList",
                retrieve_type: "occupation_name",
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

    function setIDRestriction(x, input_id) {
        document.getElementById(input_id).value = "";
        if (x.value == "Drivers License") {
            document.getElementById(input_id).setAttribute('maxLength', '18');
        } else if (x.value == "Passport") {
            document.getElementById(input_id).setAttribute('maxLength', '8');
        } else if (x.value == "Voter ID") {
            document.getElementById(input_id).setAttribute('maxLength', '10');
        } else if (x.value == "SSNIT") {
            document.getElementById(input_id).setAttribute('maxLength', '20');
        }
    }

    var terms_shown = 0;
    $("#declarationtext").change(function() {
        if (terms_shown == 0) {
            $('#model_link').click();
            terms_shown = 1;
        }
    });
   

   

    function get_branches_list(x) {
        // console.log('here');
        var ele = $(this);
        var row = ele.closest('tr');
        var organ = document.getElementById("payment_bank_branch");

        //console.log(ele.val());

        // organ.find('option:not(:first)').remove();
        var postData = {
            'type_id': x.value,
            'action': "bank_branch",
        };
        $.ajax({
            url: '../ajax_calls/dependent_dropdowns',
            type: 'GET',
            data: postData,
            dataType: 'json',

            success: function(res) {
                //console.log(res);
                if (res.state == 1) {
                    organ.innerHTML = "";
                    // console.log(res.data.Banks, true);
                    $.each(res.data, function(e, u) {
                        // var option = "";
                        //select_item.removeChild(options.u);

                        organ.innerHTML += "<option value=" + u.id + "_" + u.bankBranchName +
                            " selected>" + u.bankBranchName +
                            "</option>";


                    });

                }
            }
        });

    }

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
                '_full_name">Full Name *:</label><div class="col-md-12 col-sm-12 mb-2"><input class="form-control" required type="text" id="beneficiary_' +
                position_num + '_full_name" name="beneficiary_' + position_num +
                '_full_name" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num +
                '_dob">Date Of Birth *:</label><div class="col-md-12 col-sm-12 mb-2"><input class="form-control"  min="<?php echo change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); ?>"  max="<?php echo date('Y-m-d'); ?>" type="date" required id="beneficiary_' +
                position_num + '_dob" name="beneficiary_dob[]" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num +
                '_relationship">Relationship *:</label><div class="col-md-12 col-sm-12 mb-2"><select name="beneficiary_' +
                position_num + '_relationship"  onchange="set_real_rela_input(this)" data-bentype="' + position_num +
                '"  id="beneficiary_' + position_num +
                '_relationship"  required class="form-control"><option value=""></option><option value="SON">SON</option><option value="WIFE">WIFE</option><option value="HUSBAND">HUSBAND</option><option value="MOTHER">MOTHER</option><option value="FATHER">FATHER</option><option value="DAUGHTER">DAUGHTER</option><option value="BROTHER">BROTHER</option><option value="SISTER">SISTER</option><option value="FATHER-IN-LAW">FATHER-IN-LAW</option><option value="MOTHER-IN-LAW">MOTHER-IN-LAW</option></select></div></div></div><div class="col-md-12" id="beneficiary_' +
                position_num +
                '_real_relationship_holder" style="display: none;"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num +
                '_real_relationship">State Relationship *:</label><div class="col-md-12 col-sm-12 mb-2"><input class="form-control" type="text" id="beneficiary_' +
                position_num + '_real_relationship" name="beneficiary_' + position_num +
                '_real_relationship" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num +
                '_percentage">Percentage(%) *:</label><div class="col-md-12 col-sm-12 mb-2"><input class="form-control beneficiary-percentage" type="number"  min="0" id="beneficiary_' +
                position_num + '_percentage" name="beneficiary_percentage[]" value="" required /></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num + '_id_type">ID Type :</label><div class="col-md-12 col-sm-12 mb-2"><select id="beneficiary_' +
                position_num + '_id_type" name="beneficiary_' + position_num +
                '_id_type" class="form-control"><option value=""></option><option value="Drivers License">Drivers License</option><option value="Passport">Passport</option><option value="National ID">National ID</option><option value="Voter ID">Voter ID</option><option value="SSNIT">SSNIT</option></select></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num +
                '_id_number">ID Number :</label><div class="col-md-12 col-sm-12 mb-2"><input class="form-control" type="text" id="beneficiary_' +
                position_num + '_id_number" name="beneficiary_' + position_num +
                '_id_number" value="" /></div></div></div><div class="col-md-12"><div class="form-group"><label class="form-label" for="beneficiary_' +
                position_num +
                '_phone_no">Phone Number :</label><div class="col-md-12 col-sm-12 mb-2"><input class="form-control" type="text" id="beneficiary_' +
                position_num + '_phone_no" name="beneficiary_' + position_num +
                '_phone_no" value="" /></div></div></div></div></div></div></div>');
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
            $("#sign_img").prop('required', true);
        } else {
            $('#choose_signing_holder').hide();
            $('#signing_holder').fadeIn();
            $("#sign_img").prop('required', false);
        }
    }

    function set_payment_method_needed_info(x) {
        if (x.value == "CAG Deductions" || x.value == "Stop Order") {
            $('#momo_payment').hide();
            $('#bank_payment').hide();
            $('#cag_payment').fadeIn();
            $("#wallet_number").prop('required', false);
            $("#telco_name").prop('required', false);
            $("#payment_bank_name").prop('required', false);
            $("#payment_bank_branch").prop('required', false);
            $("#payment_account_number").prop('required', false);
            $("#payment_account_holder_name").prop('required', false);
            $("#staff_id").prop('required', true);
            $("#employer").prop('required', true);
        } else if (x.value == "Debit Order") {
            $('#momo_payment').hide();
            $('#cag_payment').hide();
            $('#bank_payment').fadeIn();
            $("#staff_id").prop('required', false);
            $("#employer").prop('required', false);
            $("#wallet_number").prop('required', false);
            $("#telco_name").prop('required', false);
            $("#payment_bank_name").prop('required', true);
            $("#payment_bank_branch").prop('required', true);
            $("#payment_account_number").prop('required', true);
            $("#payment_account_holder_name").prop('required', true);
        } else if (x.value == "Mobile Money") {
            $('#cag_payment').hide();
            $('#bank_payment').hide();
            $('#momo_payment').fadeIn();
            $("#staff_id").prop('required', false);
            $("#employer").prop('required', false);
            $("#payment_bank_name").prop('required', false);
            $("#payment_bank_branch").prop('required', false);
            $("#payment_account_number").prop('required', false);
            $("#payment_account_holder_name").prop('required', false);
            $("#wallet_number").prop('required', true);
            $("#telco_name").prop('required', true);
        } else {
            $('#cag_payment').hide();
            $('#bank_payment').hide();
            $('#momo_payment').hide();
            $("#staff_id").prop('required', false);
            $("#employer").prop('required', false);
            $("#wallet_number").prop('required', false);
            $("#telco_name").prop('required', false);
            $("#payment_bank_name").prop('required', false);
            $("#payment_bank_branch").prop('required', false);
            $("#payment_account_number").prop('required', false);
            $("#payment_account_holder_name").prop('required', false);
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

        // var premium = $('#contribution_amount').val();
        // if (premium.trim() != "") {
        //     console.log("premium: " + premium);
        //     $('#sum_assured').val(Math.min((120 * premium), 10000));
        // } else {
        //     console.log("premium: " + premium);
        //     $('#sum_assured').val("");
        // }
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

    function change_form_stage(x, stageName) {
        var fieldIds = {
            panel_for_stage_2: [
                'title',
                'surname',
                'firstname',
                'othernames',
                'gender',
                'date_of_birth',
                'country_of_birth',
                'nationality',
                'marital_status',
                'id_type',
                'id_number',
                'id_type_in_resident_country',
                'id_number_in_resident_country',
                'is_politically_exposed',
                'is_a_smoker',
                'with_dependants'
            ],
            panel_for_stage_3: [
                'mobile',
                'email',
                'address',
                'client_resides_in_ghana',
                'region_in_ghana'

            ],
            panel_for_stage_4: [
                'source_of_income',
                'other_income_sources',
                'occupation',
                'tin',
            ],
            panel_for_stage_5: [
                'payment_term',
                'other_payment_term',
                'payment_method',
                'telco_name',
                'wallet_number',
                'payment_bank_name',
                'payment_bank_branch',
                'payment_account_number',
                'payment_account_holder_name',
                'employer',
                'staff_id',
                'payment_frequency',
                'contribution_amount',
                'sum_assured',
                'payment_commencement_month',
            ],
            panel_for_stage_6: [
                'health_issues1',
                'health_issues2',
                'illment_description',
            ],
            panel_for_stage_8: [
                'beneficiary_one_full_name',
                'beneficiary_one_dob',
                'beneficiary_one_relationship',
                'beneficiary_one_real_relationship',
                'beneficiary_one_percentage',
                'beneficiary_one_id_type',
                'beneficiary_one_id_number',
                'trustee_full_name',
                'trustee_dob',
                'trustee_relationship',
                'trustee_id_type',
                'trustee_id_number',
                'trustee_mobile_number',
            ]

        };
        var isValid = true;
        var fields = fieldIds[stageName];
        if (fields) {

            fields.forEach(function(id) {
                var selector = '#' + id;
                var $field = $(selector);
                var isRequired = $field.attr('required');
                if (isRequired && !$field.val()) {
                    isValid = false;
                    $field.addClass('error');
                } else {
                    $field.removeClass('error');
                }
            })

            if (!isValid) {
                return;
            }
        }

        change = x.getAttribute("panel");
        $('.panel-inverse').hide();
        $('#' + change).fadeIn();
        if (change == "panel_for_stage_8") {
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
</script>

<script>

</script>

@stop