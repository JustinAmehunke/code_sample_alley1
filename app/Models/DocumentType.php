<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_document_type';
    public $timestamps = false;

    protected $primaryKey = 'id';


    public function badge(){
        return $this->belongsTo(Badge::class, 'tbl_badges_id', 'id');
    }
}
