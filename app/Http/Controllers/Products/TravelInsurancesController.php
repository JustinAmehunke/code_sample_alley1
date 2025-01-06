<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\DocumentApplication;
use App\Models\DocumentChecklist;
use App\Models\Ti;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\DocumentSysEngine;
use Illuminate\Support\Str;
use DB;

class TravelInsurancesController extends Controller
{
    use DocumentSysEngine;
    public function saveOrUpdate(Request $request){

        // DB::beginTransaction();
        
        if (isset($request->doc_appl_id) && $request->doc_appl_id !== null){
            //UPDATE
            $document_application = DocumentApplication::find($request->doc_appl_id);
           
            if($document_application !== null){
                try {

                    $doc_id = $document_application->id;
        
                    $customizedFields = [];
                    $beneficiaryFields = [];
                    $coverFields = [];
                    $excludeFields = ['_token', 'doc_appl_id', 'age' , 'rec_id', 'product_name', 'flexdatalist-occupation', 'flexdatalist-empoyment_occupation', 'flexdatalist-empoyment_employer', 'flexdatalist-agent_code', 'flexdatalist-employer', 'product_beneficiary','product_cover','product_signature'];

                    if(isset($request->product_name)){
                        foreach ($request->all() as $field => $value) {
                            // Check if the field is not empty in the request
                            if(!in_array($field, $excludeFields)){
                                if (!empty($value)) {
                                    $customizedFields[$field] = $value;
                                } else {
                                    // If the field is not present in the request or empty, retain the existing value from the database
                                    $customizedFields[$field] = $document_application->$field;
                                }
                            }
                            
                        }

                        Ti::where('tbl_document_applications_id', $doc_id)->update($customizedFields);
                    }

                    if(isset($request->product_beneficiary)){

                        $beneficiaryFields = array_map(function ($field) {
                            return str_replace('beneficiary_', '', $field);
                        }, array_keys($request->all()));

                        //delete previous
                        Beneficiary::where('tbl_document_applications_id', $doc_id)->delete();
                        foreach ($request->beneficiary_full_name as $key => $fullName) {
                            // Create a new Beneficiary instance
                            $beneficiary = new Beneficiary();
                        
                            // Assign beneficiary details from form data
                            foreach ($beneficiaryFields as $field) {
                                if(!in_array($field, $excludeFields)){
                                    // Check if the field exists in the form data
                                    if (isset($request["beneficiary_$field"][$key ])) {
                                        $beneficiary->$field = $request["beneficiary_$field"][$key ];
                                    }
                                }
                            }
                            //add custom fields
                            $beneficiary->tbl_document_applications_id = $doc_id;
                            $beneficiary->created_by = auth()->user()?->id;
                            $beneficiary->created_at = Carbon::now();

                            // Save the beneficiary record
                            $beneficiary->save();
                        }
                    }

                    if(isset($request->product_signature)){
                        //update agent how_did_you_hear
                        if($request->how_did_you_hear == "Self-Discovery"){
                            Ti::where('tbl_document_applications_id', $doc_id)->update([
                                'how_did_you_hear' => $request->how_did_you_hear,
                                'signopt' => $request->signopt
                            ]);
                        }else{
                            Ti::where('tbl_document_applications_id', $doc_id)->update([
                                'how_did_you_hear' => $request->how_did_you_hear,
                                'agent_code' => $request->agent_code,
                                'signopt' => $request->signopt
                            ]);
                        }

                        //Continue with signature logic
                        if($request->signopt == 1){ //signed
                            if (empty($rec['signature_file'])) {
                                // Extract the mime type and base64 image data
                                list($type, $data) = explode(';', $request->final_signature_base64_image_svg);
                                list(, $data)      = explode(',', $data);

                                // Decode the base64 data into binary
                                $imageData = base64_decode($data);
                                
                                // Generate a unique filename using UUID
                                $uuid = Str::uuid()->toString();
                            
                                // Concatenate the UUID and extension to create the unique filename
                                $fullName = 'signed-'.$uuid . '.' . '.png';
                                $targetDir = 'signatures/';
                                // Store the file with the unique filename on S3
                                $status  =  Storage::disk('s3')->put($targetDir . $fullName, $imageData);
                                //
                                Ti::where('tbl_document_applications_id', $doc_id)->update([
                                    'signature_file' => $fullName,
                                ]);
                            }
                        }

                        if($request->signopt == 2){ //upload
                            if (empty($rec['uploaded_signature'])) {
                                if ($request->hasFile('sign_img')) {
                                    // Handle file upload
                                    $uploadedFile = $request->file('sign_img');
                                    // Assuming $uploadedFile contains the uploaded file instance
                                    $extension = $uploadedFile->getClientOriginalExtension(); // Get the original extension
                            
                                    // Generate a unique filename using UUID
                                    $uuid = Str::uuid()->toString();
                            
                                    // Concatenate the UUID and extension to create the unique filename
                                    $fullName = 'uploaded-'.$uuid . '.' . $extension;
                                    $targetDir = 'signatures/';
                                    // Store the file with the unique filename on S3
                                    $status  =  Storage::disk('s3')->put($targetDir . $fullName, file_get_contents($uploadedFile));
                                    Ti::where('tbl_document_applications_id', $doc_id)->update([
                                        // 'signature_option' => $request->signopt,
                                        'uploaded_signature' => $fullName,
                                    ]);
                                }
                            }
                        }

                        $filled = Ti::where('tbl_document_applications_id', $doc_id)->first();
                        //If there is the agent code, mark Mandate as filled
                        if($filled->agent_code){
                            DocumentChecklist::where([
                                ['tbl_document_applications_id', '=', $doc_id],
                                ['tbl_document_type_id', '=', 2]
                            ])->update(['form_filled' => 1]);
                        }
                        //Mark the proposal as filled
                        DocumentChecklist::where([
                            ['tbl_document_applications_id', '=', $doc_id],
                            ['tbl_document_type_id', '=', 1]
                        ])->update(['form_filled' => 1]);

                        //Up on completion logics 
                        DocumentApplication::findOrFail($document_application->id)->update(['form_filled' => 1, 'tbl_application_status_id' => 66]);
                        $this->checkCheckList($document_application->id, -1);
 
                    }



                    // DB::commit();
                    
                    return response( [
                        'status' => 'success',
                        'token' => $document_application->token,
                        'message' => 'Changes updated successfully',
                    ], 200);

                } catch (\Throwable $th) {
                    DB::rollBack();
        
                    // throw $th;
                    return response( [
                        'status' => 'failed',
                        'error'=> $th->getMessage(),
                        'message' => 'Something went wrong',
                    ], 200);
                }
            }else{
                return response( [
                    'status' => 'failed',
                    'error'=> $th->getMessage(),
                    'message' => 'Something went wrong. Record not found.',
                ], 200);
            }
           
          
        }else{
            return response( [
                'status' => 'failed',
                'error'=> 'Unknown record!',
                'message' => 'Something went wrong. Record not found.',
            ], 200);
        }
    }
}
