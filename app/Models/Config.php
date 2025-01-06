<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_config';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
