<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignedFunction extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_users_assigned_functions';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_departments(){
        return $this->belongsTo(Department::class, 'tbl_departments_id', 'id');
    }
}
