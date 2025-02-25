<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_city';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
