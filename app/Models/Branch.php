<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_branch';
    public $timestamps = false;

    protected $primaryKey = 'id';

    public function organisation(){
       return $this->belongsTo(OrganisationUnit::class, 'tbl_organisation_unit_id', 'id');
    }
}
