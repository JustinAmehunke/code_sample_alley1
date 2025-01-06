<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiryNotificationInterval extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_inventory_exp_notification';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
