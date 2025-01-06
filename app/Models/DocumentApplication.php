<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ApplicationStatus;
use App\Models\DocumentProduct;
use App\Models\Branch;
use App\Models\User;


class DocumentApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'tbl_document_applications';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function documentProduct(){
        return $this->belongsTo(DocumentProduct::class, 'tbl_documents_products_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'tbl_users_id', 'id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class, 'tbl_branch_id', 'id');
    }
    public function applicationStatus(){
        return $this->belongsTo(ApplicationStatus::class, 'tbl_application_status_id', 'id');
    }

    public function tbl_documents_products(){
        return $this->belongsTo(DocumentProduct::class, 'tbl_documents_products_id', 'id');
    }
    public function tbl_users(){
        return $this->belongsTo(User::class, 'tbl_users_id', 'id');
    }
    public function createdby(){
        return $this->belongsTo(User::class, 'createdby', 'id');
    }
    public function tbl_branch(){
        return $this->belongsTo(Branch::class, 'tbl_branch_id', 'id');
    }
    public function tbl_application_status(){
        return $this->belongsTo(ApplicationStatus::class, 'tbl_application_status_id', 'id');
    }

    //
    public function shareDocumentLogs()
    {
        return $this->hasMany(ShareDocumentLog::class, 'tbl_document_applications_id');
    }

    // public function documentProduct()
    // {
    //     return $this->belongsTo(DocumentProduct::class, 'tbl_documents_products_id');
    // }
    
    public function tbl_document_workflow()
    {
        return $this->hasMany(DocumentWorkflow::class, 'tbl_document_applications_id');
    }

    public function tbl_document_checklist(){
        return $this->hasMany(DocumentChecklist::class, 'tbl_document_applications_id');
    }

    // public function tbl_document_setup_details(){
    //     return $this->belongsTo(DocumentSetupDetail::class, 'tbl_document_setup_details_id', 'id');
    // }
}
