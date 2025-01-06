<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ti extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_travel_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
