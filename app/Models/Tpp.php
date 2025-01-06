<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tpp extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_tpp_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
