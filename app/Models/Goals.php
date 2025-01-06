<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_goals';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
