<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transition extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_transition_request';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
