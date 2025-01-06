<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationUnit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_organisation_unit';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function usercategory(){
        return $this->belongsTo(UserCategory::class, 'tbl_user_category_id', 'id');
    }
    public function usersubcategory(){
        return $this->belongsTo(UserSubCategory::class, 'tbl_sub_user_category_id', 'id');
    }
}
