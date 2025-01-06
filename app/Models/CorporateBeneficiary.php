<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateBeneficiary extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_corporate_beneficiaries';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
