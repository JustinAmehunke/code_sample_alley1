<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionSubModule extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_actions_sub_module';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
