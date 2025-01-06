<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSetup extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_document_setup';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
