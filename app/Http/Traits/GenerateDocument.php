<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Models\DocumentProduct;
use App\Models\Occupation;
use App\Models\Employer;
use App\Models\Agent;
use App\Models\Beneficiary;
use App\Models\CorporateBeneficiary;
use App\Models\Cover;
use App\Models\Bank;
use App\Models\BankBranch;
use App\Models\MandateRequest;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\DocumentSysEngine;
use Carbon\Carbon;
use App\Http\Traits\AmountToWords;
use Illuminate\Support\Facades\Http;

trait GenerateDocument {
    use DocumentSysEngine;
    use AmountToWords;

    public function generateProposal($token, $serverside = false)
    {
        $document_application = DocumentApplication::with('tbl_users')->with('tbl_branch')->where('token', $token)->first();
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        $company_profile = CompanyProfile::where('deleted', 0)->first();
        // return $comp_profile ;

        // Map placeholders to template data attributes //country_of_residence
       // <img id="logo-field" class="logo-field" style="max-height: 120px; width: 200px" src="'.storage_path('app/public/company_profile/'.$company_profile->company_logo_path).'" alt="">
        //http://127.0.0.1:8000/storage/company_profile/DhYlsP79yo.png
        //public_path('/assets/images/logo.png')

       

        // // // //Image Source
        ///////web
        // Fetch the remote image
        $diabled_option = false;
        if($diabled_option){
            $imageUrl = 'https://dms.oldmutual.com.gh/assets/images/logo.png';
            $imageContent_web = Http::withoutVerifying()->get($imageUrl)->body();
            $logo_rmt = '<img src="data:image/png;base64,'.base64_encode($imageContent_web).'"/>';
        }
        ///////aws
        // Fetch the image content from S3
        if($diabled_option){
            $imageContent_s3 = Storage::disk('s3')->get('documents/'.$company_profile->company_logo_path);
            $logo_s3 = '<img src="data:image/png;base64,'.base64_encode($imageContent_s3).'"/>';
        }
        //fetch dynamic logo
        // $fileContent = Storage::disk('public')->get('company_profile/'.$company_profile->company_logo_path);
        $fileContent = Storage::disk('public')->get('company_profile/DhYlsP79yo.png');
        //public_path('assets/images/logo.png')
        $logo = '<img src="data:image/png;base64,'.base64_encode($fileContent).'" alt="" width="200"/>';
        // return $templateData;
        $dataMap = [
            '[LOGO]' => $logo,
            '[POLICY_NO]' => $document_application->policy_no,
            '[BRANCH]' => $templateData->branch_name,
            '[DATE]' => date('d-M-Y H:i:s', strtotime($document_application->createdon)),
            '[TITLE]' => $templateData->title,
            '[SURNAME]' => $templateData->surname,
            '[FIRSTNAME]' => $templateData->firstname,
            '[OTHER_NAMES]' => $templateData->othernames,
            '[GENDER]' => $templateData->gender,
            '[TIN]' => $templateData->tin,
            '[DOB]' => $templateData->date_of_birth,
            '[MOBILE_NO]' => $templateData->mobile,
            '[MARITAL_STATUS]' => $templateData->marital_status,
            '[NATIONALITY]' => $templateData->nationality ?? $templateData->country_of_birth,
            '[EMAIL]' => $templateData->email,
            '[SUM_ASSURED]' => $templateData->sum_assured,
            '[ID_TYPE_NON_RESIDENCE]' => $templateData->id_type_in_resident_country,
            '[ID_NUMBER_NON_RESIDENCE]' => $templateData->id_number_in_resident_country,
            '[PAYMENT_TERM]' => $templateData->payment_term,
            '[PAYMENT_METHOD]' => $templateData->payment_method,
            '[PAYMENT_FREQUENCY]' => $templateData->payment_frequency,
            // '[PREMIUM]' => $templateData->premium,
            '[PAYMENT_MONTH]' => $templateData->payment_month,
            '[MEDICAL_DESCRIPTION]' => $templateData->medical_description ?? $templateData->illment_description,
            '[YESNO]' => $templateData->yes_no ?? $templateData->health_issues,
            '[COUNTRY_OF_BIRTH]' => $templateData->country_of_birth,
            '[POSTAL_ADDRESS]' => $templateData->postal_address,
            '[INCOME]' => $templateData->income,
            '[PRIVACY]' => $templateData->privacy,
            '[ANNUAL_PREMIUM]' => $templateData->annual_premium,
            '[ID_TYPE]' => $templateData->id_type,	
            '[ID_NUMBER]' => $templateData->id_number,
        ];

        if ($templateData->signopt == 1) { //signed
            $imageContent_s3 = Storage::disk('s3')->get('signatures/'.$templateData['signature_file']);
        } else {//uploaded
            $imageContent_s3 = Storage::disk('s3')->get('signatures/'.$templateData['uploaded_signature']);
        }

        $signature = '<img src="data:image/png;base64,'.base64_encode($imageContent_s3).'" width="100"/>';

        
        $dataMap['[SIGNATURE]'] = $signature;
        if($templateData->client_resides_in_ghana){
            $dataMap['[REGION_GHANA]'] = $templateData->region_in_ghana;
        }else{
            $dataMap['[REGION_GHANA]'] = '';
        }
       
        if(is_numeric($templateData->occupation)){
            $dataMap['[OCCUPATION]'] = Occupation::where('slams_id', $templateData->occupation)->value('occupation_name');
        } else {
            $dataMap['[OCCUPATION]'] = $templateData->occupation;
        }

        if(is_numeric($templateData->employer)){
            $employer_name = Employer::where('emp_code', $templateData->employer)->value('name');
            $dataMap['[EMPLOYER]'] = $employer_name;
            $dataMap['[EMPLOEYER]'] = $employer_name;
        } else {
            $dataMap['[EMPLOYER]'] = $templateData->employer;
        }
        //
        if($templateData->payer_relationship_to_policy_holder == "Self"){
            $dataMap['[PAYER_NAME]'] = "Self";
            $dataMap['[PAYER_RELATIONSHIP]'] = "";
            $dataMap['[PAYER_ID_TYPE]'] = "";
            $dataMap['[PAYER_ID_NUMBER]'] = "";
        }else{
            if($templateData->payer_name){
                $dataMap['[PAYER_NAME]'] = $templateData->payer_name;
                $dataMap['[PAYER_RELATIONSHIP]'] = $templateData->payer_relationship_to_policy_holder;
                $dataMap['[PAYER_ID_TYPE]'] = $templateData->payer_id_type;
                $dataMap['[PAYER_ID_NUMBER]'] = $templateData->id_number;
            }
        }
       
        //
        if ($templateData->agent_code !== '' && is_numeric($templateData->agent_code) ) {
            $agent = Agent::where('agent_code', $templateData->agent_code)->first();
            if ($agent) {
                $dataMap['[AGENT_DETAILS]'] = '<tr>
                    <td style="width: 156px;">&nbsp;</td>
                    <td style="width: 248px;">&nbsp;</td>
                    <td style="text-align: right;"><span style="font-family:Georgia,serif;">Agent Information :&nbsp;</span></td>
                    <td><span style="font-family:Georgia,serif;"><strong>' . $agent->agent_name . '</strong></span></td>
                </tr>';
            }

            $dataMap['[AGENT_NAME]'] =  $agent->agent_name;
            $dataMap['[AGENT_NUMBER]'] = $agent->agent_code;
            $dataMap['[AGENT_NAMR]'] = $agent->agent_name;
        }
        if($templateData->payer_relationship_to_policy_holder == "Self"){
            $dataMap['[PAYER]'] = '
            <tr>
                <td>Self</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
        }else{
            if($templateData->payer_name){
                $dataMap['[PAYER]'] = '
                <tr>
                    <td>' . $templateData->payer_name . '</td>
                    <td>' . $templateData->payer_relationship_to_policy_holder . '</td>
                    <td>' . $templateData->payer_id_type . '</td>
                    <td>' . $templateData->payer_id_number . '</td>
                    <td></td>
                </tr>';
            }
        }
      
        //
        $dataMap['[PREMIUM]'] =  $templateData['premium'];
        $premium = ($templateData['premium'] > 0) ? $templateData['premium'] : 0;
    
        $dataMap['[PREMIUM_IN_WORDS]'] = $premium ? $this->amountToWords($premium) : 0; //$premium ? $this->amountToWords($premium) : 0;
     

        //
        $beneficiary_html= '';
        $trustee_html = '';
        $beneficiaries = Beneficiary::where('tbl_document_applications_id', $document_application->id)->get();

        if ($beneficiaries) {
            // <td>' . $beneficiary->gender . '</td>
          foreach ($beneficiaries as $key => $beneficiary) {
            $beneficiary_html .= '<tr>
                <td>' . $beneficiary->full_name . '</td>
                <td>' . $beneficiary->dob . '</td>
                <td>' . $beneficiary->relationship . '</td>
                <td>' . $beneficiary->phone_no . '</td>
                <td>' . $beneficiary->percentage . '</td>
            </tr>';
          }
        }
        $dataMap['[BENEFICIARY]'] = $beneficiary_html;

        //
        if ($templateData->trustee_full_name) {
            $dataMap['[TRUSTEE]'] = '
            <tr colspan="4">
                <td><strong>TRUSTEE</strong></td>
            </tr>
            <tr>
                <td>' . $templateData->trustee_full_name . '</td>
                <td>' . $templateData->trustee_dob . '</td>
                <td>' . $templateData->trustee_relationship . '</td>
                <td>' . $templateData->trustee_mobile_number . '</td>
                <td></td>
            </tr>';

            $trustee_html = '
            <tr colspan="4">
                <td><strong>TRUSTEE</strong></td>
            </tr>
            <tr>
                <td>' . $templateData->trustee_full_name . '</td>
                <td>' . $templateData->trustee_dob . '</td>
                <td>' . $templateData->trustee_relationship . '</td>
                <td>' . $templateData->trustee_mobile_number . '</td>
                <td></td>
            </tr>';

            $dataMap['[BENEFICIARY]'] .= $trustee_html;
        }

        //
        $cover_html ='';
        $covers = Cover::where('tbl_document_applications_id', $document_application->id)->get();
        $a_cover = Cover::where('tbl_document_applications_id', $document_application->id)->first();
        if ($covers->isNotEmpty()) {
            $cover_html  = '
            <tr colspan="4">
                <td><strong>COVER</strong></td>
            </tr>';

            //self 
            if(!is_null($a_cover)){
                $cover_html .=  '<tr>
                    <td>' . $templateData->firstname . ' ' . $templateData->othernames . ' ' .$templateData->surname .'</td>
                    <td>' . $templateData->date_of_birth . '</td>
                    <td>' . $templateData->mobile . '</td>
                    <td> Self </td>
                    <td>' . $a_cover->sum_assured . '</td>
                    <td>' .  $a_cover->cover_one_premium . '</td>
                </tr>';
            }

            foreach ($covers as $key => $cover) {
               if($cover->cover_surname_name != null){
                $cover_html .=  '<tr>
                    <td>' . $cover->cover_surname_name . ' ' . $cover->cover_first_name . '</td>
                    <td>' . $cover->cover_dob . '</td>
                    <td>' . $cover->cover_telephone_number . '</td>
                    <td>' . $cover->cover_relationship . '</td>
                    <td>' . $cover->cover_sum_assured . '</td>
                    <td>' . $cover->cover_premium . '</td>
                </tr>';
               }
            }
        
        }
        $dataMap['[COVERS]'] = $cover_html;

        //
        $payment_html = '';
        if (strtoupper($templateData->payment_method) == 'CAG DEDUCTIONS' || strtoupper($templateData->payment_method) == 'STOP ORDER') {
            $payment_html .= '<p><span style="font-family:Georgia,serif;"><strong>STOP ORDER</strong></span></p><hr />' .
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 775px;">
                <tbody>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Staff/Payroll/Regimental/Employee Number&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->staff_id . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Name of Employer</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->employer . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Office Building/Location</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->office_building_location . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        } elseif (strtoupper($templateData->payment_method) == 'MOBILE MONEY' ||  strtoupper($templateData->payment_option) == 'MOBILE MONEY') {
            $payment_html .= '
            
            <p><span style="font-family:Georgia,serif;"><strong>MOBILE MONEY</strong></span></p><hr />' .
        
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 775px;">
                <tbody>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Telco Name&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->telco_name . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Wallet Number</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->wallet_number . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        } elseif (strtoupper($templateData->payment_method) == 'DEBIT ORDER' || strtoupper($templateData->payment_option) == 'BANK' || strtoupper($templateData->payment_method) == 'BANK PAYMENT' || strtoupper($templateData->payment_option) == 'BANK PAYMENT' || $templateData->payment_method == 'BANK') {
            $branch_id_name = explode("_", $templateData['payment_bank_branch']);
            $branch_name =  $branch_id_name[1];

            $bank = Bank::find($templateData['payment_bank_name']);
            $bank_name = $bank->bank_name;

            $payment_html .= '<p><span style="font-family:Georgia,serif;"><strong>&nbsp;DEBIT ORDER</strong></span></p>
            <hr />' .
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 773px;">
                <tbody>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Holder Name</span></td>
                        <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->payment_account_holder_name . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Bank Name</span></td>
                        <td colspan="2" rowspan="1" style="width: 162px;"><span style="font-family:Georgia,serif;"><strong>' . $bank_name . '</strong></span></td>
                        <td rowspan="1" style="width: 159px; text-align: right;"><span style="font-family:Georgia,serif;">Bank Branch&nbsp; <strong>&nbsp; &nbsp;</strong></span></td>
                        <td rowspan="1" style="width: 294px;"><span style="font-family:Georgia,serif;"><strong>&nbsp;' . $branch_name	 . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Number</span></td>
                        <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->payment_account_number . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        } elseif (strtoupper($templateData->payment_method) == 'STOP ORDER') {
            $payment_html .= '<p><span style="font-family:Georgia,serif;"><strong>&nbsp;STOP ORDER</strong></span></p>' .
            '<hr />' .
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 773px;">
                <tbody>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Employer</span></td>
                        <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->employer . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Staff ID</span></td>
                        <td colspan="2" rowspan="1" style="width: 162px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->staff_id . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        }else if (strtoupper($templateData->payment_option) == 'CASH/CHEQUE' || strtoupper($templateData->payment_option) == 'CASH/CHEQUE') {
            $payment_html .= '<tr>
                <td style="width: 158px;"><span style=" font-size: 14px;">Payment Method: <strong>Cash/Checque&nbsp;</strong></span></td>
                <td style="width: 265px;">&nbsp;</td>
                <td style="width: 158px;">&nbsp;</td>
                <td style="width: 265px;">&nbsp;</td>
            </tr>';
        }
        
        $dataMap['[PAYMENT_DETAILS]'] = $payment_html;
        //Mandate Form
        $dataMap['[MANDATE_DETAILS]']  = $payment_html;

        //Sip
        $dataMap['[COUNTRY_OF_RESIDENCE]'] = $templateData->nationality;
        //fido sip
        $dataMap['[PAYMENT_MONTH]'] = $templateData->payment_commencement_month;
        if ($document_application->tbl_documents_products_id  == 15) {
            $dataMap['[PREMIUM_ESCALATION]']  = $templateData->annual_premium;

            $age = $this->getAgebyDOB($templateData->date_of_birth);
            $maturity_age = $age + $templateData->payment_term;

            $dataMap['[MATURITY_AGE]']  = $maturity_age;

            // $dataMap['[MATURITY_AGE]']  =  $templateData->payment_term;
        }
       

       

        //SIP
        if ($document_application->tbl_documents_products_id  == 8) {
            // $dataMap['[PAYMENT_DETAILS]'] = $html;
            $dataMap['[COUNTRY]'] = $templateData->nationality;
            // $dataMap['[PAYMENT_TERM]']  = $templateData->payment_term_;

            $dataMap['[PAYMENT_TERM]'] = $templateData->payment_term;

            $age = $this->getAgebyDOB($templateData->date_of_birth);
            $maturity_age = $age + $templateData->payment_term;

            $dataMap['[MATURITY_AGE]']  = $maturity_age;
            $dataMap['[PREMIUM_ESCALATION]']  = $templateData->annual_premium;
        }
        
        //FSIP
        if ($document_application->tbl_documents_products_id  == 13) {
            $age =  $this->getAgebyDOB($templateData->date_of_birth);
            $term = $age + $templateData->payment_term;
            // ['[PAYMENT_DETAILS]']  = $html;
            $dataMap['[COUNTRY]']  = $templateData->nationality;
            $dataMap['[PAYMENT_TERM]']  = $term;
        }

        //Mandate Form AGENT_NAMR
        if ($document_application->tbl_documents_products_id == 5) {
            $agent = Agent::where('agent_code', $templateData->agent_code)->first();
           
            $dataMap['[FA_NAME]'] = $agent->agent_name ?? '';
            $dataMap['[FA_CODE]'] = $agent->agent_code ?? '';
        
            $dataMap['[POLICY_HOLDER_NAME]'] = $templateData->policy_holder_name;
            $dataMap['[ACCOUNT_HOLDER_NAME]'] = $templateData->account_holder_name;
            $dataMap['[BANK_NAME]'] = $templateData->payment_bank_name;
            $dataMap['[BANK_BRANCH]'] = $templateData->payment_bank_branch;
            $dataMap['[ACCOUNT_NUMBER]'] = $templateData->payment_account_number;
            $dataMap['[ACCOUNT_TYPE]'] = $templateData->account_type;
            $dataMap['[EMPLOYER_NAME]'] = $templateData->name_of_employer;
            $dataMap['[OFFICE_BUILDING]'] = $templateData->office_building_locatio;
            $dataMap['[TELCO_NAME]'] = $templateData->telco_name;
            $dataMap['[WALLET_NUMBER]'] = $templateData->wallet_number;

            // $dataMap['[PREMIUM]'] =  $templateData->premium;
            // $premium =  is_numeric($templateData->premium)?$templateData->premium:0;

            $dataMap['[PREMIUM]'] =  $templateData['premium'];
            $premium = ($templateData['premium'] > 0) ? $templateData['premium'] : 0;
        
            $dataMap['[PREMIUM_IN_WORDS]'] = $premium ? $this->amountToWords($premium) : 0; //$premium ? $this->amountToWords($premium) : 0;
     
            
            // $dataMap['[PREMIUM_IN_WORDS]'] =  $premium ? $this->amountToWords($premium) : '';

            $dataMap['[MANDATE_DETAILS]']  = $payment_html;
        }
        //PERSONAL ACCIDENT
        if ($document_application->tbl_documents_products_id == 6) {
            $agent = Agent::where('agent_code', $templateData->agent_code)->first();
           
            $dataMap['[AGENT_NAME]'] = $agent->agent_name ?? '';
            $dataMap['[AGENT_NO]'] = $agent->agent_code ?? '';

            $dataMap['[PHYSICAL_DEFECT]'] =  $templateData->in_good_health;
            $dataMap['[PERSONAL_INSURANCE]'] = $templateData->already_have_a_personal_accident_insurance;
            $dataMap['[DETAILS]'] = $templateData->already_have_a_personal_accident_insurance_details;
            $dataMap['[LIFE_INSURANCE]'] = $templateData->already_have_a_life_insurance_with_us;
            $dataMap['[PAST_ILLNESS]'] = $templateData->past_illness;
            $dataMap['[INTENTION]'] = $templateData->accident_prone_activities;
            $dataMap['[SUM_INSURED]'] = $templateData->sum_assured_at_death ?? $templateData->sum_assured;
            $dataMap['[CLASS]'] = $templateData->class;
            $dataMap['[INSURE_FROM]'] = $templateData->insurance_for_twelve_months;
            $dataMap['[ADD]'] = $templateData->add;
            $dataMap['[DEDUCT]'] = $templateData->deduct;
            $dataMap['[NET_PREMIUM]'] = $templateData->net_premium;
            $dataMap['[PREMIUM_MED_EXP]'] = $templateData->premium_medical_exps;
            $dataMap['[SPORTS]'] = $templateData->sport;
            $dataMap['[TOTAL_OBSTEINER]'] = $templateData->total_abstainer;
            $bene_trustee = $beneficiary_html;

            // $trustee_html = '
            // <tr colspan="4">
            //     <td><strong>TRUSTEE</strong></td>
            // </tr>
            // <tr>
            //     <td>' . $templateData->trustee_full_name . '</td>
            //     <td>' . $templateData->trustee_dob . '</td>
            //     <td>' . $templateData->trustee_relationship . '</td>
            //     <td>' . $templateData->trustee_mobile_number . '</td>
            //     <td></td>
            // </tr>';

            $bene_trustee .= $trustee_html;
            $dataMap['[PA_BENEFICARIES]'] = $bene_trustee;
        }
        //REFUND REQUEST
        if ($document_application->tbl_documents_products_id == 7) {
            $payment_html_claimant ='';
            if (strtoupper($templateData->payment_method) == 'MOBILE MONEY' || strtoupper($templateData->payment_option) == 'MOBILE MONEY') {
            $payment_html_claimant .= '<tr>
            <td style="width: 158px;"><strong><u>MOBILE MONEY</u>&nbsp;</strong></td>
            <td style="width: 265px;">&nbsp;</td>
            <td style="width: 158px;">&nbsp;</td>
            <td style="width: 265px;">&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 158px;">Name of Telco&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->telco_name. '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Wallet Number&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->wallet_number . '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Account Holder&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->wallet_name . '</span></strong></td>
            </tr>';
            }
            if (strtoupper($templateData->payment_method) == 'BANK PAYMENT' || strtoupper($templateData->payment_option) == 'BANK PAYMENT') {
            $branch_id_name = explode("_", $templateData['payment_bank_branch']);
            $branch_name =  $branch_id_name[1];

            $bank = Bank::find($templateData['payment_bank_name']);
            $bank_name = $bank->bank_name;

            $payment_html_claimant .= '<tr>
            <td style="width: 158px;"><strong><u>BANK DETAILS</u>&nbsp;</strong></td>
            <td style="width: 265px;">&nbsp;</td>
            <td style="width: 158px;">&nbsp;</td>
            <td style="width: 265px;">&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 158px;">Bank Name&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $bank_name . '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Bank Branch&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $branch_name . '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Account Holder Name&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->account_holder_name . '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Account Number&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->account_number . '</span></strong></td>
            </tr>';
            }
        
        
            //Source of payment
            $source_payment_html = '';
            if (strtoupper($templateData->source_of_payment_option) == 'MOBILE MONEY') {
            $source_payment_html .= '<tr>
            <td style="width: 158px;"><strong><u>MOBILE MONEY</u>&nbsp;</strong></td>
            <td style="width: 265px;">&nbsp;</td>
            <td style="width: 158px;">&nbsp;</td>
            <td style="width: 265px;">&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 158px;">Name of Telco&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->source_of_telco_name . '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Wallet Number&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->source_of_wallet_number . '</span></strong></td>
            </tr>
            <tr>
            <td style="width: 158px;">Account Holder&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->source_of_wallet_name . '</span></strong></td>
            </tr>';
            } elseif (strtoupper($templateData->source_of_payment_option) == 'BANK') {
            $branch_id_name = explode("_", $templateData['payment_bank_branch']);
            $branch_name =  $branch_id_name[1];

            $bank = Bank::find($templateData['payment_bank_name']);
            $bank_name = $bank->bank_name;

            $source_payment_html .= '<tr>
            <td style="width: 158px;"><strong><u>BANK DETAILS</u>&nbsp;</strong></td>
            <td style="width: 265px;">&nbsp;</td>
            <td style="width: 158px;">&nbsp;</td>
            <td style="width: 265px;">&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 158px;">Bank Name&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $bank_name . '</span></strong></td>
            </tr>
            
            <tr>
            <td style="width: 158px;">Account Number&nbsp;</td>
            <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->source_of_account_number . '</span></strong></td>
            </tr>';
            }
        
        
            //DumpScreen($payment_html, true);
        
            $dataMap['[PAYMENT_DETAILS]'] =  $payment_html_claimant;
            $dataMap['[NAME]'] = $templateData->my_name;
            $dataMap['[PROPOSAL_NO]'] = $templateData->policy_number;
            $dataMap['[ADDRESS]'] = $templateData->address;
            $dataMap['[TELEPHONE_NO]'] = $templateData->mobile;
            $dataMap['[EMAIL]'] = $templateData->email;
            $dataMap['[TIN]'] = $templateData->tin;
            $dataMap['[BANK_NAME]'] = $templateData->bank_name;
            $dataMap['[ACCOUNT_NUMBER]'] = $templateData->account_number;
            $dataMap['[WORKSITE]'] = $templateData->worksite;
            $dataMap['[STAFF_NO]'] = $templateData->staff_number ?? $templateData->empoyment_staff_id;
            $dataMap['[ID_TYPE]'] = $templateData->id_type;
            $dataMap['[ID_NUMBER]'] = $templateData->id_number;
            $dataMap['[BANK_BRANCH]'] = $templateData->bank_branch;
            if ($templateData->refund_type == 'Other') {
            $dataMap['[REFUND_TYPE]'] = $templateData->other_reason_for_refund;
            } else {
                $dataMap['[REFUND_TYPE]'] = $templateData->refund_type;
            }
        
            $dataMap['[MOMO_NAME]'] = $templateData->momo_name;
            $dataMap['[TELCO]'] = $templateData->telco;
            $dataMap['[WALLET_NUMBER]'] = $templateData->wallet_number;
            $dataMap['[SOURCE_OF_PAYMENT]'] = $source_payment_html;
        }

        /*CLAIM INSURANCE*/
        $payment_html_dclaim = '';
        if ($document_application->tbl_documents_products_id == 2) {
            // $policy_branch = $orm->tbl_document_applications()->where(array('policy_no' => $record['policy_number']))->fetch();
            // $policyBranch = DocumentApplication::where('policy_no', $record['policy_number'])->first();
            if (strtoupper($templateData->payment_method) == 'MOBILE MONEY' || strtoupper($templateData->payment_option) == 'MOBILE MONEY') {
                $payment_html_dclaim  .= '<tr>
                <td style="width: 158px;"><strong><u>MOBILE MONEY</u>&nbsp;</strong></td>
                <td style="width: 265px;">&nbsp;</td>
                <td style="width: 158px;">&nbsp;</td>
                <td style="width: 265px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 158px;">Name of Telco&nbsp;</td>
                <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->telco_name . '</span></strong></td>
            </tr>
            <tr>
                <td style="width: 158px;">Wallet Number&nbsp;</td>
                <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->wallet_number . '</span></strong></td>
            </tr>
            <tr>
                <td style="width: 158px;">Account Holder&nbsp;</td>
                <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->wallet_name . '</span></strong></td>
            </tr>';
            }
            if (strtoupper($templateData->payment_method) == 'BANK' || strtoupper($templateData->payment_method) == 'BANK PAYMENT' || strtoupper($templateData->payment_option) == 'BANK PAYMENT') {
                $branch_id_name = explode("_", $templateData['payment_bank_branch']);
                $branch_name =  $branch_id_name[1];
    
                $bank = Bank::find($templateData['payment_bank_name']);
                $bank_name = $bank->bank_name;

                $payment_html_dclaim .= '<tr>
                <td style="width: 158px;"><strong><u>BANK DETAILS</u>&nbsp;</strong></td>
                <td style="width: 265px;">&nbsp;</td>
                <td style="width: 158px;">&nbsp;</td>
                <td style="width: 265px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 158px;">Bank Name&nbsp;</td>
                    <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $bank_name . '</span></strong></td>
                </tr>
                <tr>
                    <td style="width: 158px;">Bank Branch&nbsp;</td>
                    <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $branch_name . '</span></strong></td>
                </tr>
                <tr>
                    <td style="width: 158px;">Account Holder Name&nbsp;</td>
                    <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->account_holder_name . '</span></strong></td>
                </tr>
                <tr>
                    <td style="width: 158px;">Account Number&nbsp;</td>
                    <td colspan="3" rowspan="1" style="width: 265px;">&nbsp;<strong><span contenteditable="false" tabindex="-1">' . $templateData->account_number . '</span></strong></td>
                </tr>';
            }

            $dataMap['[CLAIM_TYPE]'] = strtoupper($document_product->product_name);
            $dataMap['[NAME]'] = $templateData->name_of_deceased;
            $dataMap['[DATE_OF_DECEASED]'] = Carbon::createFromTimestamp($templateData->date_of_birth_of_deceased)->format('d-M-Y'); //date('d-M-Y', $templateData->date_of_birth_of_deceased);
            $dataMap['[WORK_TELEPHONE]'] = $templateData->work_tel_of_deceased;
            $dataMap['[HOME_TELEPHONE]'] = $templateData->home_tel_of_deceased;
            $dataMap['[RELATIONSHIP]'] = $templateData->relationship_to_claimant;
            $dataMap['[ADDRESS]'] = $templateData->address_of_deceased;
            $dataMap['[HOUSE_NO]'] = $templateData->house_number_of_deceased;
            $dataMap['[LANDMARK]'] = $templateData->landmark_to_house_of_deceased;
            $dataMap['[OCCUPATION_DECEASED]'] = $templateData->occupation_of_deceased;
            $dataMap['[EMPLOYER_DECEASED]'] = $templateData->employer_of_deceased;
            $dataMap['[PLACE_OF_DEATH]'] = $templateData->place_of_death;
            $dataMap['[EMPLOYER_LOCATION]'] = $templateData->employer_location_of_deceased;
            $dataMap['[CAUSE_OF_DEATH]'] = $templateData->cause_of_death;
            $dataMap['[PLACE_OF_ACCIDENT]'] = $templateData->place_of_accident;
            $dataMap['[POLICE_STATION]'] = $templateData->name_of_police_station;
            $dataMap['[SUMMARY_OF_ACCIDENT]'] = $templateData->illment_description;
            $dataMap['[MORTUARY_YESNO]'] = $templateData->body_deposited;
            $dataMap['[NAME_OF_MORTUARY]'] = $templateData->motuary_name;
            $dataMap['[BODY_BURIED]'] = $templateData->body_buried;
            $dataMap['[DATE_OF_BURIAL]'] = Carbon::createFromTimestamp($templateData->burial_date)->format('d-M-Y'); //date('d-M-Y', $templateData->burial_date']);
            $dataMap['[NAME_OF_CEMETARY]'] = $templateData->cemetery_name;
            $dataMap['[NAME_OF_CHURCH_BURIAL]'] = $templateData->name_of_entity_handled_burial_service;

            $dataMap['[MORTUARY_CONTACT]'] ='';
            $dataMap['[PLAN_OF_INSURANCE]'] ='';
            $dataMap['[DATE_OF_ISSUE]'] = Carbon::parse($document_application->createdon)->format('d-M-Y H:i:s'); //date('d-M-Y H:i:s', strtotime($rs['createdon']));
            $dataMap['[AMOUNT_PAYABLE]'] = '';
            $dataMap['[DEATH_CERTIFICATE]'] ='';
            $dataMap['[MEDICAL_CERTIFICATE]'] = '';
            $dataMap['[AREA]'] = $templateData->area_of_deceased;
            $dataMap['[PAYMENT_DETAILS]'] = $payment_html_dclaim;
            $dataMap['[NAME_OF_CLAIMANT]'] = $templateData->name_of_claimant;
            $dataMap['[CLAIMANT_PHONE_NO]'] = $templateData->mobile_no;
            $dataMap['[DIGITAL_ADDRESS]'] = $templateData->digital_address;
            $dataMap['[CLAIMANT_BRANCH]'] = $document_application->tbl_branch?->branch_name;
            $dataMap['[CONTACT_DETAILS_CONFIRMATION]'] = $templateData->contact_details_confirmation;
            $dataMap['[DECEASED_POLICY_NO]'] = $templateData->policy_number;
            $dataMap['[DATE_OF_DEATH]'] = $templateData->date_of_death;
        }

        //CLAIM REQUEST
        if ($document_application->tbl_documents_products_id == 3) {

            $dataMap['[NAME]'] = $templateData->my_name;
            $dataMap['[CLAIM_TYPE]'] = $templateData->claim_type;
            if ($document_application->reason_for_claim != "" && $templateData->reason_for_claim != 'OTHER') {
                $dataMap['[REASON]'] = $templateData->reason_for_claim;
            } else {
                $dataMap['[REASON]'] = $templateData->other_reason_for_claim;
            }
            $dataMap['[TELCO_NAME]'] = $templateData->telco_name;
            $dataMap['[EMAIL]'] = $templateData->email;
            $dataMap['[TIN]'] = $templateData->tin;
            $dataMap['[PROPOSAL_NO]'] = $templateData->policy_number;
            $dataMap['[BANK_NAME]'] = $templateData->bank_name;
            $dataMap['[ACCOUNT_NUMBER]'] = $templateData->account_number;
            $dataMap['[WORKSITE]'] = $templateData->worksite;
            $dataMap['[STAFF_NO]'] = $templateData->staff_number;
            $dataMap['[ID_TYPE]'] = $templateData->id_type;
            $dataMap['[ID_NUMBER]'] = $templateData->id_number;
            $dataMap['[BANK_BRANCH]'] = $templateData->bank_branch;
            $dataMap['[REFUND_TYPE]'] = $templateData->refund_type;
            $dataMap['[MOMO_NAME]'] = $templateData->momo_name;
            $dataMap['[TELCO]'] = $templateData->telco;
            $dataMap['[MOMO_NUMBER]'] = $templateData->wallet_number;
            $dataMap['[TELEPHONE_NO]'] = $templateData->mobile;
            $dataMap['[CREATED_BY]'] = strtoupper($document_application->tbl_users['full_name']);
            $dataMap['[ADDRESS]'] = $templateData->address;
            $dataMap['[CLAIM_OPTION]'] = $templateData->claim_options;
            $dataMap['[MONTHLY_INCOME]'] = $templateData->monthly_income;
       
            // $template->assign('PAYMENT_DETAILS', $payment_html);
        }

        //
        //TERM
        if ($document_application->tbl_documents_products_id == 4 || $document_application->tbl_documents_products_id == 12) {
            if ($templateData->policy_term != "" && $templateData->policy_term != "Other") {
                $dataMap['[POLICY_TERM]'] = $templateData->policy_term;
            } else if ($templateData->other_payment_term_holder != "") {
                $dataMap['[POLICY_TERM]'] = $templateData->other_payment_term_holder;
            }

            if(is_numeric($templateData->empoyment_employer)){
                $employer_name = Employer::where('emp_code', $templateData->empoyment_employer)->value('name');
                $dataMap['[EMPLOEYER]'] = $employer_name;
                $dataMap['[STAFF_NO]'] = $templateData->staff_id ?? $templateData->empoyment_staff_id;
            } 

            if(is_numeric($templateData->empoyment_occupation)){
                $dataMap['[OCCUPATION]'] = Occupation::where('slams_id', $templateData->empoyment_occupation)->value('occupation_name');
            } else {
                $dataMap['[OCCUPATION]'] = $templateData->occupation;
            }

        }
        //TPP
        if ($document_application->tbl_documents_products_id == 9) {
            $dataMap['[PARENTS_LIFE_STATUS]'] = $templateData->parents_alive;
            $dataMap['[POLICY_EXIST]'] = $templateData->any_other_policy;
            $dataMap['[POLICY_EXIST_NAME]'] = $templateData->policy_name;
            $dataMap['[POLICY_EXIST_NUM]'] = $templateData->policy_number;
            $dataMap['[STAFF_NO]'] = $templateData->staff_id ?? $templateData->empoyment_staff_id;
            $dataMap['[RESIDENTIAL_ADDRESS_]'] = $templateData->address;
            $dataMap['[POSTAL_ADDRESS_]'] = $templateData->postal_address;
            $dataMap['[STAFF_NO]'] = $templateData->staff_number ?? $templateData->empoyment_staff_id;

            if(is_numeric($templateData->empoyment_employer) || is_numeric($templateData->employer_name)){
                $empoyment_id = $templateData->employer_name; //$templateData->empoyment_employer ??
                $employer_name = Employer::where('emp_code', $empoyment_id)->value('name');
                $dataMap['[EMPLOEYER]'] = $employer_name;
            } 
            if(is_numeric($templateData->empoyment_occupation)){
                $dataMap['[OCCUPATION]'] = Occupation::where('slams_id', $templateData->occupation)->value('occupation_name');
            } 
            // else {
            //     $dataMap['[OCCUPATION]'] = $templateData->occupation;
            // }

            $p_cover_sum = Cover::where('tbl_document_applications_id', $document_application->id)
            ->sum('cover_premium');
            $a_cover = Cover::where('tbl_document_applications_id', $document_application->id)->first();

            $cover_one_premium = !is_null($a_cover) ? $a_cover->cover_one_premium : 0;

            if($p_cover_sum >= 0){
                $dataMap['[PREMIUM]'] =  $p_cover_sum + $cover_one_premium;
                $premium = ($p_cover_sum > 0) ? $p_cover_sum : 0;
                $total_premium = $premium + $cover_one_premium;

                $dataMap['[PREMIUM_IN_WORDS]'] = $total_premium ? $this->amountToWords($total_premium) : 0;
            }
        }

        //Travel 
        if($document_application->tbl_documents_products_id == 11){
            $dataMap['[PREMIUM]'] = "GHS " . $templateData->premium;
            $dataMap['[ADDRESS]'] = $templateData->address;
            $dataMap['[DEPARTURE_DATE]'] = $templateData->date_of_departure;
            $dataMap['[RETURN_DATE]'] = $templateData->date_of_return;
            $dataMap['[DESTINATION]'] = $templateData->destination;
            $dataMap['[SUM_INSURED]']  = ''; //€33,000
            $dataMap['[BANK_NAME]'] = $templateData->payment_bank_name;
            $dataMap['[BANK_BRANCH]'] = $templateData->payment_bank_branch;
            $dataMap['[ACCOUNT_NUMBER]'] = $templateData->payment_account_number;
            $dataMap['[ACCOUNT_TYPE]'] = $templateData->account_type;
            $dataMap['[EMPLOYER_NAME]'] = $templateData->name_of_employer;
            $dataMap['[OFFICE_BUILDING]'] = $templateData->office_building_location;
            $dataMap['[TELCO_NAME]'] = $templateData->telco_name;
            $dataMap['[WALLET_NUMBER]'] = $templateData->wallet_number;
            $dataMap['[WALLET_NAME]'] = $templateData->wallet_name;
            $dataMap['[YESNO]'] = $templateData->free_from_sickness;
            $dataMap['[MEDICAL_DESCRIPTION]'] = $templateData->illment_description;
            
        }

        if($document_application->tbl_documents_products_id == 1){

            $dataMap['[PREMIUM]'] =  $templateData->premium; 
            $premium = ($templateData->premium > 0) ? $templateData->premium : 0;
        
            $dataMap['[PREMIUM_IN_WORDS]'] = $premium ? $this->amountToWords($premium) : 0;
        }

        //
        if ($document_application->tbl_documents_products_id == 14) {
            // $age =  get_age_from_date_of_birth($templateData->date_of_birth);
            $age = Carbon::parse($templateData->date_of_birth)->age;
            $term = $age + $templateData->payment_term;
         
            $dataMap['[INSTUT_NAME]'] =  $templateData->instut_name;
            $dataMap['[REL_PURPOSE]'] = $templateData->rel_purpose;
            $dataMap['[INC_COUNTRY]'] = $templateData->inc_country;
            $dataMap['[INCOME_SOURCE]'] = $templateData->income_source;
            $dataMap['[BUSINESS_NATURE]'] = $templateData->business_nature;
            $dataMap['[REG_NUMBER]'] = $templateData->reg_number;
            $dataMap['[CORP_ENT_TEL]'] = $templateData->corp_ent_tel;
            $dataMap['[CORP_ENT_EMAIL]'] = $templateData->corp_ent_email;
            $dataMap['[CORP_ENT_WEBSITE]'] = $templateData->corp_ent_website;
            $dataMap['[CORP_ENT_POST_ADDRESS]'] = $templateData->corp_ent_post_address;
            $dataMap['[CORP_ENT_PERM_ADDRESS]'] = $templateData->corp_ent_perm_address;
            //
            $dataMap['[FIRST_DIRECTOR_FNAME]'] = $templateData->first_director_fname;
            $dataMap['[FIRST_DIRECTOR_MNAME]'] = $templateData->first_director_mname;
            $dataMap['[FIRST_DIRECTOR_SNAME]'] = $templateData->first_director_sname;
            $dataMap['[FIRST_DIRECTOR_IDTYPE]'] = $templateData->first_director_idType;
            $dataMap['[FIRST_DIRECTOR_IDNUMBER]'] = $templateData->first_director_idNumber;
            $dataMap['[FIRST_DIRECTOR_NATIONALITY]'] = $templateData->first_director_nationality;
            $dataMap['[FIRST_DIRECTOR_OCCUPATION]'] = $templateData->first_director_occupation;
            $dataMap['[FIRST_DIRECTOR_PHYSICALADRESS]'] = $templateData->first_director_physicalAdress;
            $dataMap['[FIRST_DIRECTOR_DOB]'] = $templateData->first_director_dob;
            $dataMap['[FIRST_DIRECTOR_TEL]'] = $templateData->first_director_tel;
            $dataMap['[FIRST_DIRECTOR_RESIDATATUS]'] = $templateData->first_director_residAtatus;
            //
            $dataMap['[SECOND_DIRECTOR_FNAME]'] = $templateData->second_director_fname;
            $dataMap['[SECOND_DIRECTOR_MNAME]'] = $templateData->second_director_mname;
            $dataMap['[SECOND_DIRECTOR_SNAME]'] = $templateData->second_director_sname;
            $dataMap['[SECOND_DIRECTOR_IDTYPE]'] = $templateData->second_director_idType;
            $dataMap['[SECOND_DIRECTOR_IDNUMBER]'] = $templateData->second_director_idNumber;
            $dataMap['[SECOND_DIRECTOR_NATIONALITY]'] = $templateData->second_director_nationality;
            $dataMap['[SECOND_DIRECTOR_OCCUPATION]'] = $templateData->second_director_occupation;
            $dataMap['[SECOND_DIRECTOR_PHYSICALADRESS]'] = $templateData->second_director_physicalAdress;
            $dataMap['[SECOND_DIRECTOR_DOB]'] = $templateData->second_director_dob;
            $dataMap['[SECOND_DIRECTOR_TEL]'] = $templateData->second_director_tel;
            $dataMap['[SECOND_DIRECTOR_RESIDATATUS]'] = $templateData->second_director_residAtatus;
            $dataMap['[BENEFICIARY_OWNERSHIP_TYPE]'] = $templateData->beneficiary_ownership_type;
            //
            // $dataMap['[BENEFICIARY_FULLNAME]'] = $templateData->beneficiary_fullname;
            // $dataMap['[BENEFICIARY_DOB]'] = $templateData->beneficiary_dob;
            // $dataMap['[BENEFICIARY_SEX]'] = $templateData->beneficiary_sex;
            // $dataMap['[BENEFICIARY_NATIONALITY]'] = $templateData->beneficiary_nationality;
            // $dataMap['[BENEFICIARY_IDTYPE]'] = $templateData->beneficiary_idType;
            // $dataMap['[BENEFICIARY_IDNUMBER]'] = $templateData->beneficiary_idNumber;
            // $dataMap['[BENEFICIARY_TEL]'] = $templateData->beneficiary_tel;
            // $dataMap['[BENEFICIARY_EMAIL]'] = $templateData->beneficiary_email;
            // $dataMap['[BENEFICIARY_POSTAL_ADDRESS]'] = $templateData->beneficiary_postal_address;
            // $dataMap['[BENEFICIARY_RESIDADDRESS]'] = $templateData->beneficiary_residAddress;
            $beneficiary_html= '';
            $beneficiaries = CorporateBeneficiary::where('tbl_document_applications_id', $document_application->id)->get();
    
            if ($beneficiaries) {  // <td>' . $beneficiary->gender . '</td>
              foreach ($beneficiaries as $key => $beneficiary) {
                $beneficiary_html .= '<tr>
                    <td>' . $beneficiary->full_name . '</td>
                    <td>' . $beneficiary->dob . '</td>
                    <td>' . $beneficiary->relationship . '</td>
                    <td>' . $beneficiary->phone_no . '</td>
                    <td>' . $beneficiary->percentage . '</td>
                </tr>';
              }
            }
            $dataMap['[BENEFICIARY]'] = $beneficiary_html;
            //
            $dataMap['[OFFICIAL_FNAME]'] = $templateData->official_fname;
            $dataMap['[OFFICIAL_MNAME]'] = $templateData->official_mname;
            $dataMap['[OFFICIAL_SNAME]'] = $templateData->official_sname;
            $dataMap['[OFFICIAL__IDTYPE]'] = $templateData->official__idType;
            $dataMap['[OFFICIAL__IDNUMBER]'] = $templateData->official__idNumber;
            $dataMap['[OFFICIAL__NATIONALITY]'] = $templateData->official__nationality;
            $dataMap['[OFFICIAL__DOB]'] = $templateData->official__dob;
            $dataMap['[OFFICIAL_PHYSICAL_ADDRESS]'] = $templateData->official_physical_address;
            $dataMap['[OFFICIAL_TEL]'] = $templateData->official_tel;
            $dataMap['[SIGNOPT]'] = $templateData->signopt;
            // $dataMap['[FINAL_SIGNATURE_BASE64_IMAGE_SVG]'] = $templateData->final_signature_base64_image_svg;


        }

            
        return $dataMap;
    }

    public function generateMandate($token, $from_mandate = false){// $serverside = false
        $document_application = DocumentApplication::where('token', $token)->first();
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        // $templateData = MandateRequest::where('tbl_document_applications_id', $document_application->id)->first();

        if($from_mandate){
            $templateData = MandateRequest::where('tbl_document_applications_id', $document_application->id)->first();
        }else{ //from proposal
            $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        }
        // $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        $company_profile = CompanyProfile::where('deleted', 0)->first();

        //
        //$html = '';
        // if ($templateData['payment_method'] == 'CAG DEDUCTIONS' || $templateData['payment_method'] == 'STOP ORDER') {
        //     $html .= '<p><span style="font-family:Georgia,serif;"><strong>STOP ORDER</strong></span></p><hr />
        //     <table border="0" cellpadding="0" cellspacing="0" style="width: 775px;">
        //        <tbody>
        //        <tr>
        //        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Name of Employer</span></td>
        //        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['employer'] . '</strong></span></td>
        //     </tr>
        //           <tr>
        //              <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Staff/Payroll/Regimental/Employee Number&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></td>
        //              <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['staff_id'] . '</strong></span></td>
        //           </tr>
            
        //           <tr>
        //              <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Office Building/Location</span></td>
        //              <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['office_building_location'] . '</strong></span></td>
        //           </tr>
        //        </tbody>
        //     </table>';
        // } elseif ($templateData['payment_method'] == 'MOBILE MONEY') {
        //     $html .= '<p><span style="font-family:Georgia,serif;"><strong>MOBILE MONEY</strong></span></p><hr />
        
        //     <table border="0" cellpadding="0" cellspacing="0" style="width: 775px;">
        //        <tbody>
        //           <tr>
        //              <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Telco Name&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></td>
        //              <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['telco_name'] . '</strong></span></td>
        //           </tr>
        //           <tr>
        //              <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Wallet Number</span></td>
        //              <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['wallet_number'] . '</strong></span></td>
        //           </tr>
        //        </tbody>
        //     </table>';
        // } elseif ($templateData['payment_method'] == 'DEBIT ORDER') {
        //     $bank = BankBranch::find($templateData['payment_bank_branch']);

        //     $html .= '<p><span style="font-family:Georgia,serif;"><strong>&nbsp;DEBIT ORDER</strong></span></p>
        
        //     <hr />
        //     <table border="0" cellpadding="0" cellspacing="0" style="width: 773px;">
        //       <tbody>
        //          <tr>
        //             <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Holder Name</span></td>
        //             <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['payment_account_holder_name'] . '</strong></span></td>
        //          </tr>
        //          <tr>
        //             <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Bank Name</span></td>
        //             <td colspan="2" rowspan="1" style="width: 162px;"><span style="font-family:Georgia,serif;"><strong>' . $bank->tbl_banks['bank_name'] . '</strong></span></td>
        //             <td rowspan="1" style="width: 159px; text-align: right;"><span style="font-family:Georgia,serif;">Bank Branch&nbsp; <strong>&nbsp; &nbsp;</strong></span></td>
        //             <td rowspan="1" style="width: 294px;"><span style="font-family:Georgia,serif;"><strong>&nbsp;' . $bank['branch_name'] . '</strong></span></td>
        //          </tr>
        //          <tr>
        //             <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Number</span></td>
        //             <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['payment_account_number'] . '</strong></span></td>
        //          </tr>
        //          <!--
        //          <tr>
        //             <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Type</span></td>
        //             <td colspan="2" rowspan="1" style="width: 162px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData['payment_method'] . '</strong></span></td>
        //             <td rowspan="1" style="width: 159px;">&nbsp;</td>
        //             <td rowspan="1" style="width: 294px;">&nbsp;</td>
        //          </tr>
        //          -->
        //       </tbody>
        //    </table>';
        // }

        $payment_html = '';
        if (strtoupper($templateData->payment_method) == 'CAG DEDUCTIONS' || strtoupper($templateData->payment_method) == 'STOP ORDER') {
            $payment_html .= '<p><span style="font-family:Georgia,serif;"><strong>STOP ORDER</strong></span></p><hr />' .
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 775px;">
                <tbody>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Staff/Payroll/Regimental/Employee Number&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->staff_id . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Name of Employer</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->employer . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Office Building/Location</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->office_building_location . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        } elseif (strtoupper($templateData->payment_method) == 'MOBILE MONEY') {
            $payment_html .= '
            
            <p><span style="font-family:Georgia,serif;"><strong>MOBILE MONEY</strong></span></p><hr />' .
        
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 775px;">
                <tbody>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Telco Name&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->telco_name . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 151px; text-align: left;"><span style="font-family:Georgia,serif;">Wallet Number</span></td>
                        <td colspan="4" rowspan="1" style="width: 190px; text-align: left;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->wallet_number . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        } elseif (strtoupper($templateData->payment_method) == 'DEBIT ORDER' || strtoupper($templateData->payment_option) == 'BANK') {
            $branch_id_name = explode("_", $templateData['payment_bank_branch']);
            $branch_name =  $branch_id_name[1];

            $bank = Bank::find($templateData['payment_bank_name']);
            $bank_name = $bank->bank_name;

            $payment_html .= '<p><span style="font-family:Georgia,serif;"><strong>&nbsp;DEBIT ORDER</strong></span></p>
            <hr />' .
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 773px;">
                <tbody>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Holder Name</span></td>
                        <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->payment_account_holder_name . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Bank Name</span></td>
                        <td colspan="2" rowspan="1" style="width: 162px;"><span style="font-family:Georgia,serif;"><strong>' . $bank_name . '</strong></span></td>
                        <td rowspan="1" style="width: 159px; text-align: right;"><span style="font-family:Georgia,serif;">Bank Branch&nbsp; <strong>&nbsp; &nbsp;</strong></span></td>
                        <td rowspan="1" style="width: 294px;"><span style="font-family:Georgia,serif;"><strong>&nbsp;' . $branch_name	 . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Account Number</span></td>
                        <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->payment_account_number . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        } elseif (strtoupper($templateData->payment_method) == 'STOP ORDER') {
            $payment_html .= '<p><span style="font-family:Georgia,serif;"><strong>&nbsp;STOP ORDER</strong></span></p>' .
            '<hr />' .
            '<table border="0" cellpadding="0" cellspacing="0" style="width: 773px;">
                <tbody>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Employer</span></td>
                        <td colspan="4" rowspan="1" style="width: 619px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->employer . '</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 148px; text-align: left;"><span style="font-family:Georgia,serif;">Staff ID</span></td>
                        <td colspan="2" rowspan="1" style="width: 162px;"><span style="font-family:Georgia,serif;"><strong>' . $templateData->staff_id . '</strong></span></td>
                    </tr>
                </tbody>
            </table>';
        }

        // $fileContent = Storage::disk('public')->get('company_profile/'.$company_profile->company_logo_path);
        $fileContent = Storage::disk('public')->get('company_profile/DhYlsP79yo.png');
        //public_path('assets/images/logo.png')
        $logo = '<img src="data:image/png;base64,'.base64_encode($fileContent).'" alt="" width="200"/>';
        // return $templateData;
        $dataMap = [
            '[LOGO]' => $logo,
        ];

        $dataMap['[PREMIUM]'] =  $templateData->premium; 
        
        $premium = ($templateData->premium > 0) ? $templateData->premium : 0;
    
        $dataMap['[PREMIUM_IN_WORDS]'] = $premium ? $this->amountToWords($premium) : 0;

        //TPP
        if ($document_application->tbl_documents_products_id == 9) {

            $p_cover_sum = Cover::where('tbl_document_applications_id', $document_application->id)
                        ->sum('cover_premium');
            $a_cover = Cover::where('tbl_document_applications_id', $document_application->id)->first();
            $cover_one_premium = !is_null($a_cover) ? $a_cover->cover_one_premium : 0;
            if($p_cover_sum >= 0){
                $dataMap['[PREMIUM]'] =  $p_cover_sum + $cover_one_premium;
                $premium = ($p_cover_sum > 0) ? $p_cover_sum : 0;
                $total_premium = $premium + $cover_one_premium;
            
                $dataMap['[PREMIUM_IN_WORDS]'] = $total_premium ? $this->amountToWords($total_premium) : 0;
            }

        }

        if ($document_application->tbl_documents_products_id == 6) {

            $dataMap['[PREMIUM]'] =  $templateData->net_premium; 

            $premium = ($templateData->net_premium > 0) ? $templateData->net_premium : 0;
        
            $dataMap['[PREMIUM_IN_WORDS]'] = $premium ? $this->amountToWords($premium) : 0;
        }
        
        
        if($templateData->agent_code){
            $ag = Agent::where('agent_code', $templateData->agent_code)->first();

                if($ag){
                    if($ag['agent_name']){
                        $dataMap['[FA_NAME]'] =  $ag['agent_name'];
                        $dataMap['[FA_CODE]'] =  $ag['agent_code'];

                        $dataMap['[AGENT_NAME]'] =  $ag['agent_name'];
                        $dataMap['[AGENT_NUMBER]'] =  $ag['agent_code'];
                    }else{
                        $dataMap['[FA_NAME]'] =  $templateData['agent_name'];
                        $dataMap['[FA_CODE]'] =  $templateData['agent_code'];

                        $dataMap['[AGENT_NAME]'] =  $templateData['agent_name'];
                        $dataMap['[AGENT_NUMBER]'] = $templateData['agent_code'];
                    }
                }
        }
    
        $dataMap['[ACCOUNT_HOLDER_NAME]'] =   $document_application['customer_name'];
        if($templateData['policy_holder_name']){
        $dataMap['[POLICY_HOLDER_NAME]'] =  $templateData['policy_holder_name'];
        }else{
        $dataMap['[POLICY_HOLDER_NAME]'] =   $document_application['customer_name'];
        }


        $dataMap['[BRANCH]'] = $document_application->tbl_branch ? $document_application->tbl_branch['branch_name'] : '';
        $dataMap['[MANDATE_DETAILS]'] =  $payment_html;
        // return $dataMap;
        //
    
        if ($templateData->signopt == 1) { //signed
            $imageContent_s3 = Storage::disk('s3')->get('signatures/'.$templateData['signature_file']);
        } else {//uploaded
            $imageContent_s3 = Storage::disk('s3')->get('signatures/'.$templateData['uploaded_signature']);
        }

        $signature = '<img src="data:image/png;base64,'.base64_encode($imageContent_s3).'" width="100"/>';

        $dataMap['[SIGNATURE]'] = $signature;

        $dataMap['[POLICY_NUMBER]'] =  strtoupper($document_application['policy_no']);

        $dataMap['[DATE]'] =  date('d-M-Y H:i:s', strtotime($document_application['createdon']));
        $dataMap['[POLICY_NO]'] =  strtoupper($document_application['policy_no']);
        $dataMap['[AGENT_NAME]'] =  strtoupper($document_application->tbl_users['full_name']);
        $dataMap['[AGENT_NO]'] =  strtoupper( $document_application->tbl_users['code']);

        return $dataMap;
    }
}


?>