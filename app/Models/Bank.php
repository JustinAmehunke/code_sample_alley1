<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_banks';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
