<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifiedBy extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_notified_by';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
