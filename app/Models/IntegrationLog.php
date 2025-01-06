<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_integration_logs';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
