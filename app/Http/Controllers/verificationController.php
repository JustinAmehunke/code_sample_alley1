<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use App\Models\DocumentApplication;
use App\Models\DocumentProduct;
use App\Models\DocumentType;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// use App\Http\Traits\CodeGenerator;
use App\Http\Traits\DocumentSysEngine;
use Auth;

class verificationController extends Controller
{
    // use CodeGenerator;
    use DocumentSysEngine;

    public function verifyPhoneNumber(Request $request)
    {
     //   return $request;
        $client = new Client([
            'verify' => false,
        ]);

        $doc_appl_id = base64_decode($request->doc_appl_id);

        $response = $client->post('https://iverify.shrinqghana.com/api/external/verify-identity', [
            'headers' => [
                'x-configuration-id' => 'test_me_now',
                'accept' => 'application/json'
            ],
            'json' => [
                "channel" =>"mobile",
                "mobile_number"=>$request->phone_number,
                "operator"=>$request->operator
            ]
        ]);
        // return $response;
        // Get the response body
        $payload = $response->getBody()->getContents();
        // Decode the JSON payload
        $data = json_decode($payload, true);
        // return $data;
        $status = '';
        $occurences = 0;
        if ($data !== null && $data['status'] == 'success') {
            $name1 = Session::get('id_name');
            $name2 = $data['name'];
            $occurences = $this->verifySameCustomer($name1, $name2);
            Session::put('proceed', $occurences > 0 ? 1 : 0);
           if($request->doc_appl_id){
                if($occurences <= 0){
                    DocumentApplication::findOrFail($doc_appl_id)->update([
                        "flag_request" => 1,
                        "flag_comment" => "Credentials on Momo number do not match that of the verified ID card"
                    ]);
                    $status = "failed";
                }else{
                    $status = "success";
                }
           }
        }

        return  response([
            'status' => $occurences > 0 ? 'success' : 'failed',
            'data' => $data,
            'matched_status' => $status
        ], 200);
    }

    public function verifyIdNumber(Request $request){

        // Temporary alternative due to server down time
        // // verifies only Ghana card
        // $client = new Client([
        //     'verify' => false,
        // ]);

        // $response = $client->post('https://api.access89.com/services/v1/nia/verify-id', [
        //     'headers' => [
        //         'x-api-user' => 'thirdparty',
        //         'accept' => 'application/json'
        //     ],
        //     'json' => [
        //         "ghanaCardNumber"=>$request->id_number,
        //     ]
        // ]);

        // // Get the response body
        // $payload = $response->getBody()->getContents();
        // // Decode the JSON payload
        // $data = json_decode($payload, true);

        // $id_type = "Ghana Card";

        // $data = json_decode($payload, true);
        // if ($data !== null) {
        //     // return $data['data']['name'];
        //     $full_id_ver_name = $data['data']['name'];
        //     Session::put('id_name', $full_id_ver_name);
        // }

        // return response([
        //     'data' => $data,
        //     'id_selected' =>  $id_type,
        // ], 200);
        //end


        // Iverify Implementation (main)
        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get('https://iverify.shrinqghana.com/api/external/kyc', [
            'headers' => [
                'x-configuration-id' => 'test_me_now',
                'accept' => 'application/json'
            ],
            'json' => [
                "id_type" =>$request->id_type,
                "id_number"=>$request->id_number,
            ]
        ]);

        // Get the response body
        $payload = $response->getBody()->getContents();
        // Decode the JSON payload
        $data = json_decode($payload, true);
        if ($data !== null) {
            $full_id_ver_name = $data['firstName'] . " " . $data['middleName'] . " " . $data['lastName'];
            Session::put('id_name', $full_id_ver_name);
        }

        switch ($request->id_type) {
            case 'ghana-card':
                $id_type = 'Ghana Card';
                break;
            case 'drivers-license':
                $id_type = 'Drivers License';
                break;
            case 'passport':
                $id_type = 'Passport';
                break;
            case 'voter-id':
                $id_type = 'Voter ID';
                break;
            case 'ssnit':
                $id_type = 'SSNIT';
                break;
            case 'voter-old':
                $id_type = 'Old Voter ID';
                break;
            default:
            $id_type = '';
                break;
        }

        // return $data;

        return response([
            'data' => $data,
            'id_selected' =>  $id_type,
        ], 200);

         // $api_id_options = ["voter-id", "ghana-card", "passport", "voter-old", "ssnit", "drivers-license"];

        // switch ($request->id_type) {
        //     case 'Ghana Card':
        //         $id_type = $id_options[1];
        //         break;
        //     case 'Drivers License':
        //         $id_type = $id_options[5];
        //         break;
        //     case 'Passport':
        //         $id_type = $id_options[2];
        //         break;
        //     case 'Voter ID':
        //         $id_type = $id_options[0];
        //         break;
        //     case 'SSNIT':
        //         $id_type = $id_options[4];
        //         break;
        //     case 'Old Voter ID':
        //         $id_type = $id_options[3];
        //         break;
        //     default:
        //     $id_type = '';
        //         break;
        // }

        // $stak_id_options = ["Voter ID", "Ghana Card", "Passport", "Old Voter ID", "SSNIT", "Drivers License"];
        // $api_id_options = ["voter-id", "ghana-card", "passport", "voter-old", "ssnit", "drivers-license"];
        // switch ($request->id_type) {
        //     case 'ghana-card':
        //         $id_type = 'Ghana Card';
        //         break;
        //     case 'drivers-license':
        //         $id_type = 'Drivers License';
        //         break;
        //     case 'passport':
        //         $id_type = 'Passport';
        //         break;
        //     case 'voter-id':
        //         $id_type = 'Voter ID';
        //         break;
        //     case 'ssnit':
        //         $id_type = 'SSNIT';
        //         break;
        //     case 'voter-old':
        //         $id_type = 'Old Voter ID';
        //         break;
        //     default:
        //     $id_type = '';
        //         break;
        // }
    }

    public function verifySameCustomer($name1, $name2) {
        // Normalize the names by converting them to lowercase and splitting them into arrays
        $name1_array = explode(' ', strtolower(trim($name1)));
        $name2_array = explode(' ', strtolower(trim($name2)));

        // return  $name1_array;
        // Use array_intersect to find common names
        $common_names = array_intersect($name1_array, $name2_array);
        // return $common_names;
        // Check if at least two names are common
        return count($common_names) >= 2;
    }

    public function uploadId(Request $request){

        if ($request->hasFile('id_file') && $request->has('token')) {

            $token = $request->token;
            $record = DocumentApplication::where('token', $token)->first();
            $document_product = DocumentProduct::find($record->tbl_documents_products_id);
            $product_model =  $document_product->product_model;
            // Handle file upload
            $uploadedFile = $request->file('id_file');
            // Assuming $uploadedFile contains the uploaded file instance
            $extension = $uploadedFile->getClientOriginalExtension(); // Get the original extension

            // Generate a unique filename using UUID
            $uuid = Str::uuid()->toString();

            // Concatenate the UUID and extension to create the unique filename
            $fullName = 'uploaded-'.$uuid . '.' . $extension;
            $targetDir = 'documents/';
            // Store the file with the unique filename on S3
            $status  =  Storage::disk('s3')->put($targetDir . $fullName, file_get_contents($uploadedFile));

            $checklist = DocumentType::find(3);
            $docType = $this->getImageDoc($uploadedFile->getMimeType());
            $refNo = 'ST-' . now()->format('dmYHis');

            $doc = new Document([
                'tbl_document_type_id' => 3,
                'tbl_branch_id' => auth()->user()?->branch_id,
                'createdby' => auth()->user()?->id,
                'document' =>  $fullName,
                'tbl_document_images_id' => $docType['id'],
                'tbl_customers_id' => 0,
                'content' =>  $fullName,
                'tbl_restrictions_id' => 1,
                'document_no' => 'P' . $refNo,
                'document_name' => "National ID",
                'policy_no' => $record->policy_no,
                'tbl_document_applications_id' => $record->id,
            ]);

            $doc->save();

            $this->scannedRequestLogs($record->id, $fullName, 'Uploaded a ' . $checklist->document_name . ' document', 3);
            return response([
                'status' => 'success',
            ], 200);
        }
    }

    public function verifyAccNumber(Request $request){
        // return $request;{"acc_number":"2030441568218","acc_bank_code":"FBL"}
        $client = new Client([
            'verify' => false,
        ]);

        $doc_appl_id = base64_decode($request->doc_appl_id);

        $response = $client->post('https://iverify.shrinqghana.com/api/external/verify-identity', [
            'headers' => [
                'x-configuration-id' => 'test_me_now',
                'accept' => 'application/json'
            ],
            'json' => [
                "channel" => "bank",
                "bank_code" => $request->acc_bank_code,
                "account_number" => $request->acc_number,
                "username" => "steve.ameyaw"

                // "channel" => "bank",
                // "bank_code" => 'STB',
                // "account_number" => '0150529281500',
                // "username" => "steve.ameyaw"
            ]
        ]);
        // return $response;
        // Get the response body
        $payload = $response->getBody()->getContents();
        // Decode the JSON payload
        $data = json_decode($payload, true);

        $status = '';
        $occurences = 0;

        if ($data !== null &&  $data['status'] == 'success') {
            $name1 = Session::get('id_name');
            $name2 = $data['name'];
            $occurences = $this->verifySameCustomer($name1, $name2);
            if($occurences <= 0){
                DocumentApplication::findOrFail($doc_appl_id)->update([
                    "flag_request" => 1,
                    "flag_comment" => "Credentials on Account number do not match that of the verified ID card"
                ]);
                $status = "failed";
            }else{
                $status = "success";
            }
        }

        return response([
            'data' => $data,
            'matched_status' => $status
        ], 200);

    }

    public function flagRequest(Request $request){
        // Retrieve the record based on the provided token
        $record = DocumentApplication::where('token', $request->token)->first();

        // Check if the record exists before proceeding
        if ($record) {
            // Initialize flag_comment if it's null
            $flag_comment = $record->flag_comment ?? '';

            // Append the new message to flag_comment
            $flag_comment .= $request->message;

            // Update the record with the new flag_request and flag_comment
            $record->update([
                "flag_request" => 1,
                "flag_comment" => $flag_comment
            ]);

            return response([
               'status' =>'success',
                'flag_comment' => $flag_comment
            ], 200);
        } else {
            // Handle the case where the record does not exist (e.g., return an error response)
            return response()->json(['error' => 'Record not found'], 404);
        }
    }

}
