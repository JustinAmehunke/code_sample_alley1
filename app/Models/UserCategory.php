<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_user_category';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
