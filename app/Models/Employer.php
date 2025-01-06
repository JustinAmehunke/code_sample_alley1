<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_employers';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
