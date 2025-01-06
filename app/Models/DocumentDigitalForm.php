<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentDigitalForm extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_document_digital_forms';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
