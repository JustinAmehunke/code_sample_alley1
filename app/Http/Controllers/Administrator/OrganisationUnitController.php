<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganisationUnit;
use App\Http\Traits\CodeGenerator;
use App\Models\UserSubCategory;

class OrganisationUnitController extends Controller
{
    use CodeGenerator;
    public function savePartner(Request $request){

        try {
            $insertedId = OrganisationUnit::insertGetId([
                'org_name' => ucfirst(strtolower($request->org_name)),
                'org_address' => $request->org_address,
                'org_contact' => ucfirst(strtolower($request->org_contact)),
                'org_contact_no' => $request->org_contact_no,
                'org_alt_contact_no' => $request->org_alt_contact_no,
                'org_nic_no' => $request->org_nic_no,
                // 'deleted' => isset($request->status) ? $request->status : 0,
                'org_contact_email' => strtolower($request->org_contact_email),
                // 'tbl_branch_id' => isset($request->branch_id) ? $request->branch_id : Auth()->id(),
                'tbl_user_category_id' => $request->org_type,
                'tbl_sub_user_category_id' => (empty($request->sub_org_type) ? 0 : $request->sub_org_type),
                'is_local' => $request->org_is_local,
            ]);

            $generatedCode = $this->generateCode('partner', array(
                'id' => $insertedId,
                'tbl_user_category_id' => $request->org_type,
                'tbl_sub_user_category_id' => (empty($request->sub_org_type) ? 0 : $request->sub_org_type),
            ));

            OrganisationUnit::findOrFail($insertedId)->update([
                "code" => $generatedCode
            ]);

            $partners = OrganisationUnit::with('usercategory')->with('usersubcategory')->where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'partners'=> $partners,
                'message' => 'Changes saved successfully',
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response( [
                'status' => 'failed',
                'error'=> $th->getMessage(),
                'message' => 'Something went wrong',
            ], 200);
        }
    }
    public function updatePartner(Request $request){
        // return $request;
        try {
            OrganisationUnit::findOrFail($request->org_id)->update([
                'org_name' => ucfirst(strtolower($request->org_name)),
                'org_address' => $request->org_address,
                'org_contact' => ucfirst(strtolower($request->org_contact)),
                'org_contact_no' => $request->org_contact_no,
                'org_alt_contact_no' => $request->org_alt_contact_no,
                'org_nic_no' => $request->org_nic_no,
                // 'deleted' => isset($request->status) ? $request->status : 0,
                'org_contact_email' => strtolower($request->org_contact_email),
                // 'tbl_branch_id' => isset($request->branch_id) ? $request->branch_id : Auth()->id(),
                'tbl_user_category_id' => $request->org_type,
                'tbl_sub_user_category_id' => (empty($request->sub_org_type) ? 0 : $request->sub_org_type),
                'is_local' => $request->org_is_local,
            ]);

            $partners = OrganisationUnit::with('usercategory')->with('usersubcategory')->where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'partners'=> $partners,
                'message' => 'Changes saved successfully',
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response( [
                'status' => 'failed',
                'error'=> $th->getMessage(),
                'message' => 'Something went wrong',
            ], 200);
        }
       

		// if (empty($row['code'])) {
		// 	$data['code'] = generateCode('partner', $row);
		// }
		// $row->update($data);
    }

    public function softdeletePartner($id){
        OrganisationUnit::findOrFail($id)->update([
            'deleted' => 1,
        ]);
        $partners = OrganisationUnit::with('usercategory')->with('usersubcategory')->where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'partners'=> $partners,
            'message' => 'Partner deleted successfully',
        ], 200);
    }

    public function getPartnerSubCategory($id){
        $subcategory = UserSubCategory::where('tbl_user_category_id', $id)->get();

        return response( [
            'status' => 'success',
            'subcategory'=> $subcategory,
            'message' => 'Data retrieved successfully',
        ], 200);
    }
    
}
