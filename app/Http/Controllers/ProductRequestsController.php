<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Http\CustomClasses\ApplicationStatusClass;

use App\Models\DocumentWorkflow;
use App\Models\Document;
use App\Models\DocumentChecklist;
use App\Models\DocumentApplicationsComment;
use App\Models\IntegrationLog;
use App\Models\DocumentProduct;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductRequestsController extends Controller
{

    public function initiateRequest(){
        return view('products.new-product');
    }

    public function customerInfo(){
        return view('product-requests.customer-info');
    }

    public function digitalForm(Request $request){
        //Reset proceed session
        Session::forget('proceed');

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
    
    public function requestProfile(Request $request){
        $param_id = base64_decode($request->query('id'));
        $param_section = base64_decode($request->query('section'));

        $record = DocumentApplication::with(['tbl_branch', 'tbl_application_status', 'tbl_documents_products'])->find($param_id);
        // $doc_product = DocumentProduct::find($record->tbl_documents_products_id);
        // $product_model = $doc_product->product_model;
        // $check_proposal_filled =  $product_model::where('tbl_document_applications_id', $record->id)->get();
   
        $workflows = DocumentWorkflow::with(['tbl_workflow_status','tbl_document_applications','tbl_document_setup_details','tbl_document_workflow_type'])->where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->get();

        $documents = Document::with(['tbl_document_type', 'tbl_document_images', 'tbl_users'])->where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->get();
        

        $checklists = DocumentChecklist::where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->orderBy('sort')
            ->get();
        
        $comments = DocumentApplicationsComment::where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->orderByDesc('id')
            ->get();

        return view('product-requests.request-profile', compact('record', 'workflows', 'documents', 'checklists', 'comments'));
    }

    public function productChecklist(){
        return view('product-requests.checklist');
    }
    public function attachedDocuments(Request $request){
        $param_id = base64_decode($request->query('id'));
        $param_section = base64_decode($request->query('section'));

        $record = DocumentApplication::with(['tbl_branch', 'tbl_application_status', 'tbl_documents_products'])->find($param_id);

        $documents = Document::where('tbl_document_applications_id', $record->id)
            ->where('deleted', 0)
            ->get();

        return view('product-requests.attached-documents', compact('record', 'documents'));
    }

    public function slamsLogs(Request $request){
        $param_id = base64_decode($request->query('id'));
        $param_section = base64_decode($request->query('section'));

        $record = DocumentApplication::with(['tbl_branch', 'tbl_application_status', 'tbl_documents_products'])->find($param_id);

        $slamlogs = IntegrationLog::where('tbl_document_applications_id', $record['id'])
            ->where('deleted', 0)
            ->latest('createdon')
            ->get();
        return view('product-requests.slams-logs', compact('slamlogs'));
    }

    public function createCustomerInfo(){
        //SUCCESS
        //Product Request 2001OMPS000170 created succesfully
    }
    // PRODUCTS
    public function claimRequest(){
        return view('products.claim_request');
    }
    public function deathClaim(){
        return view('products.death_claim');
    }
    public function educator(){
        return view('products.educator');
    }
    public function mandateRequest(){
        return view('products.mandate_request');
    }
    public function refundRequest(){
        return view('products.refund_request');
    }
    public function personalAccident(){
        return view('products.personal_accident');
    }
    public function sip(){
        return view('products.sip');
    }
    public function tpp(){
        return view('products.tpp2');
    }
    public function transition(){
        return view('products.transition');
    }
    public function travelInsurance(){
        return view('products.travel_insurance');
    }
    public function keyman(){
        return view('products.key_man');
    }
    public function corporate(){
        return view('products.corporate');
    }
    public function fidosip(){
        return view('products.fido_sip');
    }

    public function viewReport(){
        return view('product-requests.reports.view-report');
    }

    // PRODUCT REQUEST
    public function searchRequest(){
        return view('product-requests.search-request');
    }

    public function submitRequests(Request $request){
        return $request;
    }
    
    public function myRequests(){
        $incomplete = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [75], 
            'createdby' => Auth()->id(),
        ])->count();
        
        $pending_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [66, 71],
            'createdby' => Auth()->id(),
        ])->count();
        
        $payment_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [69],
            'createdby' => Auth()->id(),
        ])->count();
        
        $paid_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [70],
            'createdby' => Auth()->id(),
        ])->count();

        return view('product-requests.my-requests', compact('incomplete', 'pending_reqs', 'payment_reqs', 'paid_reqs'));
    }
    public function ussdRequests(){
        $incomplete = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [75], 
            'source' => 'USSD'
        ])->count();
        
        $pending_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [66, 71],
            'source' => 'USSD'
        ])->count();
        
        $payment_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [69],
            'source' => 'USSD'
        ])->count();
        
        $paid_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [70],
           'source' => 'USSD'
        ])->count();
        return view('product-requests.ussd-requests', compact('incomplete', 'pending_reqs', 'payment_reqs', 'paid_reqs'));
    }
    public function slamsRequests(){
        $incomplete = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [75], 
            'source' => 'SLAMS'
        ])->count();
        
        $pending_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [66, 71],
            'source' => 'SLAMS'
        ])->count();
        
        $payment_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [69],
            'source' => 'SLAMS'
        ])->count();
        
        $paid_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [70],
            'source' => 'SLAMS'
        ])->count();
        return view('product-requests.slams-requests', compact('incomplete', 'pending_reqs', 'payment_reqs', 'paid_reqs'));
    }
    public function websiteRequests(){
        $incomplete = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [75], 
            'source' => 'WEBSITE'
        ])->count();
        
        $pending_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [66, 71],
            'source' => 'WEBSITE'
        ])->count();
        
        $payment_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [69],
            'source' => 'WEBSITE'
        ])->count();
        
        $paid_reqs = DocumentApplication::where(['deleted' => 0, 'tbl_application_status_id' => [70],
            'source' => 'WEBSITE'
        ])->count();
        return view('product-requests.website-requests', compact('incomplete', 'pending_reqs', 'payment_reqs', 'paid_reqs'));
    }
    public function reportAllSubmissions(){
        return view('product-requests.reports.all-proposals-submissions');
    }
    public function reportReceivedProposals(){
        return view('product-requests.reports.proposals-received');
    }
    public function reportRejectedProposals(){
        return view('product-requests.reports.proposals-rejected');
    }

    public function getSearchResults(Request $request)
    {
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // Get data from data table
        $search = $request->search;
        $query = DocumentApplication::with('documentProduct')
            ->with('user')
            ->with('branch')
            ->with('applicationStatus')
            ->where('deleted', 0)
            ->where(function ($query) use ($search) {
                $query->orWhere('request_no', 'like', "%$search%")
                    ->orWhere('customer_name', 'like', "%$search%")
                    ->orWhere('policy_no', 'like', "%$search%")
                    ->orWhere('tbl_documents_products_id', 'like', "%$search%")
                    ->orWhere('tbl_users_id', 'like', "%$search%")
                    ->orWhere('tbl_branch_id', 'like', "%$search%")
                    ->orWhere('createdon', 'like', "%$search%")
                    ->orWhere('tbl_application_status_id', 'like', "%$search%")
                    ->orWhere('source', 'like', "%$search%");
            });

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

    public function getUserRequests(Request $request)
    {
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // Get data from data table
        $search = $request->search;
        $query = DocumentApplication::with('documentProduct')
            ->with('user')
            ->with('branch')
            ->with('applicationStatus')
            ->where('createdby', Auth()->id())
            ->where('deleted', 0)
            ->where(function ($query) use ($search) {
                $query->orWhere('request_no', 'like', "%$search%")
                    ->orWhere('customer_name', 'like', "%$search%")
                    ->orWhere('policy_no', 'like', "%$search%")
                    ->orWhere('tbl_documents_products_id', 'like', "%$search%")
                    ->orWhere('tbl_users_id', 'like', "%$search%")
                    ->orWhere('tbl_branch_id', 'like', "%$search%")
                    ->orWhere('createdon', 'like', "%$search%")
                    ->orWhere('tbl_application_status_id', 'like', "%$search%")
                    ->orWhere('source', 'like', "%$search%");
            });

        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                // $orderByName = 'customer_name'; // correct one
                $orderByName = 'id'; // override to arrange data by id first load
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

    public function getUssdRequests(Request $request)
    {
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // Get data from data table
        $search = $request->search;
        $query = DocumentApplication::with('documentProduct')
            ->with('user')
            ->with('branch')
            ->with('applicationStatus')
            ->where('source', 'USSD')
            ->where('deleted', 0)
            ->where(function ($query) use ($search) {
                $query->orWhere('request_no', 'like', "%$search%")
                    ->orWhere('customer_name', 'like', "%$search%")
                    ->orWhere('policy_no', 'like', "%$search%")
                    ->orWhere('tbl_documents_products_id', 'like', "%$search%")
                    ->orWhere('tbl_users_id', 'like', "%$search%")
                    ->orWhere('tbl_branch_id', 'like', "%$search%")
                    ->orWhere('createdon', 'like', "%$search%")
                    ->orWhere('tbl_application_status_id', 'like', "%$search%")
                    ->orWhere('source', 'like', "%$search%");
            });

        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                $orderByName = 'customer_name';
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

    public function getSlamsRequests(Request $request)
    {
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // Get data from data table
        $search = $request->search;
        $query = DocumentApplication::with('documentProduct')
            ->with('user')
            ->with('branch')
            ->with('applicationStatus')
            ->where('source', 'SLAMS')
            ->where('deleted', 0)
            ->where(function ($query) use ($search) {
                $query->orWhere('request_no', 'like', "%$search%")
                    ->orWhere('customer_name', 'like', "%$search%")
                    ->orWhere('policy_no', 'like', "%$search%")
                    ->orWhere('tbl_documents_products_id', 'like', "%$search%")
                    ->orWhere('tbl_users_id', 'like', "%$search%")
                    ->orWhere('tbl_branch_id', 'like', "%$search%")
                    ->orWhere('createdon', 'like', "%$search%")
                    ->orWhere('tbl_application_status_id', 'like', "%$search%")
                    ->orWhere('source', 'like', "%$search%");
            });

        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                $orderByName = 'customer_name';
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

    public function getWebsiteRequests(Request $request)
    {
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // Get data from data table
        $search = $request->search;
        $query = DocumentApplication::with('documentProduct')
            ->with('user')
            ->with('branch')
            ->with('applicationStatus')
            ->where('source', 'WEBSITE')
            ->where('deleted', 0)
            ->where(function ($query) use ($search) {
                $query->orWhere('request_no', 'like', "%$search%")
                    ->orWhere('customer_name', 'like', "%$search%")
                    ->orWhere('policy_no', 'like', "%$search%")
                    ->orWhere('tbl_documents_products_id', 'like', "%$search%")
                    ->orWhere('tbl_users_id', 'like', "%$search%")
                    ->orWhere('tbl_branch_id', 'like', "%$search%")
                    ->orWhere('createdon', 'like', "%$search%")
                    ->orWhere('tbl_application_status_id', 'like', "%$search%")
                    ->orWhere('source', 'like', "%$search%");
            });

        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                $orderByName = 'customer_name';
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

    function searchRequests(Request $request)
    {
        // return $request; 
        $form_params = $request->all();
        return redirect()->route('document-search-request')->with('form-params', $form_params);
        $search = $request->all();
        // return $search;

        $query = DocumentApplication::where('deleted', 0);

        if (is_array($search)) {
            if (!empty($search['dynamic_search']) && strlen($search['dynamic_search']) >= 1) {
                $policy = str_replace(' ', '', preg_replace("/[\\x0-\x20\x7f]/", '', $search['dynamic_search']));

                $query->where(function ($subquery) use ($policy) {
                    $subquery->whereRaw("lower(policy_no) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(request_no) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(mobile_no) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(source) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(sms) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(email) like ?", [strtolower($policy)]);
                });
            } else {
                if (!empty($search['start_date']) && !empty($search['end_date'])) {
                    $start_date = Carbon::createFromFormat('d M, Y', $search['start_date'])->format('Y-m-d');
                    $end_date = Carbon::createFromFormat('d M, Y', $search['end_date'])->format('Y-m-d');
                    $query->whereBetween('createdon', [$start_date, $end_date]);
                }

                if (!empty($search['branch_id']) && count($search['branch_id']) > 0) {
                    $query->whereIn('tbl_branch_id', $search['branch_id']);
                }
                
                if (!empty($search['policy_number']) && count($search['policy_number']) > 0) {
                    $query->where(function ($subquery) use ($search) {
                        foreach ($search['policy_number'] as $pnum) {
                            $subquery->orWhereRaw("lower(policy_no) like ?", [strtolower($pnum)]);
                        }
                    });
                }
                
                if (!empty($search['req_number']) && count($search['req_number']) > 0) {
                    $query->where(function ($subquery) use ($search) {
                        foreach ($search['req_number'] as $reqnum) {
                            $subquery->orWhereRaw("lower(request_no) like ?", [strtolower($reqnum)]);
                        }
                    });
                }
            
                if (!empty($search['client_id'])) {
                    $query->whereIn('id', $search['client_id']);
                }
                
                if (!empty($search['product_id'])) {
                    $query->whereIn('tbl_document_applications.tbl_documents_products_id', $search['product_id']);
                }
                
                if (!empty($search['uploaded_by']) && is_array($search['uploaded_by']) && array_sum($search['uploaded_by']) > 0) {
                    $query->whereIn('tbl_document_applications.createdby', $search['uploaded_by']);
                }
                
                if (isset($search['doc_status']) && count($search['doc_status']) > 0) {
                    $query->whereIn('tbl_document_applications.tbl_application_status_id', $search['doc_status']);
                }
            }
        } else {
            $statusIds = (new ApplicationStatusClass(11, 16))->getStatusbyStage([1, 2, 3])->pluck('id')->toArray();
            $user = getUserContext('user_id');
            $branches = limitBranchAccess()->pluck("id");

            $query->whereIn('tbl_application_status_id', $statusIds)
                ->whereIn('tbl_branch_id', $branches)
                ->whereIn('createdby', [$user, $user['reports_to']]);
        }

        return $query->orderByDesc('id')->get();
    }

    public function customSearchRequests(Request $request)
    {
        // return $request;
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        $t_search = $request->search;
        $query = DocumentApplication::with('documentProduct')
        ->with('user')
        ->with('branch')
        ->with('applicationStatus')
        ->where('deleted', 0)
        ->where(function ($query) use ($t_search) {
            $query->orWhere('request_no', 'like', "%$t_search%")
                ->orWhere('customer_name', 'like', "%$t_search%")
                ->orWhere('policy_no', 'like', "%$t_search%")
                ->orWhere('tbl_documents_products_id', 'like', "%$t_search%")
                ->orWhere('tbl_users_id', 'like', "%$t_search%")
                ->orWhere('tbl_branch_id', 'like', "%$t_search%")
                ->orWhere('createdon', 'like', "%$t_search%")
                ->orWhere('tbl_application_status_id', 'like', "%$t_search%")
                ->orWhere('source', 'like', "%$t_search%");
        });

        $search = $request->form_params;
        if (is_array($search)) {
            if (!empty($search['dynamic_search']) && strlen($search['dynamic_search']) >= 1) {
                $policy = str_replace(' ', '', preg_replace("/[\\x0-\x20\x7f]/", '', $search['dynamic_search']));

                $query->where(function ($subquery) use ($policy) {
                    $subquery->whereRaw("lower(policy_no) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(request_no) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(mobile_no) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(source) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(sms) like ?", [strtolower($policy)])
                        ->orWhereRaw("lower(email) like ?", [strtolower($policy)]);
                });
            } else {
                if (!empty($search['start_date']) && !empty($search['end_date'])) {
                    $start_date = Carbon::createFromFormat('d M, Y', $search['start_date'])->format('Y-m-d');
                    $end_date = Carbon::createFromFormat('d M, Y', $search['end_date'])->format('Y-m-d');
                    $query->whereBetween('createdon', [$start_date, $end_date]);
                }

                if (!empty($search['branch_id']) && count($search['branch_id']) > 0) {
                    $query->whereIn('tbl_branch_id', $search['branch_id']);
                }
                
                if (!empty($search['policy_number']) && $search['policy_number']) {
                    $query->where(function ($subquery) use ($search) {
                        $pnum = $search['policy_number'];
                        $subquery->orWhereRaw("lower(policy_no) like ?", [strtolower($pnum)]);
                    });
                }
                
                if (!empty($search['req_number']) && count($search['req_number']) > 0) {
                    $query->where(function ($subquery) use ($search) {
                        foreach ($search['req_number'] as $reqnum) {
                            $subquery->orWhereRaw("lower(request_no) like ?", [strtolower($reqnum)]);
                        }
                    });
                }
            
                if (!empty($search['client_id'])) {
                    $query->whereIn('id', $search['client_id']);
                }
                
                if (!empty($search['product_id'])) {
                    $query->whereIn('tbl_document_applications.tbl_documents_products_id', $search['product_id']);
                }
                
                if (!empty($search['uploaded_by']) && is_array($search['uploaded_by']) && array_sum($search['uploaded_by']) > 0) {
                    $query->whereIn('tbl_document_applications.createdby', $search['uploaded_by']);
                }
                
                if (isset($search['doc_status']) && count($search['doc_status']) > 0) {
                    $query->whereIn('tbl_document_applications.tbl_application_status_id', $search['doc_status']);
                }
            }
        }


        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                $orderByName = 'customer_name';
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

    public function initiateSubmissionReportORG(Request $request){
        $form_params = $request->all();
        return redirect()->route('document.view-report')->with('form-params', $form_params);
    }
    public function generateSubmissionReport(Request $request)
    { 
        // return $request;
        // Page Length
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        $t_search = $request->search;
        $query = DocumentApplication::with('documentProduct')
                ->with('user')
                ->with('branch')
                ->with('applicationStatus')
                ->where('deleted', 0)
                ->where(function ($query) use ($t_search) {
                    $query->orWhere('request_no', 'like', "%$t_search%")
                        ->orWhere('customer_name', 'like', "%$t_search%")
                        ->orWhere('policy_no', 'like', "%$t_search%")
                        ->orWhere('tbl_documents_products_id', 'like', "%$t_search%")
                        ->orWhere('tbl_users_id', 'like', "%$t_search%")
                        ->orWhere('tbl_branch_id', 'like', "%$t_search%")
                        ->orWhere('createdon', 'like', "%$t_search%")
                        ->orWhere('tbl_application_status_id', 'like', "%$t_search%")
                        ->orWhere('source', 'like', "%$t_search%");
                });

        $search = $request->form_params;
        if (is_array($search)) {

            if (!empty($search['start_date']) && !empty($search['end_date'])) {
                $start_date = Carbon::createFromFormat('d M, Y', $search['start_date'])->format('Y-m-d');
                $end_date = Carbon::createFromFormat('d M, Y', $search['end_date'])->format('Y-m-d');
                $query->whereBetween('createdon', [$start_date, $end_date]);
            }

            if (isset($search['branch_id']) && !empty($search['branch_id'])) {
                $query->where('tbl_branch_id', $search['branch_id']);
            }

            if (!empty($search['customer_id']) && !empty($search['customer_id'])) {
                $query->where('tbl_customers_id', $search['customer_id']);
            }

            if (!empty($search['product_id']) && $search['product_id'] == 'ALL CLAIMS') {
                $query->where('tbl_documents_products.id', [2, 3]);
            }

            if (!empty($search['product_id']) && $search['product_id'] == 'ALL PRODUCTS') {
                $query->where('tbl_document_applications.tbl_documents_products_id', [1, 3, 4, 6, 7, 8, 9, 10, 11, 12, 3]);
            }

            if (!empty($search['product_id']) && $search['product_id'] > 1) {
                $query->where('tbl_documents_products.id', $search['pds']);
            }

            if (!empty($search['uploaded_by']) && !empty($search['uploaded_by'])) {
                $query->where('tbl_document_applications.createdby', $search['uploaded_by']);
            }

            if (isset($search['doc_status']) && !empty($search['doc_status'])) {
                $query->where('tbl_document_applications.tbl_application_status_id', $search['doc_status']);
            }
        }

        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'request_no';
                break;
            case '1':
                $orderByName = 'customer_name';
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

    function  initiateSubmissionReporto(Request $request)
    { //getProposalRequestListForReport
        $search = $request->all();

        // $query = DocumentApplication::select([
        //         'tbl_document_applications.*',
        //         'tbl_branch.branch_name',
        //         'tbl_documents_products.product_name',
        //         'tbl_users.full_name',
        //         'tbl_application_status.status_name',
        //         'tbl_users.reports_to.full_name as reports_full_name',
        //         'tbl_document_checklist.mode as checklist_mode',
        //         'tbl_document_checklist.tbl_checklist_status_id as checklist_status_id',
        //         \DB::raw('CASE
        //                     WHEN tbl_document_workflow.tbl_document_workflow_type_id = 1 THEN tbl_document_workflow.tbl_document_setup_details.reference.department_name
        //                     WHEN tbl_document_workflow.tbl_document_workflow_type_id = 2 THEN "n/a"
        //                     WHEN tbl_document_workflow.tbl_document_workflow_type_id = 3 THEN "n/a"
        //                     WHEN tbl_document_workflow.tbl_document_workflow_type_id = 4 THEN createdby.team_leader_id.full_name
        //                     WHEN tbl_document_workflow.tbl_document_workflow_type_id = 5 THEN "Request Completed"
        //                     ELSE "Not Pending in Bin"
        //                 END as pending_dept')
        //     ])
        //     ->where('tbl_document_applications.deleted', 0);
        // $query = DocumentApplication::select([
        //     'tbl_document_applications.*',
        //     'branch.branch_name',
        //     'products.product_name',
        //     'users.full_name',
        //     'application_status.status_name',
        //     'users2.full_name as reports_full_name',
        //     'document_checklist.mode as checklist_mode',
        //     'document_checklist.tbl_checklist_status_id as checklist_status_id',
        //     \DB::raw('CASE
        //                 WHEN workflow.tbl_document_workflow_type_id = 1 THEN setup_details.reference_department.department_name
        //                 WHEN workflow.tbl_document_workflow_type_id = 2 THEN "n/a"
        //                 WHEN workflow.tbl_document_workflow_type_id = 3 THEN "n/a"
        //                 WHEN workflow.tbl_document_workflow_type_id = 4 THEN users3.full_name
        //                 WHEN workflow.tbl_document_workflow_type_id = 5 THEN "Request Completed"
        //                 ELSE "Not Pending in Bin"
        //             END as pending_dept')
        // ])
        // ->join('tbl_document_workflow as workflow', 'workflow.document_application_id', '=', 'tbl_document_applications.id')
        // ->leftJoin('tbl_document_setup_details as setup_details', function($join) {
        //     $join->on('workflow.document_id', '=', 'setup_details.document_id')
        //          ->where('workflow.tbl_document_workflow_type_id', 1);
        // })
        // ->leftJoin('tbl_users as users3', 'tbl_document_applications.created_by', '=', 'users3.id')
        // ->leftJoin('tbl_branch as branch', 'branch.id', '=', 'tbl_document_applications.branch_id')
        // ->leftJoin('tbl_documents_products as products', 'products.id', '=', 'tbl_document_applications.product_id')
        // ->leftJoin('tbl_users as users', 'users.id', '=', 'tbl_document_applications.user_id')
        // ->leftJoin('tbl_application_status as application_status', 'application_status.id', '=', 'tbl_document_applications.status_id')
        // ->leftJoin('tbl_document_checklist as document_checklist', 'document_checklist.application_id', '=', 'tbl_document_applications.id')
        // ->where('tbl_document_applications.deleted', 0);
        $query = DocumentApplication::select([
            'tbl_document_applications.*',
            'branch.branch_name',
            'products.product_name',
            'users.full_name',
            'application_status.status_name',
            'users.reports_to_full_name as reports_full_name',
            'document_checklist.mode as checklist_mode',
            'document_checklist.tbl_checklist_status_id as checklist_status_id',
            \DB::raw('CASE
                        WHEN workflow.tbl_document_workflow_type_id = 1 THEN setup_details.reference_department.department_name
                        WHEN workflow.tbl_document_workflow_type_id = 2 THEN "n/a"
                        WHEN workflow.tbl_document_workflow_type_id = 3 THEN "n/a"
                        WHEN workflow.tbl_document_workflow_type_id = 4 THEN users3.full_name
                        WHEN workflow.tbl_document_workflow_type_id = 5 THEN "Request Completed"
                        ELSE "Not Pending in Bin"
                    END as pending_dept')
        ])
        ->join('tbl_document_workflow as workflow', 'workflow.document_application_id', '=', 'tbl_document_applications.id')
        ->leftJoin('tbl_document_setup_details as setup_details', function($join) {
            $join->on('workflow.document_id', '=', 'setup_details.document_id')
                 ->where('workflow.tbl_document_workflow_type_id', 1);
        })
        ->leftJoin('tbl_users as users3', 'tbl_document_applications.created_by', '=', 'users3.id')
        ->leftJoin('tbl_branch as branch', 'branch.id', '=', 'tbl_document_applications.branch_id')
        ->leftJoin('tbl_documents_products as products', 'products.id', '=', 'tbl_document_applications.product_id')
        ->leftJoin('tbl_users as users', 'users.id', '=', 'tbl_document_applications.user_id')
        ->leftJoin('tbl_application_status as application_status', 'application_status.id', '=', 'tbl_document_applications.status_id')
        ->leftJoin('tbl_document_checklist as document_checklist', 'document_checklist.application_id', '=', 'tbl_document_applications.id')
        ->where('tbl_document_applications.deleted', 0);
    

        if (is_array($search)) {
            if (!empty($search['start_date']) && !empty($search['end_date'])) {
                $start_date = Carbon::createFromFormat('d M, Y', $search['start_date'])->format('Y-m-d');
                $end_date = Carbon::createFromFormat('d M, Y', $search['end_date'])->format('Y-m-d');
                $query->whereBetween('createdon', [$start_date, $end_date]);
            }

            if (isset($search['branch_id']) && !empty($search['branch_id'])) {
                $query->where('tbl_branch_id', $search['branch_id']);
            }

            if (!empty($search['customer_id'])) {
                $query->where('tbl_customers_id', $search['customer_id']);
            }

            // if (!empty($search['pds'])) {
            //     $pdsConditions = [
            //         'ALL CLAIMS' => [2, 3],
            //         'ALL PRODUCTS' => [1, 3, 4, 6, 7, 8, 9, 10, 11, 12, 3]
            //     ];

            //     $query->whereIn('tbl_documents_products.id', $pdsConditions[$search['pds']] ?? [$search['pds']]);
            // }
            if (!empty($search['product_id']) && $search['product_id'] == 'ALL CLAIMS') {
                $query->where('tbl_documents_products.id', [2, 3]);
            }

            if (!empty($search['product_id']) && $search['product_id'] == 'ALL PRODUCTS') {
                $query->where('tbl_document_applications.tbl_documents_products_id', [1, 3, 4, 6, 7, 8, 9, 10, 11, 12, 3]);
            }
            if (!empty($search['product_id']) && $search['product_id'] > 1) {
                $query->where('tbl_documents_products.id', $search['pds']);
            }

            if (!empty($search['uploaded_by']) && !empty($search['uploaded_by'])) {
                $query->where('tbl_document_applications.createdby', $search['uploaded_by']);
            }

            if (isset($search['doc_status']) && !empty($search['doc_status'])) {
                $query->where('tbl_document_applications.tbl_application_status_id', $search['doc_status']);
            }
        }

        $query->join('tbl_branch', 'tbl_document_applications.tbl_branch_id', '=', 'tbl_branch.id')
        ->join('tbl_documents_products', 'tbl_document_applications.tbl_documents_products_id', '=', 'tbl_documents_products.id')
        ->join('tbl_users', 'tbl_document_applications.createdby', '=', 'tbl_users.id')
        ->join('tbl_application_status', 'tbl_document_applications.tbl_application_status_id', '=', 'tbl_application_status.id')
        // ->join('tbl_document_checklist', 'tbl_document_applications.id', '=', 'tbl_document_checklist.tbl_document_applications_id')
        ->join('tbl_document_workflow', 'tbl_document_applications.id', '=', 'tbl_document_workflow.tbl_document_applications_id')

        ->join('tbl_document_checklist', function ($join) {
            $join->on('tbl_document_applications.id', '=', 'tbl_document_checklist.tbl_document_applications_id')
                ->where('tbl_document_checklist.tbl_document_type_id', 1)
                ->where('tbl_document_checklist.deleted', 0);
        });
   


        return $query->orderByDesc('id')->get();
    }


    public function initiateSubmissionReport(Request $request)
    {
        $search = $request->all();
        return view('product-requests.reports.view-report-php', compact('search'));
    }

    public function getProposalRequestListForReport(Request $request)
    {

        // return $request->search;
        // return $search['start_date'];
        // {
        //     "_token": "g0ntUyQwJLcn12r0JlchC5Yz2onjUr34mxcG74LS",
        //     "start_date": "01 Apr, 2022",
        //     "end_date": "15 Apr, 2024",
        //     "branch_id": "2",
        //     "payment_received": "1",
        //     "product_id": "1",
        //     "uploaded_by": "75",
        //     "doc_status": "68"
        //   }
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        $search = $request->search;

        $sdate = $search['start_date'];
        $start_date = Carbon::createFromFormat('d M, Y', $sdate)->format('Y-m-d');

        $edate = $search['end_date'];
        $end_date = Carbon::createFromFormat('d M, Y',  $edate)->format('Y-m-d');

        // return  $sdate.'_'.$start_date .'_'.$edate.'_'.$end_date;

        $searchDst = isset($start_date) ? $start_date : date('Y-m-d', strtotime(now() . "-30 days"));
        $searchDen = isset($end_date) ? $end_date . ' 23:59:59' : now();
        $searchBn = isset($search['branch_id']) ? $search['branch_id'] : null;
        $searchCustomerId = isset($search['customer_id']) ? $search['customer_id'] : null;
        $searchPds = isset($search['product_id']) ? $search['product_id'] : null;
        $searchCby = isset($search['uploaded_by']) ? $search['uploaded_by'] : null;
        $searchDtus = isset($search['doc_status']) ? $search['doc_status'] : null;

        $results = DB::table('tbl_document_applications')
            ->select(
                'tbl_document_applications.*',
                'tbl_branch.branch_name',
                'tbl_documents_products.product_name',
                'tbl_users.full_name',
                'tbl_application_status.status_name',
                'reports_to.full_name as reports_full_name',
                'tbl_document_checklist.mode as checklist_mode',
                'tbl_document_checklist.tbl_checklist_status_id as checklist_status_id',
                DB::raw('CASE
                    WHEN tbl_document_workflow.tbl_document_workflow_type_id = 1 THEN reference.department_name
                    WHEN tbl_document_workflow.tbl_document_workflow_type_id = 2 THEN "n/a"
                    WHEN tbl_document_workflow.tbl_document_workflow_type_id = 3 THEN "n/a"
                    WHEN tbl_document_workflow.tbl_document_workflow_type_id = 4 THEN team_leader_id.full_name
                    WHEN tbl_document_workflow.tbl_document_workflow_type_id = 5 THEN "Request Completed"
                    ELSE "Not Pending in Bin"
                    END as pending_dept')
            )
            ->leftJoin('tbl_document_checklist', 'tbl_document_applications.id', '=', 'tbl_document_checklist.tbl_document_applications_id')
            ->leftJoin('tbl_branch', 'tbl_document_applications.tbl_branch_id', '=', 'tbl_branch.id')
            ->leftJoin('tbl_documents_products', 'tbl_document_applications.tbl_documents_products_id', '=', 'tbl_documents_products.id')
            ->leftJoin('tbl_users', 'tbl_document_applications.tbl_users_id', '=', 'tbl_users.id')
            ->leftJoin('tbl_application_status', 'tbl_document_applications.tbl_application_status_id', '=', 'tbl_application_status.id')
            ->leftJoin('tbl_users as reports_to', 'tbl_users.reports_to', '=', 'reports_to.id')
            ->leftJoin('tbl_document_workflow', 'tbl_document_applications.id', '=', 'tbl_document_workflow.tbl_document_applications_id')
            ->leftJoin('tbl_document_setup_details', 'tbl_document_workflow.tbl_document_setup_details_id', '=', 'tbl_document_setup_details.id')
            ->leftJoin('tbl_departments as reference', 'tbl_document_setup_details.reference', '=', 'reference.id')
            ->leftJoin('tbl_users as createdby', 'tbl_document_applications.createdby', '=', 'createdby.id')
            ->leftJoin('tbl_users as team_leader_id', 'createdby.team_leader_id', '=', 'team_leader_id.id')
            ->where('tbl_document_applications.deleted', 0)
            ->whereBetween('tbl_document_applications.createdon', [$searchDst, $searchDen])
            ->when(isset($searchBn), function ($query) use ($searchBn) {
                return $query->where('tbl_branch_id', $searchBn);
            })
            ->when(isset($searchCustomerId), function ($query) use ($searchCustomerId) {
                return $query->where('tbl_customers_id', $searchCustomerId);
            })
            ->when(isset($searchPds), function ($query) use ($searchPds) {
                if ($searchPds == 'ALL CLAIMS') {
                    return $query->whereIn('tbl_documents_products.id', [2, 3]);
                } elseif ($searchPds == 'ALL PRODUCTS') {
                    return $query->whereIn('tbl_documents_products.id', [1, 3, 4, 6, 7, 8, 9, 10, 11, 12, 3]);
                } elseif ($searchPds > 1) {
                    return $query->where('tbl_documents_products.id', $searchPds);
                }
                return $query;
            })
            ->when(isset($searchCby), function ($query) use ($searchCby) {
                return $query->where('tbl_document_applications.createdby', $searchCby);
            })
            ->when(isset($searchDtus), function ($query) use ($searchDtus) {
                return $query->where('tbl_document_applications.tbl_application_status_id', $searchDtus);
            })
            ->where('tbl_document_checklist.tbl_document_type_id', 1)
            ->where('tbl_document_checklist.deleted', 0)
            ->orderByDesc('tbl_document_applications.id');
            // ->get();
        // return $results;

        $orderByName = "id";
        $orderBy = "desc";

        $results->orderBy($orderByName, $orderBy);

        $recordsFiltered = $recordsTotal = $results->count();
        $all_submissions = $results->skip($skip)->take($pageLength)->get();

        return response()->json(["draw" => $request->draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $all_submissions ], 200);
   

        return view('product-requests.reports.view-report-php', compact('results'));
    
    }



    public function proposalReceivedReport(Request $request)
    {
        // $validatedData = $request->validate([
        //     'start' => 'nullable|date_format:d M, Y',
        //     'end' => 'nullable|date_format:d M, Y',
        //     'prod' => 'nullable|exists:tbl_documents_products,id',
        //     'dept' => 'nullable|exists:tbl_departments,id',
        //     'bn' => 'nullable',
        //     'cby' => 'nullable',
        //     'dtus' => 'nullable',
        // ]);

        $validatedData = $request->all();

        // return  $validatedData;
        // return count($validatedData['prod']);
    
        $query = DocumentWorkflow::with(['tbl_document_applications', 'tbl_document_setup_details'])->where('deleted', 0)
            ->where('started_yn', 1)
            ->where('completed_yn', 0);
    
        if (!empty($validatedData['start']) && !empty($validatedData['end'])) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereBetween('createdon', [
                    Carbon::createFromFormat('d M, Y', $validatedData['start'])->startOfDay(),
                    Carbon::createFromFormat('d M, Y', $validatedData['end'])->endOfDay()
                ]);
            });
        }
    
        if (!empty($validatedData['prod']) && count ($validatedData['prod']) > 0 && $validatedData['prod'][0] !== null) {
            $searchPds = $validatedData['prod'];
            $query->whereHas('tbl_document_applications', function ($q) use ($searchPds) {
                $q->when($searchPds, function ($query) use ($searchPds) {
                    return $query->where(function ($query) use ($searchPds) {
                        if ($searchPds == 'ALL CLAIMS') {
                            $query->whereIn('tbl_documents_products_id', [2, 3]);
                        } elseif ($searchPds == 'ALL PRODUCTS') {
                            $query->whereIn('tbl_documents_products_id', [1, 3, 4, 6, 7, 8, 9, 10, 11, 12]);
                        } elseif ($searchPds > 1) {
                            $query->whereIn('tbl_documents_products_id', $searchPds);
                        }
                    });
                });
            });
        }

        if (!empty($validatedData['dept']) && count($validatedData['dept']) > 0 && $validatedData['dept'][0] !== null) {
            $query->whereHas('tbl_document_setup_details', function ($q) use ($validatedData) {
                $q->whereIn('reference', $validatedData['dept']);
            });
        }
    
        if (!empty($validatedData['bn']) && count($validatedData['bn']) > 0 && $validatedData['bn'][0] !== null ) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereIn('tbl_branch_id', $validatedData['bn']);
            });
        }
    
        if (!empty($validatedData['cby']) && count($validatedData['cby']) > 0 && $validatedData['cby'][0] !== null) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereIn('createdby', $validatedData['cby']);
            });
        }
    
        if (!empty($validatedData['dtus']) && count($validatedData['dtus']) > 0 && $validatedData['dtus'][0] !== null) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereIn('tbl_application_status_id', $validatedData['dtus']);
            });
        }

        $results = $query->orderByDesc('id')->get();

        // return $results;

        $fromDate = Carbon::createFromFormat('d M, Y', $validatedData['start'])->startOfDay();
        $toDate = Carbon::createFromFormat('d M, Y', $validatedData['end'])->endOfDay();
        
        return view('product-requests.reports.view-report-proposals-received', compact('results', 'fromDate', 'toDate'));
        






        //
        $search = $request->all();
        // return $search;
        $query = DocumentWorkflow::query()
            ->where('deleted', 0)
            ->where('started_yn', 1)
            ->where('completed_yn', 0);

        $sdate = $search['start'];
        $search['dst'] = Carbon::createFromFormat('d M, Y', $sdate)->format('Y-m-d');

        $edate = $search['end'];
        $search['den']= Carbon::createFromFormat('d M, Y',  $edate)->format('Y-m-d');

        if (!empty($search['prod'])) {
            $query->whereHas('tbl_document_applications', function ($q) use ($search) {
                $q->where('tbl_documents_products_id', $search['prod']);
            });
        }

        if (!empty($search['dept'])) {
            $query->whereHas('tbl_document_setup_details', function ($q) use ($search) {
                $q->where('reference', $search['dept']);
            });
        }

        if (empty($search['dst'])) {
            $search['dst'] = now()->subDays(30)->format('Y-m-d');
        }

        if (empty($search['den'])) {
            $search['den'] = now()->format('Y-m-d 23:59:59');
        } else {
            $search['den'] .= ' 23:59:59';
        }

        $query->whereHas('tbl_document_applications', function ($q) use ($search) {
            $q->whereBetween('createdon', [$search['dst'], $search['den']]);
            
            if (!empty($search['bn'])) {
                $q->where('tbl_branch_id', $search['bn']);
            }

            if (!empty($search['cby'])) {
                $q->where('createdby', $search['cby']);
            }

            if (!empty($search['dtus'])) {
                $q->where('tbl_application_status_id', $search['dtus']);
            }
        });

        return $query->orderByDesc('id')->get();
    }


    public function proposalRejectedReport(Request $request)
    {
        $validatedData = $request->all();

        // return  $validatedData;
        // return count($validatedData['prod']);
        $status = new ApplicationStatusClass(11, 16);
        $rejected = $status->getStatusByEndpoint(4);

    
        $query = DocumentWorkflow::with(['tbl_document_applications', 'tbl_document_setup_details'])->where('deleted', 0)
            ->where('started_yn', 1)
            ->where('completed_yn', 0)
            // ->whereHas('tbl_document_applications', function ($q) use ($rejected) {
            //     $q->where('tbl_application_status_id', $rejected->id);
            // })
            ;
    
        if (!empty($validatedData['start']) && !empty($validatedData['end'])) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereBetween('createdon', [
                    Carbon::createFromFormat('d M, Y', $validatedData['start'])->startOfDay(),
                    Carbon::createFromFormat('d M, Y', $validatedData['end'])->endOfDay()
                ]);
            });
        }
    
        if (!empty($validatedData['prod']) && count ($validatedData['prod']) > 0 && $validatedData['prod'][0] !== null) {
            $searchPds = $validatedData['prod'];
            $query->whereHas('tbl_document_applications', function ($q) use ($searchPds) {
                $q->when($searchPds, function ($query) use ($searchPds) {
                    return $query->where(function ($query) use ($searchPds) {
                        if ($searchPds == 'ALL CLAIMS') {
                            $query->whereIn('tbl_documents_products_id', [2, 3]);
                        } elseif ($searchPds == 'ALL PRODUCTS') {
                            $query->whereIn('tbl_documents_products_id', [1, 3, 4, 6, 7, 8, 9, 10, 11, 12]);
                        } elseif ($searchPds > 1) {
                            $query->whereIn('tbl_documents_products_id', $searchPds);
                        }
                    });
                });
            });
        }

        if (!empty($validatedData['dept']) && count($validatedData['dept']) > 0 && $validatedData['dept'][0] !== null) {
            $query->whereHas('tbl_document_setup_details', function ($q) use ($validatedData) {
                $q->whereIn('reference', $validatedData['dept']);
            });
        }
    
        if (!empty($validatedData['bn']) && count($validatedData['bn']) > 0 && $validatedData['bn'][0] !== null ) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereIn('tbl_branch_id', $validatedData['bn']);
            });
        }
    
        if (!empty($validatedData['cby']) && count($validatedData['cby']) > 0 && $validatedData['cby'][0] !== null) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereIn('createdby', $validatedData['cby']);
            });
        }
    
        if (!empty($validatedData['dtus']) && count($validatedData['dtus']) > 0 && $validatedData['dtus'][0] !== null) {
            $query->whereHas('tbl_document_applications', function ($q) use ($validatedData) {
                $q->whereIn('tbl_application_status_id', $validatedData['dtus']);
            });
        }

        $results = $query->orderByDesc('id')->get();

        // return $results;

        $fromDate = Carbon::createFromFormat('d M, Y', $validatedData['start'])->startOfDay();
        $toDate = Carbon::createFromFormat('d M, Y', $validatedData['end'])->endOfDay();
        
        return view('product-requests.reports.view-report-proposals-rejected', compact('results', 'fromDate', 'toDate'));

        //
        $search = $request->all();

        $status = new ApplicationStatusClass(11, 16);
        $rejected = $status->getStatusByEndpoint(4);
        
        $query = DocumentWorkflow::with('tbl_document_setup_details')->with('tbl_document_applications')->query()
            ->where('deleted', 0)
            // ->whereHas('documentApplication', function ($q) use ($rejected) {
            //     $q->where('tbl_application_status_id', $rejected->id);
            // })
            ;

        $sdate = $search['start'];
        $search['dst'] = Carbon::createFromFormat('d M, Y', $sdate)->format('Y-m-d');

        $edate = $search['end'];
        $search['den']= Carbon::createFromFormat('d M, Y',  $edate)->format('Y-m-d');
    

        if (!empty($search['prod'])) {
            $query->whereHas('documentApplication', function ($q) use ($search) {
                $q->where('tbl_documents_products_id', $search['prod']);
            });
        }

        if (!empty($search['dept'])) {
            $query->whereHas('documentApplication.documentSetupDetail', function ($q) use ($search) {
                $q->where('reference', $search['dept']);
            });
        }

        if (!empty($search['status'])) {
            $query->whereHas('documentApplication', function ($q) use ($search) {
                $q->where('tbl_application_status_id', $search['status']);
            });
        }

        if (empty($search['dst'])) {
            $search['dst'] = now()->subDays(30)->format('Y-m-d');
        }

        if (empty($search['den'])) {
            $search['den'] = now()->format('Y-m-d 23:59:59');
        } else {
            $search['den'] .= ' 23:59:59';
        }

        $query->whereHas('documentApplication', function ($q) use ($search) {
            $q->whereBetween('createdon', [$search['dst'], $search['den']]);
            
            if (!empty($search['bn'])) {
                $q->where('tbl_branch_id', $search['bn']);
            }

            if (!empty($search['cby'])) {
                $q->where('createdby', $search['cby']);
            }
        });

        return $query->orderByDesc('id')->get();
    }




}
