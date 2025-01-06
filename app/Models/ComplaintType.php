<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_complaint_types';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
