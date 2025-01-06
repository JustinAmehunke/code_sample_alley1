<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
   
    protected $table = 'tbl_requested_documents_doc';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
