<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UserSubCategory;

class UserSubCategoryController extends Controller
{
    public function saveSubCategory(Request $request){
        $subcategory = UserSubCategory::where('tbl_user_category_id', $request->tbl_user_category_id)->where('category_name', $request->subcategory_name)->where('deleted', 0)->get();
      
        if(count($subcategory)>0){
            return response([
                'status' => 'failed',
                'message' => 'User Sub Category already exists',
            ], 200);
       }else{
            UserSubCategory::insert([
                'category_name' => $request->subcategory_name,
                'tbl_user_category_id' => $request->tbl_user_category_id,
                'createdon' => Carbon::now(),
            ]);
            $subcategory = UserSubCategory::with('usercategory')->where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'subcategory'=> $subcategory,
                'message' => 'Changes saved successfully',
            ], 200);
       }
    }
    public function updateSubCategory(Request $request){

        UserSubCategory::findOrFail($request->id)->update([
            'category_name' => $request->subcategory_name,
            'tbl_user_category_id' => $request->tbl_user_category_id,
            'modon' => Carbon::now(),
            'modby' => Auth()->id(),
        ]);
        $subcategory = UserSubCategory::with('usercategory')->where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'subcategory'=> $subcategory,
            'message' => 'Changes saved successfully',
        ], 200);
    }

    public function softdeleteSubCategory($id){

        UserSubCategory::findOrFail($id)->update([
            'deleted' => 1,
        ]);
        $subcategory = UserSubCategory::with('usercategory')->where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'subcategory'=> $subcategory,
            'message' => 'User Category deleted successfully',
        ], 200);
    }
}
