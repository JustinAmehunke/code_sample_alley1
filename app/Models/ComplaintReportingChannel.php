<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintReportingChannel extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_complaint_reporting_channel';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
