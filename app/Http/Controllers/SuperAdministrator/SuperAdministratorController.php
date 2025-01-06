<?php

namespace App\Http\Controllers\SuperAdministrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\FaIcons;
use App\Http\Traits\Menus;
use Illuminate\Support\Facades\Crypt;
use App\Models\Menu;
use App\Models\Department;
use App\Models\Designation;
use App\Models\UserRole;
use App\Models\CompanyProfile;
use App\Models\CompanyProfileBank;
use App\Models\EmailTemplate;
use DB;
use Auth;
class SuperAdministratorController extends Controller
{
    use FaIcons;
    use Menus;

    public function listMenu(){
        $message = session('success_message');
        $menus = Menu::all();
        //
        $designation_id = Auth::user()->tbl_designations_id;
        $user_menu = DB::table('tbl_menu')
        ->select('tbl_menu.*')
        ->join('tbl_user_role as e', 'e.tbl_menu_id', '=', 'tbl_menu.id')
        ->where('tbl_menu.status', 1)
        ->where(function($query) use ($designation_id) {
            $query->where('e.tbl_designations_id', $designation_id)
                ->orWhere('tbl_menu.default_item', 1);
        })
        ->orderBy('tbl_menu.parent')
        ->orderBy('tbl_menu.sort')
        ->get();
        // return  $user_menu;
        return view('super-admin.menu.list-menu', compact('menus'))->with('message', $message);
    }
    public function newMenu(){
        $departments = Department::with('designations')->where('deleted', 0)->orderBy('department_name')->get();
        // return $departments;
        $departmentsdata = [];

        foreach ($departments as $department) {
            $items = [];
            foreach ($department['designations'] as $desig) {
                array_push($items, array(
                    'id'=>$desig->id, 
                    'designations'=> $desig->designations, 
                ));
            }

            array_push($departmentsdata, array(
                'id' => $department->id, 
                'department_name' => $department->department_name, 
                'expanded' => false, 
                'items' => $items
            ));
        }

        // Retrived from Traits
        $faicons = $this->getFaIcons();
        $parentsMenu = $this->getChildrenMenus();
        $childrenMenu = $this->getParentMenus();
        
        return view('super-admin.menu.add-menu', compact('departmentsdata', 'faicons', 'childrenMenu', 'parentsMenu'));
    }
    public function editMenu($encryptedId){
        $id = Crypt::decrypt($encryptedId);
        $menu = Menu::find($id);
        $userRole = UserRole::where('tbl_menu_id',$id)->pluck('tbl_designations_id')->toArray();
        
        $departments = Department::with('designations')->where('deleted', 0)->orderBy('department_name')->get();
        // return $departments;
        $departmentsdata = [];

        foreach ($departments as $department) {
            $items = [];
            foreach ($department['designations'] as $desig) {
                array_push($items, array(
                    'id'=>$desig->id, 
                    'designations'=> $desig->designations, 
                ));
            }

            array_push($departmentsdata, array(
                'id' => $department->id, 
                'department_name' => $department->department_name, 
                'expanded' => false, 
                'items' => $items
            ));
        }

        // Retrived from Traits
        $faicons = $this->getFaIcons();
        $parentsMenu = $this->getChildrenMenus();
        $childrenMenu = $this->getParentMenus();
        return view('super-admin.menu.edit-menu', compact('departmentsdata','faicons', 'childrenMenu', 'parentsMenu', 'menu', 'userRole'));
    }

    public function companyProfile(){
        $company_profile = CompanyProfile::where('deleted', 0)->first();
        $bank_details =  CompanyProfileBank::where('deleted', 0)->get();
        return view('super-admin.company-profile', compact('company_profile', 'bank_details'));
    }

    public function emailTemplates(){
        $categories = EmailTemplate::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->get();
        $emailTemplates = EmailTemplate::all();
        $caategory = "";
        $viewEmailTemplate = EmailTemplate::first();
        return view('super-admin.email-templates.email-templates', compact('emailTemplates', 'categories', 'viewEmailTemplate', 'caategory'));
    }

    public function viewEmailTemplate($encryptedId){
        $id = Crypt::decrypt($encryptedId);
        $viewEmailTemplate = EmailTemplate::find($id);

        $categories = EmailTemplate::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->get();
        $emailTemplates = EmailTemplate::all();
        $caategory = $viewEmailTemplate->category;
        return view('super-admin.email-templates.email-templates', compact('emailTemplates', 'categories', 'viewEmailTemplate', 'caategory'));
    }

    public function detailsEmailTemplate($encryptedId){
        $id = Crypt::decrypt($encryptedId);
        $viewEmailTemplate = EmailTemplate::find($id);

        $categories = EmailTemplate::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->get();
        $emailTemplates = EmailTemplate::all();
        $caategory = $viewEmailTemplate->category;
        return view('super-admin.email-templates.details-email-template', compact('emailTemplates', 'categories', 'viewEmailTemplate', 'caategory'));
    }


    public function newEmailTemplate(){
        $categories = EmailTemplate::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->get();
        return view('super-admin.email-templates.new-email-template', compact('categories'));
    }
    public function categoryEmailTemplate($encryptedCategory){
        $caategory = Crypt::decrypt($encryptedCategory);
        $viewEmailTemplate = EmailTemplate::where('category', $caategory)->first();

        $categories = EmailTemplate::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->get();
        $emailTemplates = EmailTemplate::where('category', $caategory)->get();
        return view('super-admin.email-templates.email-templates', compact('emailTemplates', 'categories', 'viewEmailTemplate', 'caategory'));
    }
    public function applicationStatus(){
        return view('super-admin.application-status.application-status');
    }
}
