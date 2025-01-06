<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintType;
use App\Models\UserCategory;
use App\Models\UserSubCategory;
use App\Models\Department;
use App\Models\Branch;
use App\Models\Requisition;
use App\Models\DocumentType;
use App\Models\DocumentSetup;
use App\Models\Goals;
use App\Models\RequisitionTimeLimit;
use App\Models\Restriction;
use App\Models\DocumentProduct;
use App\Models\DocumentWorkflowType;
use App\Models\DocumentSetupDetail;
use App\Models\OrganisationUnit;
use App\Models\DocumentsProductsChecklist;
use App\Models\User;
use App\Models\Badge;
use App\Models\Region;
use App\Models\City;

class AdministratorController extends Controller
{
    public function complaintsType(){
        $complaintTypes = ComplaintType::where('deleted', 0)->get();
        return view('admin.complaint-types', compact('complaintTypes'));
    }
    public function listUsers(){
        $users = User::with('designation')->with('department')->where('deleted', 0)->get();
        // return  $users;
        return view('admin.list-users', compact('users'));
    }
    public function manageProfile(){
        return view('admin.manage-profile');
    }
    public function mainCategory(){
        $user_categories = UserCategory::where('deleted', 0)->get();
        return view('admin.main-category', compact('user_categories'));
    }
    public function subCategory(){
        $user_subcategories = UserSubCategory::with('usercategory')->where('deleted', 0)->get();
        $user_categories = UserCategory::where('deleted', 0)->get();
        // return  $user_categories;
        return view('admin.sub-category', compact('user_subcategories', 'user_categories'));
    }
    public function listPartners(){
        // return Auth()->user()->id;
        $conditions = [
            ['state', '=', 1],
            ['has_branch', '=', 'user_belong_to_branch'],
            ['internal', '=', 0],
            ['service_provider', '=', 1],
            ['deleted', '=', 0],
        ];
        $types = UserCategory::where($conditions)->get();
        $user_categories = UserSubCategory::where('deleted', 0)->get();

        $branches = Branch::where([['deleted', '=', 0], ['tbl_organisation_unit_id', '=', 1]])->get();

        $partners = OrganisationUnit::with('usercategory')->with('usersubcategory')->where('deleted', 0)->get();
        // return $partners;
        return view('admin.list-partners', compact('partners', 'types', 'branches', 'user_categories'));
    }
    public function listDepartments(){
        $departments = Department::where('deleted', 0)->get();
        return view('admin.departments', compact('departments'));
    }
    public function listRequisition(){
        $user_categories = Requisition::where('deleted', 0)->get();
        return view('admin.requisition', compact('user_categories'));
    }
    public function listBranches(){
        $branches = Branch::with('organisation')->where('deleted', 0)->get();
        $partners = OrganisationUnit::with('usercategory')->with('usersubcategory')->where('deleted', 0)->get();
        $regions = Region::where('deleted', 0)->get();
        $cities = City::where('deleted', 0)->get();
        return view('admin.branches', compact('branches', 'partners', 'regions', 'cities'));
    }
    //
    public function documentManageProduct(){
        $document_products = DocumentProduct::where('deleted', 0)->get();
        return view('admin.documents.manage-products', compact('document_products'));
    }
    public function documentNewProduct(){
        $document_products = DocumentProduct::where('deleted', 0)->get();
        return view('admin.documents.new-product', compact('document_products'));
    }
    public function documentViewProduct(Request $request){
        $id = base64_decode($request->query('id'));

        $document_product = DocumentProduct::find($id);
        $document_products_checklists = DocumentsProductsChecklist::where([['tbl_documents_products_id', $id], [ 'deleted', 0]])->get();
        // return $document_products_checklists;
        return view('admin.documents.view-product', compact('document_product', 'document_products_checklists'));
    }

    public function documentCreateProduct(){
        $document_products = DocumentProduct::where('deleted', 0)->get();
        return view('admin.documents.manage-products', compact('document_products'));
    }
    public function documentUpdateProduct(){
        $document_products = DocumentProduct::where('deleted', 0)->get();
        return view('admin.documents.manage-products', compact('document_products'));
    }


    public function documentType(){
        $document_types= DocumentType::with('badge')->where('deleted', 0)->get();
        $badges = Badge::where('deleted', 0)->get();
        return view('admin.documents.manage-types', compact('document_types', 'badges'));
    }
    public function documentRestriction(){
        $restrictions = Restriction::where('deleted', 0)->get();
        return view('admin.documents.manage-restrictions', compact('restrictions'));
    }
    public function documentWorkflow(){
        $document_setups = DocumentSetup::where('deleted', 0)->get();
        $document_products = DocumentProduct::all();
        return view('admin.documents.manage-workflows', compact('document_setups', 'document_products'));
    }
    public function documentWorkflowDetails($workflow){
        $document_setup_id = base64_decode($workflow);
        $document_setups = DocumentSetup::where('deleted', 0)->get();
        $workflow_types = DocumentWorkflowType::where('deleted', 0)->get();
        $document_setup_details = DocumentSetupDetail::where('tbl_document_setup_id', $document_setup_id)->where('deleted', 0)->get();
       
        $departments = Department::where('deleted', 0)->get();
        $document_types = DocumentType::where('deleted', 0)->get();
        $users = User::where('deleted', 0)->get();

        $document_products = DocumentProduct::all();

        return view('admin.documents.manage-workflows', compact('document_setups', 'document_setup_details', 'departments', 'document_types' , 'workflow_types', 'users', 'document_setup_id', 'document_products'));
    }
    public function documentProduct(){
        $document_products = DocumentProduct::where('deleted', 0)->get();
        return view('admin.documents.manage-products', compact('document_products'));
    }
    //
    public function seGoals(){
        $branches = Branch::where('deleted', 0)->get();
        return view('admin.dashboard.set-goals', compact('goals'));
    }
    public function setRequisition(){
        $branches = Branch::where('deleted', 0)->get();
        return view('admin.inventory.set-requisition', compact('requisitions'));
    }
    public function expiryNotification(){
        $branches = Branch::where('deleted', 0)->get();
        return view('admin.inventory.set-expiry-notification', compact('expiry_notifications'));
    }

    public function createUpdateUser(){
        return view('admin.create-user');
    }

    public function switchProfile(Request $request){
        $des_id = base64_decode($request->des_id);
        $dep_id = base64_decode($request->dep_id);

        // return $des_id."_".$dep_id;

        $user_id = auth()->user()?->id;

        if($user_id){
           $u =  User::findOrFail($user_id)->update([
                'tbl_designations_id' => $des_id,
                'tbl_departments_id' => $dep_id,
            ]);

            // return User::find($user_id);

            return response([
                "status" => "success",
            ], 200);
        }else{
            return response([
                "status" => "error",
            ], 400);
        }

        

       
    }
}
