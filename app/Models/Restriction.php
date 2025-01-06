<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_restrictions';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
