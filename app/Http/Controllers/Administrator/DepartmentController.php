<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function createDepartment(Request $request){
        // return $request;
        $request->validate([
            'department_name' => 'required|string|max:255',
            'mailing_list' => 'required|email|max:255',
        ]);
        $exist = Department::where('department_name', trim(strtoupper($request->department_name)))->where('deleted', 0)->get();
        if(count($exist)>0){
            return response( [
                'status' => 'failed',
                'message' => 'This department already exists',
            ], 200);
        }else{
           try {
            Department::insert([
                'department_name' => strtoupper($request->department_name),
                'mailing_list' => strtolower($request->mailing_list),
                'modon' => Carbon::now(),
                'modby' => Auth()->id(),
            ]);
            $departments = Department::where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'departments'=> $departments,
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
       
    }
    public function updateDepartment(Request $request){
        // return $request;
        $request->validate([
            'department_id' => 'required|integer',
            'department_name' => 'required|string|max:255',
            'mailing_list' => 'required|email|max:255',
        ]);
        try {
            Department::findOrFail($request->department_id)->update([
                'department_name' => strtoupper($request->department_name),
                'mailing_list' => strtolower($request->mailing_list),
                'modon' => Carbon::now(),
                'modby' => Auth()->id(),
            ]);
            $departments = Department::where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'departments'=> $departments,
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
    public function softdeleteDepartment($id){
        Department::findOrFail($id)->update([
            'deleted' => 1,
        ]);
        $departments = Department::where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'departments'=> $departments,
            'message' => 'Department deleted successfully',
        ], 200);
    }
}
