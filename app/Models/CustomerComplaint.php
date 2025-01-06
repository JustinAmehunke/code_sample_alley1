<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComplaint extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_customer_complaints';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id'); // ?? Not Assigned
    }
    public function tbl_users()
    {
        return $this->belongsTo(User::class, 'tbl_users_id', 'id');
    }

    public function tbl_branch()
    {
        return $this->belongsTo(Branch::class, 'tbl_branch_id', 'id');
    }

    public function tbl_application_status()
    {
        return $this->belongsTo(ApplicationStatus::class, 'tbl_application_status_id', 'id');
    }

    public function tbl_complaint_reporting_channel()
    {
        return $this->belongsTo(ComplaintReportingChannel::class, 'reporting_channel', 'id');
    }

    public function tbl_complaints_categories()
    {
        return $this->belongsTo(ComplaintCategory::class, 'tbl_complaints_categories_id', 'id');
    }

    //
   

    public function tbl_complaint_classifications()
    {
        return $this->belongsTo(ComplaintClassification::class, 'tbl_complaint_classifications_id');
    }

    public function tbl_method_of_contact()
    {
        return $this->belongsTo(MethodOfContact::class, 'tbl_method_of_contact_id');
    }

    public function tbl_notified_by()
    {
        return $this->belongsTo(NotifiedBy::class, 'tbl_notified_by_id');
    }

    public function tbl_product_type()
    {
        return $this->belongsTo(ProductType::class, 'tbl_product_type_id');
    }

    public function tbl_process()
    {
        return $this->belongsTo(Processs::class, 'tbl_process_id');
    }

    public function tbl_level()
    {
        return $this->belongsTo(Level::class, 'tbl_level_id');
    }

    public function tbl_how_resolved()
    {
        return $this->belongsTo(HowResolved::class, 'tbl_how_resolved_id');
    }

}
