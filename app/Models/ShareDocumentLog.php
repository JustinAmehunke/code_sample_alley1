<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareDocumentLog extends Model
{
    use HasFactory; 
    protected $guarded = [];
    
    protected $table = 'tbl_share_document_log';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function documentApplication()
    {
        return $this->belongsTo(DocumentApplication::class, 'tbl_document_applications_id');
    }

    public function tbl_users(){
        return $this->belongsTo(User::class, 'tbl_users_id', 'id');
    }

    public function tbl_document_type(){
        return $this->belongsTo(DocumentType::class, 'tbl_document_type_id', 'id');
    }

    public function tbl_document_applications(){
        return $this->belongsTo(DocumentApplication::class, 'tbl_document_applications_id');
    }
}
