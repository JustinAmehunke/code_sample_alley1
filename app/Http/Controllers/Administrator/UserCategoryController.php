<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UserCategory;


class UserCategoryController extends Controller
{
    public function saveMainCategory(Request $request){
        $category = UserCategory::where('user_category', $request->user_category)->where('deleted', 0)->get();
      
        if(count($category)>0){
            return response([
                'status' => 'failed',
                'message' => 'User Category already exists',
            ], 200);
       }else{
            UserCategory::insert([
                'user_category' => $request->user_category,
                'prefix' => strtoupper($request->prefix),
                'service_provider' => $request->service_provider,
                'createdon' => Carbon::now(),
            ]);
            $category = UserCategory::where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'category'=> $category,
                'message' => 'Changes saved successfully',
            ], 200);
       }
    }
    public function updateMainCategory(Request $request){

        UserCategory::findOrFail($request->id)->update([
            'user_category' => $request->user_category,
            'prefix' => strtoupper($request->prefix),
            'service_provider' => $request->service_provider,
            'modon' => Carbon::now(),
            'modby' => Auth()->id(),
        ]);
        $category = UserCategory::where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'category'=> $category,
            'message' => 'Changes saved successfully',
        ], 200);
    }

    public function softdeleteMainCategory($id){

        UserCategory::findOrFail($id)->update([
            'deleted' => 1,
        ]);
        $category = UserCategory::where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'category'=> $category,
            'message' => 'User Category deleted successfully',
        ], 200);
    }
}
