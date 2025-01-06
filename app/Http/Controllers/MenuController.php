<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\UserRole;
use Auth;
class MenuController extends Controller
{

    public function saveMenu(Request $request){

        $request->validate([
            'menu_label' => 'required|string|max:255',
            'page_url' => 'required|string|max:255',
        ], [
            'menu_label.required' => 'The field is required.'
        ]);
    
        // menu_label":"sd","menu_icon":"...","is_subpage":"1","sub_page":"...","parent_page":"...",
        // "sort":"er","page_url":"http:\/\/localhost:84\/menu\/new","visible_to_all":"1"
        $default_item = isset($request->visible_to_all) ? $request->visible_to_all : 0;
	    $hidden_menu = isset($request->is_subpage) ? $request->is_subpage : 0;
        if(empty($request->parent_page)) {
            $parent_page = 0;
            $label = strtoupper($request->menu_label);
        } else {
            $parent_page = $request->parent_page;
            $label = $request->menu_label;
        }
        if ($hidden_menu && !empty($request->sub_page)) {
            $sub_page = $request->sub_page;
        }else{
            $sub_page = 0;
        }

       
        DB::beginTransaction();
        try {
            
            $menuId = DB::table('tbl_menu')->insertGetId([
                "label" => $label,	
                "link" => $request->page_url,	
                "icon" => $request->menu_icon,	
                "parent" => $parent_page,
                "sort" => $request->sort,
                "time" => Carbon::now(),
                "status"=>  1,
                "default_item" =>  $default_item,
                "hidden_menu" =>   $hidden_menu,
                "main_page"	=> $sub_page,
                "createdon"	=> Carbon::now(),	
                "created_by"	=> Auth::id(),
                // "updatedon"	=> Carbon::now()	
                "deleted"	=>  0
            ]);
            if(isset($request->designations) && is_array($request->designations) && !empty($request->designations)){
                foreach ($request->designations as $desig_id) {
                    DB::table('tbl_user_role')->insert([
                        "tbl_menu_id" => $menuId,
                        "tbl_designations_id" => $desig_id,	
                        "permissions" => json_encode(array("create"=>0,"execute"=>0,"delete"=>0)),	
                        "deleted" => 0,
                        "createdon" => Carbon::now()
                    ]);
                }
            }

         DB::commit();
         
         return redirect()->route('list-menu')->with('success_message', 'Data saved successfully!');

        } catch (\Throwable $th) {
             // If an exception occurs (e.g., database error), redirect back with an error message
            return redirect()->back()->with('error_message', 'Error saving data: ' . $th->getMessage())->withInput();
        }
       
    }

    public function updateMenu(Request $request){
       
        $request->validate([
            'id' => 'required|integer',
            'menu_label' => 'required|string|max:255',
            'page_url' => 'required|string|max:255',
        ], [
            'menu_label.required' => 'The field is required.',
            'id.required' => 'Something is wrong.'
        ]);
        // menu_label":"sd","menu_icon":"...","is_subpage":"1","sub_page":"...","parent_page":"...",
        // "sort":"er","page_url":"http:\/\/localhost:84\/menu\/new","visible_to_all":"1"
        $default_item = isset($request->visible_to_all) ? $request->visible_to_all : 0;
	    $hidden_menu = isset($request->is_subpage) ? $request->is_subpage : 0;
        if(empty($request->parent_page)) {
            $parent_page = 0;
            $label = strtoupper($request->menu_label);
        } else {
            $parent_page = $request->parent_page;
            $label = $request->menu_label;
        }
        if ($hidden_menu && !empty($request->sub_page)) {
            $sub_page = $request->sub_page;
        }else{
            $sub_page = 0;
        }

        DB::beginTransaction();
        try {

            $record = DB::table('tbl_menu')->where('id', $request->id)->first();
            if($record){

                DB::table('tbl_menu')->where('id', $request->id)->update([
                    "label" => $label,	
                    "link" => $request->page_url,	
                    "icon" => $request->menu_icon,	
                    "parent" => $parent_page,
                    "sort" => $request->sort,
                    "time" => Carbon::now(),
                    "status"=>  1,
                    "default_item" =>  $default_item,
                    "hidden_menu" =>   $hidden_menu,
                    "main_page"	=> $sub_page,	
                    // "updated_by"	=> Auth::id(),
                    // "updatedon"	=> Carbon::now()	
                    "deleted"	=>  0
                ]);

                $userRole = UserRole::where('tbl_menu_id',$request->id)->pluck('tbl_designations_id')->toArray();
                if(!empty($userRole)){
                    UserRole::where('tbl_menu_id',$request->id)->delete();
                }
    
                if(isset($request->designations) && is_array($request->designations) && !empty($request->designations)){
                    foreach ($request->designations as $desig_id) {
                        DB::table('tbl_user_role')->insert([
                            "tbl_menu_id" => $request->id,
                            "tbl_designations_id" => $desig_id,	
                            "permissions" => json_encode(array("create"=>0,"execute"=>0,"delete"=>0)),	
                            "deleted" => 0,
                            "createdon" => Carbon::now()
                        ]);
                    }
                }
            }else{
                return redirect()->back()->with('error_message', 'Record not found');
            }

          

         DB::commit();
         
         return redirect()->route('list-menu')->with('success_message', 'Data updated successfully!');

        } catch (\Throwable $th) {
             // If an exception occurs (e.g., database error), redirect back with an error message
             return redirect()->back()->with('error_message', 'Something went wrong!');
            // return  $th->getMessage();
        }
    }

    public function deleteMenu($id){
        // delete menu
        Menu::find($id)->delete();
        // return new menu list
        $menus = Menu::all();
        $encriptedMenus =  $menus->map(function($menu){
            $menu->encrypte_id = encrypt($menu->id);
            return  $menu;
        });

        return response([
            'message' => "Menu deleted successfully.",
            'menus' => $encriptedMenus,
            'status' => "success"
        ], 200);
    }

    public function getMenus(){
        $menus = Menu::all();

        $encriptedMenus =  $menus->map(function($menu){
            $menu->encrypte_id = encrypt($menu->id);
            return  $menu;
        });

        return response([
            'message' => "Menu retrieved successfully.",
            'menus' =>  $encriptedMenus,
            'status' => "success"
        ], 200);
    }
}
