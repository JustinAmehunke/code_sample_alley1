<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentProduct;
use App\Models\DocumentWorkflow;
use App\Models\DocumentApplication;
use App\Models\RequestedDocumentCustom; 
use App\Models\User;
use Auth;

use App\Models\Menu;

class IndexController extends Controller
{
    public function index(){
        // $menus = Menu::getSidebarParentMenus();
        $document_products = DocumentProduct::where('deleted', 0)->get();
        // Assuming models are named accordingly:
       
        return view('admin.index', compact('document_products'));
    }

    public function pendingBin(){
        $perPage = 500; // 

        $proposals = DocumentWorkflow::with(['tbl_document_applications',
        'tbl_document_applications.tbl_documents_products',
        'tbl_document_applications.tbl_users',
        'tbl_document_applications.tbl_branch',
        'tbl_document_applications.tbl_application_status'])
            ->where('started_yn', 1)
            ->where('completed_yn', 0)
            ->where('deleted', 0)
            ->whereHas('tbl_document_applications', function ($query) {
                $query->whereIn('tbl_application_status_id', [66, 71])
                    ->where('deleted', 0);
            })
            ->whereHas('tbl_document_setup_details', function ($query) {
                $query->where('reference', auth()->user()->tbl_departments_id);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return view('admin.my-pending-bin', compact('proposals'));
    }

    public function ajaxPendingBin(Request $request){
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;
    
        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';
    
        $search = $request->search;
    
        $query = DocumentWorkflow::with(['tbl_document_applications',
        'tbl_document_applications.tbl_documents_products',
        'tbl_document_applications.tbl_users',
        'tbl_document_applications.tbl_branch',
        'tbl_document_applications.tbl_application_status'])
        ->where('started_yn', 1)
        ->where('completed_yn', 0)
        ->where('deleted', 0)
        ->whereHas('tbl_document_applications', function ($query) use ($search) {
            $query->whereIn('tbl_application_status_id', [66, 71])
                ->where('deleted', 0)
                ->where(function ($query) use ($search) {
                    $query->where('request_no', 'like', "%$search%")
                        ->orWhere('policy_no', 'like', "%$search%")
                        ->orWhere('customer_name', 'like', "%$search%")
                        ->orWhere('source', 'like', "%$search%")
                        ->orWhereHas('tbl_documents_products', function ($query) use ($search) {
                            $query->where('product_name', 'like', "%$search%");
                        })
                        ->orWhereHas('tbl_users', function ($query) use ($search) {
                            $query->where('full_name', 'like', "%$search%");
                        })
                        ->orWhereHas('tbl_application_status', function ($query) use ($search) {
                            $query->where('status_name', 'like', "%$search%");
                        })
                        ->orWhereHas('tbl_branch', function ($query) use ($search) {
                            $query->where('branch_name', 'like', "%$search%");
                        });
                });
               
        });
    
        // return $query->get();
    
        // Order By Logic
        switch ($orderColumnIndex) {
            case '0':
                $query->orderBy('id', $orderBy);
                break;
            case '1':
                $query->orderBy('tbl_document_applications.request_no', $orderBy);
                break;
            case '2':
                $query->orderBy('tbl_document_applications.policy_no', $orderBy);
                break;
            case '3':
                $query->orderBy('customer_name', $orderBy);
                break;
            case '4':
                $query->orderBy('tbl_documents_products.product_name', $orderBy);
                break;
            case '5':
                $query->orderBy('tbl_users.full_name', $orderBy);
                break;
            case '6':
                $query->orderBy('tbl_document_applications.createdon', $orderBy);
                break;
            case '7':
                $query->orderBy('tbl_document_applications.last_updated_date', $orderBy);
                break;
            case '8':
                $query->orderBy('tbl_branch.branch_name', $orderBy);
                break;
            case '9':
                $query->orderBy('tbl_document_applications.source', $orderBy);
                break;
            case '10':
                $query->orderBy('tbl_application_status.status_name', $orderBy);
                break;
            default:
                $query->orderBy('id', $orderBy);
                break;
        }
    
        $recordsFiltered = $recordsTotal = $query->count();
        $users = $query->skip($skip)->take($pageLength)->get();
    
        return response()->json(["draw" => $request->draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $users], 200);
    }
    

    public function ajaxPendingBin0(Request $request){
        $pageNumber = ($request->start / $request->length) + 1;
        $pageLength = $request->length;
        $skip = ($pageNumber - 1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        $search = $request->search;

        $query = DocumentWorkflow::with(['tbl_document_applications', 'tbl_users', 'tbl_documents_products'])
                ->where('started_yn', 1)
                ->where('completed_yn', 0)
                ->where('deleted', 0)
                ->whereHas('tbl_document_applications', function ($query) use ($search) {
                    $query->whereIn('tbl_application_status_id', [66, 71])
                        ->where('deleted', 0)
                        ->where(function ($query) use ($search) {
                            $query->where('request_no', 'like', "%$search%")
                                ->orWhere('policy_no', 'like', "%$search%")
                                ->orWhere('customer_name', 'like', "%$search%")
                                ->orWhere('source', 'like', "%$search%");
                        })
                        ->whereHas('tbl_documents_products', function ($query) use ($search) {
                            $query->where('product_name', 'like', "%$search%");
                        })
                        ->orWhereHas('tbl_application_status', function ($query) use ($search) {
                            $query->where('status_name', 'like', "%$search%");
                        })
                        ->orWhereHas('tbl_branch', function ($query) use ($search) {
                            $query->where('branch_name', 'like', "%$search%");
                        })
                        ->orWhereHas('tbl_users', function ($query) use ($search) {
                            $query->where('full_name', 'like', "%$search%");
                        });
        });
                // ->get();
            
        $orderByName = 'id';
        switch ($orderColumnIndex) {
            case '0':
                $orderByName = 'id';
                break;
            case '1':
                $orderByName = 'request_no';
                break;
            case '2':
                $orderByName = 'policy_no';
                break;
            case '3':
                $orderByName = 'customer_name';
                break;
            case '4':
                $orderByName = 'tbl_documents_products.product_name';
                break;
            case '5':
                $orderByName = 'tbl_users.full_name';
                break;
            case '6':
                $orderByName = 'tbl_document_applications.createdon';
                break;
            case '7':
                $orderByName = 'tbl_document_applications.last_updated_date';
                break;
            case '8':
                $orderByName = 'tbl_branch.branch_name';
                break;
            case '9':
                $orderByName = 'tbl_document_applications.source';
                break;
            case '9':
                $orderByName = 'tbl_application_status.status_name ';
                break;
        }
        
        // Move the orderBy clause before get() to avoid the error
        $query->orderBy($orderByName, $orderBy);

        $recordsFiltered = $recordsTotal = $query->count();
        $users = $query->skip($skip)->take($pageLength)->get();

        return response()->json(["draw" => $request->draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $users], 200);
    
       

    }

    public function requestedDocuments(){
        $perPage = 500;
        // Query for requested documents
        $requested_docs = RequestedDocumentCustom::where('tbl_users_id', Auth::user()->id)
        ->where('deleted', 0)
        ->orderBy('id', 'desc')
        ->paginate($perPage);

       
        return view('admin.requested-documents-dashboard', compact('requested_docs'));
    }
    public function faDashboard(){
        $perPage = 500; 
        // Query for fabin
        $fabin = DocumentWorkflow::with('tbl_document_applications')->where('started_yn', 1)
        ->where('completed_yn', 0)
        ->where('deleted', 0)
        ->whereHas('tbl_document_applications', function ($query) {
            $query->whereIn('tbl_application_status_id', [66, 71])
                ->where('tbl_users_id', Auth::user()->id)
                ->where('deleted', 0);
        })->orderBy('id', 'desc')->paginate($perPage);
        // return $fabin ;

        return view('admin.fa-dashboard', compact('fabin'));
    }
    public function teamLeadDashboard(){
        $perPage = 500;
        // Query for team leads' team - team_leader_id reports_to
        $team_leads_team = User::where('team_leader_id', Auth::user()->id)
        ->where('deleted', 0)
        ->pluck('id');

        // Query for team leads' team bin
        $team_leads_team_bin = DocumentWorkflow::where('started_yn', 1)
        ->where('completed_yn', 0)
        ->where('deleted', 0)
        ->whereHas('tbl_document_applications', function ($query) use ($team_leads_team) {
            $query->whereIn('tbl_application_status_id', [66, 71])
            ->whereIn('tbl_users_id', $team_leads_team)
            ->where('deleted', 0);
        })->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.team-lead-dashboard', compact('team_leads_team_bin'));
    }

    public function corporateInfill(Request $request){
        return response( [
            'status' => 'success',
            'restrictions'=> $request,
            'message' => 'Changes received successfully',
        ], 200);
    }
}
