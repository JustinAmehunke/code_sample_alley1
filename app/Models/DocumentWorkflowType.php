<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentWorkflowType extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $table = 'tbl_document_workflow_type';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
