<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobGradeApprovalLimit extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_job_grades_approval_limits';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
