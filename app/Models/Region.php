<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_region';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
