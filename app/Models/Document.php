<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_documents';
    public $timestamps = false;

    protected $primaryKey = 'id';
    
    public function tbl_document_type(){
        return $this->belongsTo(DocumentType::class, 'tbl_document_type_id', 'id');
    }

    public function tbl_document_images(){
        return $this->belongsTo(DocumentImage::class, 'tbl_document_images_id', 'id');
        
    }

    public function tbl_users(){
        return $this->belongsTo(User::class, 'createdby', 'id');
    }

    public function createdby(){
        return $this->belongsTo(User::class, 'createdby', 'id');
    }

    
    
}
