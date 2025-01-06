<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_banks_branch';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function tbl_banks(){
        return $this->belongsTo(Bank::class, 'tbl_banks_id', 'id');
    }
}
