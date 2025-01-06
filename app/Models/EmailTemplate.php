<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_email_templates';
    public $timestamps = false;

    protected $primaryKey = 'id';
    
    
}
