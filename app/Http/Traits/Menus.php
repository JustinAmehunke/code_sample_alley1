<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Models\Menu;
use Auth;
use DB;


trait Menus {

public static function getParentMenus(){
    
    // $designation_id = Auth::user()->tbl_designations_id;
    // return DB::table('tbl_menu')
    // ->select('tbl_menu.*')
    // ->join('tbl_user_role as e', 'e.tbl_menu_id', '=', 'tbl_menu.id')
    // ->where('tbl_menu.status', 1)
    // ->where(function($query) use ($designation_id) {
    //     $query->where('e.tbl_designations_id', $designation_id)
    //         ->orWhere('tbl_menu.default_item', 1);
    // })
    // ->orderBy('tbl_menu.parent')
    // ->orderBy('tbl_menu.sort')
    // ->get();

    return Menu::where('status', 1)->get();
}

public static function getChildrenMenus(){
$nav = Menu::where('status', 1)->get();
return Menu::where('status', 1)
->where('hidden_menu', 0)
->whereNotIn('id', $nav->pluck('parent')->unique())
->get();
}

public static function getLeftMenus(){
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

    $subpages = DB::table('tbl_menu')
    ->where('tbl_menu.status', 1)
    ->where('hidden_menu', 1)
    ->whereIn('main_page', $arr)
    ->get();
}
}



?>