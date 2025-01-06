<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateDueDiligence extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_corporate_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
