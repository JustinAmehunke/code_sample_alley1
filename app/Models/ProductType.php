<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_product_type';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
