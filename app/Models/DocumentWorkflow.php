<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentWorkflow extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_document_workflow';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_document_workflow_type(){
        return $this->belongsTo(DocumentWorkflowType::class, 'tbl_document_workflow_type_id', 'id');
    }

    public function tbl_document_setup_details(){
        return $this->belongsTo(DocumentSetupDetail::class, 'tbl_document_setup_details_id', 'id');
    }

    public function tbl_document_applications()
    {
        return $this->belongsTo(DocumentApplication::class, 'tbl_document_applications_id', 'id');
    }

    public function tbl_workflow_status()
    {
        return $this->belongsTo(WorkflowStatus::class, 'tbl_workflow_status_id', 'id');
    }

}
