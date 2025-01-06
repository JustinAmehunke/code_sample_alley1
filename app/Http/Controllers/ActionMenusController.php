<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Models\DocumentApplicationsLog;
use App\Models\CompanyProfile;

use App\Http\Traits\DocumentSysEngine;
use App\Models\User;
use App\Models\ShareDocumentLog;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use App\Models\DocumentWorkflow;
use App\Models\Department;
use App\Http\CustomClasses\ApplicationStatusClass;

use App\Models\WorkflowStatus; 
use App\Models\DocumentWorkflowType; 
// use App\Helpers\ApplicationStatus; 

use App\Models\Document;
use App\Models\DocumentChecklist;
use App\Models\PayinRequest;
use App\Models\DocumentApplicationsComment;

use Illuminate\Support\Facades\View;



class ActionMenusController extends Controller
{
    use DocumentSysEngine;
    public function sharePage($id){
        $id = base64_decode($id);
        $document_application = DocumentApplication::find($id);

        return view('product-requests.action-menu-pages.share', compact('document_application'));
    }
    public function auditPage($id){
        $id = base64_decode($id);
        $records = DocumentApplicationsLog::with('tbl_document_applications')->with('tbl_document_workflow_type')
        ->where([['tbl_document_applications_id', '=', $id],['deleted', '=',  0]])->orderBy('id', 'DESC')->get();

        $company = CompanyProfile::where('deleted', 0)->first();
        return view('product-requests.action-menu-pages.audit', compact('records', 'company'));  
    }
    public function multipleActionsPage(Request $request){
        $id = $request->id;
        $rec = $request->rec;

        $record = DocumentApplication::with('tbl_users')->find($id);
        
        $company = CompanyProfile::where('deleted', 0)->first();

        return view('product-requests.action-menu-pages.multiple-actions', compact('record', 'company', 'rec', 'id'));  
    }
    public function digitalFormPage(Request $request){
        
    }

    public function shareSend(Request $request){
        // return $request;
       
        // Extract data from the request
        $data = $request->only(['delivery', 'doc_type', 'to', 'cc', 'phone_no', 'name', 'id']);

        // Generate token
        $token = Str::upper(md5(now()->format('dmYHis')));

        // Get user details
        $user = User::find(Auth::user()->id);
        $full_name = $user->firstname . ' ' . $user->lastname;
        // Get document application details
        $rec = DocumentApplication::find($data['id']);

        // Create array for insertion
        $arr = [
            'tbl_users_id' => $user->id,
            'token' => $token,
            'delivery' => $data['delivery'],
            'tbl_documents_products_id' => $rec->tbl_documents_products_id,
            'tbl_document_type_id' => $data['doc_type'],
            'name' => $data['name'],
            'tbl_document_applications_id' => $data['id']
        ];

        // Generate URLs
        $url = url('document/external/view/proposal?token='. $token);
        $tandc_url = url('document/external/terms-and-conditions?token='.$token);

        // Perform actions based on delivery method
        if ($data['delivery'] == 'EMAIL') {
            $arr['email_to'] = $data['to'];
            // $arr['email_cc'] = $data['cc'];
            $shareDocumentLog = ShareDocumentLog::create($arr);

            $this->shareDocumentViaEmail($shareDocumentLog->id, $url);

        } elseif ($data['delivery'] == 'SMS') {
            $arr['phone_no'] = $data['phone_no'];
            $shareDocumentLog = ShareDocumentLog::create($arr);

            $message = "";
            if ($data['doc_type'] == 1) {
                $message = "{$full_name} has shared a Proposal Form for {$rec->tbl_documents_products->product_name} with Policy Number {$rec->policy_no} to you. Click on URL to access document: {$url} . Find terms and conditions below: {$tandc_url}";
            } elseif ($data['doc_type'] == 2) {
                $message = "{$full_name} has shared a Mandate Form for {$rec->tbl_documents_products->product_name} with Policy Number {$rec->policy_no} to you. Click on URL to access document: {$url} . Find terms and conditions below: {$tandc_url}";
            } elseif ($data['doc_type'] == 3) {
                $message = "{$full_name} has shared Terms and conditions for {$rec->tbl_documents_products->product_name} with Policy Number {$rec->policy_no} to you. Click on URL to access terms and conditions below: {$tandc_url}";
            }

            // $this->sendSMS('OLDMUTUAL', '0' . substr($data['phone_no'], -9), $message);
            $to = $data['phone_no'];
            $sender = 'OLDMUTUAL';

            $this->sendSMS($to, $message, $sender);
            $this->logDocumentsTrail($data['id'], $full_name, 'Shared Document via SMS', strtoupper($data['name']));
        }
 
        return response(['message' => 'Document shared successfully'], 200);
    }

    public function shareRender(Request $request){
        $type = base64_decode($request->query('type'));
        $token = $request->query('token');
        return $type."_ _".$token;
    }

    public function shareTandc(Request $request){
        $type = base64_decode($request->query('type'));
        $token = $request->query('token');
        return $type."_ _".$token;
    }

    public function saveMultipleActionsAction(Request $request){

        $bypass = ($request->has('bypass_check') && $request->bypass_check == 'on') ? 1 : 0;

        $key = $request->action;
        $id = $request->id;
        $comments = $request->comments;
        $departments = $request->tbl_departments_id;

        $status = new ApplicationStatusClass(11, 16);
        $review_status = $status->getStatusbyEndpoint(3);
        $pending_status = $status->getStatusbyEndpoint(1);
        $record = DocumentApplication::find($id);
        $workflow = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
            // ->where('started_yn', 1)
            // ->where('completed_yn', 0)
            ->first();
        
        if($workflow){
            $validate = $this->validateAccessWorkflow($workflow, Auth::user()->id);

            if ($validate > 0) {
                $user = Auth::user();
                if ($record) {
                    if ($workflow->tbl_document_setup_details['require_evidence'] > 0) {
                        $this->sendRequireEvidence($record->id, $uid);
                        $log_action = 'Sent Email to require Evidence';
                    }
                    if ($key == 'APPROVE') {
                        $pass_key = 'approved';
                        $log_action = 'Approved Request';
    
                        $workflow->update([
                            'processed_date' => now(),
                            'processed_by' => $user->full_name,
                            'comments' => $comments
                        ]);
    
                        $message = ['status' => 'success','message' => 'Request approved successfully'];
    
                    } elseif ($key == 'approved-review') {
                        $pass_key = 'approved-review';
                        $log_action = 'Approved Review done';
    
                        $obs = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
                            ->where('tbl_document_setup_details.reference', $workflow['reference'])
                            ->where('tbl_document_workflow.deleted', 0)
                            ->first();
    
                        if ($obs) {
                            $obs->update([
                                'attempt' => 0,
                                'tbl_system_status_id' => 3,
                                'tbl_workflow_status_id' => 11,
                                'started_yn' => 0,
                                'comments' => $comments,
                                'reference' =>  $workflow->requester_review,
                                'completed_yn' => 0,
                                'attempt' => 0
                            ]);
                        }
    
                        $workflow->update([
                            'processed_date' => now(),
                            'processed_by' => $user->full_name,
                            'tbl_workflow_status_id' => 10,
                            'tbl_system_status_id' => 2,
                            'started_yn' => 1,
                            'completed_yn' => 0
                        ]);
                        $record->update(['tbl_application_status_id' => $pending_status->id]);
    
                        $message = ['status' => 'success','message' => 'Request approved successfully'];
                    } elseif ($key == 'DECLINE') {
                        $pass_key = 'declined';
                        $workflow->update([
                            'processed_date' => now(),
                            'processed_by' => $user->full_name,
                            'comments' => $comments
                        ]);
                        $log_action = 'Declined Request';
                       
                        $message = ['status' => 'success','message' => 'Request Declined successfully'];
                    } elseif ($key == 'REVIEW') {
                        $req_rev = ($workflow->tbl_document_setup_details['reference'] > 0) ? $workflow->tbl_document_setup_details['reference'] : 0;
                        if ($departments == 0) {
                            $review_type = 'CUSTOMER';
                        } elseif ($departments == -1) {
                            $review_type = 'ORIG-CUSTOMER';
                        } else {
                            $review_type = 'DEPARTMENT';
                        }
                        $log_action = 'Sent Request for Review';
    
                        $workflow->update([
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
                        ]);
                        $this->sendDocumentReview($record->id, $departments, $workflow->id);
    
                        $obs = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
                            ->where('tbl_document_setup_details.reference', $departments)
                            ->where('tbl_document_workflow.deleted', 0)
                            ->first();
    
                        if ($obs) {
                            $obs->update([
                                'tbl_workflow_status_id' => 9,
                                'tbl_system_status_id' => 2,
                                'attempt' => 0,
                                'reviewed_department' => $departments,
                                'started_yn' => 1,
                                'completed_yn' => 0
                            ]);
                        }
                        $record->update(['tbl_application_status_id' => $review_status->id]);
    
                        $message = ['status' => 'success','message' => 'Request Sent for review successfully'];
                    }
                    if ($comments <> '') {
                        $this->passComments($id, $user->id, $log_action, $comments);
                    }
    
                    $this->scannedRequestLogs($record->id, $user->full_name, $log_action, $workflow->tbl_document_workflow_type_id);
                    $this->WorkflowActionDocuments($pass_key, $record->id, $workflow->id);
                    $this->passWorkflowDocuments($record->id, $bypass);
    
                    return response($message, 200);
                } else {
                    return response(['status' => 'error','message' => 'You are not authorised to access this request'], 200);
                }
            } else {
                return response(['status' => 'error','message' => 'You are not authorised to update request'], 200);
            }
        }else {
            return response(['status' => 'error','message' => 'It looks like this request has been marked as completed'], 200);
        }
       
}

    public function approveRequest(Request $request){
     
        $workflow_id = base64_decode($request->id);

        $action = 'approved';
        $log_action = 'Approved Request';
        $full_name = Auth::user()->firstname.' '.Auth::user()->lastname;
        $workflow = DocumentWorkflow::find($workflow_id);
        $document_applications = DocumentApplication::findOrFail($workflow->tbl_document_applications_id);
  
        // Update the workflow with processed date and processed by information
        $workflow->update([
            'processed_date' => Carbon::now(), 
            'processed_by' => $full_name // Assuming 'full_name' is a property of the authenticated user
        ]);

        $this->scannedRequestLogs($document_applications['id'], Auth::user()->id, $log_action, $workflow['tbl_document_workflow_type_id']);
        $this->WorkflowActionDocuments($action, $document_applications['id'], $workflow_id);
        $this->passWorkflowDocuments($document_applications['id']);

        return response(['status' => 'success','message' => 'Request approved successfully'], 200);
    }

    public function initiateReview(Request $request)
    {
        $workflow_id = base64_decode($request->id);
        $workflow = DocumentWorkflow::find($workflow_id);
        $record = DocumentApplication::find($workflow->tbl_document_applications_id);

        $user = User::find($record->tbl_users_id);
        $full_name = $user->firstname . ' ' . $user->lastname;

        $select = '<select name="tbl_departments_id" class="form-select">
                        <option value=""></option>
                        <option value="0">Requester - '.$full_name.'</option>';

        $reqs = $record->tbl_document_workflow()->where('tbl_document_workflow_type_id', 1)->where('deleted', 0)->get();

       if($reqs){
        foreach ($reqs as $req) {
            $dept = Department::find($req->tbl_document_setup_details->reference);
            if($dept){
                $select .= '<option value="'.$dept->id.'">'.$dept->department_name.'</option>';
            }
        }
       }

        $select .= '</select>';

        return $select;
    }

    public function reviewRequest(Request $request){
        // return $request;
        $workflow_id = base64_decode($request->id);
        $workflow = DocumentWorkflow::findOrFail($workflow_id);
        // return  $workflow ;
        $user = auth()->user();
        $full_name = $user->firstname.' '.$user->lastname;
        // return $user->id;
        $validate = $this->validateAccessWorkflow($workflow, $user->id);
        // return $validate;
        if ($validate > 0) {
            $record = DocumentApplication::find($workflow['tbl_document_applications_id']);
            $comments = $request->comments;
            $departments = $request->tbl_departments_id;
    
            $statusHelper = new ApplicationStatusClass(11, 16);
            $review_status = $statusHelper->getStatusbyEndpoint(3);
            $pending_status = $statusHelper->getStatusbyEndpoint(1);
    
            // 
            $req_rev = $workflow->tbl_document_setup_details->reference > 0 ? $workflow->tbl_document_setup_details->reference : 0;
            $review_type = $departments == 0 ? 'CUSTOMER' : 'DEPARTMENT';

            $log_action = 'Sent Request for Review';
            $workflow->update([
                'processed_date' => now(),
                'processed_by' => $full_name,
                'comments' => $comments,
                'reference' => $departments,
                'requester_review' => $req_rev,
                'tbl_system_status_id' => 1,
                'started_yn' => 0,
                'tbl_workflow_status_id' => 8,
                'attempt' => 0,
                'review_type' => $review_type
            ]);

            $record->tbl_application_status_id = $review_status->id;
            $record->save();

            $tt =  $this->sendDocumentReview($record->id, $departments, $workflow_id);
            // return $tt;

            $obs = DocumentWorkflow::where('tbl_document_applications_id', $record['id'])
            ->whereHas('tbl_document_setup_details', function ($query) use ($departments) {
                $query->where('reference', $departments);
            })
            ->where('deleted', 0)
            ->first();

            if ($obs) {
                $obs->update([
                    'tbl_workflow_status_id' => 9,
                    'tbl_system_status_id' => 2,
                    'attempt' => 0,
                    'reviewed_department' => $departments,
                    'started_yn' => 1,
                    'completed_yn' => 0
                ]);
            }

            $response = response(['status' => 'success','message' => 'Request Sent for review successfully'], 200);
            // 
    
            $this->scannedRequestLogs($record->id, $full_name, $log_action, $workflow->tbl_document_workflow_type_id);
            $this->WorkflowActionDocuments($request->input('action'), $record->id, $workflow_id);
            $this->passWorkflowDocuments($record->id);
        } else {
            $response = response(['status' => 'error','message' => 'You are not authorised to update request'], 200);
        }

        return $response;
    }


    public function declineRequest(Request $request){
        // return $request;
        $workflow_id = base64_decode($request->id);
        $workflow = DocumentWorkflow::findOrFail($workflow_id);
        $user = auth()->user();
        $full_name = $user->firstname.' '.$user->lastname;
        $validate = $this->validateAccessWorkflow($workflow, $user->id);
        $response = '';
    
        if ($validate > 0) {
            $record = DocumentApplication::find($workflow['tbl_document_applications_id']);
            $comments = $request->comments;
    
            $statusHelper = new ApplicationStatusClass();
            $review_status = $statusHelper->getStatusbyEndpoint(3);
            $pending_status = $statusHelper->getStatusbyEndpoint(1);
    
            //    
            $workflow->update([
                'processed_date' => now(),
                'processed_by' => $full_name,
                'comments' => $comments
            ]);

            $log_action = 'Declined Request';
            $response = response(['status' => 'success','message' => 'Request Declined successfully'], 200);
            // 
    
            $this->scannedRequestLogs($record->id, $full_name, $log_action, $workflow->tbl_document_workflow_type_id);
            $this->WorkflowActionDocuments($request->input('action'), $record->id, $workflow_id);
            $this->passWorkflowDocuments($record->id);
        } else {
            $response = response(['status' => 'error','message' => 'You are not authorised to update request'], 200);
        }

        return $response;
    }

    public function deleteRequest(Request $request){
       
        if($request->has('del_type')){
            $id = base64_decode($request->id);
        }else{
            $workflow_id = base64_decode($request->id);
            $workflow = DocumentWorkflow::find($workflow_id);
            $id = $workflow->tbl_document_applications_id;
        }
        
        $rs = DocumentApplication::findOrFail($id);

        // return $rs;
        if (Auth::user()->delete_products > 0) {
            if ($rs) {
                $rs->update([
                    'deleted' => 1,
                    'modon' => Auth::user()->id,
                    'modby' => now(),
                ]);
                return response(['status' => 'success','message' => 'Request deleted successfully'], 200);
            }
        } else {
            $user = Auth::user()->id;
            if ($user != $rs->createdby) {
                return response(['status' => 'error','message' => 'You do not have authorization to delete request. Request can only be deleted by the Initiator'], 200);
            } else {
                if ($rs) {
                    $rs->update(['deleted' => 1]);
                    return response(['status' => 'success','message' => 'Request deleted successfully'], 200);
                }
            }
        }
        
    }

    public function overrideStatus(Request $request){
        $id = base64_decode($request->id);

        $document = DocumentApplication::find($id);
        $doc_workflow = DocumentWorkflow::where('tbl_document_applications_id', $id)->get();
        $workflow_status = WorkflowStatus::all();
        $document_workflow_type = DocumentWorkflowType::all();
        $appl_status = new ApplicationStatusClass(11, 16);
        $status = $appl_status->getStatusList();
        
         // Render the view to HTML
        $html = View::make('product-requests.action-menu-pages.overridestatus', compact('id', 'status', 'document', 'doc_workflow', 'workflow_status', 'document_workflow_type'))->render();

        // Return JSON response with the HTML content
        return response()->json(['html' => $html]);

        return view('product-requests.action-menu-pages.overridestatus', compact('status', 'document', 'doc_workflow', 'workflow_status', 'document_workflow_type'));
    }

    public function overrideStatusSave(Request $request){
        $id = $request->input('id');
        $tbl_application_status_id = $request->input('tbl_application_status_id');
        $doc_workflow = $request->input('doc_workflow');
        $tbl_document_workflow_type_id = $request->input('tbl_document_workflow_type_id');
        $tbl_workflow_status_id = $request->input('tbl_workflow_status_id');

        // Update tbl_document_applications
        $rs = DocumentApplication::findOrFail($id);
        $rs->update(['tbl_application_status_id' => $tbl_application_status_id]);

        // Update tbl_document_workflow
        foreach ($doc_workflow as $key => $dwflow_id) {
            $dwflow = DocumentWorkflow::findOrFail($dwflow_id);
            $dwflow->update([
                'tbl_document_workflow_type_id' => $tbl_document_workflow_type_id[$key],
                'tbl_workflow_status_id' => $tbl_workflow_status_id[$key]
            ]);
        }

        return response(['status' => 'success','message' => 'Status overridden successfully'], 200);

    }

    public function deleteDocument(Request $request){
        $id = base64_decode($request->id);

        $document = Document::find($id);

        if (Auth::user()->delete_documents > 0) {
            if ($document) {
                $document->update(['deleted' => 1]);
                return response(['status' => 'success','message' => 'Document deleted successfully'], 200);
            }
        } else {
            if (Auth::user()->id != $document->createdby) {
                return response(['status' => 'error','message' => 'You do not have authorization to delete this document. Only the creator can delete it.'], 200);
            } else {
                if ($document) {
                    $document->update(['deleted' => 1]);
                    return response(['status' => 'success','message' => 'Document deleted successfully'], 200);
                }
            }
        }
    }
    public function externalActionRequest(Request $request){
        return $request;
        $key = base64_decode($request->query('key')); //approved-review //declined-review
        $token = $request->query('token'); //$userData->token
        $workflow_id = base64_decode($request->query('uid')); // $workflow_id
        $auth = $request->query('auth'); //auth=1

      
    }
    public function externalViewRequest(Request $request){
        return $request;
        $token = $request->query('token'); //$userData->token
    }


    public function requestOverview(Request $request){
        $id = base64_decode($request->id);
        // return $id;
        $record = DocumentApplication::findOrFail($id);

        $workflows = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->get();

        $documents = Document::with('tbl_users')->where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->get();

        $checklists = DocumentChecklist::where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->orderBy('sort')
            ->get();

        $payin = PayinRequest::where('policy_number', $record->policy_no)
            ->where('payment_status', 'paid')
            ->orderBy('id', 'DESC')
            ->first();

        $comments = DocumentApplicationsComment::with('tbl_users')->where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->orderBy('id', 'desc')
            ->get();

        $paid_amount = 0;
        $paid_date = "";

        if (trim($record->policy_no) != "" && trim($record->policy_no) == trim(optional($payin)->policy_number) && trim(optional($payin)->payment_status) == "paid") {
            $paid_amount = floatval(optional($payin)->payment_amount);
            $paid_date = optional($payin)->payment_as_at ? date('m/d/Y', strtotime(optional($payin)->payment_as_at)) : "";
        }
        // return $documents;

        $html = View::make('product-requests.action-menu-pages.request-progress-overview', compact('id', 'record', 'workflows', 'documents', 'checklists', 'payin', 'comments', 'paid_amount', 'paid_date'))->render();

        // Return JSON response with the HTML content
        return response()->json(['html' => $html]);
    }
}
