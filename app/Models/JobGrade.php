<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobGrade extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_job_grades';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
