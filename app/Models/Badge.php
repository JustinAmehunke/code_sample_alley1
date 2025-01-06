<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_badges';
    public $timestamps = false;

    protected $primaryKey = 'id';

}
