<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MethodOfContact extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_method_of_contact';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
