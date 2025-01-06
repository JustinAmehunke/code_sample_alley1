<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyman extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_keyman_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
