<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentChecklist extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_document_checklist';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_document_type(){
        return $this->belongsTo(DocumentType::class, 'tbl_document_type_id', 'id');
    }

    public function tbl_checklist_status(){
        return $this->belongsTo(ChecklistStatus::class, 'tbl_checklist_status_id', 'id');
    }
}
