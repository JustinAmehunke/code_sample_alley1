<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_application_status';
    public $timestamps = false;

    protected $primaryKey = 'id';
    
    
}
