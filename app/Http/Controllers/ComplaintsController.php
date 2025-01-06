<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerComplaint;
use App\Http\Traits\DocumentSysEngine;
use App\Models\CustomerComplaintLog;
use App\Models\ShareDocumentLog;
use App\Models\User;
use App\Models\HowResolved;
use App\Models\Level;

use App\Models\ComplaintCategory;
use App\Models\ComplaintClassification;
use App\Models\NotifiedBy;
use App\Models\ProductType;
use App\Models\Processs;

use Illuminate\Support\Facades\Storage;
use App\Models\CustomerComplaintDocument;
use App\Models\DocumentImage;

use App\Http\CustomClasses\ApplicationStatusClass;
use Auth;
use Carbon\Carbon;


class ComplaintsController extends Controller
{
    use DocumentSysEngine;
    public function assignedComplaints()
    {
        return view('customer-complaints.assigned-complaints');
    }

    public function assignedComplaintsAdmin()
    {
        return view('customer-complaints.assigned-complaints-admin');
    }

    public function closedComplaints()
    {
        return view('customer-complaints.closed-complaints');
    }

    public function closedComplaintsAdmin()
    {
        return view('customer-complaints.closed-complaints-admin');
    }

    public function pendingComplaints()
    {
        return view('customer-complaints.pending-complaints');
    }

    public function pendingComplaintsAdmin()
    {
        return view('customer-complaints.pending-complaints-admin');
    }

    public function receivedComplaints()
    {
        return view('customer-complaints.received-complaints');
    }

    public function receivedComplaintsAdmin()
    {
        return view('customer-complaints.received-complaints-admin');
    }

    public function searchComplaints()
    {
        return view('customer-complaints.search-complaints');
    }

    public function searchComplaintsAdmin()
    {
        return view('customer-complaints.search-complaints-admin');
    }

    public function registerComplaints()
    {
        return view('customer-complaints.register-complaints');
    }

    public function reportComplaints()
    {
        return view('customer-complaints.reports');
    }

    public function webApi(){
        
    }

    public function viewComplaint(Request $request){
        $token = $request->query('token');

        // Find the customer complaint by token
        $record = CustomerComplaint::where('token', $token)
                                    ->where('deleted', 0)
                                    ->first();
        return view('customer-complaints.view-complaint', compact('record'));
    }


 /**
  * The function `registerComplaintSave` processes and saves a customer complaint along with any
  * attached documents and sends a confirmation SMS to the customer.
  * 
  * @param Request request The `registerComplaintSave` function is used to handle the submission of a
  * customer complaint form. Let me explain the code step by step:
  * 
  * @return The function `registerComplaintSave` is returning a redirect response to the route named
  * 'complaints-received' with a message stored in the session. The message contains information about
  * the success or failure of the complaint submission process.
  */
    public function registerComplaintSave(Request $request)
    {
        $targetDir = 'attachments/';
        
        $cntImage = $request->hasFile('file') ? count($request->file('file')) : 0;

        $get_status = new ApplicationStatusClass(2, 2);
        $status = $get_status->getStatusbyEndpoint(9);
        
        $refNo = rand(1, 1000000);
        $token = md5(microtime());
        
        $complaint = new CustomerComplaint();
        $complaint->name = strtoupper($request->customer_name);
        $complaint->address = $request->address;
        $complaint->email = strtolower($request->email_address);
        $complaint->phone_number = $request->contact_details;
        $complaint->reporting_channel = $request->reporting_channel;
        $complaint->tbl_complaints_categories_id = $request->cat_id;
        $complaint->policy_number = $request->policy_number;
        $complaint->description = $request->description;
        $complaint->request_no = $refNo;
        $complaint->tbl_branch_id = (auth()->user()?->tbl_branch_id > 0) ? auth()->user()?->tbl_branch_id : 1;
        $complaint->tbl_users_id = auth()->user()?->id;
        $complaint->tbl_application_status_id = $status->id;
        $complaint->tbl_complaint_classifications_id = $request->class_id;
        $complaint->tbl_complaint_reporting_channel_id = $request->channel_id;
        $complaint->token = $token;
        $complaint->tbl_method_of_contact_id = $request->method_of_contact_id;
        $complaint->createdby = auth()->user()?->id;
        $complaint->createdon = ($request->received_date == '') ? now() : $request->received_date;
        $complaint->save();
        
        if ($cntImage > 0) {
            foreach ($request->file('file') as $uploadedFile) {

                // Get the original extension
                $extension = $uploadedFile->getClientOriginalExtension(); 
                // Generate a unique filename using UUID
                $uuid = Str::uuid()->toString();
                // Concatenate the UUID and extension to create the unique filename
                $fullName = 'attached-'.$uuid . '.' . $extension;
                // Store the file with the unique filename on S3
                $status  =  Storage::disk('s3')->put($targetDir . $fullName, file_get_contents($uploadedFile));
    
                $uploadedFile = $file->store('attachments');
                $docType = $this->getImageDoc($uploadedFile->getMimeType());

                $document = new CustomerComplaintDocument();
                $document->tbl_document_images_id = $docType->id;
                $document->tbl_customer_complaints_id = $complaint->id;
                $document->document_name = $request->doc_name[$i];
                $document->file = $fullName;
                $document->save();
            }
        }
        
        if ($complaint->id) {
            $message = 'Dear ' . strtoupper($request->customer_name) . '. A complaint has been logged successfully on your behalf. Your reference number is ' . $refNo . '';

            $this->sendSMS($request->contact_details, $message, 'OLDMUTUAL');
            
             // set message 
            $message = [ 'type' => 'success', 'class'=>'success',  'message' => "Your complaint has been submitted successfully"];
        } else {
             // set message 
            $message = [ 'type' => 'success', 'class'=>'danger',  'message' => "There was an error in processing your request. Please try again"];
        }

        return redirect()->route('complaints-received')->with('message', $message);
    }



    public function getComplaintsSearchResults(Request $request){

        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        //
        $branches = $this->limitBranchAccess();
        $status = new ApplicationStatusClass(2, 2);
        $pending_status = $status->getStatusbyEndpoint(9);
        $assigned_status = $status->getStatusbyEndpoint(5);
        $resolved_status = $status->getStatusbyEndpoint(2);
        $completed_status = $status->getStatusbyEndpoint(35);
        $held_status = $status->getStatusbyEndpoint(28); // issue
        $reassigned_status = $status->getStatusbyEndpoint(36);

        //
        switch ($request->complaint_type ) {
            case 'assigned':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $assigned_status->id)
                    ->where('createdby', Auth::user()->id); 
                break;
            case 'assigned-admin':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $assigned_status->id); 
                break;
            case 'closed':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $completed_status->id); 
                break;
            case 'closed-admin':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $completed_status->id); 
                break;
            case 'pending':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $held_status->id)
                    ->where('createdby', Auth::user()->id)
                    ->orWhere('assigned_to', Auth::user()->id); 
                break;
            case 'pending-admin':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $held_status->id); 
                break;
            case 'received':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $pending_status->id)
                    ->where('createdby', Auth::user()->id); 
                break;
            case 'received-admin':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('tbl_application_status_id', $pending_status->id); 
                break;
            case 'search':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0)
                    ->where('createdby', Auth::user()->id); 
                    //
                    $status = new ApplicationStatusClass(2, 2);
                    $stat = $status->getStatusbyStage(array(1, 2, 3));
                    // Extract the IDs from the status result
                    $statusIds = [];
                    foreach ($stat as $stat_us) {
                        $statusIds[] = $stat_us->id;
                    }
                    $query->whereIn('tbl_application_status_id', $statusIds);
                break;
            case 'search-admin':
                $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0); 

                break;
            default:
                # code...
                
                break;
        }
       

        //
        $status = new ApplicationStatusClass(2, 2);
        $statuses = $status->getStatusbyStage([1, 2, 3])->pluck('id')->toArray();
        $query->whereIn('tbl_application_status_id', $statuses);

        //
        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'assigned_to';
                break;
            case '1':
                // $orderByName = 'request_no';
                $orderByName = 'id'; // not right, but just wanted to arrange data by id
                break;
            case '2':
                $orderByName = 'name';
                break;
            case '3':
                $orderByName = 'phone_number';
                break;
            case '4':
                $orderByName = 'email';
                break;
            case '5':
                $orderByName = 'policy_number';
                break;
            case '6':
                $orderByName = 'tbl_complaints_categories_id';
                break;
            case '7':
                $orderByName = 'tbl_users_id';
                break;
            case '8':
                $orderByName = 'tbl_branch_id';
                break;
            case '9':
                $orderByName = 'description';
                break;
            case '9':
                $orderByName = 'assign_comment';
                break;
            case '9':
                $orderByName = 'createdon';
                break;
            case '9':
                $orderByName = 'tbl_application_status_id';
                break;
            case '9':
                $orderByName = 'reporting_channel'; //tbl_complaint_reporting_channel_id
                break;
        }

        // Move the orderBy clause before get() to avoid the error
        $query->orderBy($orderByName, $orderBy);

        $recordsFiltered = $recordsTotal = $query->count();
        $complaints = $query->skip($skip)->take($pageLength)->get();

        return response()->json(["draw" => $request->draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $complaints], 200);

    }

    public function initCustomComplaintsSearchRequests(Request $request){
        $form_params = $request->all();
        return back()->with('form-params', $form_params);
    }

    public function getCustomComplaintsSearchRequests(Request $request){
         
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // $search_results = $this->searchCustomerComplaints($request);
        $search = $request->form_params;

        // return $search;

        $status = new ApplicationStatusClass(2, 2);
        $pending_status = $status->getStatusbyEndpoint(9);
        $assigned_status = $status->getStatusbyEndpoint(5);
        $resolved_status = $status->getStatusbyEndpoint(2);
        $completed_status = $status->getStatusbyEndpoint(35);
        $held_status = $status->getStatusbyEndpoint(28); // issue
        $reassigned_status = $status->getStatusbyEndpoint(36);

        $query = CustomerComplaint::with('tbl_users')
                    ->with('assigned')
                    ->with('tbl_branch')
                    ->with('tbl_application_status')
                    ->with('tbl_complaint_reporting_channel')
                    ->with('tbl_complaints_categories')
                    ->where('deleted', 0)
                    ->where('completed_yn', 0);

        switch ($request->complaint_type) {
            case 'assigned':
                    $query->where('tbl_application_status_id', $assigned_status->id)
                    ->where('createdby', Auth::user()->id); 
                break;
            case 'assigned-admin':
                $query->where('tbl_application_status_id', $assigned_status->id); 
                break;
            case 'closed':
                $query->where('tbl_application_status_id', $completed_status->id); 
                break;
            case 'closed-admin':
                $query->where('tbl_application_status_id', $completed_status->id); 
                break;
            case 'pending':
                $query->where('tbl_application_status_id', $held_status->id)
                    ->where('createdby', Auth::user()->id)
                    ->orWhere('assigned_to', Auth::user()->id); 
                break;
            case 'pending-admin':
                $query->where('tbl_application_status_id', $held_status->id); 
                break;
            case 'received':
                $query->where('tbl_application_status_id', $pending_status->id)
                    ->where('createdby', Auth::user()->id); 
                break;
            case 'received-admin':
                $query->where('tbl_application_status_id', $pending_status->id); 
                break;

            default:
                # code...
                
                break;
        }

        if (is_array($search)) {
            // if (!empty($search['branch_id']) && count($search['branch_id']) > 0 && $search['branch_id'][0] !== null) {
            //     $query->whereIn('tbl_branch_id', $search['branch_id']);
            // }
            if (!empty($search['branch_id']) && count(array_filter($search['branch_id'], function($value) { return $value !== null; })) > 0) {
                $query->whereIn('tbl_branch_id', array_filter($search['branch_id'], function($value) { return $value !== null; }));
            }
            if (!empty($search['categ'])) {
                $query->where('tbl_complaints_categories_id', $search['categ']);
            }
            if (!empty($search['reqnum'])) {
                $query->where('request_no', $search['reqnum']);
            }
            if (!empty($search['assigned_to']) && count($search['assigned_to']) > 0) {
                $query->whereIn('assigned_to', $search['assigned_to']);
            }
            if (!empty($search['customer_name'])) {
                $query->whereRaw('LOWER(name) like ?', [strtolower($search['customer_name'])]);
            }
            if (!empty($search['phone_number'])) {
                $query->where('phone_number', 'like', '%' . $search['phone_number'] . '%');
            }
        } else {
            $status = new ApplicationStatusClass(2, 2);
            $statuses = $status->getStatusbyStage([1, 2, 3])->pluck('id')->toArray();
            $query->whereIn('tbl_application_status_id', $statuses);
        }

        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                // $orderByName = 'customer_name';
                $orderByName = 'id'; // not right, but just wanted to arrange data by id
                break;
            case '2':
                $orderByName = 'policy_no';
                break;
            case '3':
                $orderByName = 'tbl_documents_products_id';
                break;
            case '4':
                $orderByName = 'tbl_users_id';
                break;
            case '5':
                $orderByName = 'tbl_branch_id';
                break;
            case '6':
                $orderByName = 'createdon';
                break;
            case '7':
                $orderByName = 'tbl_application_status_id';
                break;
            case '8':
                $orderByName = 'source';
                break;
            case '9':
                $orderByName = 'createdon';
                break;
        }

        // Move the orderBy clause before get() to avoid the error
        $query->orderBy($orderByName, $orderBy);

        $recordsFiltered = $recordsTotal = $query->count();
        $users = $query->skip($skip)->take($pageLength)->get();

        return response()->json(["draw" => $request->draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $users], 200);

    }

    public function initAction(Request $request){

        $action = base64_decode($request->action);

        switch ($action) {
            case 'assign':
                $req = $request->id;
                $id = base64_decode($req);
                $complaints = CustomerComplaint::with('tbl_complaints_categories')->find($id);
                $categories = ComplaintCategory::orderBy("name")->get();
                $classifications = ComplaintClassification::orderBy("name")->get();
                $notifiedBy = NotifiedBy::orderBy("name")->get();
                $productTypes = ProductType::
                where('description', 1)->  //bring back in production
                orderBy("name")->get();
                $processes = Processs::orderBy("name")->get();
                $users = User::where('deleted', 0)->orderBy('full_name')->get();

                return view('customer-complaints.action-menu-pages.assign', compact('req','complaints', 'categories', 'classifications', 'notifiedBy', 'productTypes', 'processes', 'users'));

            break;
            case 'assign-next':
                $req = $request->id;
                $id = base64_decode($req);
                $complaints = CustomerComplaint::with('tbl_complaints_categories')->find($id);
                $categories = ComplaintCategory::orderBy("name")->get();
                $classifications = ComplaintClassification::orderBy("name")->get();
                $notifiedBy = NotifiedBy::orderBy("name")->get();
                $productTypes = ProductType::
                 where('description', 1)->  //bring back in production
                orderBy("name")->get();
                $processes = Processs::orderBy("name")->get();
                $users = User::where('deleted', 0)->orderBy('full_name')->get();

                return view('customer-complaints.action-menu-pages.assign-next', compact('req','complaints', 'categories', 'classifications', 'notifiedBy', 'productTypes', 'processes', 'users'));

            break;

            case 'complete':
                $req = $request->id;
                $id = base64_decode($req);
                $complaints = CustomerComplaint::with('tbl_complaints_categories')->find($id);
                $categories = ComplaintCategory::orderBy("name")->get();
                $classifications = ComplaintClassification::orderBy("name")->get();
                $notifiedBy = NotifiedBy::orderBy("name")->get();
                $productTypes = ProductType::
                 where('description', 1)->  //bring back in production
                orderBy("name")->get();
                $processes = Processs::orderBy("name")->get();
                $users = User::where('deleted', 0)->orderBy('full_name')->get();

                return view('customer-complaints.action-menu-pages.complete', compact('req','complaints', 'categories', 'classifications', 'notifiedBy', 'productTypes', 'processes', 'users'));

            break;

            case 'complete-next':
                $req = $request->id;
                $id = base64_decode($req);
                $complaints = CustomerComplaint::with('tbl_complaints_categories')->find($id);
                $categories = ComplaintCategory::orderBy("name")->get();
                $classifications = ComplaintClassification::orderBy("name")->get();
                $notifiedBy = NotifiedBy::orderBy("name")->get();
                $productTypes = ProductType::
                 where('description', 1)->  //bring back in production
                orderBy("name")->get();
                $processes = Processs::orderBy("name")->get();
                $users = User::where('deleted', 0)->orderBy('full_name')->get();

                return view('customer-complaints.action-menu-pages.complete-next', compact('req','complaints', 'categories', 'classifications', 'notifiedBy', 'productTypes', 'processes', 'users'));

            break;

            case 'audit':
                $req = $request->id;
                $id = base64_decode($req);
                $complaints = CustomerComplaint::with('tbl_complaints_categories')->find($id);
                $categories = ComplaintCategory::orderBy("name")->get();
                $classifications = ComplaintClassification::orderBy("name")->get();
                $notifiedBy = NotifiedBy::orderBy("name")->get();
                $productTypes = ProductType::
                 where('description', 1)->  //bring back in production
                orderBy("name")->get();
                $processes = Processs::orderBy("name")->get();
                $users = User::where('deleted', 0)->orderBy('full_name')->get();

                $records = CustomerComplaintLog::with(['tbl_customer_complaints','creator'])->where('tbl_customer_complaints_id', $id)
                    ->where('deleted', 0)
                    ->orderByDesc('id')
                    ->get();

                return view('customer-complaints.action-menu-pages.audit', compact('req', 'records', 'users'));

            break;

            case 'update':
                $req = $request->id;
                $id = base64_decode($req);
                $complaints = CustomerComplaint::with('tbl_complaints_categories')->find($id);
                // $categories = ComplaintCategory::orderBy("name")->get();
                // $classifications = ComplaintClassification::orderBy("name")->get();
                // $notifiedBy = NotifiedBy::orderBy("name")->get();
                // $productTypes = ProductType::where('description', 1)->orderBy("name")->get();
                // $processes = Processs::orderBy("name")->get();
                $users = User::where('deleted', 0)->orderBy('full_name')->get();

                $levels = Level::orderBy("name")->get();
                $resolves = HowResolved::orderBy("name")->get();


                return view('customer-complaints.action-menu-pages.update-final', compact('req','complaints', 'levels', 'resolves',  'users'));

            break;
            
            default:
                # code...
                break;
        }
    }

    public function handleActionDelPend(Request $request)
    {
        $action = base64_decode($request->action);
        $id = base64_decode($request->id);
        $rs = CustomerComplaint::find($id);
        $user = Auth::user(); //

        // return $action.'_'. $id;

        $status = new ApplicationStatusClass(2, 2);
        $pending_status = $status->getStatusbyEndpoint(9);
        $assigned_status = $status->getStatusbyEndpoint(5);
        $resolved_status = $status->getStatusbyEndpoint(2);
        $completed_status = $status->getStatusbyEndpoint(35);
        $held_status = $status->getStatusbyEndpoint(28);
        $reassigned_status = $status->getStatusbyEndpoint(36);

        if ($action == 'delete') {
            if (auth()->user()?->delete_documents_yn > 0 || $user->id == $rs->createdby) {
                if ($rs) {
                    $rs->update(['deleted' => 1]);
                    $message = ['status' => 'success','message' => 'Complaint deleted successfully'];
                }
            } else {
                return response(['status' => 'error','message' => 'You do not have authorization to delete request. Request can only be deleted by the initiator.'], 200);             
            }
        }

        if ($action == 'pend') {
            $rs->update(['tbl_application_status_id' => $held_status->id, 'last_updated_date' => now()]);

            $v_arr = [
                'tbl_customer_complaints_id' => $rs->id,
                'createdby' => $user->id,
                'status' => 'Pended by ' . $user->full_name,
                'tbl_application_status_id' => $held_status->id
            ];

            CustomerComplaintLog::create($v_arr);

            $message = ['status' => 'success','message' => 'Complaint Pended successfully'];
        }

        return response($message, 200);
    }

    public function handleShareDocument(Request $request)
    {
        $user = Auth::user();
        $token = md5(date('dmYHis'));
        $rec = DocumentApplication::find($request->id);

        $arr = [
            'tbl_users_id' => $user->id,
            'token' => strtoupper($token),
            'delivery' => $request->delivery,
            'tbl_documents_products_id' => $rec->tbl_documents_products_id,
            'tbl_document_type_id' => $request->doc_type,
            'name' => $request->name,
            'tbl_document_applications_id' => $request->id
        ];

        $url = url('document-rendering/web-view-share?type=' . base64_encode($request->doc_type) . '&token=' . $rec->token);
        $tandc_url = url('document-rendering/tandc-share?token=' . $rec->token);

        if ($request->delivery == 'EMAIL') {
            $arr['email_to'] = $request->to;
            $arr['email_cc'] = $request->cc;
            $shareDocumentLog = ShareDocumentLog::create($arr);

            $this->shareDocument2($shareDocumentLog->id, $url);
        } elseif ($request->delivery == 'SMS') {
            $arr['phone_no'] = $request->phone_no;
            ShareDocumentLog::create($arr);
            if ($request->doc_type == 1) {
                $message = "{$user->full_name} has shared a Proposal Form for {$rec->tbl_documents_products->product_name} with Policy Number " . strtoupper($rec->policy_no) . " to you. Click on URL to access document: {$url}. Find terms and conditions below: {$tandc_url}";
            } elseif ($request->doc_type == 2) {
                $message = "{$user->full_name} has shared a Mandate Form for {$rec->tbl_documents_products->product_name} with Policy Number " . strtoupper($rec->policy_no) . " to you. Click on URL to access document: {$url}. Find terms and conditions below: {$tandc_url}";
            } elseif ($request->doc_type == 3) {
                $message = "{$user->full_name} has shared Terms and conditions for {$rec->tbl_documents_products->product_name} with Policy Number " . strtoupper($rec->policy_no) . " to you. Click on URL to access terms and conditions below: {$tandc_url}";
            }

            $to = '0' . substr($request->phone_no, -9);
            $sender = 'OLDMUTUAL';

            $this->sendSMS($to, $message, $sender);

            $this->logDocumentsTrail($request->id, getUserContext('fullname'), 'Shared Document via SMS', strtoupper($request->name));
        }

        $message = ['status' => 'success','message' => 'Document shared successfully'];

        return response($message, 200);
    }

    public function handleAssignComplaint(Request $request)
    {
        $status = new ApplicationStatusClass(2, 2);
        $pending_status = $status->getStatusbyEndpoint(9);
        $assigned_status = $status->getStatusbyEndpoint(5);
        $resolved_status = $status->getStatusbyEndpoint(2);
        $completed_status = $status->getStatusbyEndpoint(35);
        $held_status = $status->getStatusbyEndpoint(28);
        $reassigned_status = $status->getStatusbyEndpoint(36);

        $user = Auth::user();
        $id = base64_decode($request->id);
        $rec = CustomerComplaint::with('tbl_complaints_categories')->find($id);
        if ($rec) {
            $rec->update([
                'tbl_application_status_id' => $assigned_status['id'],
                'assigned_to' => $request->assign_to,
                'assign_comment' => $request->assign_comment,
                'tbl_complaints_categories_id' => $request->cat_id,
                'tbl_complaint_classifications_id' => $request->class_id,
                'last_updated_date' => now(),

                "tbl_notified_by_id" => $request->notified_by_id,
                "tbl_product_type_id" => $request->product_type_id,
                "tbl_process_id" => $request->process_id,
            ]);

            $logData = [
                'tbl_customer_complaints_id' => $rec->id,
                'createdby' => $user->id,
                'status' => 'Assigned to ' . $user->full_name,
                'tbl_application_status_id' => $assigned_status['id'],
                'tbl_process_id' => $request->process_id,
                'tbl_product_type_id' => $request->product_type_id,
                'tbl_notified_by' => $request->notified_by_id,
            ];

            CustomerComplaintLog::create($logData);

            if ($request->delivery == 'EMAIL') {
                $this->sendCustomerComplaint($rec, $user, $request->assign_comment);
            }
            if ($request->delivery == 'SMS') {
                $message = 'A complaint with request number ' . $rec->request_no . ' has been sent you for processing. Use link to access details of complaint: ' .
                    url('customer-complaint/view-complaint?token=' . $rec->token);
                   
                    $to = $user->mobile;
                    $sender = 'OLDMUTUAL';
                    $this->sendSMS($to, $message, $sender);
            }

            $message = ['status' => 'success','message' => 'Request assigned successfully'];
        }
         return response($message, 200);
    }

    public function handleCompleteComplaint(Request $request)
    {

        $status = new ApplicationStatusClass(2, 2);
        $pending_status = $status->getStatusbyEndpoint(9);
        $assigned_status = $status->getStatusbyEndpoint(5);
        $resolved_status = $status->getStatusbyEndpoint(2);
        $completed_status = $status->getStatusbyEndpoint(35);
        $held_status = $status->getStatusbyEndpoint(28);
        $reassigned_status = $status->getStatusbyEndpoint(36);

        $id  = base64_decode($request->id);
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $notified_by_id = $request->notified_by_id;
        $product_type_id = $request->product_type_id;
        $process_id = $request->process_id;
        $note = $request->note;

        $customerComplaint = CustomerComplaint::find($id);

        if ($customerComplaint) {
            // Update the customer complaint with the provided data
            $customerComplaint->update([
                'tbl_complaints_categories_id' => $cat_id,
                'tbl_complaint_classifications_id' => $class_id,
                'tbl_notified_by_id' => $notified_by_id,
                'tbl_product_type_id' => $product_type_id,
                'tbl_process_id' => $process_id,
                'tbl_application_status_id' => $completed_status['id'],
                'note' => $note,
                'completed_date' => now(), // Assuming you have a 'completed_date' column
                'last_updated_date' => now() // Assuming you have a 'last_updated_date' column
            ]);
    
            // Send SMS notification
            $message = 'Dear ' . strtoupper($customerComplaint->name) . '. Your complaint has successfully been resolved. Kindly click on https://forms.office.com/r/fE6shEZXGd to rate us.';
           
            $to = '0' . substr($customerComplaint->phone_number, -9);
            $sender = 'OLDMUTUAL';
            $this->sendSMS($to, $message, $sender);

            $message = ['status' => 'success','message' => 'Complaint closed successfully'];
            return response($message, 200);
        }

    }

    public function handleCompleteUpdateFinal(Request $request){
        $id = base64_decode($request->id);
        $level_id = $request->input('level_id');
        $how_resolved_id = $request->input('how_resolved_id');

        // Update the customer complaint directly using the update method
        $updated = CustomerComplaint::where('id', $id)
            ->update([
                'tbl_level_id' => $level_id,
                'tbl_how_resolved_id' => $how_resolved_id,
                'completed_yn' => 1,
                'last_updated_date' => now(), 
            ]);

        $message = ['status' => 'success','message' => 'Complaint updated successfully'];
        return response($message, 200);
    }


    public function searchCustomerComplaints(Request $request)
    {

        $query = CustomerComplaint::with(['tbl_complaints_categories', 'tbl_complaint_classifications',
         'tbl_application_status', 'tbl_complaint_reporting_channel', 'tbl_method_of_contact', 'tbl_notified_by',
         'tbl_product_type', 'tbl_process', 'tbl_level', 'tbl_how_resolved', 'tbl_users', 'tbl_branch'])
        ->where('deleted', 0);

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start_date = Carbon::createFromFormat('d M, Y', $request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d M, Y', $request->end_date)->format('Y-m-d');

            $query->where(function($q) use ($start_date, $end_date) {
                $q->whereBetween('createdon', [$start_date, $end_date])
                  ->orWhereBetween('completed_date', [$start_date, $end_date])
                  ->orWhereBetween('last_updated_date', [$start_date, $end_date]);
            });
        }
       
        if ($request->has('categ')) {
            $query->where('tbl_complaints_categories_id', $request->input('categ'));
        }

        if ($request->has('status')) {
            $query->whereIn('tbl_application_status_id', $request->input('status'));
        }

        if (!empty($request->branch_id) && count($request->branch_id) > 0) {
            $query->whereIn('tbl_branch_id', $request->branch_id);
        }

        if (!empty($request->reqnum)) {
            $query->where('request_no', $request->reqnum);
        }

        $results = $query->orderBy('id', 'desc')->get();

        return view('customer-complaints.view-report', compact('results', 'start_date', 'end_date'));
    }


}
