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
                    Policy Number: <span id="policy-number">{{$policy_no}}</span> 
                </h5>
                
                <div class="card-body">
                    <div class="card">
                        <h5 class="card-header card-header-b">
                           <span class="mb-3">Form Progress: </span>
                            <span id="step_header"></span>
                            {{-- <span class="badge rounded-pill badge-soft-success"><i class="ri-checkbox-circle-line align-middle me-1"></i><span class="black">Step 1:</span> Success</span>
                            <span class="badge rounded-pill badge-soft-danger"><i class="ri-error-warning-line align-middle me-1"></i><span class="black">Step 2:</span> Failed</span>
                            <span class="badge rounded-pill  badge-light">
                                <div class="spinner-grow text-secondary mm-1" role="status" style="width: 10px; height: 10px;"><span class="sr-only">Loading...</span></div>
                                Step 2: Submitting</span>
                            <span class="badge rounded-pill  badge-light"><i class="ri-focus-2-fill align-middle me-1"></i>Final Step: Pending</span> --}}
                        </h5>
                        <div class="card-body card-body-grey">
                               <!-- Step 1: -->
                    {{-- <form id="form_step_1" class="step" action="/test1" method="POST" >
                        @csrf
                        <div class="col-md-8">
                            <div class="mb-3 position-relative">
                                <label for="validationTooltip04" class="form-label">Department Name 1</label>
                                <input type="text" class="form-control" name="department_name"  id="name1"  required="">
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="validationTooltip04" class="form-label">Mailing List 1</label>
                                <input type="email" class="form-control" name="mailing_list" id="mailing_list"  required="">
                            </div>
                            <div class="modal-footer">
                                <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Save & Proceed</button>
                            </div>
                        </div>
                    </form>
                      <!-- Step 2: -->
                    <form id="form_step_2" class="step" action="#" method="POST" >
                        <div class="modal-body">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3 position-relative">
                                        <label for="validationTooltip04" class="form-label">Department Name 2</label>
                                        <input type="text" class="form-control" name="department_name"  id="name"  required="">
                                    </div>
                                    <div class="mb-3 position-relative">
                                        <label for="validationTooltip04" class="form-label">Mailing List 2</label>
                                        <input type="email" class="form-control" name="mailing_list" id="mailing_list"  required="">
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                            <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button> 
                            <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Save & Proceed</button>
                        </div>
                    </form>
                      <!-- Step 3: -->
                    <form id="form_step_3" class="step" action="#" method="POST" >
                        <div class="modal-body">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3 position-relative">
                                        <label for="validationTooltip04" class="form-label">Department Name 3</label>
                                        <input type="text" class="form-control" name="department_name"  id="department_name"  required="">
                                    </div>
                                    <div class="mb-3 position-relative">
                                        <label for="validationTooltip04" class="form-label">Mailing List 3</label>
                                        <input type="email" class="form-control" name="mailing_list" id="mailing_list"  required="">
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                            <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button>
                            <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Finish Application</button>
                        </div>
                    </form> --}}

                    {{-- Start --}}
                    <!--
                        ----------------------------------------------------------------------------------------------------------------
                                                        SECTION One OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_1" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content" id="priv_show">
                                <div class="col-md-12" id="panel_for_stage_0">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Privacy Policy</h4>
                                    </div>
                                    <div class="panel-body panel-form">
                                        <p>
                                            The Old Mutual Group would like to offer you ongoing financial services and may use your
                                            personal
                                            information to provide you with information about products or services that may be suitable to
                                            meet your
                                            financial needs.
                                            If you consent to us contacting you for this purpose, please select ‘Yes below which indicates
                                            you agree to
                                            receive marketing communication from Old Mutual
                
                
                                        </p>
                
                                        <p>We may use your information or obtain information about you for the following purposes:</p>
                                        <ul>
                                            <li>Underwriting</li>
                                            <li>Assessment and processing of claims</li>
                                            <li>Credit searches and/or verification of personal information</li>
                                            <li>Claims checks</li>
                                            <li>Tracing beneficiaries</li>
                                            <li>Fraud prevention and detection</li>
                                            <li>Market research and statistical analysis</li>
                                            <li>Audit & record keeping purposes</li>
                                            <li>Compliance with legal & regulatory requirements</li>
                                            <li>Verifying your identity</li>
                                            <li>Sharing information with service providers we engage to process such information on our
                                                behalf or who render services to us. These service providers may be abroad, but we will not
                                                share your information with them unless we are satisfied that they have adequate security
                                                measures in place to protect your personal information.</li>
                
                                        </ul>
                
                                        <p>Sharing information with service providers we engage to process such information on our
                                            behalf or who render services to us. These service providers may be abroad, but we will not
                                            share your information with them unless we are satisfied that they have adequate security
                                            measures in place to protect your personal information.</p>
                                        <p>You also have the right to complain to the Data Protection Commission, whose contact details
                                            are:<br>
                                            https://dataprotection.org.gh/<br>
                                            Tel: +233 (0) 302 222 929</br>
                                            Fax: +233-(0)30 2222 927 +233-(0)506177979</br>
                                            Email: info@dataprotection.org.gh<br>
                                            East Legon, Paw Paw Street, GPS: GA-414-1469, P.O. Box CT7195, Accra<br><br>
                                            To view our full privacy notice and to exercise your preferences, please visit our website
                                            on<br>
                                            <a href="mailto:https://www.oldmutual.com.gh">https://www.oldmutual.com.gh</a>
                                        </p>
                
                
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="privacy" id="optionsRadios1" value="YES">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="privacy" id="optionsRadios2" value="NO">
                                                No
                                            </label>
                                        </div>
                                        <div class="modal-footer">
                                            <span class="showajaxfeed" id="showajax_feed_view_department"></span>
                                            <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Start Application</button>
                                        </div>
                
                                    </div>
                
                                </div>
                            </div>
                        </div>
                       
                    </form>
                    <!--
                        ----------------------------------------------------------------------------------------------------------------
                                                        SECTION Two OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_2" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content" id="panel_for_stage_1">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Policyholder Details</h4>
                                </div>
                                <div class="panel-body panel-form">
                                    <div class="form-group">
                                        <label class="form-label" for="title">Title :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="title" name="title" onchange="set_other_title_field(this)" class="form-select"
                                                required>
                                                <option value=""></option>
                                                <option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Dr">Dr</option>
                                                <option value="Professor">Professor</option>
                                                <option value="Reverend">Reverend</option>
                                                <!-- <option value="Other">Other</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_name" value="EDUCATOR" style="display: none;" />
                                    <div class="form-group" id="other_title_holder" style="display: none;">
                                        <label class="form-label" for="other_title">Title *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="other_title" name="other_title" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="surname">Surname *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="surname" name="surname" value="" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="firstname">First name *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="firstname" name="firstname" value=""
                                                required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="othernames">Othernames :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="othernames" name="othernames" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="gender">Gender * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="gender" name="gender" class="form-select" required>
                                                <option value=""></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="date_of_birth">Date of birth * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="date"
                                                min="{{ change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); }}"
                                                max="{{ change_date(date('Y-m-d H:i:s'), '-18 years', 'Y-m-d'); }}"
                                                id="date_of_birth" name="date_of_birth" value="" required />
                                        </div>
                                    </div>
                                    <div class="form-group" id="show_age" style="display: none;">
                                    <label class="form-label" for="date_of_birth">Age :</label>
                                    <div class="col-md-12 col-sm-12 mb-2">
                                        <input class="form-control" type="text" id="age" name="age" readonly />
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label class="form-label" for="country_of_birth">Country Of Birth *
                                            :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="country_of_birth" name="country_of_birth" class="form-select" required>
                                                <option value="Ghana">Ghana</option>
                                                <option value="Afghanistan">Afghanistan</option>
                                                <option value="Åland Islands">Åland Islands</option>
                                                <option value="Albania">Albania</option>
                                                <option value="Algeria">Algeria</option>
                                                <option value="American Samoa">American Samoa</option>
                                                <option value="Andorra">Andorra</option>
                                                <option value="Angola">Angola</option>
                                                <option value="Anguilla">Anguilla</option>
                                                <option value="Antarctica">Antarctica</option>
                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                <option value="Argentina">Argentina</option>
                                                <option value="Armenia">Armenia</option>
                                                <option value="Aruba">Aruba</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Austria">Austria</option>
                                                <option value="Azerbaijan">Azerbaijan</option>
                                                <option value="Bahamas">Bahamas</option>
                                                <option value="Bahrain">Bahrain</option>
                                                <option value="Bangladesh">Bangladesh</option>
                                                <option value="Barbados">Barbados</option>
                                                <option value="Belarus">Belarus</option>
                                                <option value="Belgium">Belgium</option>
                                                <option value="Belize">Belize</option>
                                                <option value="Benin">Benin</option>
                                                <option value="Bermuda">Bermuda</option>
                                                <option value="Bhutan">Bhutan</option>
                                                <option value="Bolivia">Bolivia</option>
                                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                <option value="Botswana">Botswana</option>
                                                <option value="Bouvet Island">Bouvet Island</option>
                                                <option value="Brazil">Brazil</option>
                                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                <option value="Bulgaria">Bulgaria</option>
                                                <option value="Burkina Faso">Burkina Faso</option>
                                                <option value="Burundi">Burundi</option>
                                                <option value="Cambodia">Cambodia</option>
                                                <option value="Cameroon">Cameroon</option>
                                                <option value="Canada">Canada</option>
                                                <option value="Cape Verde">Cape Verde</option>
                                                <option value="Cayman Islands">Cayman Islands</option>
                                                <option value="Central African Republic">Central African Republic</option>
                                                <option value="Chad">Chad</option>
                                                <option value="Chile">Chile</option>
                                                <option value="China">China</option>
                                                <option value="Christmas Island">Christmas Island</option>
                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                <option value="Colombia">Colombia</option>
                                                <option value="Comoros">Comoros</option>
                                                <option value="Congo">Congo</option>
                                                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of
                                                    The</option>
                                                <option value="Cook Islands">Cook Islands</option>
                                                <option value="Costa Rica">Costa Rica</option>
                                                <option value="Cote D'ivoire">Cote D'ivoire</option>
                                                <option value="Croatia">Croatia</option>
                                                <option value="Cuba">Cuba</option>
                                                <option value="Cyprus">Cyprus</option>
                                                <option value="Czech Republic">Czech Republic</option>
                                                <option value="Denmark">Denmark</option>
                                                <option value="Djibouti">Djibouti</option>
                                                <option value="Dominica">Dominica</option>
                                                <option value="Dominican Republic">Dominican Republic</option>
                                                <option value="Ecuador">Ecuador</option>
                                                <option value="Egypt">Egypt</option>
                                                <option value="El Salvador">El Salvador</option>
                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                <option value="Eritrea">Eritrea</option>
                                                <option value="Estonia">Estonia</option>
                                                <option value="Ethiopia">Ethiopia</option>
                                                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                <option value="Faroe Islands">Faroe Islands</option>
                                                <option value="Fiji">Fiji</option>
                                                <option value="Finland">Finland</option>
                                                <option value="France">France</option>
                                                <option value="French Guiana">French Guiana</option>
                                                <option value="French Polynesia">French Polynesia</option>
                                                <option value="French Southern Territories">French Southern Territories</option>
                                                <option value="Gabon">Gabon</option>
                                                <option value="Gambia">Gambia</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Germany">Germany</option>
                                                <option value="Gibraltar">Gibraltar</option>
                                                <option value="Greece">Greece</option>
                                                <option value="Greenland">Greenland</option>
                                                <option value="Grenada">Grenada</option>
                                                <option value="Guadeloupe">Guadeloupe</option>
                                                <option value="Guam">Guam</option>
                                                <option value="Guatemala">Guatemala</option>
                                                <option value="Guernsey">Guernsey</option>
                                                <option value="Guinea">Guinea</option>
                                                <option value="Guinea-bissau">Guinea-bissau</option>
                                                <option value="Guyana">Guyana</option>
                                                <option value="Haiti">Haiti</option>
                                                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
                                                </option>
                                                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                <option value="Honduras">Honduras</option>
                                                <option value="Hong Kong">Hong Kong</option>
                                                <option value="Hungary">Hungary</option>
                                                <option value="Iceland">Iceland</option>
                                                <option value="India">India</option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                                <option value="Iraq">Iraq</option>
                                                <option value="Ireland">Ireland</option>
                                                <option value="Isle of Man">Isle of Man</option>
                                                <option value="Israel">Israel</option>
                                                <option value="Italy">Italy</option>
                                                <option value="Jamaica">Jamaica</option>
                                                <option value="Japan">Japan</option>
                                                <option value="Jersey">Jersey</option>
                                                <option value="Jordan">Jordan</option>
                                                <option value="Kazakhstan">Kazakhstan</option>
                                                <option value="Kenya">Kenya</option>
                                                <option value="Kiribati">Kiribati</option>
                                                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's
                                                    Republic of</option>
                                                <option value="Korea, Republic of">Korea, Republic of</option>
                                                <option value="Kuwait">Kuwait</option>
                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic
                                                </option>
                                                <option value="Latvia">Latvia</option>
                                                <option value="Lebanon">Lebanon</option>
                                                <option value="Lesotho">Lesotho</option>
                                                <option value="Liberia">Liberia</option>
                                                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                                <option value="Liechtenstein">Liechtenstein</option>
                                                <option value="Lithuania">Lithuania</option>
                                                <option value="Luxembourg">Luxembourg</option>
                                                <option value="Macao">Macao</option>
                                                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former
                                                    Yugoslav Republic of</option>
                                                <option value="Madagascar">Madagascar</option>
                                                <option value="Malawi">Malawi</option>
                                                <option value="Malaysia">Malaysia</option>
                                                <option value="Maldives">Maldives</option>
                                                <option value="Mali">Mali</option>
                                                <option value="Malta">Malta</option>
                                                <option value="Marshall Islands">Marshall Islands</option>
                                                <option value="Martinique">Martinique</option>
                                                <option value="Mauritania">Mauritania</option>
                                                <option value="Mauritius">Mauritius</option>
                                                <option value="Mayotte">Mayotte</option>
                                                <option value="Mexico">Mexico</option>
                                                <option value="Micronesia, Federated States of">Micronesia, Federated States of
                                                </option>
                                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                                <option value="Monaco">Monaco</option>
                                                <option value="Mongolia">Mongolia</option>
                                                <option value="Montenegro">Montenegro</option>
                                                <option value="Montserrat">Montserrat</option>
                                                <option value="Morocco">Morocco</option>
                                                <option value="Mozambique">Mozambique</option>
                                                <option value="Myanmar">Myanmar</option>
                                                <option value="Namibia">Namibia</option>
                                                <option value="Nauru">Nauru</option>
                                                <option value="Nepal">Nepal</option>
                                                <option value="Netherlands">Netherlands</option>
                                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                <option value="New Caledonia">New Caledonia</option>
                                                <option value="New Zealand">New Zealand</option>
                                                <option value="Nicaragua">Nicaragua</option>
                                                <option value="Niger">Niger</option>
                                                <option value="Nigeria">Nigeria</option>
                                                <option value="Niue">Niue</option>
                                                <option value="Norfolk Island">Norfolk Island</option>
                                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                <option value="Norway">Norway</option>
                                                <option value="Oman">Oman</option>
                                                <option value="Pakistan">Pakistan</option>
                                                <option value="Palau">Palau</option>
                                                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
                                                </option>
                                                <option value="Panama">Panama</option>
                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                <option value="Paraguay">Paraguay</option>
                                                <option value="Peru">Peru</option>
                                                <option value="Philippines">Philippines</option>
                                                <option value="Pitcairn">Pitcairn</option>
                                                <option value="Poland">Poland</option>
                                                <option value="Portugal">Portugal</option>
                                                <option value="Puerto Rico">Puerto Rico</option>
                                                <option value="Qatar">Qatar</option>
                                                <option value="Reunion">Reunion</option>
                                                <option value="Romania">Romania</option>
                                                <option value="Russian Federation">Russian Federation</option>
                                                <option value="Rwanda">Rwanda</option>
                                                <option value="Saint Helena">Saint Helena</option>
                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                <option value="Saint Lucia">Saint Lucia</option>
                                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines
                                                </option>
                                                <option value="Samoa">Samoa</option>
                                                <option value="San Marino">San Marino</option>
                                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                <option value="Senegal">Senegal</option>
                                                <option value="Serbia">Serbia</option>
                                                <option value="Seychelles">Seychelles</option>
                                                <option value="Sierra Leone">Sierra Leone</option>
                                                <option value="Singapore">Singapore</option>
                                                <option value="Slovakia">Slovakia</option>
                                                <option value="Slovenia">Slovenia</option>
                                                <option value="Solomon Islands">Solomon Islands</option>
                                                <option value="Somalia">Somalia</option>
                                                <option value="South Africa">South Africa</option>
                                                <option value="South Georgia and The South Sandwich Islands">South Georgia and The
                                                    South Sandwich Islands</option>
                                                <option value="Spain">Spain</option>
                                                <option value="Sri Lanka">Sri Lanka</option>
                                                <option value="Sudan">Sudan</option>
                                                <option value="Suriname">Suriname</option>
                                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                <option value="Swaziland">Swaziland</option>
                                                <option value="Sweden">Sweden</option>
                                                <option value="Switzerland">Switzerland</option>
                                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                                <option value="Tajikistan">Tajikistan</option>
                                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                <option value="Thailand">Thailand</option>
                                                <option value="Timor-leste">Timor-leste</option>
                                                <option value="Togo">Togo</option>
                                                <option value="Tokelau">Tokelau</option>
                                                <option value="Tonga">Tonga</option>
                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                <option value="Tunisia">Tunisia</option>
                                                <option value="Turkey">Turkey</option>
                                                <option value="Turkmenistan">Turkmenistan</option>
                                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                <option value="Tuvalu">Tuvalu</option>
                                                <option value="Uganda">Uganda</option>
                                                <option value="Ukraine">Ukraine</option>
                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="United States">United States</option>
                                                <option value="United States Minor Outlying Islands">United States Minor Outlying
                                                    Islands</option>
                                                <option value="Uruguay">Uruguay</option>
                                                <option value="Uzbekistan">Uzbekistan</option>
                                                <option value="Vanuatu">Vanuatu</option>
                                                <option value="Venezuela">Venezuela</option>
                                                <option value="Viet Nam">Viet Nam</option>
                                                <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                <option value="Western Sahara">Western Sahara</option>
                                                <option value="Yemen">Yemen</option>
                                                <option value="Zambia">Zambia</option>
                                                <option value="Zimbabwe">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="nationality">Nationality (State All If More
                                            Than One) *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="nationality" name="nationality" value=""
                                                required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="marital_status">Marital Status * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="marital_status" name="marital_status" class="form-select" required>
                                                <option value=""></option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Divorced">Divorced</option>
                                                <option value="Separated">Separated</option>
                                                <option value="Widowed">Widowed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="id_type">ID Type * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="id_type" name="id_type" class="form-select" required
                                                onchange="setIDRestriction(this, 'id_number')">
                                                <option value=""></option>
                                                <option value="Ghana Card">Ghana Card</option>
                                                <option value="Drivers License">Driver's License</option>
                                                <option value="Passport">Passport</option>
                                                <option value="Voter ID">Voter ID</option>
                                                <option value="SSNIT">SSNIT</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="id_number">ID Number *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="id_number" name="id_number" value=""
                                                required />
                                        </div>
                                    </div>
                                    <!-- <div class="form-group" style="display: none;">
                                    <label class="form-label" for="is_politically_exposed">Are you politically exposed? * :</label>
                                    <div class="col-md-12 col-sm-12 mb-2">
                                        <select id="is_politically_exposed" name="is_politically_exposed" class="form-select" required>
                                            <option value="0" selected>No</option>
                                        </select>
                                    </div>
                                </div> -->
                                    <!-- <div class="form-group" style="display: none;">
                                    <label class="form-label" for="with_dependants">Do you have dependants? * :</label>
                                    <div class="col-md-12 col-sm-12 mb-2">
                                        <select id="with_dependants" name="with_dependants" class="form-select" required>
                                            <option value="1" selected>Yes</option>
                                        </select>
                                    </div>
                                </div> -->
                                    <!-- <div class="form-group" style="display: none;">
                                    <label class="form-label" for="is_a_smoker">Are you a smoker? * :</label>
                                    <div class="col-md-12 col-sm-12 mb-2">
                                        <select id="is_a_smoker" name="is_a_smoker" class="form-select" required>
                                            <option value="0" selected>No</option>
                                        </select>
                                    </div>
                                </div> -->
            
                                    <div class="form-group">
                                        <label class="form-label" for="id_type_in_resident_country">ID type in
                                            another country if not Ghana :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="id_type_in_resident_country" name="id_type_in_resident_country"
                                                class="form-select">
                                                <option value=""></option>
                                                <option value="Driver's License">Driver's License</option>
                                                <option value="Passport">Passport</option>
                                                <option value="Voter ID">Voter ID</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="id_number_in_resident_country">ID Number in
                                            another country if not Ghana :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="id_number_in_resident_country"
                                                name="id_number_in_resident_country" value="" />
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
                                                        SECTION Three OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_3" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content"  id="panel_for_stage_2">
                                <div class="panel-heading">
            
                                    <h4 class="panel-title">Address & Contact</h4>
                                </div>
                                <div class="panel-body panel-form">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="mobile">Mobile Number * :</label>
                                        <div class="col-md-6 mb-2">
                                            <input type="text" name="mobile" class="form-control phone-num" id="mobile" value=""
                                            pattern="[0]{1}[0-9]{9}" 
                                            title="Invalid phone number"
                                                required />
                                            <span class="phone_number_invalid error-message">Invalid phone number</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="email" id="email" name="email" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="address">Postal / Physical Address *
                                            :</label>
                                        <div class="col-md-8 col-sm-8 mb-2">
                                            <textarea id="address" name="address" class="form-control" rows="6" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="client_resides_in_ghana">Do you live in
                                            Ghana? * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="client_resides_in_ghana" onchange="toggle_region_in_ghana_field_display(this);"
                                                name="client_resides_in_ghana" class="form-select" required>
                                                <option value=""></option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="region_in_ghana_holder" style="display: none;">
                                        <label class="form-label" for="region_in_ghana">Region In Residence *
                                            :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="region_in_ghana" name="region_in_ghana" class="form-select" required>
                                                <option value=""></option>
                                                <option value="ASHANTI">ASHANTI</option>
                                                <option value="GREATER ACCRA">GREATER ACCRA</option>
                                                <option value="UPPER-EAST">UPPER-EAST</option>
                                                <option value="UPPER-WEST">UPPER-WEST</option>
                                                <option value="EASTERN">EASTERN</option>
                                                <option value="WESTERN">WESTERN</option>
                                                <option value="VOLTA">VOLTA</option>
                                                <option value="CENTRAL">CENTRAL</option>
                                                <option value="OTI">OTI</option>
                                                <option value="BONO EAST">BONO EAST</option>
                                                <option value="AHAFO">AHAFO</option>
                                                <option value="NORTH EAST">NORTH EAST</option>
                                                <option value="SAVANNAH">SAVANNAH</option>
                                                <option value="WESTERN NORTH">WESTERN NORTH</option>
                                                <option value="NORTHERN">NORTHERN</option>
                                            </select>
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
                                                        SECTION Four OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_4" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content" id="panel_for_stage_3">
            
                                <div class="panel-heading">
                                    <h4 class="panel-title">INCOME & TIN</h4>
                                </div>
                                <div class="panel-body panel-form">
                                    <div class="form-group">
                                        <label class="form-label" for="source_of_income">Source Of Income *
                                            :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select name="source_of_income" onchange="select_income_type(this);" class="form-select">
                                                <option value="Salary">Salary</option>
                                                <option value="Investment">Investment</option>
                                                <option value="Remittance">Remittance</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="other_income_sources_holder">
                                        <label class="form-label" for="other_income_sources"
                                            id="other_income_sources_label">Other Income Sources :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="other_income_sources"
                                                name="other_income_sources" value="" />
                                        </div>
                                    </div>
            
                                    <div class="form-group" id="monthly_income">
                                        <label class="form-label" for="monthly_income" id="monthly_income">Monthly
                                            Income (GHS) :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="number" min="1" id="monthly_income" name="monthly_income"
                                                value="" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="annual_premium">
                                        <label class="form-label" for="annual_premium" id="annual_premium">Annual
                                            Update of Premium :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select name="annual_premium" id="annual_premium" class="form-select">
                                                <option value="0">0%</option>
                                                <option value="10">10%</option>
                                                <option value="15">15%</option>
                                                <option value="20">20%</option>
                                                <option value="25">25%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="occupation">Occupation *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control flexdatalist" type="text" id="occupation" name="occupation"
                                                value="" required />
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="form-label" for="tin">TIN :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="tin" name="tin" value="" />
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
                                                        SECTION Five OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_5" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content" id="panel_for_stage_4">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Payment</h4>
                                </div>
                                <div class="panel-body panel-form">
                                    <div class="form-group">
                                        <label class="form-label" for="payment_term">Payment Term * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="payment_term" onchange="set_other_payment_term(this);" name="payment_term"
                                                class="form-select" required>
                                                <option value=""></option>
                                                <option value="10">10 years</option>
                                                <option value="15">15 years</option>
                                                <option value="18">18 years</option>
                                                <option value="20">20 years</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="other_payment_term_holder" style="display: none;">
                                        <label class="form-label" for="other_payment_term">State Payment Term
                                            *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="number" min="1" id="other_payment_term"
                                                name="other_payment_term" value="" required />
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="form-label" for="payment_method">Payment Method * :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="payment_method" onchange="set_payment_method_needed_info(this)"
                                                name="payment_method" class="form-select" required>
                                                <option value=""></option>
                                                <option value="Cash/Cheque">Cash/Cheque</option>
                                                <option value="CAG Deductions">CAG Deductions</option>
                                                <option value="Mobile Money">Mobile Money</option>
                                                <option value="Stop Order">Stop Order</option>
                                                <option value="Debit Order">Debit Order</option>
                                            </select>
                                        </div>
                                    </div>
            
            
                                    <span id="momo_payment" class="form-group" style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label" for="telco_name">Telco Operator *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <select class="form-select" type="text" id="telco_name" name="telco_name" value="">
                                                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                                                    <option value="Vodafone Cash">Vodafone Cash</option>
                                                    <option value="Airtel Tigo Money">Airtel Tigo Money</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="wallet_number">Phone Number *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control phone-num" type="text" id="wallet_number"
                                                pattern="[0]{1}[0-9]{9}" 
                                                title="Invalid phone number"
                                                    name="wallet_number" value="" />
                                                <span class="phone_number_invalid error-message">Invalid phone number</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="wallet_name">Name On Number *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="wallet_name" name="wallet_name" value="" />
                                            </div>
                                        </div>
                                    </span>
            
                                    <span id="bank_payment" class="form-group" style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label" for="payment_bank_name">Bank Name *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
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
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <select class="form-select" type="text" id="payment_bank_branch"
                                                    name="payment_bank_branch" value="">
            
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="payment_account_number">Account Number
                                                *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="payment_account_number"
                                                    name="payment_account_number" value="" />
                                            </div>
                                        </div>
            
                                        <div class="form-group" style=" margin-bottom: 10px;">
                                            <label class="form-label" for="payment_account_holder_name">Account
                                                Holder Name *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="payment_account_holder_name"
                                                    name="payment_account_holder_name" value="" />
                                            </div>
                                        </div>
                                    </span>
            
                                    <span id="cag_payment" class="form-group" style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label" for="employer">Employer *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="employer" name="employer" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group" style=" margin-bottom: 10px;">
                                            <label class="form-label" for="staff_id">Staff ID *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="staff_id" name="staff_id" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group" style=" margin-bottom: 10px;">
                                            <label class="form-label" for="staff_id">Office Building Location
                                                *:</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="office_building_location"
                                                    name="office_building_location" value="" />
                                            </div>
                                        </div>
                                    </span>
            
            
                                    <div class="form-group">
                                        <label class="form-label" for="payment_frequency">Payment Frequency *
                                            :</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <select id="payment_frequency" name="payment_frequency" class="form-select" required>
                                                <option value="MONTHLY">Monthly</option>
                                                <option value="QUATERLY">Quarterly</option>
                                                <option value="HALF YEARLY">Half-Yearly</option>
                                                <option value="YEARLY">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="contribution_amount">Premium
                                            (Gh¢)*:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="number" min="50" id="contribution_amount" name="premium"
                                                value="" required />
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="form-label" for="sum_assured">Sum Assured (Gh¢):</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="text" id="sum_assured" name="sum_assured" value=""
                                                readonly />
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="form-label" for="payment_commencement_month">Payment
                                            Commencement Month *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <input class="form-control" type="date" min="<?php echo date('Y-m-d') ?>"
                                                id="payment_commencement_month" name="payment_commencement_month" value="" required />
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
                                                        SECTION Six OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_6" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content" id="panel_for_stage_5">
            
                                <div class="panel-heading">
            
                                    <h4 class="panel-title">Medical History</h4>
                                </div>
            
                                <div class="panel-body panel-form">
            
                                    <span class="control-label col-md-12 col-sm-12" style="text-align: center;" for="health_issues2">
                                        Do you suffer from any physical impairment,
                                        current illness or taking any health drug, having medicine advice, treatment or investigations*
                                        :
                                    </span>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3 col-sm-3">
                                            <input class="form-group-input" onclick="toggle_illment_details_field_display(this)"
                                                type="radio" name="health_issues" id="health_issues1" value="Yes">
                                            <label class="form-group-label" for="health_issues1">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <input class="form-group-input" onclick="toggle_illment_details_field_display(this)"
                                                type="radio" name="health_issues" id="health_issues2" value="No">
                                            <label class="form-group-label" for="health_issues2">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="illment_description_holder" style="display: none;">
                                        <label class="form-label" for="illment_description">If yes, state the
                                            ailment and give details *:</label>
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <textarea id="illment_description" name="illment_description" class="form-control"
                                                rows="2"></textarea>
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
                                                        SECTION Seven OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_7" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content"  id="panel_for_stage_6">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Beneficiaries</h4>
                                </div>
            
                                <div class="panel-body panel-form">
                                    <div class="row" id="beneficiaries_holder">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <label class="form-label"><h6><i class="ri-focus-fill align-middle me-1"></i> First Beneficiary</h6></label>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_full_name">Full Name *:</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <input class="form-control" type="text" id="beneficiary_one_full_name"
                                                                name="beneficiary_one_full_name" value="" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="beneficiary_one_dob">Date
                                                            Of Birth *:</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <input class="form-control"
                                                                min="{{ change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d');}}"
                                                                max="{{ date('Y-m-d'); }}" type="date" id="beneficiary_one_dob"
                                                                name="beneficiary_dob[]" value="" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_relationship">Relationship *:</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <select name="beneficiary_one_relationship"
                                                                onchange="set_real_rela_input(this)" data-bentype="one"
                                                                id="beneficiary_one_relationship" required class="form-select">
                                                                <option value=""></option>
                                                                <option value="SON">SON</option>
                                                                <option value="WIFE">WIFE</option>
                                                                <option value="HUSBAND">HUSBAND</option>
                                                                <option value="MOTHER">MOTHER</option>
                                                                <option value="FATHER">FATHER</option>
                                                                <option value="DAUGHTER">DAUGHTER</option>
                                                                <option value="BROTHER">BROTHER</option>
                                                                <option value="SISTER">SISTER</option>
                                                                <option value="FATHER-IN-LAW">FATHER-IN-LAW</option>
                                                                <option value="MOTHER-IN-LAW">MOTHER-IN-LAW</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_gendee">Gender *:</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <select name="beneficiary_one_gender" data-bentype="one"
                                                                id="beneficiary_one_gender" required class="form-select">
                                                                <option value=""></option>
                                                                <option value="M">MALE</option>
                                                                <option value="F">FEMALE</option>
            
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12" id="beneficiary_one_real_relationship_holder"
                                                    style="display: none;">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_real_relationship">State Relationship *:</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <input class="form-control" type="text"
                                                                id="beneficiary_one_real_relationship"
                                                                name="beneficiary_one_real_relationship" value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_percentage">Percentage(%) *:</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <input class="form-control beneficiary-percentage" type="number" min="0"
                                                                id="beneficiary_one_percentage" name="beneficiary_percentage[]"
                                                                value="" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="beneficiary_one_id_type">ID
                                                            Type :</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <select id="beneficiary_one_id_type" name="beneficiary_one_id_type"
                                                                class="form-select">
                                                                <option value=""></option>
                                                                <option value="Driver's License">Driver's License</option>
                                                                <option value="Passport">Passport</option>
                                                                <option value="National ID">National ID</option>
                                                                <option value="Voter ID">Voter ID</option>
                                                                <option value="SSNIT">SSNIT</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_id_number">ID Number :</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <input class="form-control" type="text" id="beneficiary_one_id_number"
                                                                name="beneficiary_one_id_number" value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="beneficiary_one_id_number">Phone Number :</label>
                                                        <div class="col-md-12 col-sm-12 mb-2">
                                                            <input class="form-control" type="text" id="beneficiary_one_phone_no"
                                                                name="beneficiary_one_phone_no" value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="modal-footer">
                                        <span class="showajaxfeed" id="showajax_feed_view_department"></span>

                                        <button type="button" class="oml-btn btn-light waves-effect prev" >Back</button> 
                                        <span onclick="add_more_beneficiaries()" class="btn btn-info">Add More Beneficiaries</span>

                                        <span style="display: none;" onclick="remove_beneficiary()" style="margin: 5px;"
                                            id="remove_beneficiary_btn" class="btn btn-danger">Remove Beneficiary</span>
                                        <button type="submit" id="btn_view_department" class="oml-btn oml-btn-success">Save & Proceed</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--
                        ----------------------------------------------------------------------------------------------------------------
                                                        SECTION Eight OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_8" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content" id="panel_for_stage_9">
            
                                <div class="panel-heading">
            
                                    <h4 class="panel-title">Trustee</h4>
                                </div>
            
                                <div class="panel-body panel-form">
                                    <b>A minor has been selected as a beneficiary. Enter details of trustee for minor</b>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="trustee_full_name">Full Name *
                                                :</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="trustee_full_name" name="trustee_full_name"
                                                    value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="trustee_dob">Date Of Birth * :</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="date"
                                                    min="{{ change_date(date('Y-m-d H:i:s'), '-150 years', 'Y-m-d'); }}"
                                                    max="{{ change_date(date('Y-m-d H:i:s'), '-18 years', 'Y-m-d'); }}"
                                                    id="trustee_dob" name="trustee_dob" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-label" for="trustee_gender">Gender * :</label>
                                    <div class="col-md-12 col-sm-12 mb-2">
                                        <select id="trustee_gender" name="trustee_gender" class="form-select">
                                            <option value=""></option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="trustee_relationship">Relationship *
                                                :</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
            
                                                <input class="form-control" type="text" id="trustee_relationship"
                                                    name="trustee_relationship" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="trustee_id_type">ID Type :</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <select id="trustee_id_type" name="trustee_id_type" class="form-select">
                                                    <option value=""></option>
                                                    <option value="Driver's License">Driver's License</option>
                                                    <option value="Passport">Passport</option>
                                                    <option value="National ID">National ID</option>
                                                    <option value="Voter ID">Voter ID</option>
                                                    <option value="SSNIT">SSNIT</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="trustee_id_number">ID Number :</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="trustee_id_number" name="trustee_id_number"
                                                    value="" />
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="trustee_mobile_number">Mobile Number
                                                :</label>
                                            <div class="col-md-12 col-sm-12 mb-2">
                                                <input class="form-control" type="text" id="trustee_mobile_number"
                                                    name="trustee_mobile_number" value="" />
                                            </div>
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
                                                        SECTION Nine OF FORM
                        ----------------------------------------------------------------------------------------------------------------
                    -->
                    <form id="form_step_9" class="step" action="{{route('corporate-request-infill')}}" method="POST" >
                        @csrf
                        <div class="d-flex justify-content-center">
                            <div class="col-md-8 form-content"  id="panel_for_stage_8">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Declaration</h4>
                                </div>
            
                                <div class="panel-body panel-form">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
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
                                        </div>
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
                                                    <h2 class="panel-title">Terms & Conditions</h2>
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
                                                            <input class="form-check-input" id="agree-term" type="checkbox" onclick="enable_submit_btn(this)" id="formCheck2" >
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
                                                    I, the undersigned, hereby declare that the information provided by me and required
                                                    of me on this application is both correct and accurate,
                                                    that the option as selected herein is clear and that I understand the terms and
                                                    conditions binding this policy. I absolutely authorize
                                                    Old Mutual to acquire any information it deems essential from any person, doctor or
                                                    hospital that has medical information needed in connection
                                                    with this application, including full details of my past medical history. I am
                                                    fully aware that the benefit under this policy may be cancelled
                                                    and forfeited at the company's discretion in the event that any information
                                                    provided or declaration made by me is/are inaccurate.
                                                </label>
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
                                                        accept="image/png" value="" />
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
                                                <div class="col-md-offset-5 col-md-6">
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
                    
                    <div style="display: none">
                        <div>
                            <h5>All records successfully </h5>
                        </div>
                        <div class="button-items mt-3">
                            <button type="button" class="btn btn-success btn-sm waves-effect waves-light">VIEW DIGITAL PROPOSAL FORM</button>
                            <button type="button" class="btn btn-warning btn-sm waves-effect waves-light">VIEW MANDATE FORM</button>
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light">GO BACK TO APPLICATION</button>
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