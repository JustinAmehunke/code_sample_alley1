<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_document_images';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
