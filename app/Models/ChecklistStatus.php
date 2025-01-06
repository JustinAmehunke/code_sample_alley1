<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistStatus extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_checklist_status';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
