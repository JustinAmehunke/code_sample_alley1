<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStatus extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_workflow_status';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
