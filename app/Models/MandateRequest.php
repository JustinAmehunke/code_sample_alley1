<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandateRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_mandate_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
