<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Restriction;

class RestrictionController extends Controller
{
    public function saveRestriction(Request $request){

        $request->validate([
            'restriction_name' => 'required|string|max:255',
        ]);

       try {
            $exist = Restriction::where('restriction_name', trim(strtoupper($request->restriction_name)))->where('deleted', 0)->get();
            if(count($exist)>0){
                return response([
                    'status' => 'failed',
                    'message' => 'This Restriction already exists',
                ], 200);
            }else{
                Restriction::insert([
                    'restriction_name' => strtoupper($request->restriction_name),
                    'createdon' => Carbon::now(),
                    'createdby' => Auth()->id(),
                    'modon' => Carbon::now(),
                    'modby' => Auth()->id(),
                ]);
                $restrictions = Restriction::where('deleted', 0)->get();
                return response( [
                    'status' => 'success',
                    'restrictions'=> $restrictions,
                    'message' => 'Changes saved successfully',
                ], 200);
            }
       } catch (\Throwable $th) {
        //throw $th;
        return response( [
            'status' => 'failed',
            'error'=> $th->getMessage(),
            'message' => 'Something went wrong',
        ], 200);
       }
       
    }
    public function updateRestriction(Request $request){

        $request->validate([
            'restriction_name' => 'required|string|max:255',
            'id' => 'required|integer',
        ]);

       try {
            $exist = Restriction::where('restriction_name', trim(strtoupper($request->restriction_name)))->where('deleted', 0)->get();
            if(count($exist)>0){
                return response([
                    'status' => 'failed',
                    'message' => 'This Restriction already exists',
                ], 200);
            }else{
                Restriction::findOrFail($request->id)->update([
                    'restriction_name' => strtoupper($request->restriction_name),
                    'modon' => Carbon::now(),
                    'modby' => Auth()->id(),
                ]);

                $restrictions = Restriction::where('deleted', 0)->get();
                return response( [
                    'status' => 'success',
                    'restrictions'=> $restrictions,
                    'message' => 'Changes saved successfully',
                ], 200);
            }
        } catch (\Throwable $th) {
        //throw $th;
            return response( [
                'status' => 'failed',
                'error'=> $th->getMessage(),
                'message' => 'Something went wrong',
            ], 200);
        }
    }
    public function softdeleteRestriction($id){

        Restriction::findOrFail($id)->update([
            'deleted' => 1,
        ]);
        $restrictions = Restriction::where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'restrictions'=> $restrictions,
            'message' => 'Department deleted successfully',
        ], 200);
    }
}
