<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceMainAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_finance_main_accounts';
    public $timestamps = false;

    protected $primaryKey = 'id';
}
