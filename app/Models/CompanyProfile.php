<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_company_profile';
    public $timestamps = false;

    protected $primaryKey = 'id';
    //



}
