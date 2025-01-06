<?php

namespace App\Http\Controllers\SLAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentApplication;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\Config;
use App\Http\Traits\DocumentSysEngine;
use App\Models\IntegrationLog;
use App\Models\Beneficiary;
use App\Models\Cover;
use App\Models\Agent;
use App\Models\CorporateBeneficiary;
use App\Models\DocumentProduct;
use App\Models\Bank;
use Carbon\Carbon;

class PushToSlamComtroller extends Controller
{
    use Config;
    use DocumentSysEngine;
    public function initiatePush(Request $request){
        // return $request;
        //Authenticate
        $auth_response = $this->get_auth_token_from_slams();
        //If auth fails
        if (isset($auth_response["error"])) {
            return response()->json(['state' => 0, 'msg' => 'Failed']);
        }
        //If auth succeeds: Attah bearer  to header
        $token_slams = "Bearer " . $auth_response["access_token"];
        $authHeader = [
            'Authorization' => $token_slams,
            'Accept' => 'application/form-data',
        ];
        //auth end

        //Get Document Application
        $record = DocumentApplication::find($request->id);
        //Get Application Details
        $document_product = DocumentProduct::find($record->tbl_documents_products_id);
        $product_model =  $document_product->product_model;
        $record_details = $product_model::where('tbl_document_applications_id', $record->id)->first();
        $beneficiaries = Beneficiary::where('tbl_document_applications_id', $record->id)->get();
        // return $record;
        // return $record_details;
        // return $beneficiaries;
        $agent = Agent::where('agent_code', $record_details->agent_code)->first();

        $bank_code = '';
        if(isset($record_details->payment_bank_name) && $record_details->payment_bank_name !=='' && $record_details->payment_bank_name !== null){       
            $bank = Bank::where('id', $record_details->payment_bank_name)->first();
            $bank_code = $bank->slams_id;
        }

        $brank_branch_code = '';
        if(isset($record_details->payment_bank_branch) && $record_details->payment_bank_branch !== '' && $record_details->payment_bank_branch !== null){
            $parts = explode('_', $record_details->payment_bank_branch);
            $brank_branch_code  = $parts[0];
        }
        // return $agent;
        $sum_assured = $request->sum_assured;
        $basic_premium = $request->basic_premium;
        $policyFee = $request->policyFee;
        $client_type = $request->client_type;
        $occup_class = $request->occup_class;
        $with_rider = $request->with_rider;
        $payment_date = $request->payment_date; 
        $payment_amt = $request->payment_amt;
        $product_category = $request->product_category;
        $client_attributes = $request->client_attributes;
        $nature_of_product = $request->nature_of_product;
        $source_of_funds = $request->source_of_funds;
        $client_conduct = $request->client_conduct;
        $delivery_channel = $request->delivery_channel;
        $clientRepresentedByOfficialMandate = $request->clientRepresentedByOfficialMandate;

        $allowedProducts = [2, 3];
       
        if (in_array($record['tbl_documents_products_id'], $allowedProducts)) {
            //CREATE CLAIM
            $create_claim_Data = [
                'policy_number' => '0101OMSI85409',
                'reason_for_claim' => '', // Replace '' with the actual reason for claim if available
                'claim_type' => 'PWD',
                'percentage_applied_for' => 100,
                'notification_date' => '2021-04-21',
                'PartialWithdPurpose' => 3
            ];

            //make the call
            $create_claim_response_raw  = $this->CallAPI('POST', env('SLAMS_URL_TO_CREATE_CLAIM_REQUEST'), $create_claim_Data, $authHeader);
            $create_claim_response = json_decode($create_claim_response_raw, true);
            $create_claim_response_msg = json_encode($create_claim_response);

            if (isset($create_claim_response["error"])) {
                $record->update([
                    'slams_create_client_status' => -1,
                    'slams_create_client_response_message' => "Not required for claim creation",
                    'slams_create_policy_or_claim_status' => -1,
                    'slams_create_policy_or_claim_response_message' => $create_claim_response['response'],
                    'slams_create_client_request_body' => $record->slams_create_policy_or_claim_request_body
                ]);

                return "-1 create_claim_request";
            }
            $status = ($create_claim_response['success'] == 1) ? 'PASSED' : 'FAILED';

            IntegrationLog::create([
                'tbl_document_applications_id' => $record->id,
                'status' => $status,
                'endpoint_url' => env('SLAMS_URL_TO_CREATE_CLAIM_REQUEST'),
                'request_message' => $post_fields,
                'response_message' => $create_claim_response_msg
            ]);

            $this->scannedRequestLogs($record->id, $status, 'Customer Claims in SLAMS');

            $record->update([
                'slams_create_client_status' => 1,
                'slams_create_client_response_message' => "Not required for claim creation",
                'slams_create_policy_or_claim_status' => 1,
                'slams_create_policy_or_claim_response_message' => $create_claim_response['response'],
                'slams_create_client_request_body' => $record->slams_create_policy_or_claim_request_body
            ]);

            return "1";
        }else{

            //CREATE CUSTOMER
            $create_client_Data  = [
                'surname' => $record_details->surname,
                'firstname' => $record_details->firstname,
                'othernames' => $record_details->othernames,
                'title' => $record_details->title ? $this->format_title_status_for_slams($record_details->title) : null, 
                'gender' => $record_details->gender? $this->format_gender_for_slams($record_details->gender): null, 
                'country_of_birth' => $record_details->country_of_birth,
                'date_of_birth' => $record_details->date_of_birth,
                'marital_status' => $this->format_marriage_status_for_slams($record_details->marital_status), 
                'id_type' => $this->format_id_type_for_slams($record_details->id_type), 
                'id_number' =>$record_details->id_number,
                'passport_number' => isset($record_details->id_type) && $record_details->id_type == "PASSPORT" ? $this->format_id_type_for_slams($record_details->id_type) : '', //744322
                'mobile' => $record_details->mobile ? $this->format_phone_number_for_slams($record_details->mobile, $record_details->client_resides_in_ghana) : null, 
                'email' =>$record_details->email,
                'address' =>$record_details->address,
                'occupation_class' => '', // $record_details->occupation, // should be occupation id
                'tin' => $record_details->tin,
                'payment_method' => $record_details->payment_method ? $this->format_payment_method_for_slams($record_details->payment_method) : null, // id
                'eft_bank_code' => $bank_code ?? '', //$record_details->payment_bank_name,  // bank code
                'eft_bank_branch_code' => $brank_branch_code ?? '', //'001', // bank branch name
                'eft_bank_account_number' => $record_details->payment_account_number, // bank account
                'eft_bank_account_name' => $record_details->payment_account_holder_name,
                'staff_id' => $record_details->staff_id,
                'occupation' => $record_details->occupation, // 10 should be occupation id --
                'country' => 'Ghana', //$record_details->country_of_residence, --
                'region' => $this->getRegionId($record_details['region_in_ghana']), // 8 $record_details->region_in_ghana, // should be region id --
                'income_source' => $record_details->source_of_income ? $this->format_source_of_income_for_slams($record_details->source_of_income) : null, //3 could be and int --
                'is_politically_exposed' => $record_details->is_politically_exposed ? 1: 0,
                'gps_code' => $record_details->gps_code ?? '', //not found in the form data
                'physical_address' => $record_details->address,
                'client_type' => 2, // $client_type,
                'client_resides_in_ghana' => $record_details->client_resides_in_ghana,
                //
                'clientRepresentedByOfficialMandate' => $clientRepresentedByOfficialMandate ?? '1',
                'product_category' => $product_category ?? '1',
                'client_attributes' => '1',
                'nature_of_product' => $product_category ?? '1',
                'source_of_funds' => $source_of_funds ?? '1',
                'client_conduct' => $client_conduct ?? '1',
                'delivery_channel' => $delivery_channel ?? '1',
                'client_consent' => $record_details->privacy ? (strtoupper($record_details->privacy) == 'YES'? 1 : 0) : null,
                //
                'has_medical_condition' => $record_details->health_issues ? (strtoupper($record_details->health_issues) == 'YES' ? 1: 0) : null,
                'medical_condition_type' => '2',
                'medical_comment' => $record_details->illment_description,
            ];  

            //make the call
            $create_client_response_raw = $this->CallAPI('POST', env('SLAMS_URL_TO_CREATE_CLIENT'), $create_client_Data, $authHeader);
            $create_client_response = json_decode($create_client_response_raw, true);
            $create_client_response_msg = json_encode($create_client_response);
            //end - do you thing with the response
            // return  $create_client_response_msg;
            if (isset($create_client_response["error"])) {
                $record->update(array(
                    'slams_create_client_status' => -1,
                    'slams_create_client_response_message' => $create_client_response_msg['response'],
                    'slams_create_policy_or_claim_status' => -1,
                    'slams_create_policy_or_claim_response_message' => "Initial client creation failed",
                    'slams_create_client_request_body' => $record['slams_create_policy_or_claim_request_body']
                ));

                // return ['error' => 'create_customer Failed'];
                return ['create_customer_error' => $create_client_response_msg];
            }
            //
            $status = ($create_client_response['success'] == 1) ? 'PASSED' : 'FAILED';
            IntegrationLog::create([
                'tbl_document_applications_id' => $record->id,
                'status' => $status,
                'endpoint_url' => env('SLAMS_URL_TO_CREATE_CLIENT'),
                'request_message' => json_encode($create_client_Data),
                'response_message' => $create_client_response_msg
            ]);
            $this->scannedRequestLogs($record->id, $status, 'Customer Creation in SLAMS');

            // $post_fields = $record['slams_create_policy_or_claim_request_body'];
            // return $create_client_response['client_number'];

            //CREATE POLICY
            //
            $age = $this->getAgebyDOB($record_details['date_of_birth']);
            $maturity_age = $age + $record_details['payment_term'];

            $maturity_date = $this->calculateMaturityDate($maturity_age, $record_details['date_of_birth']);
    
            
            $create_policy_Data = [
                'proposal_no' => $record->policy_no,
                'proposal_date' => Carbon::now()->format('Y-m-d'),
                'client_number' => $create_client_response['client_number'],
                'policy_no' => $record->policy_no, //$record->policy_no
                'plan_code' => $document_product->product_name ? $this->format_product_type_slams($document_product->product_name) : null,//
                'agent' => $agent ? $agent->slams_id : null,
                'pay_mode' => $record_details->payment_frequency, // quite unclear
                'CommencementDate' => $record_details->payment_commencement_month,
                'term_of_policy' => $record_details->payment_term,
                'modal_prem' => $basic_premium, // 14 POST basic_premium
                'policyFee' => $policyFee, // 2 Should be set
                'prem_escalator' => $record_details->annual_premium ? 1 : 0, // 1 yes or 0 no
                'escalator_rate' => $record_details->annual_premium ? $this->getpercentageId_from_slam($record_details->annual_premium) : null, // unclear
                'CheckOffDate' => $record_details->payment_commencement_month,
                'class_code' => $occup_class, //001 $_POST["occup_class"]
                'issued_date' => Carbon::now()->format('Y-m-d'), 
                'payment_data' => [
                    'payment_date' => isset($payment_date) ? $payment_date : Carbon::now()->format('Y-m-d'), //$_POST["payment_date"],
                    'amount' => $payment_amt, //20 $_POST["payment_amt"]
                    'created_on' => Carbon::now()->format('Y-m-d'),
                ],
                'is_smoker' => $record_details->is_smoker ? 1 : 0, // 0 or 1
                'sum_assured' => $sum_assured, //'100000'
                'with_dependants' => $record_details->with_dependants ? 1 : 0, //0 or 1
                'with_rider' => $with_rider ?? $record_details->with_rider? 1 : 0, // 0 or 1
                'basic_premium' => $basic_premium ? $basic_premium : null, //contribution_amount
                'investment_premium' => '0', // 80 unclear
                'extra_premium' => '0',  //unclear
                'premium_units' => '0', //12 unclear
                'maturity_date' => $maturity_date,
                'MaturityDate' => $maturity_date,
                'maturity_age' => $maturity_age,
                'last_premium_date' => '2020-09-08',
                'last_premium_pay_date' => '2020-09-08',
                'premium_due_date' => $record_details->payment_commencement_month,
                'status_date' => Carbon::now()->format('Y-m-d'),
                'last_withdrawal_date' => Carbon::now()->format('Y-m-d'),
                'effective_date' => Carbon::now()->format('Y-m-d'),
                'deductionday' => '0', //unclear
                'pay_method' => $record_details->payment_method ? $this->format_payment_method_for_slams($record_details->payment_method) : null, //1

                // 'telco' =>  ($this->format_telco_for_slams($record_details->telco_name) == 0 ? '': $this->format_telco_for_slams($record_details->telco_name)), //'00465', -- mostly throws pay_source_mainteinance err response from slams
                // 'momo_no' => $record_details->wallet_number?? '', 
                // 'employer_no' => $record_details->employer ?? '', // 00001  , 
                // 'staff_no' => $record_details->empoyment_staff_id ?? '', 
                // 'eft_bank_code' => $record_details->payment_bank_name ?? '',  // bank code
                // 'eft_bank_branch_code' => '1372' ?? '', // bank branch name --
                // 'eft_bank_account_number' => $record_details->payment_account_number ?? '', // bank account
                // 'eft_bank_account_name' => $record_details->payment_account_holder_name ?? '',
            ];

            if($record_details->payment_method ==  "Cash/Cheque"){
                // $create_policy_Data = [
                   
                // ];
            }else if($record_details->payment_method == "CAG Deductions" || $record_details->payment_method == "Stop Order"){
        
                $create_policy_Data['employer_no'] = isset($record_details->employer) ? $record_details->employer : ''; // 00001  , 
                $create_policy_Data['staff_no'] = isset($record_details->empoyment_staff_id) ? $record_details->empoyment_staff_id : '';  
        
            }else if($record_details->payment_method == "Mobile Money"){
        
                $create_policy_Data['telco'] = isset($record_details->telco_name) ? ($this->format_telco_for_slams($record_details->telco_name) == 0 ? '' : $this->format_telco_for_slams($record_details->telco_name)) : null; //'00465', -- mostly throws pay_source_mainteinance err response from slams
                $create_policy_Data['momo_no'] = isset($record_details->wallet_number) ? $this->format_phone_number_for_slams($record_details->wallet_number, $record_details->client_resides_in_ghana) : '';
                
            }
            else if($record_details->payment_method =="Debit Order"){
               
                $create_policy_Data['eft_bank_code'] = $bank_code ?? '';  // bank code
                $create_policy_Data['eft_bank_branch_code'] = $brank_branch_code ?? ''; // bank branch name
                $create_policy_Data['eft_bank_account_number'] = isset($record_details->payment_account_number) ? $record_details->payment_account_number : ''; // bank account
                $create_policy_Data['eft_bank_account_name'] = isset($record_details->payment_account_holder_name) ? $record_details->payment_account_holder_name : '';
              
            }else{
        
            }

            // return $create_policy_Data;
            $beneficiaries = Beneficiary::where('tbl_document_applications_id', $record->id,)->get();
            if($beneficiaries){
                foreach ($beneficiaries as $beneficiary) {
                    $create_policy_Data['beneficiary_data'][] = [
                        'Names' => $beneficiary->full_name,
                        'relationship' => $this->format_relationship_for_slams($beneficiary->relationship),
                        'perc_alloc' => $beneficiary->percentage,
                        'birth_date' => $beneficiary->dob,
                        'telephone' => $beneficiary->phone_no,
                        'address' => $beneficiary->residential_address,
                        'age' => Carbon::parse($beneficiary->age)->age,
                        'gender' => $this->format_gender_for_slams($beneficiary->gender),
                        'GuardianSurname' => isset($record_details->trustee_full_name) ? $record_details->trustee_full_name : '',
                        'GuardianOtherNames' => isset($record_details->trustee_full_name) ? $record_details->trustee_full_name : '',
                        'GuardianTelephone' => isset($record_details->trustee_mobile_number) ? $this->format_phone_number_for_slams($record_details->trustee_mobile_number, $record_details->client_resides_in_ghana) : '',
                        'GuardianEmail' => isset($record_details->trustee_email) ? $record_details->trustee_email : null,
                    ];
                }
            }

            $covers = Cover::where('tbl_document_applications_id', $record->id,)->get();
            if($covers){
                foreach ($covers as $cover) {
                    $sName = isset($record_details['cover_surname_name']) ? $record_details['cover_surname_name'] : '';
                    $fName = isset($record_details['cover_first_name']) ? $record_details['cover_first_name'] : '';
            
                    $create_policy_Data['dependants_data'][] = [
                        'names' => $fName .' '.$sName ,
                        'date_of_birth' => isset($cover->cover_dob) ? $cover->cover_dob : '',
                        'age' => isset($cover->cover_dob) ? $this->getAgebyDOB($cover->cover_dob) : '',
                        'sa' => isset($cover->cover_sum_assured) ? $cover->cover_sum_assured : '', //2000
                        'premium' => isset($cover->cover_premium) ? $cover->cover_premium : null, //2.36
                        'gender' => isset($cover->cover_gender) ? format_gender_for_slams($cover->cover_gender) : '',
                        'Relationship' => isset($cover->cover_relationship) ? format_relationship_for_slams($cover->cover_relationship) : '', //SN
                    ];
                }
            }

            //make the call
            $create_policy_response_raw  = $this->CallAPI('POST', env('SLAMS_URL_TO_CREATE_POLICY'), $create_policy_Data, $authHeader);
            $create_policy_response = json_decode($create_policy_response_raw, true);
            $claim_or_policy_response_msg = json_encode($create_policy_response);
            //end - do you thing with the response
           
            if (isset($create_policy_response["error"])) {

                $record->update(array(
                    'slams_create_client_status' => -1,
                    'slams_create_client_response_message' => $create_policy_response['message'],
                    'slams_create_policy_or_claim_status' => -1,
                    'slams_create_policy_or_claim_response_message' => $claim_or_policy_response_msg['response'],
                    'slams_create_policy_or_claim_request_body' => $newParams,
                ));
                // return ['error' => 'create_policy Failed'];
                return ['create_policy_error' => $create_policy_response_msg];
            }
        
            $record->update(array(
                'slams_create_client_status' => 1,
                'slams_create_client_response_message' =>  json_encode($create_policy_Data),
                'slams_create_policy_or_claim_status' => 1,
                'slams_create_policy_or_claim_response_message' => $claim_or_policy_response_msg
            ));
        
            // return ['success' => 'Policy created successfully'];
            return ['all_success' => $claim_or_policy_response_msg];
        }

    }
}
