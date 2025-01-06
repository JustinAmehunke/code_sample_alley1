<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processs extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_process';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
