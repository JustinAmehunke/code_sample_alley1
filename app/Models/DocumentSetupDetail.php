<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSetupDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_document_setup_details';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_document_workflow()
    {
        return $this->hasOne(DocumentWorkflow::class, 'tbl_document_setup_details_id');
    }

    public function reference()
    {
        return $this->belongsTo(Department::class, 'reference', 'id');
    }
}
