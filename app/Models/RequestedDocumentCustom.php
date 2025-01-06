<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedDocumentCustom extends Model
{
    use HasFactory;
    protected $guarded = [];
   
    protected $table = 'tbl_requested_documents_custom';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
