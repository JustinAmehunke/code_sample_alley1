<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\CustomClasses\ApplicationStatusClass;
// use GuzzleHttp\Client;
use App\Models\DocumentWorkflow;
use App\Models\DocumentApplication;
use DB;
use App\Http\Traits\CodeGenerator;
use App\Http\Traits\DocumentSysEngine;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DocumentChecklist;
use App\Models\DocumentProduct;
use Auth;
//~ documents products
use App\Models\Educator;
use App\Models\DeathClaim;
use App\Models\ClaimRequest;
use App\Models\TermAssurance;
use App\Models\MandateRequest;
use App\Models\PersonalAccident;
use App\Models\RefundRequest;
use App\Models\Sip;
use App\Models\Tpp;
use App\Models\Transition;
use App\Models\Ti;
use App\Models\Keyman;
use App\Models\FidoSip;
use App\Models\CorporateDueDiligence;
use App\Models\DocumentDigitalForm;
use Illuminate\Support\Str;
//~

class DocumentApplicationsController extends Controller
{
    use CodeGenerator;
    use DocumentSysEngine;

    public function saveCustomerInfo(Request $request){

        $token = md5($request->email_address) . date('dmYHis');
        $status = new ApplicationStatusClass(11, 16);
        $get_status = $status->getStatusbyEndpoint(27);

        // return $get_status;

        $vals = array(
            'tbl_documents_products_id' => $request->tbl_documents_products_id,
            'customer_name' => $request->customer_name,
            'mobile_no' => $request->customer_mobile_no,
            'sms' => $request->customer_mobile_no,
            'email' => $request->email_address,
            'token' =>  $token,
            'source' => 'STAK',
            'tbl_application_status_id' => $get_status['id'],
            'tbl_branch_id' => Auth()->user()->default_branch
        );

        //
        $doc_setup = $this->getDocumentWorkflowByProduct($request->tbl_documents_products_id);

        if ($request->id == 0) {
            $documentApplication = DocumentApplication::where($vals)->first();

            if (!$documentApplication) {
                $vals['request_no'] = $this->generateCode("product_request", $vals);
                $vals['createdby'] = Auth()->id();
                $vals['last_updated_date'] = Carbon::now();
                $vals['tbl_users_id'] = Auth()->id();

                if ($request->policy_no == 'Auto Generated') {
                    $policyNo = $this->generatePolicyNo($request->tbl_documents_products_id, 'STAK');
                } else {
                    $policyNo = $request->policy_no;
                }

                $vals['policy_no'] = $policyNo;
                $vals['new_app_request'] = 1;
                
                // return $vals;

                $documentApplication = DocumentApplication::create($vals);

                // $freshDocumentApplication = $documentApplication->fresh();

                // $get_pr = DocumentProduct::where('tbl_documents_products_id', $request->tbl_documents_products_id)->first();
                $get_pr = DocumentProduct::find($request->tbl_documents_products_id);
                if($get_pr->product_name != 'MANDATE REQUEST'){
                    $mand['tbl_document_applications_id'] = $documentApplication->id;
                    $mand['deleted'] = 0;
                    MandateRequest::create($mand);
                }
                // return $get_pr->product_table;

                $exists = $get_pr->product_model::where('tbl_document_applications_id', $documentApplication->id)->first();

                if (!$exists) {
                   $rr =  $get_pr->product_model::create(['tbl_document_applications_id' => $documentApplication->id]);
                }


                $this->initiateCheckList($doc_setup, $documentApplication->id);

                // return '1_ '.$documentApplication->policy_no;

                $message = [ 'type' => 'success',  'class'=>'success',  'message' => "Product Request {$vals['policy_no']} created successfully"];
            } else {
                $message = [ 'type' => 'success', 'class'=>'success', 'message' => "Product Request {$documentApplication->policy_no} updated successfully"];
                $documentApplication->update($vals);
                // $freshDocumentApplication = $documentApplication->fresh();
                // return $vals;
                // return '2_ '.$documentApplication->policy_no;
            }
        } else {
            $documentApplication = DocumentApplication::findOrFail($request->id);
            $message = [ 'type' => 'success', 'class'=>'success', 'message' => "Product Request {$documentApplication->policy_no} updated successfully"];
            $documentApplication->update($vals);
            // $freshDocumentApplication = $documentApplication->fresh();
            // return '3_ '.$documentApplication->policy_no;
        }

        $this->scannedRequestLogs($documentApplication->id, Auth()->user()->full_name, 'Initiated Proposal Request', 3);

        $this->generateDocumentWorkflow($documentApplication->id);
        $result =  $this->checkCheckList($documentApplication->id, $doc_setup);
        //    return $result;

        // if (Auth()->user()->override_status_yn > 0) {
        //     $documentApplication->update(['tbl_application_status_id' => $request->overrideStatus]);
        // }

        // redirect(route('product-checklist', ['section' => base64_encode('Checklist'), 'id' => base64_encode($documentApplication->id)]))->with('message', $message);

        return redirect()->route('product-checklist', ['section' => base64_encode('Checklist'), 'id' => base64_encode($documentApplication->id)])->with('message', $message);

    }

    function saveCheckListDocuments(Request $request)
    {
        $record_id = $request->record_id;
        $id = $request->id;
        $xx_id = $request->xx_id;
        $tbl_document_checklist_id  = $request->tbl_document_checklist_id;
        $tbl_document_type_id  = $request->tbl_document_type_id;
        $proposal_mode  = $request->proposal_mode ;
        $proposal_image  = $request->proposal_image;
        $notification_type = $request->notification_type;
        $sms  = $request->sms;
        $email  = $request->email;
        $bntsubmitChecklist  = $request->bntsubmitChecklist;
        $proposal_file  = $request->proposal_file;
        // return isset(request()->hasFile('proposal_file')[0]);

        // return $request;
        // After all inputs are validated and sanitized 
        $record = DocumentApplication::findOrFail($record_id);
        $ref_no = 'ST-' . now()->format('dmYHis');
        $target_dir = 'documents/';
        
       if(isset($proposal_mode) && sizeof($proposal_mode) > 0){
       
        for ($i = 0; $i < sizeof($proposal_mode); $i++) {
            $doc_setup = $this->getDocumentWorkflowByProduct($record->tbl_documents_products_id);
            $checklist = DocumentChecklist::findOrFail($tbl_document_checklist_id[$i]);

            $calc_attempt = $checklist->attempt + 1;

            $check_list_arr = [
                'mode' => $proposal_mode[$i] > 0 ? $proposal_mode[$i] : 0,
                'notification_type' => $notification_type[$i],
                'sms' => $sms[$i],
                'email' => $email[$i]
            ];

            $checklist->update($check_list_arr);

            if ($proposal_mode[$i] == 3) {
             
                if ($notification_type[$i] == 'SMS'  ) { // && $checklist->notification_type != $notification_type[$i]
                   
                    $product = DocumentProduct::findOrFail($record->tbl_documents_products_id);

                    if ($checklist->tbl_document_type_id == 1) {
                        $message = "Dear Customer,\nKindly find below, the link to our {$product->product_name} digital request: ";
                        $message .= url("/document/external/fill/proposal?token={$record->token}");
                    } elseif ($checklist->tbl_document_type_id == 2) {
                        $mandate_form = DocumentProduct::where('template_link', 'mandate')->where('deleted', 0)->first();
                        $message = "Dear Customer,\nKindly find below, the link to our {$mandate_form->product_name} digital request:";
                        $message .= url("/document/external/fill/mandate?token={$record->token}&mandate=mandate");
                    } else {
                        $doc = DocumentType::findOrFail($checklist->tbl_document_type_id);

                        $url = $doc->require_camera > 0 ?
                            url("/document/custom-request-camera-product?token={$record->token}&pid=" . base64_encode($checklist->id)) :
                            url("/document/custom-request-product?token={$record->token}&pid=" . base64_encode($checklist->id));

                        $message = "Dear Customer,\nA {$doc->document_name} has been requested for processing of your {$product->product_name} request: ";
                        $message .= $url;
                    }
                    $this->sendSMS( '0' . substr($sms[$i], -9), $message, 'OLDMUTUAL');
                }
                
                if ($notification_type[$i] == 'EMAIL'  ) { //&& $checklist->notification_type != $notification_type[$i]
                    
                    $doc = DocumentType::findOrFail($checklist->tbl_document_type_id);

                    $url = $doc->require_camera > 0 ?
                        url("/document/custom-request-camera-product?token={$record->token}&pid=" . base64_encode($checklist->id)) :
                        url("/document/custom-request-product?token={$record->token}&pid=" . base64_encode($checklist->id));
                   
                    if ($checklist->tbl_document_type_id == 1) {
                        $this->sendProductRequest($record->id, $email[$i]);
                    } elseif ($checklist->tbl_document_type_id == 2) {
                        $url = url("/document/external/fill/mandate?token={$record->token}&mandate=mandate");
                        $this->sendRequestDocumentsCustomProducts($record->id, $url, $checklist->id);
                    } else {
                        $this->sendRequestDocumentsCustomProducts($record->id, $url, $checklist->id);
                    }
                }

                $checklist->update(['attempt' => $calc_attempt]);
            }

            if($proposal_mode[$i] == 2){
                if (request()->hasFile('proposal_file')) {
                    $files = request()->file('proposal_file');
                    if (isset($files[$i]) && $files[$i]->isValid()) {
                            $uploaded_image = '';
                            $content = '';
                            $file = request()->file('proposal_file')[$i];
                            $extension = $file->getClientOriginalExtension();
                            $uploadOk = 1;
                            $new_filename = '';

                            // Generate a unique filename using UUID
                            $uuid = Str::uuid()->toString();
                            $fullName = 'uploaded-' . $uuid . '.' . $extension;
                            $targetDir = 'documents/';

                            // Store the file with the unique filename on S3
                            $status = Storage::disk('s3')->put($targetDir . $fullName, file_get_contents($file));

                            $doc_type = $this->getImageDoc($file->getMimeType());
            
                            $doc_arr = [
                                'tbl_document_type_id' => $tbl_document_type_id[$i],
                                'tbl_branch_id' => Auth()->user()->default_branch,
                                'createdby' => Auth()->id(),
                                'document' => $fullName,
                                'tbl_document_images_id' => $doc_type['id'],
                                'tbl_customers_id' => 0,
                                'content' => $content,
                                'tbl_restrictions_id' => 1,
                                'document_no' => 'P' . $ref_no,
                                'document_name' => 'P' . $ref_no,
                                'policy_no' => $record->policy_no,
                                'tbl_document_applications_id' => $record->id,
                            ];
                            
                            $doc_result = Document::create($doc_arr);
                            
                            $this->scannedRequestLogs($record->id, Auth()->user()->full_name, 'Attached ' . $checklist->tbl_document_type->document_name, 3);
                            $this->updateCheckList($record->id, $tbl_document_type_id[$i], 'PASSED', $checklist->tbl_document_type->document_name . 'form attached to request');
                            
                        // }
                    } else {
                        // Handle the case where the file at index $i is not valid or does not exist
                    }
                } else {
                    // Handle the case where no files are uploaded
                }
               
            }

          

            if (!empty($proposal_image[$i])) {
                $doc_type = 4;
            
                $doc_arr = [
                    'tbl_document_type_id' => $tbl_document_type_id[$i],
                    'tbl_branch_id' => Auth()->user()->default_branch,
                    'createdby' => Auth()->id(),
                    'document' => 'documents/' . $proposal_image[$i],
                    'tbl_document_images_id' => $doc_type,
                    'tbl_customers_id' => 0,
                    'content' => 'documents/' . $proposal_image[$i],
                    'tbl_restrictions_id' => 1,
                    'document_no' => 'P' . $ref_no,
                    'document_name' => 'P' . $ref_no,
                    'policy_no' => $record->policy_no,
                    'tbl_document_applications_id' => $record->id,
                ];
            
                $doc_result = Document::create($doc_arr);
            
                $this->scannedRequestLogs($record->id, Auth()->user()->full_name, 'Captured ' . $checklist->tbl_document_type->document_name . ' Form', 3);
                $this->updateCheckList($record->id, $tbl_document_type_id[$i], 'PASSED', $checklist->tbl_document_type->document_name . ' Form captured');
            }
        }
       }else{
        return redirect()->back()->with('message', ['type' => 'Error', 'class' => 'danger', 'message' => 'Please select at least one document type']);
       }

        $chk = Document::where('tbl_document_applications_id', $record->id)->where('tbl_document_type_id', 1)->count();

        if ($chk > 0) {
            $record->update(['form_filled' => 1, 'last_updated_date' => now()]);
        }

        $this->checkCheckList($record->id, $doc_setup);
        $message = [ 'type' => 'success', 'class'=>'success', 'message' => "Request processed successfully"];

        return redirect()->route('request-profile', ['section' => base64_encode('Request-Profile'), 'id' => base64_encode($record_id)])->with('message', $message);
    }
    
    // public function saveAttachedDocuments(Request $request){
    //     return $request;
    // }


    function saveAttachedDocuments(Request $request)
    {
        
        // Assuming there is a DocumentApplication model for 'tbl_document_applications' table
        $record = DocumentApplication::find($request->id);
        $fullname = Auth()->user()->firstname.' '.Auth()->user()->lastname;

        $cnt = count(request()->file('file'));

        for ($i = 0; $i < $cnt; $i++) {
            $isSignature = in_array(request('tbl_document_type_id')[$i], [19, 26]);

            $targetDir = $isSignature ? 'documents/signatures/' : 'documents/';

            $refNo = 'ST-' . now()->format('dmYHis');

            $uploadedFile = request()->file('file')[$i];
            // return  $uploadedFile->getClientOriginalName();
            // $newFilename = $this->generateUniqueFilename($targetDir, $uploadedFile->getClientOriginalName());
            
            // $fileLocation = $this->saveFileOnS3($targetDir . $newFilename, $uploadedFile);
            //
                 // Handle file upload
                //  $uploadedFile = $request->file('sign_img');
                 // Assuming $uploadedFile contains the uploaded file instance
                 $extension = $uploadedFile->getClientOriginalExtension(); // Get the original extension
         
                 // Generate a unique filename using UUID
                 $uuid = Str::uuid()->toString();
         
                 // Concatenate the UUID and extension to create the unique filename
                 $fullName = 'uploaded-'.$uuid . '.' . $extension;
                //  $targetDir = 'signatures/';
                 // Store the file with the unique filename on S3
                 $status  =  Storage::disk('s3')->put($targetDir . $fullName, file_get_contents($uploadedFile));

            //

            $docType = $this->getImageDoc($uploadedFile->getMimeType());
            $checklist = DocumentType::find(request('tbl_document_type_id')[$i]);

            $doc = new Document([
                'tbl_document_type_id' => request('tbl_document_type_id')[$i],
                'tbl_branch_id' => Auth::user()->branch_id,
                'createdby' => Auth()->id(),
                'document' => $fullName,
                'tbl_document_images_id' => $docType['id'],
                'tbl_customers_id' => 0,
                'content' => $fullName,
                'tbl_restrictions_id' => 1,
                'document_no' => 'P' . $refNo,
                'document_name' => $request->reference_name[$i],
                'policy_no' => $request->policy_no,
                'tbl_document_applications_id' => $request->id,
            ]);

            $doc->save();

            $this->scannedRequestLogs($request->id, $fullname, 'Uploaded a ' . $checklist->document_name . ' document', 3);

            if (request('tbl_document_type_id')[$i] == 1) {
                $record->update(['form_filled' => 1]);
            }

            // Check if it's a signature and update accordingly
            // In this section we will update accordin to the new logic
            if (request('tbl_document_type_id')[$i] == 26) {
                $getM = DocumentApplication::find($id);

                if (in_array($getM->tbl_application_status_id, [75, 66, 71])) {
                    DocumentDigitalForm::where([
                        'tbl_document_applications_id' => $getM->id,
                        'rec_name' => 'signopt',
                    ])->delete();

                    DocumentDigitalForm::where([
                        'tbl_document_applications_id' => $getM->id,
                        'rec_name' => 'uploaded_signature',
                    ])->delete();

                    DocumentDigitalForm::create([
                        'tbl_document_applications_id' => $getM->id,
                        'rec_name' => 'signopt',
                        'rec_value' => 1,
                    ]);

                    DocumentDigitalForm::create([
                        'tbl_document_applications_id' => $getM->id,
                        'rec_name' => 'uploaded_signature',
                        'rec_value' => $newFilename,
                    ]);
                }
            }
        }

        $docSetup = $this->getDocumentWorkflowByProduct($record->tbl_documents_products_id);
        $this->checkCheckList($record->id, $docSetup);

        // set message 
        $message = [ 'type' => 'success', 'class'=>'success',  'message' => "Documents uploaded on STAK successfully"];

        return redirect()->route('attached-documents', ['section' => base64_encode('attached-documents'), 'id' => base64_encode($record->id)])->with('message', $message);
    }

   


}
