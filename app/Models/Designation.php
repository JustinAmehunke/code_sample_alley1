<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $table = 'tbl_designations';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
