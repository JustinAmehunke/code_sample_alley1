<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionModule extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_actions_module';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
