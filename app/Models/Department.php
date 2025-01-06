<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $table = 'tbl_departments';
    public $timestamps = false;

    protected $primaryKey = 'id';

    // public function product(){
    // 	return $this->belongsTo(Product::class,'product_id','id');
    // }
    
    public function designations(){
        return $this->hasMany(Designation::class,'tbl_departments_id','id');
    }
}
