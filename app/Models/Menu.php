<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_menu';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public static function getSidebarParentMenus(){
        // tbl_designations_id
        // return self::where('parent', '=', 0)->where('deleted', 0)->orderBy('sort', 'ASC')->get();
        
        $menu_profile = auth()->user()?->tbl_designations_id;
        return self::select("tbl_menu.*")
            ->join("tbl_user_role as e", "e.tbl_menu_id", "=", "tbl_menu.id")
            ->where("tbl_menu.status", 1)
            ->where('tbl_menu.parent', '=', 0)
            ->where('tbl_menu.deleted', 0)
            ->where(function($query) use ($menu_profile) {
                $query->where("e.tbl_designations_id", $menu_profile)
                    ->orWhere("tbl_menu.default_item", 1);
            })
            ->orderBy("tbl_menu.parent")
            ->orderBy("tbl_menu.sort")
            ->distinct() 
            ->get();

    }

    public static function getSidebarChildrenMenus($parent){
        return self::where('parent', '=', $parent)->where('deleted', 0)->orderBy('sort', 'ASC')->get();
    }

    public static function getSidebarSubChildrenMenus($child){
        return self::where('parent', '=', $child)->where('deleted', 0)->orderBy('sort', 'ASC')->get();
    }

    public static function getSidebarSubSubChildrenMenus($subchild){
        return self::where('parent', '=', $subchild)->where('deleted', 0)->orderBy('sort', 'ASC')->get();
    }

}
