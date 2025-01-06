<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_gender';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
