<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfileBank extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_company_profile_banks';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
