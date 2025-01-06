<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Traits\CodeGenerator;
use App\Models\Branch;
use App\Models\FinanceMainAccount;

class BranchesController extends Controller
{
    use CodeGenerator;
    public function saveBranch(Request $request){
        // return $request;
        $request->validate([
            "branch_name" => 'required|string|max:255',
            "branch_type" => 'required|integer',
            "branch_address" => 'required|string|max:255',
            "branch_contact_no" => 'required|string|max:255',
            "branch_contact_email" => 'required|email|max:255',
            "branch_region" => 'required|integer',
            "branch_city" => 'required|integer',
        ]);

        $exist = Branch::where([
            ['branch_name', '=', $request->branch_name],
            ['branch_addr', '=', $request->branch_address],
            ['branch_contact_no', '=', $request->branch_contact_no],
            ['branch_contact_email', '=',  $request->branch_contact_email],
            ['tbl_organisation_unit_id', '=', $request->branch_type],
            ['deleted', '=', 0]
        ])->get();

        if(count($exist)>0){
            return response( [
                'status' => 'failed',
                'message' => 'This Branch has already been used',
            ], 200);
        }else{
            $insertesID = Branch::insertGetId([
                'branch_code' => $this->generateCode("BranchCode", []),
                'branch_name' => $request->branch_name,
                'branch_addr' => $request->branch_address,
                'branch_contact_no' => $request->branch_contact_no,
                'branch_contact_email' => $request->branch_contact_email,
                'esu_region_id' => (int) $request->branch_region,
                'esu_city_id' => (int) $request->branch_city,
                'tbl_organisation_unit_id' => $request->branch_type,
                'modby' => Auth()->id(),
                'modon' => Carbon::now(),
                'created_by' => Auth()->id(),
                'createdon' => Carbon::now(),
            ]);
            //
            if($insertesID){
                $justInserted = Branch::find($insertesID);

                $ca_account = FinanceMainAccount::where([
                    [ 'deleted', '=', 0],
                    ['tbl_branch_id', '=', $justInserted->id],
                    [ 'cash_account_flag', '=', 1]
                    ])->get();

                    return count($ca_account);
                
                if (count($ca_account) > 0) {
                    // $ca_account->update(array('account_name' => $row['branch_name'],'account_code' => $row['account_code']));
                    FinanceMainAccount::findOrFail($ca_account[0]->id)->update([
                        'account_name' => $justInserted->branch_name,
                        'account_code' => $justInserted->branch_code,
                    ]);
                }else{
                    // $rs = $orm->tbl_finance_main_accounts()->insert($datacashaccount);
                    FinanceMainAccount::insert([
                        'account_name' => $justInserted->branch_name . " Cash",
                        'account_code' => $justInserted->branch_code,
                        'description' => $justInserted->branch_name . " Cash",
                        'tbl_branch_id' => $justInserted->id,
                        'tbl_finance_account_categories_id' => 1,
                        'tbl_finance_account_types_id' => 4,
                        'open_entry_flag' => 1
                    ]);
                }
            }
            return response( [
                'status' => 'success',
                'document_types'=> $document_types,
                'message' => 'Changes saved successfully',
            ], 200);
        }
       
    }
    public function updateBranch(Request $request){
        // return $request;
        $request->validate([
            "branch_code" => 'required|string|max:255',
            "branch_id" => 'required|integer',
            "branch_name" => 'required|string|max:255',
            "branch_type" => 'required|integer',
            "branch_address" => 'required|string|max:255',
            "branch_contact_no" => 'required|string|max:255',
            "branch_contact_email" => 'required|email|max:255',
            "branch_region" => 'required|integer',
            "branch_city" => 'required|integer',
        ]);
           
        Branch::findOrFail($request->branch_id)->update([
            'branch_name' => $request->branch_name,
            'branch_addr' => $request->branch_address,
            'branch_contact_no' => $request->branch_contact_no,
            'branch_contact_email' => $request->branch_contact_email,
            'esu_region_id' => (int) $request->branch_region,
            'esu_city_id' => (int) $request->branch_city,
            'tbl_organisation_unit_id' => $request->branch_type,
            'modby' => Auth()->id(),
            'modon' => Carbon::now(),
        ]);
        $ca_account = FinanceMainAccount::where([
            [ 'deleted' => 0,],
            ['tbl_branch_id', '=', $request->branch_id,],
            [ 'cash_account_flag', '=', 1]]
        )->get();
        
        if (count($ca_account) == 0) {
            // $rs = $orm->tbl_finance_main_accounts()->insert($datacashaccount);
            FinanceMainAccount::insert([
                'account_name' => $request->branch_name . " Cash",
                'account_code' => $request->branch_address,
                'description' => $request->branch_name . " Cash",
                'tbl_branch_id' => $request->branch_id,
                'tbl_finance_account_categories_id' => 1,
                'tbl_finance_account_types_id' => 4,
                'open_entry_flag' => 1
            ]);
        }else{
            // $ca_account->update(array('account_name' => $row['branch_name'],'account_code' => $row['account_code']));
            FinanceMainAccount::findOrFail()->update([
                'account_name' => $request->branch_name,
                'account_code' => $request->branch_code,
            ]);
        }
    }
    public function softdeleteBranch($id){
        return $id;
    }
}
