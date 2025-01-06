<?php

namespace App\Http\Controllers\SuperAdministrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActionModule;
use App\Models\ActionSubModule;
use App\Models\JobGradeApprovalLimit;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStatusStage;
use App\Models\ApplicationStatusEndpoint;
use App\Models\ApplicationParameter;
use Illuminate\Support\Carbon;

class ApplicationStatusController extends Controller
{
    //Module
    public function createActionModule(Request $request){
        
        try {
            //Validate
            $request->validate([
                'module_name' => 'required|string|max:255',
            ]);
            //Check if exists
            $exist = ActionModule::where('module_name', $request->module_name)->get();
            if(count($exist) > 0){
                return response([
                    'status' => 'failed',
                    'message' => 'Module already exist.',
                ], 200);
            }else{
                ActionModule::insert([
                    'module_name' => $request->module_name,
                    'status' => 0,
                    'date' => Carbon::now(),
                    'createdon' => Carbon::now()
                ]);
                return response( [
                    'status' => 'success',
                    'message' => 'Success!',
                   ], 200);
            }

           
          } catch (\Throwable $th) {
            // throw $th;
          
          }
    }

    public function updateActionModule(Request $request){
      try {
        ActionModule::findOrFail($request->mod_id)->update([
            'module_name' => $request->module_name,
        ]);
        return response( [
            'status' => 'success',
            'message' => 'Success!',
           ], 200);
      } catch (\Throwable $th) {
        // throw $th;
        return response( [
            'status' => 'failed',
            'message' => 'Something went wrong',
           ], 200);
      }
    }
    public function updateJobApprovalLimit(Request $request){
        // return $request;
        $exist_arr = [];
        foreach($request->grade_id as $key => $item){
            $exist = JobGradeApprovalLimit::where([['tbl_job_grades_id',$item],['tbl_actions_module_id',$request->module_id]])->get();
            // return $exist[0]->id ;
            // array_push($exist_arr, $exist);
            if(count($exist)>0 && isset($exist[0]) > 0){
                try {
                    JobGradeApprovalLimit::findOrFail($exist[0]->id)->update([
                        'tbl_job_grades_id' => $item,
                        'tbl_actions_module_id' => $request->module_id,
                        'can_approve' => $request->can_approve[$key],
                        'same_user_approval' => $request->same_user_approval[$key],
                        'approval_limit' => $request->approval_limit[$key],
                        'modon' => Carbon::now(),
                        'mod_by' => Auth()->id()
                    ]);
                } catch (\Throwable $th) {
                    throw $th;
                    return response( [
                        'status' => 'failed',
                        'message' => 'Something went wrong',
                       ], 200);
                }
            }else{
                try {
                    JobGradeApprovalLimit::insert([
                        'tbl_job_grades_id' => $item,
                        'tbl_actions_module_id' => $request->module_id,
                        'can_approve' => $request->can_approve[$key],
                        'same_user_approval' => $request->same_user_approval[$key],
                        'approval_limit' => $request->approval_limit[$key],
                        'createdon' => Carbon::now(),
                        'created_by' => Auth()->id()
                    ]);
                } catch (\Throwable $th) {
                    throw $th;
                    return response( [
                        'status' => 'failed',
                        'message' => 'Something went wrong',
                       ], 200);
                }
            }
        }

        return response( [
            'status' => 'success',
            'message' => 'Changes saved successfully!',
           ], 200);
    }

    public function saveUpdateActionSubModuleDetails(Request $request){
        //  return $request;
        //  $exist_arr = [];
         foreach($request->status_name as $key => $item){
             $exist = ApplicationStatus::where([['id', $request->application_status_id[$key]]])->get();
          
             if(count($exist)>0 && isset($exist[0]) > 0){
                 try {
                    $delete = 0;
                    if(isset($request->delete)){
                        if(in_array($request->application_status_id[$key], $request->delete)){
                            $delete = 1;
                        }
                    }
                   
                    ApplicationStatus::findOrFail($exist[0]->id)->update([
                        "tbl_actions_module_id" => $request->module_id,
                        "tbl_actions_sub_module_id"	=> $request->submodule_id,
                        "status_name" => $request->status_name[$key],	
                        "workflow_no" => (empty($request->workflow_no[$key])? null: $request->workflow_no[$key]),	
                        "tbl_application_status_stage_id" => $request->stage[$key],	
                        "tbl_application_status_endpoints_id" => $request->endpoint[$key],	
                        "deleted" => $delete,	
                     ]);
                 } catch (\Throwable $th) {
                     throw $th;
                     return response( [
                         'status' => 'failed',
                         'message' => 'Something went wrong',
                    ], 200);
                 }
             }else{
                 try {
                    ApplicationStatus::insert([
                        "tbl_actions_module_id" => $request->module_id,
                        "tbl_actions_sub_module_id"	=> $request->submodule_id,
                        "status_name" => $request->status_name[$key],	
                        "workflow_no" => (empty($request->workflow_no[$key])? null: $request->workflow_no[$key]),	
                        "tbl_application_status_stage_id" => $request->stage[$key],	
                        "tbl_application_status_endpoints_id" => $request->endpoint[$key],	
                        "deleted" => 0,	
                        "createdon"	=> Carbon::now(),
                        "approval_list_type" => 1,
                     ]);
                 } catch (\Throwable $th) {
                     throw $th;
                     return response( [
                         'status' => 'failed',
                         'message' => 'Something went wrong',
                        ], 200);
                 }
             }
         }
 
         return response( [
             'status' => 'success',
             'message' => 'Changes saved successfully!',
            ], 200);
    }

    //Sub Module
    public function createActionSubModule(Request $request){
        
        try {
            //Validate
            $request->validate([
                'module_id' => "required|integer",
                'module_name' => 'required|string|max:255',
            ]);
            //Check if exist already
            $exist = ActionSubModule::where('sub_module', $request->module_name)->get();
            // return count($exist);
            if(count($exist) > 0){
                return response([
                    'status' => 'failed',
                    'message' => 'Sub Module already exist.',
                ], 200);
            }else{
                ActionSubModule::insert([
                    'sub_module' => $request->module_name,
                    'tbl_actions_module_id' => $request->module_id,
                    'deleted'	=> 0,
                    'use_module' =>  1,	
                    'date_added' => Carbon::now(),	
                    'createdon' => Carbon::now()
                ]);
                return response( [
                    'status' => 'success',
                    'message' => 'Success!',
                ], 200);
            }
          
          } catch (\Throwable $th) {
            throw $th;
            return response( [
                'status' => 'failed',
                'message' => 'Something went wrong',
            ], 200);
          }
    }
    // Update
    public function updateActionSubModule(Request $request){
      try {
        ActionSubModule::findOrFail($request->submod_id)->update([
            "sub_module" => $request->submodule_name,
			"use_module" => isset($request->use_module) ? 1 : 0,
        ]);
        return response( [
            'status' => 'success',
            'message' => 'Success!',
           ], 200);
      } catch (\Throwable $th) {
        // throw $th;
        return response( [
            'status' => 'failed',
            'message' => 'Something went wrong',
           ], 200);
      }
    }
     
    public function subModuleDetails($module, $sub_module){
        $stages = ApplicationStatusStage::where('deleted', 0)->get(); 
        $endpoints = ApplicationStatusEndpoint::where('deleted', 0)
        ->where('tbl_actions_sub_module_id', null)
        ->orWhere('tbl_actions_sub_module_id', $sub_module)
        ->get();
        

        $custom_list = ApplicationStatus::where([
            'tbl_actions_module_id' => $module,
            'tbl_actions_sub_module_id' => $sub_module,
            'deleted' => 0
        ])->orderBy('id')->orderBy('workflow_no')->get();
        
        return response([
            'status' => 'success',
            'stages' =>  $stages ,
            'endpoints' => $endpoints,
            'custom_list' => $custom_list,
            'message' => 'Data retrieved successfully'
        ], 200);

    }

    public function endpointDetails($module, $sub_module, $endpoint){

        if($endpoint == 'global'){
            $endpoints = ApplicationStatusEndpoint::where('deleted', 0)
            ->Where('tbl_actions_sub_module_id', null)
            ->get();
            $seg = "Global";
        }else{
            $endpoints = ApplicationStatusEndpoint::where('deleted', 0)
            ->Where('tbl_actions_sub_module_id', $endpoint)
            ->get();
            $submoduleDetails = ActionSubModule::find($endpoint);
            $seg = $submoduleDetails->sub_module;
        }
       
        $endpoint_status = ApplicationParameter::where('deleted', 0)
        ->where('label', '<>', '')
        ->where([["page","administration"], ["name", "endpoint_status"]])
        ->orderBy('sort')
        ->get();

        return response([
            'status' => 'success',
            'endpoint_status' =>  $endpoint_status,
            'endpoints' => $endpoints,
            'seg' => $seg,
            'message' => 'Data retrieved successfully'
        ], 200);
    }

    public function saveUpdateEndpointDetails(Request $request){
        // return $request;
        foreach($request->endpoint_name as $key => $item){
            $exist = ApplicationStatusEndpoint::where([['id', $request->endpoint_details_id[$key]]])->get();
         
            if(count($exist)>0 && isset($exist[0]) > 0){
                try {
                   $delete = 0;
                   if(isset($request->delete)){
                       if(in_array($request->endpoint_details_id[$key], $request->delete)){
                           $delete = 1;
                       }
                   }
                  
                   ApplicationStatusEndpoint::findOrFail($exist[0]->id)->update([
                        "endpoint_name" =>	 $request->endpoint_name[$key],
                        "tbl_actions_sub_module_id"	=>  $request->submodule_id,
                        "endpoint_id" =>  $request->endpoint_type[$key],
                        "deleted" => $delete,
                    ]);
                } catch (\Throwable $th) {
                    throw $th;
                    return response( [
                        'status' => 'failed',
                        'message' => 'Something went wrong',
                   ], 200);
                }
            }else{
                try {
                    ApplicationStatusEndpoint::insert([
                        "endpoint_name" =>	 $request->endpoint_name[$key],
                        "tbl_actions_sub_module_id"	=>  $request->submodule_id,
                        "endpoint_id" =>  $request->endpoint_type[$key],	
                        "deleted" => 0,	
                        "createdon"	=> Carbon::now(),
                    ]);
                } catch (\Throwable $th) {
                    throw $th;
                    return response( [
                        'status' => 'failed',
                        'message' => 'Something went wrong',
                       ], 200);
                }
            }
        }

        return response( [
            'status' => 'success',
            'message' => 'Changes saved successfully!',
           ], 200);
    }
}
