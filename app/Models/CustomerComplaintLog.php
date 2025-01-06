<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComplaintLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $table = 'tbl_customer_complaints_logs';
    public $timestamps = false;

    protected $primaryKey = 'id';


    public function tbl_customer_complaints(){
        return $this->belongsTo(CustomerComplaint::class, 'tbl_customer_complaints_id', 'id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'createdby', 'id');
    }
}
