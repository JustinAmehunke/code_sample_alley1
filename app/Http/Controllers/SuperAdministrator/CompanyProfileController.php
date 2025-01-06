<?php

namespace App\Http\Controllers\SuperAdministrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\CompanyProfile;
use App\Models\CompanyProfileBank;

class CompanyProfileController extends Controller
{
    public function updateCompanyProfile(Request $request){
       
        if($request->hasFile('company_logo')){
            $validator = Validator::make($request->all(), [
                'company_logo' => '|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
          
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
       
        if($request->org_id){
            //Update
            CompanyProfile::findOrFail($request->org_id)->update([
                'company_name' => $request->company_name,	
                'company_shortname' =>$request->short_name,	
                'company_address' =>trim($request->company_address),
                'company_contact' =>$request->contact_name,
                'company_contact_no'	=>$request->contact_number,
                'company_contact_email'	=>$request->contact_email,
                // 'company_logo_path'	=>$imageName,	
                // 'vat_no'	=>$request->vat_number,	
                'code'	=>$request->company_code,	
                'vat_reg_no'	=>$request->vat_number,
                'mod_by'	=>Auth()->id()
            ]);

            // Logo
            if($request->hasFile('company_logo')){
                //
                $current_record =  CompanyProfile::first();
                $currentLogoPath = storage_path('app/public/company_profile/' . $current_record->company_logo_path);

               
                if (file_exists($currentLogoPath) && is_file($currentLogoPath)) {
                    unlink($currentLogoPath);
                }
                // Handle logo upload
                $image = $request->file('company_logo');
                $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/company_profile', $imageName);
                //update Logo path
                CompanyProfile::findOrFail($request->org_id)->update([
                    'company_logo_path'	=>$imageName,	
                ]);
            }

            //Soft delete Banks
            CompanyProfileBank::query()->update([
                'deleted' => 1,
            ]);
            //Insert New

            if($request->bank_name){
               foreach ($request->bank_name as $key => $value) {
                CompanyProfileBank::insert([
                    'tbl_company_profile_id' =>$request->org_id,
                    'bank' =>$request->bank_name[$key],		
                    'branch'	=>$request->bank_branch[$key],
                    'account_name'	=>$request->account_name[$key],
                    'account_no'	=>$request->account_number[$key],	
                    'createdon'	=> Carbon::now(),	
                    'createdby'	=> Auth()->id(),	
                    'deleted' => 0,
                ]);
               }
            }

            

            return redirect()->back()->with('success_message', 'Company profile updated successfully.');

        }else{
            //Save
            $rec_id = CompanyProfile::insertGetId([
                'company_name' => $request->company_name,	
                'company_shortname' =>$request->short_name,	
                'company_address' =>$request->company_address,
                'company_contact' =>$request->contact_name,
                'company_contact_no'	=>$request->contact_number,
                'company_contact_email'	=>$request->contact_email,
                'company_logo_path'	=>$request->company_logo,	
                // 'vat_no'	=>$request->id,	
                'code'	=>$request->company_code,	
                'vat_reg_no'	=>$request->vat_number,
                'createdon'	=>Carbon::now(),
                'created_by'	=>Auth()->id(),
            ]);

            // Logo
            if($request->hasFile('company_logo')){
                // validate image
                $request->validate([
                    'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
    
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                // Handle logo upload
                $image = $request->file('company_logo');
                $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/company_profile', $imageName);
                //update logo path
                CompanyProfile::findOrFail($rec_id)->update([
                    'company_logo_path'	=>$request->company_logo,	
                ]);
            }
            return redirect()->back()->with('success_message', 'Company profile saved successfully.');
        }
       	
            
    }

    public function geProfile(){

    }
}
