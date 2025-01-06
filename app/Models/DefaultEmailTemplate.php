<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultEmailTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $table = 'tbl_default_template';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
