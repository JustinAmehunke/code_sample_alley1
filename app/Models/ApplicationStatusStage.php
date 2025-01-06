<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatusStage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_application_status_stage';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
