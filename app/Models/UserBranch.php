<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_user_branch';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
