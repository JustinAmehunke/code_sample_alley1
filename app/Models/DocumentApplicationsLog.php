<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentApplication;
use App\Models\DocumentWorkflowType;
class DocumentApplicationsLog extends Model
{
    use HasFactory; 
    protected $guarded = [];

    protected $table = 'tbl_document_applications_logs';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_document_applications(){
        return $this->belongsTo(DocumentApplication::class, 'tbl_document_applications_id', 'id');
    }

    public function tbl_document_workflow_type(){
        return $this->belongsTo(DocumentWorkflowType::class, 'tbl_document_workflow_type_id', 'id');
    }
}
