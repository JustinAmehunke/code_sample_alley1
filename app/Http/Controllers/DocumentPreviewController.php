<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Models\DocumentProduct;
use App\Models\CompanyProfile;
use App\Http\Traits\GenerateDocument;
use App\Models\ShareDocumentLog;
use App\Models\DocumentChecklist;
use App\Models\MandateRequest;

use App\Models\User;
use App\Models\DocumentWorkflow;
use App\Http\CustomClasses\ApplicationStatusClass;
use App\Http\Traits\DocumentSysEngine;

class DocumentPreviewController extends Controller
{
    use GenerateDocument;
    use DocumentSysEngine;
    public function previewProposal($token)
    {
        $document_application = DocumentApplication::where('token', $token)->first();
        // return $document_application;
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        // return $document_application->id;
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        // return $templateData;
        $company_profile = CompanyProfile::where('deleted', 0)->first();

        $dataMap = $this->generateProposal($token);
        // return $dataMap;
        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $document_product->template);

        //Append T&C
        $previewContent .= $document_product->terms_and_conditions ;

        // Return the preview content
        return response()->json(['previewContent' => $previewContent]);
        // return view('document.preview')->with('previewContent', $previewContent);
    }

    public function previewMandate($token)
    {
        $document_application = DocumentApplication::where('token', $token)->first();
        // return $document_application;
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        // return $document_application->id;
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();

        $mandate = DocumentProduct::where([['template_link', '=' ,'mandate'], ['deleted', '=', 0]])->first();
        // return $templateData;
        $company_profile = CompanyProfile::where('deleted', 0)->first();

        // $mandate_filled = DocumentChecklist::where([
        //     ['tbl_document_applications_id', '=', $document_application->id],
        //     ['tbl_document_type_id', '=', 2]
        // ])->first();
        $mandate_filled = MandateRequest::where([
            ['tbl_document_applications_id', '=', $document_application->id],
            ['deleted', '=', 0]
        ])->first();

        if(!is_null($mandate_filled) && $mandate_filled->signopt > 0){
            $dataMap = $this->generateMandate($token, true);
        }else{
            $dataMap = $this->generateMandate($token, false);
        }
        // return $dataMap;
        // return $dataMap;
        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $mandate->template);

        // Return the preview content
        return response()->json(['previewContent' => $previewContent]);
        // return view('document.preview')->with('previewContent', $previewContent);
    }

    public function viewProposal(Request $request)
    {
        if($request->has('token')){
            $param_token = $request->query('token');
            $param_section = base64_decode($request->query('section'));

            $shareDocumentLog = ShareDocumentLog::where('token', $param_token)->first();
        //    return $shareDocumentLog;
            // $record = DocumentApplication::find($shareDocumentLog->tbl_document_applications_id);
            $document_application = DocumentApplication::find($shareDocumentLog->tbl_document_applications_id);
            // return $document_application;
            $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
            // return $document_application->id;
            //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
            $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
            // return $templateData;
            $company_profile = CompanyProfile::where('deleted', 0)->first();

            $dataMap = $this->generateProposal($document_application->token);
            // return $dataMap;
            // Replace placeholders with actual data
            $dynamicTitle = $document_product->product_name;
            $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $document_product->template);
        }else{
            $previewContent= null;
            $dynamicTitle = 'Request Not Found';
        }

        return view('documents-products.external.view-blueprint', compact('previewContent', 'dynamicTitle'));
    }

    public function termsAndConditions(){

    }

    public function fillProposal(Request $request){
        if($request->has('token') && !$request->has('mandate')){
            $param_token = $request->query('token');
            $param_section = base64_decode($request->query('section'));

            $record = DocumentApplication::where('token', $param_token)->first();
            $document_product = DocumentProduct::find($record->tbl_documents_products_id);
            $product_model =  $document_product->product_model;
            $dynamicTitle = $document_product->product_name;
            $mandate = '';
        }else if($request->has('token') && $request->has('mandate')){
            $param_token = $request->query('token');
            $param_section = base64_decode($request->query('section'));

            $record = DocumentApplication::where('token', $param_token)->first();
            $document_product = DocumentProduct::where([['template_link', '=' ,'mandate'], ['deleted', '=', 0]])->first();
            $product_model =  $document_product->product_model;
            $dynamicTitle = 'Mandate Form';
            $mandate = 'Mandate Request';
        }else{
            $record = null;
            $product_model = 'Request Not Found';
            $dynamicTitle = 'Request Not Found';
            $mandate = '';
        }

        return view('documents-products.request-blueprint', compact('record', 'product_model', 'dynamicTitle', 'mandate'));
    }

    public function fillMandate(Request $request){
        if($request->has('token') && $request->has('mandate')){
            $param_token = $request->query('token');
            $param_section = base64_decode($request->query('section'));

            $record = DocumentApplication::where('token', $param_token)->first();
            $document_product = DocumentProduct::where([['template_link', '=' ,'mandate'], ['deleted', '=', 0]])->first();
            $product_model =  $document_product->product_model;
            $dynamicTitle = 'Mandate Form';
            $mandate = 'Mandate Request';
        }else{
            $record = null;
            $product_model = 'Request Not Found';
            $dynamicTitle = 'Request Not Found';
            $mandate = '';
        }

        return view('documents-products.request-blueprint', compact('record', 'product_model', 'dynamicTitle', 'mandate'));
    }

    public function ationFromEmail(Request $request){
        $dynamicTitle = 'Action Document';
        return  view('documents-products.external.action-document', compact('dynamicTitle'));
    }

    public function viewFromEmail(Request $request){
        $dynamicTitle = 'View Document';
        return  view('documents-products.external.view-documents', compact('dynamicTitle'));
    }

    public function emailAction(Request $request)
    {
        $bypass = $request->input('bypass_check') === 'on' ? 1 : 0;
        $token = $request->input('token');
        $key = base64_decode($request->input('key'));
        $uid = base64_decode($request->input('uid'));
        $username = $request->input('username');
        $comments = $request->input('comments');
        $departments = $request->input('tbl_departments_id');
        $tbl_departments_id = $request->input('tbl_departments_id');

        $status = new ApplicationStatusClass(11, 16);
        $review_status = $status->getStatusbyEndpoint(3);
        $pending_status = $status->getStatusbyEndpoint(1);

        $user = User::where('email', strtolower(trim($username)))
                    ->orWhere('mobile', $username)
                    ->where('deleted', 0)
                    ->first();

        if ($user) {
            $record = DocumentApplication::where('token', $token)
                                        ->where('deleted', 0)
                                        ->first();

            if ($record) {
                $workflow = DocumentWorkflow::with('tbl_document_applications')->find($uid);

                if ($workflow->done_yn == 0) {
                    $validate = $this->validateAccessWorkflow($workflow, $user->id);
                    $log_action = '';
                    $message = '';
                    if ($validate > 0 || $record->createdby == $user->id) {

                        if ($workflow->tbl_document_setup_details['require_evidence'] > 0) {
                            $this->sendRequireEvidence($record->id, $uid);
                            $log_action = 'Sent Email to require Evidence';
                        }
                        
                        if ($key == 'approved') {
                            $log_action = 'Approved Request';
                        
                            $workflow->update([
                                'processed_date' => now(),
                                'processed_by' => $user->full_name,
                                'done_yn' => 1
                            ]);
                        
                           $message = ["status" => 'success', "message" => 'Request approved successfully'];
                        } elseif ($key == 'approved-review') {
                            $log_action = 'Approved Review done';
                            
                            // $obs = DocumentWorkflow::where([
                            //     'tbl_document_applications_id' => $record->id,
                            //     'tbl_document_setup_details.reference' => $workflow->reference,
                            //     'deleted' => 0
                            // ])->first();

                            $obs = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
                                ->whereHas('tbl_document_setup_details', function ($query) use ($departments) {
                                    $query->where('reference', $departments);
                                })
                                ->where('deleted', 0)
                                ->first();
                        
                            if ($obs) {
                                $obs->update([
                                    'attempt' => 0,
                                    'tbl_system_status_id' => 3,
                                    'tbl_workflow_status_id' => 11,
                                    'started_yn' => 0,
                                    'comments' => $comments,
                                    'reference' => $workflow->requester_review,
                                    'completed_yn' => 0,
                                    'done_yn' => 0
                                ]);
                            }
                        
                            $workflow->update([
                                'processed_date' => now(),
                                'processed_by' => $user->full_name,
                                'tbl_workflow_status_id' => 10,
                                'tbl_system_status_id' => 2,
                                'started_yn' => 1,
                                'completed_yn' => 0,
                                'done_yn' => 1
                            ]);
                        
                            $record->update([
                                'tbl_application_status_id' => $pending_status['id'],
                                'last_updated_date' => now()
                            ]);
                        
                            $message = ["status" => 'success', "message" => 'Request approved successfully'];
                        }else if ($key == 'declined') {
                            
                            $workflow->update([
                                'processed_date' => now(),
                                'processed_by' => $user->full_name,
                                'comments' => $comments,
                                'done_yn' => 1
                            ]);
                        
                            $log_action = 'Declined Request';
                            $message = ["status" => 'success', "message" => 'Request Declined successfully'];
                        } elseif ($key == 'reviewed') {
                            $req_rev = ($workflow->tbl_document_setup_details['reference'] > 0) ? $workflow->tbl_document_setup_details['reference'] : 0;
                        
                            if ($departments == 0) {
                                $review_type = 'CUSTOMER';
                            } elseif ($departments == -1) {
                                $review_type = 'ORIG-CUSTOMER';
                            } else {
                                $review_type = 'DEPARTMENT';
                            }
                        
                            $log_action = 'Sent Request for Review';
                        
                            $x_arr = [
                                'processed_date' => now(),
                                'processed_by' => $user->full_name,
                                'comments' => $comments,
                                'reference' => $departments,
                                'requester_review' => $req_rev,
                                'tbl_system_status_id' => 1,
                                'started_yn' => 0,
                                'tbl_workflow_status_id' => 8,
                                'attempt' => 0,
                                'review_type' => $review_type
                            ];
                        
                            if ($tbl_departments_id == 0) {
                                $x_arr['done_yn'] = 0;
                            } else {
                                $x_arr['done_yn'] = 1;
                            }
                        
                            $workflow->update($x_arr);
                        
                            $this->sendDocumentReview($record->id, $departments, $uid);
                        
                            // $obs = DocumentWorkflow::where([
                            //     'tbl_document_applications_id' => $record->id,
                            //     'tbl_document_setup_details.reference' => $departments,
                            //     'deleted' => 0
                            // ])->first();

                            $obs = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
                                ->where('deleted', 0)
                                ->whereHas('tbl_document_setup_details', function ($query) use ($departments) {
                                    $query->where('reference', $departments);
                                })
                                ->first();
                        
                            if ($obs) {
                                $obs->update([
                                    'tbl_workflow_status_id' => 9,
                                    'tbl_system_status_id' => 2,
                                    'attempt' => 0,
                                    'reviewed_department' => $departments,
                                    'started_yn' => 1,
                                    'completed_yn' => 0,
                                    'done_yn' => 0
                                ]);
                            }
                        
                            $record->update([
                                'tbl_application_status_id' => $review_status['id'],
                                'last_updated_date' => now()
                            ]);
                        
                            $message = ["status" => 'success', "message" => 'Request Sent for review successfully'];
                        }

                        if (!empty($comments)) {
                            $this->passComments($record->id, $user->id, $log_action, $comments);
                        }
                        
                        $this->scannedRequestLogs($record->id, $user->full_name, $log_action, $workflow->tbl_document_workflow_type_id);
                        $this->WorkflowActionDocuments($key, $record->id, $uid);
                        $this->passWorkflowDocuments($record->id, $bypass);

                        return response([
                            "message" => $message
                        ], 200);
                        
                    } else {
                        $message = ["status" => 'danger', "message" => 'You are not authorized to access update request'];
                        return response([
                            "message" => $message
                        ], 400);
                        // return redirect()->route('action-document', ['key' => $request->query('key'), 'token' => $request->query('token'), 'uid' => $request->query('uid'), 'auth' => 0]);
                    }
                } else {
                    $message = ["status" => 'danger', "message" => 'Request has already been processed'];
                    return response([
                        "message" => $message
                    ], 400);
                }
            } else {
                $message = ["status" => 'danger', "message" => 'You are not authorized to access this request'];
                return response([
                    "message" => $message
                ], 400);
            }
        } else {
            $message = ["status" => 'danger', "message" => 'Your username and password are invalid. Please try again'];
            return response([
                "message" => $message
            ], 400);
            // return redirect()->route('action-document', ['key' => $request->query('key'), 'token' => $request->query('token'), 'uid' => $request->query('uid'), 'auth' => 1]);
        }
    }

}
