<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatusEndpoint extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_application_status_endpoints';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
