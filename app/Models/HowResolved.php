<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowResolved extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_how_resolved';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
