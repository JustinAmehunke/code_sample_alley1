<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComplaintDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_customer_complaints_documents';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
