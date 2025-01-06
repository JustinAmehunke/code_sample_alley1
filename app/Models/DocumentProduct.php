<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_documents_products';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
