<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_claim_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
