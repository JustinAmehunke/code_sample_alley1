<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentApplicationsComment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_document_applications_comments';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_users(){
        return $this->belongsTo(User::class, 'createdby', 'id');
    }
}
