<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionTimeLimit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_requisition_time_limit';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
