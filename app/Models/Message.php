<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory; 

    protected $guarded = [];

    protected $table = 'tbl_messages';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
