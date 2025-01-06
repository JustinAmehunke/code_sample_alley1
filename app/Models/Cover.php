<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_covers';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
