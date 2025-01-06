@extends('layouts.main-master')
@section('content')

<style>
    .list-nostyled{
        list-style: none;
    }
    .tab-primary {
    color: #fff;
    background-color: #0f9cf3 !important;
    border-color: #0f9cf3 !important;
    }
    .tab-white{
        color: #fff;
    }

    .card .collapsed .card-header{
        background-color: #f1f5f7 !important;
        border-bottom: 0 solid #f1f5f7 !important;
    }
    .card .collapsed .card-header .tab-white{
        color: #0a1832 !important;
    }
    
element.style {
}
.alert-danger {
    color: #921c32;
    background-color: #fdd5dd;
    border-color: #fbc1cb;
}
.alert-dismissible {
    padding-right: 3.75rem;
}
.alert {
    padding: 0.3rem 1.25rem;
}
</style>
{{-- <form class="custom-validation" action="{{route('update-company-profile')}}" method="post" enctype="multipart/form-data"> --}}
    @csrf
    @if(session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
    @endif
    @if(session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger parsley-danger">
            <ul> 
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    Application Modules
                    <button type="button" id="new_module" style="float: right;" data-module="" class="btn btn-primary btn-sm waves-effect waves-light new_module">
                        <i class="ri-add-fill align-middle me-2"></i> Add New Module
                    </button>
                </h5>
                <div class="card-body">
                    <div class="col-12">
                        @if(session('success_message'))
                          <div class="alert alert-success alert-dismissible fade show success-notification" role="alert">
                            {{ session('success_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                        @endif
                        @if(request()->has('id'))
                            @php
                                $btn = "Create ";
                                $id = base64_decode(request()->input('id'));
                                $user = \App\Models\User::find($id);
                                $restrictions = json_decode($user->restrictions);
                                $stockPrivileges = json_decode($user->stock_privileges);
                                $title = "Edit User: " . $user->full_name;
                                $btn = "Update";

                                if($user) {
                                    $data = $user;
                                    $branches = $user->tbl_user_branch()->where('deleted', 0)->pluck('tbl_branch_id')->toArray();
                                    $data['assigned_branches'] = $branches;
                                }
                            @endphp
                        @elseif (!isset($data))
                            @php
                                $btn = "Create";
                                $data = [
                                    'firstname' => '',
                                    'lastname' => '',
                                    'tbl_gender_id' => '',
                                    'mobile' => '',
                                    'email' => '',
                                    // 'esu_organisation_unit_id' => '',
                                    'img' => '',
                                    'tbl_departments_id' => '',
                                    'tbl_designations_id' => '',
                                    'actions_profile' => '',
                                    'default_branch' => '',
                                    // 'username' => '',
                                    'password' => '',
                                    'tbl_job_grades_id' => '',
                                    'auth_type' => '',
                                    'tbl_user_category_id' => '',
                                    'address' => '',
                                    'emergency_contact' => '',
                                    'date_of_birth' => '',
                                    'date_of_employ' => '',
                                    'date_of_exit' => '',
                                    'restrictions' => '',
                                    'auth_type' => '',
                                    'user_code' => '',
                                    'pin_code' => '',
                                    'stock_privileges' => ''
                                ];
                            @endphp
                        @endif
                    


                        @php
                            $user_category =  \App\Models\UserCategory::where('state', 1)
                                        ->where('user_belong_to_branch', 1)
                                        ->get();
                        @endphp         
                       
                        <form class="form-horizontal form-bordered" action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- begin col-6 -->
                                    <div class="col-md-6">
                                        <!-- begin panel -->
                                        <div class="panel panel-inverse">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">1. Personal Details</h4>
                                            </div>
                                            <div class="panel-body panel-form">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="firstname">First name *:</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control" type="text" id="firstname" name="firstname" value="<?= (isset($data['firstname'])) ? $data['firstname'] : "" ?>" required />
                                                        <input type="hidden" id="actiontype" name="actiontype" value="<?= (isset($id)) ? $id : 0 ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="lastname">Lastname *:</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control" type="lastname" id="lastname" name="lastname" value="<?= (isset($data['lastname'])) ? $data['lastname'] : "" ?>" required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="email">Email * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control" type="email" id="email" name="email" placeholder="eg. stev@mail.com" value="<?= (isset($data['email'])) ? $data['email'] : "" ?>" required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="gender">Gender * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select id="gender" name="gender" class="form-control" required>
                                                            <option value=""></option>
                                                            <?php foreach (\App\Models\Gender::all() as $item) : ?>
                                                                <option value="<?= $item['id'] ?>" <?= (isset($data['tbl_gender_id']) && $data['tbl_gender_id'] == $item['id']) ? "selected" : "" ?>><?= $item['gender_name'] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
            
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4" for="mobile">Mobile Number</label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="mobile" class="form-control phone-num" id="mobile" value="<?= (isset($data['mobile'])) ? $data['mobile'] : "" ?>" required />
                                                    </div>
                                                </div>
            
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Upload Profile Image :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control" type="file" id="fileToUpload" name="fileToUpload" />
                                                    </div>
                                                </div>
                                                <?php if (isset($data['img']) && !empty($data['img'])) : ?>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4 col-sm-4" for="view">Profile Image :</label>
                                                        <div class="col-md-6 col-sm-6">
            
                                                            <img class="form-control" id="view" style="min-height:200px; height:200px;" src="userImages/<?= $data['img'] ?>" />
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <!-- end panel -->
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">User Particulars</h4>
                                            </div>
                                            <div class="panel-body panel-form">
                                                <?php if (isset($id)) : ?>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">User Code/Staff ID</label>
                                                        <div class="col-md-6">
                                                            <input type="text" name="code" id="code" class="form-control m-b-5" readonly onfocus="blur()" value="<?= (isset($data['code'])) ? $data['code'] : "" ?>" />
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="address">Home Address * :</label>
                                                    <div class="col-md-8 col-sm-8">
                                                        <textarea id="address" name="address" class="form-control" rows="6"><?= (isset($data['address'])) ? $data['address'] : "" ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="channel">Emergency Contact *:</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control phone-num" type="text" id="emergency_contact" name="emergency_contact" value="<?= (isset($data['emergency_contact'])) ? $data['emergency_contact'] : "" ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="date_of_birth">Date of Birth * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control datepicker-age" type="text" id="date_of_birth" name="date_of_birth" value="<?= (isset($data['date_of_birth'])) ? $data['date_of_birth'] : "" ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="date_of_employ">Employment Date * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control datepicker-default" type="text" id="date_of_employ" name="date_of_employ" value="<?= (isset($data['date_of_employ'])) ? $data['date_of_employ'] : "" ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="date_of_exit">Exit Date * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input class="form-control datepicker-default" type="text" id="date_of_exit" name="date_of_exit" value="<?= (isset($data['date_of_exit'])) ? $data['date_of_exit'] : "" ?>" />
                                                    </div>
                                                </div>
            
                                            </div>
                                        </div>
            
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">User Account</h4>
                                            </div>
                                            <div class="panel-body panel-form">
            
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="user_category">User Type:</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select id="user_category" name="user_category" class="form-control" required>
                                                            <option value=""></option>
                                                            <?php foreach ($user_category as $item) : ?>
                                                                <option data-has="<?= $item['user_belong_to_branch'] ?>" value="<?= $item['id'] ?>" <?= (isset($data['tbl_user_category_id']) && $data['tbl_user_category_id'] == $item['id']) ? "selected" : "" ?>><?= $item['user_category'] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
            
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="user_category">Sub User Type:</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select id="sub_user_category" name="sub_user_category" class="form-control" required>
                                                            <option value=""></option>
                                                        </select>
            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">Global Access Privileges</h4>
                                            </div>
                                            <div class="panel-body panel-form">
                                                <div class="form-group has_branch">
                                                    <label class="control-label col-md-4 col-sm-4" for="organisation">Organisation * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select id="organisation" name="organisation" class="form-control" readonly>
            
                                                            <option value="">
                                                            <option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group has_branch">
                                                    <label class="control-label col-md-4 col-sm-4" for="assigned_branches">Branch(es) * :</label>
                                                    <div class="col-md-8 col-sm-8">
                                                        <div class="row">
                                                            <div class="col-md-10 col-sm-12">
                                                                <select id="assigned_branches" name="assigned_branches[]" class="form-control selectMultiple" multiple readonly>
                                                                    <option value="">
                                                                    <option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 col-sm-6">
                                                                <button id="selectall" class="btn btn-xs btn-info">All</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9" id="branch_details">
                                                            <div class="radio" id="radioTemp">
                                                                <?php
                                                                $branch_item = getConfigbyKey("default_branch");
                                                                $branchqry = \App\Models\Branch::find($branch_item);
                                                                ?>
                                                                <label>
                                                                    <input type="radio" name="default_branch" id="default_branch" required value="<?= $branch_item ?>" <?= (isset($data['default_branch']) && $data['default_branch'] == $branch_item) ? "checked" : "" ?>>
                                                                    <span id="branch_txt"><?= $branchqry['branch_name'] ?></span>
                                                                </label>
                                                            </div>
            
                                                            @if (!empty($data['assigned_branches']))
                                                            @foreach ($data['assigned_branches'] as $branch_item)
                                                                @php
                                                                    $branchqry = \App\Models\Branch::find($branch_item);
                                                                @endphp
                                                                @if ($branchqry)
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input type="radio" name="default_branch" id="default_branch" value="{{ $branch_item }}" {{ (isset($data['default_branch']) && $data['default_branch'] == $branch_item) ? "checked" : "" }}>
                                                                            <span id="branch_txt">{{ $branchqry->branch_name }}</span>
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        
            
            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="designations">Job Role * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select id="job_role" name="tbl_job_grades_id" class="form-control" required>
                                                            <option value=""></option>
                                                            @foreach(\App\Models\JobGrade::where('deleted', 0)->get() as $item)
                                                                <option value="{{ $item->id }}" {{ (isset($data['tbl_job_grades_id']) && $data['tbl_job_grades_id'] == $item->id) ? 'selected' : '' }}>{{ $item->job_grade }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="designations">Floor * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <input type="text" name="floor" class="form-control numeric-int" value="<?= isset($data['floor']) ? $data['floor'] : '' ?>">
                                                    </div>
                                                </div>
            
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4" for="reports_to">Reports to * :</label>
                                                    <div class="col-md-6 col-sm-6">
                                                        <select id="reports_to" name="reports_to" class="form-control selectMultiple">
                                                            <option value="-1">Self Managed
                                                            <option>
                                                            @foreach(\App\Models\User::where('deleted', 0)->orderBy('firstname')->get() as $user)
                                                                <option value="{{ $user->id }}" {{ (isset($data['reports_to']) && $data['reports_to'] == $user->id) ? 'selected' : '' }}>
                                                                    {{ $user->firstname }} {{ $user->lastname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
            
            
            
            
            
            
                                            </div>
                                        </div>
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">Assign Function</h4>
                                            </div>
                                            <div class="panel-body panel-form">
            
                                                <div id="technical_requirement">
                                                    <div id="custom_tr" class="hide">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4 col-sm-4" for="department">Function * :</label>
                                                            <div class="col-md-6 col-sm-6">
                                                                <select id="department" name="department[]" class="form-control" required>
                                                                    <option value=""></option>
                                                                    @foreach(\App\Models\Department::where('deleted', 0)->get() as $item)
                                                                        <option value="{{ $item->id }}">{{ $item->department_name }}</option>
                                                                    @endforeach                                                                
                                                                </select>
            
                                                            </div>
                                                        </div>
            
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4 col-sm-4" for="designations">Profile Designation * :</label>
                                                            <div class="col-md-6 col-sm-6">
                                                                <select id="designations" name="designations[]" class="form-control" readonly>
                                                                    <option value="">
                                                                    <option>
                                                                </select>
                                                            </div>
                                                        </div>
            
            
                                                        <a href="#" id="cmdRemoveCustomTr" class="btn btn-xs btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                                            <i class="fa fa-minus"></i>
                                                        </a>
                                                        <br>
                                                    </div>
            
                                                    @php
                                                        $ass = null;
                                                        if (isset($data['id'])) {
                                                            $ass = \App\Models\UserAssignedFunction::where('tbl_users_id', $data['id'])->where('deleted', 0)->get();
                                                        }
                                                    @endphp
                                                
                                                   @if ($ass)
                                                    @foreach($ass as $as)
                                                        <div id="custom_tr">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 col-sm-4" for="department">Function * :</label>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <select id="department" name="department[]" class="form-control" required>
                                                                        <option value=""></option>
                                                                        @foreach(\App\Models\Department::where('deleted', 0)->get() as $item)
                                                                            <option value="{{ $item->id }}" {{ (isset($as->tbl_departments_id) && $as->tbl_departments_id == $item->id) ? "selected" : "" }}>{{ $item->department_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                    
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4 col-sm-4" for="designations">Profile Designation * :</label>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <select id="designations" name="designations[]" class="form-control" required>
                                                                        <option value=""></option>
                                                                        @foreach(\App\Models\Designation::where('deleted', 0)->where('tbl_departments_id', $as->tbl_departments_id)->get() as $item)
                                                                            <option value="{{ $item->id }}" {{ (isset($as->tbl_designations_id) && $as->tbl_designations_id == $item->id) ? "selected" : "" }}>{{ $item->designation }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <a href="#" id="cmdRemoveCustomTr" class="btn btn-xs btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                                                <i class="fa fa-minus"></i>
                                                            </a>
                                                            <br>
                                                        </div>
                                                    @endforeach
                                                   @endif
                                                </div>
                                                <div class="row-fluid">
                                                    <div class="col-md-12">
                                                        <a href="#" id="add_row" class="btn btn-xs btn-danger pull-left" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus"></i> Add Another</a>
                                                    </div>
                                                </div>
            
                                            </div>
                                        </div>
            
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">Document Privileges</h4>
                                            </div>
                                            <div class="panel-body panel-form">
            
            
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Security Classification</label>
                                                    <div class="col-md-6">
                                                        <?= comboBuilderMultipleSelect('tbl_restrictions', 'restrictions[]', 'restriction_name', 'id', isset($restrictions) ? $restrictions : '', 'where deleted = 0') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Delete all documents</label>
                                                    <div class="col-md-6">
                                                        <div class="switch switch-sm switch-primary">
                                                            <input type="checkbox" name="delete_documents_yn" data-plugin-ios-switch <?= ($data['delete_documents_yn'] > 0) ? 'checked="checked"' : '' ?> />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Delete Product Requests</label>
                                                    <div class="col-md-6">
                                                        <div class="switch switch-sm switch-primary">
                                                            <div class="switch switch-sm switch-success">
                                                                <input type="checkbox" name="delete_product_request_yn" data-plugin-ios-switch <?= ($data['delete_product_request_yn'] > 0) ? 'checked="checked"' : '' ?> />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Override status</label>
                                                    <div class="col-md-6">
                                                        <div class="switch switch-sm switch-primary">
                                                            <div class="switch switch-sm switch-success">
                                                                <input type="checkbox" name="override_status_yn" data-plugin-ios-switch <?= ($data['override_status_yn'] > 0) ? 'checked="checked"' : '' ?> />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
            
                                            </div>
            
                                        </div>
            
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">Memo Privileges</h4>
                                            </div>
                                            <div class="panel-body panel-form">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">View All Memo's</label>
                                                    <div class="col-md-6">
                                                        <select name="approve_memo_yn" class="form-control">
                                                            <?= YesNo($data['approve_memo_yn']) ?>
                                                        </select>
                                                    </div>
                                                </div>
            
                                            </div>
                                        </div>
            
                                        <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                            <div class="panel-heading">
            
                                                <h4 class="panel-title">Inventory Privileges</h4>
                                            </div>
                                            <div class="panel-body panel-form">
            
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">User Code</label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="user_code" class="form-control numeric-int" value="<?= (isset($data['user_code'])) ? $data['user_code'] : "" ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Pin Code</label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="pin_code" class="form-control numeric-int" value="<?= (isset($data['pin_code'])) ? $data['pin_code'] : "" ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Stock Privileges</label>
                                                    <div class="col-md-6">
                                                        <?= comboBuilderMultipleSelectNR('app_parameters', 'stock_privileges[]', 'label', 'value', isset($stock_privileges) ? $stock_privileges : '', "where name = 'STOCK_ACCESS'") ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
            
                                    </div>
                                </div>
            
            
                                <div class="col-md-12">
                                    <div class="panel panel-inverse" data-sortable-id="form-plugins-8">
                                        <div class="panel-body panel-form">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <button type="submit" name="bntCreate" class="mb-xs mt-xs mr-xs btn btn-success"><?= $btn ?>User</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="list-users" class="btn btn-warning m-r-5 m-b-5">Back to User List</a>
                                                </div>
                                            </div>
            
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                        </form>
                    </div>
                </div>
            </div>
           
        </div>
       
    </div>
{{-- </form> --}}
{{-- Module / Sub  Module Modal --}}
<div id="new_module_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adding New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_new_module" action="#" method="POST" >
                <div class="modal-body">
                        @csrf
                        <div class="col-md-12">
                            <div id="modal_concent">
                              
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <span class="showajaxfeed" id="showajax_feed_new_module"></span>
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_new_module" class="btn btn-primary waves-effect waves-light">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection
    
@section('application-status-script')
    <!-- Required datatable js -->
    <script src="{{asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    
    <!-- Responsive examples -->
    <script src="{{asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Datatable init js -->
    {{-- <script src="{{asset('/assets/js/custom/datatable.init.js')}}"></script> --}}
     <!-- Datatable init js -->
     <script src="{{asset('/assets/js/pages/datatables.init.js')}}"></script>
      <!-- Buttons examples -->
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
      <script src="{{asset('/assets/libs/jszip/jszip.min.js')}}"></script>
      <script src="{{asset('/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
      <script src="{{asset('/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
      <script src="{{asset('/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

    {{-- <script src="{{asset('/assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('/assets/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{asset('/assets/js/ajax-utils.js')}}"></script> --}}
    <script>
         $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function(){
            // $("#datatable").DataTable();
        });
    </script>
    
    <script>
        $('.new_module').on('click', function(){
            $('#new_module_modal').modal('show');
            $('#modal_concent').empty();
            let id = this.id;
            let mod_id = $(this).data('module');

            //append content into modal
            if(id == 'new_module'){
                $('#modalLabel').html('Adding New Module');
                $('#form_new_module').attr('action', '/super-admin/create/action-module/');
                
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Module Name</label>
                        <input type="text" class="form-control" name="module_name" id="module_name"  required="">
                    </div>
                `);
            }
            if(id == 'new_sub-module'){
                $('#modalLabel').html('Adding New Sub Module');
                $('#form_new_module').attr('action', '/super-admin/create/action-submodule/');
               
                $('#modal_concent').append(`
                    <div class="mb-3 position-relative">
                        <label for="validationTooltip04" class="form-label">Sub Module Name</label>
                        <input type="text" class="form-control" name="module_name" id="module_name"  required="">
                        <input type="hidden" name="module_id" id="module_id" value="${mod_id}"  required="">
                    </div>
                `);
            }
        });
        // $('#new_sub-module').on('click', function(){
        //     $('#new_sub-module_modal').modal('show');
        // });
    </script>
    <script>
        $('.action_submodule').on('click', function(){
            $('#submodule_details').modal('show');
            $('#tr-cont').empty();
            // $('.modal-btns').css('cursor', 'not-allowed');
            $('.modal-btns').prop('disabled', true);
            
            // get data from on button element
            let id = this.id;
            let module_id = $(this).data('module');
            let submodule_id = $(this).data('submodule');
            let submodule_name = $(this).data('submodule_name');
            let module_name = $(this).data('module_name');
            let use_module = $(this).data('use_module');
            // add module and sub module id to sub module details the form in modal
            $('#module_id').val(module_id);
            $('#submodule_id').val(submodule_id);
            $('#submod_id').val(submodule_id);
            $('#submodule_name').val(submodule_name);
            $('#trigger_btn').val(id);
            if(use_module){$('#use_module').prop('checked', true);}
            $('#submodule_name_title').html(submodule_name);
            $('#module_name_title').html(module_name);
            // clear local storage
            localStorage.removeItem('ajax-data');
            // show loader
            $('#ajax-loading').css('display', 'block');
            //get submodule details data
            $.ajax({
                url: '/super-admin/view/action-submodule/'+module_id+'/'+submodule_id,
                type: 'POST',
                data: 'nth',
                success: function(resp) {
                    localStorage.setItem('ajax-data', JSON.stringify(resp));
                    let count = $('.tr').length;
                    $('#ajax-loading').css('display', 'none');
                    $('.modal-btns').prop('disabled', false);
                    // $('.modal-btns').css('cursor', 'pointer');
                    if(resp){
                        if(resp.custom_list && resp.custom_list.length > 0){
                            resp.custom_list.forEach(list => {
                                let x = ++count
                                $('#tr-cont').append(`
                                    <tr class="tr" id="tr-${x}">
                                        <th scope="row">
                                            ${x}
                                            <input type="hidden" name="application_status_id[]" value="${list.id}">
                                        </th>
                                        <td>
                                            ${list.id}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="status_name[]" id="" value="${list.status_name}" >
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="workflow_no[]" id="" value="${list.workflow_no}" style="width: 80px;">
                                        </td>
                                        <td>
                                            <select class="form-select" name="stage[]" id="stage_${x}">
                                            
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select" name="endpoint[]" id="endpoint_${x}">
                                                
                                            </select>
                                        </td>
                                       
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="delete[]" value="${list.id}" id="formCheck2">
                                        </td>
                                    </tr>
                                `);

                                if( resp.stages){
                                    resp.stages.forEach(stage => {
                                        let option;
                                        if(stage.id == list.tbl_application_status_stage_id){
                                            option = `<option value="${stage.id}" selected="">${stage.stage_name}[${stage.id}]</option>`;
                                        }else{
                                            option = `<option value="${stage.id}">${stage.stage_name}[${stage.id}]</option>`;
                                        }
                                        $('#stage_'+x).append(option);
                                    });
                                }
                            
                                if(resp.endpoints){
                                    resp.endpoints.forEach(endpoint => {
                                        let option;
                                        if(endpoint.id == list.tbl_application_status_endpoints_id){
                                            option = `<option value="${endpoint.id}" selected="">${endpoint.endpoint_name}[${endpoint.id}]</option>`;
                                        }else{
                                            option = `<option value="${endpoint.id}">${endpoint.endpoint_name}[${endpoint.id}]</option>`;
                                        }
                                        $('#endpoint_'+x).append(option);
                                    });
                                }
                            });
                        }else{
                            $('#ajax-nodata').css('display', 'block');
                            setTimeout(() => {
                                $('#ajax-nodata').css('display', 'none');
                            }, 2000);
                        }
                        
                    }else{
                        $('#ajax-nodata').css('display', 'block');
                        setTimeout(() => {
                            $('#ajax-nodata').css('display', 'none');
                        }, 2000);
                    }
                   
                }
            });
        });

        $('#add_row').on('click', function(){
            let count = $('.tr').length;
            let x = ++count;
            let data = JSON.parse(localStorage.getItem('ajax-data'));
           
            $('#tr-cont').append(`
                <tr class="tr" id="tr-${x}">
                    <th scope="row">
                        ${x}
                        <input type="hidden" name="application_status_id[]" value="">
                    </th>
                    <td>
                        
                    </td>
                    <td>
                        <input type="text" name="status_name[]" id="" class="form-control" >
                    </td>
                    <td>
                        <input type="number" name="workflow_no[]" id="" class="form-control" value="" style="width: 80px;">
                    </td>
                    <td>
                        <select class="form-select" name="stage[]" id="stage_${x}">
                           
                        </select>
                    </td>
                    <td>
                        <select class="form-select" name="endpoint[]" id="endpoint_${x}">
                          
                        </select>
                    </td>
                    <td>

                    </td>
                    <td>
                        <button type="button"  data-tr="${x}" class="btn btn-danger btn-remove btn-sm waves-effect waves-light">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `);

            if(data.stages){
                data.stages.forEach(stage => {
                    let option = `<option value="${stage.id}">${stage.stage_name}[${stage.id}]</option>`;
                    $('#stage_'+x).append(option);
                });
            }
            
            if(data.endpoints){
                data.endpoints.forEach(endpoint => {
                    let option = `<option value="${endpoint.id}">${endpoint.endpoint_name}[${endpoint.id}]</option>`;
                    $('#endpoint_'+x).append(option);
                });
            }
           
        });

        $(document).on('click', '.btn-remove', function(){
            let num = $(this).data('tr');
            if($(this).data('type')){
                $('#ep-tr-'+num).remove();
            }else{
                $('#tr-'+num).remove();
            }
            
        });
    </script>

    <script>
        // Handle all form submission on this page
        $(document).ready(function() {
            $(document).on('submit', 'form', function(e) {
                e.preventDefault(); // Prevent the default form submission
                $('.modal-btns').prop('disabled', true); //disable buttons

                // Get the form element that triggered the submit event
                let form = $(this);

                // console.log(form.attr('id').split('_')[1]);
                let message = 'loading';
                let fullid = form.attr('id');
                let id = fullid.split('_')[1];
                let type = fullid.split('_')[2];
                let action = form.attr('action');
                // console.log(action);
                // return;

                showAjaxLoading(message, fullid, status = true);
                $('#btn_'+ id + '_' + type).prop('disabled', true);

                // Get the form data
                let formData = form.serialize();

                // Send the form data using AJAX
                $.ajax({
                url: action,
                type: 'POST',
                data: formData,
                success: function(resp) {
                    showAjaxLoading(message = null , fullid, status = false);
                    
                    if(resp.status == 'success'){
                        // Request was successful
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        $('.modal-btns').prop('disabled', false);
                        showAjaxSuccess(message = resp.message , fullid);
                        if(id == 'new'){
                            $('#new_module_modal').modal('hide');
                            location.reload();
                        }
                        if(id == 'details'){
                            // $('#submodule_details').modal('hide');
                        }
                        if(id == 'update'){
                            // $('#submodule_details').modal('hide');
                            let trigger_btn = $('#trigger_btn').val();
                            let updated_submodule_name = $('#submodule_name').val();
                            console.log(trigger_btn);
                            // update data params on trigger button
                            $('#'+trigger_btn).data('submodule_name', updated_submodule_name);
                            if($('#use_module').prop('checked')){$('#'+trigger_btn).data('use_module', 1);}else{$('#'+trigger_btn).data('use_module', 0);}
                            // update button text
                            $('#'+trigger_btn+'_html').html(updated_submodule_name);
                            // update endpoint button text
                            $('#'+trigger_btn+'_ep').data('submodule_name', updated_submodule_name)
                            $('#'+trigger_btn+'_html_ep').html(updated_submodule_name);

                            // update modal title
                            $('#submodule_name_title').html(updated_submodule_name);
                        }

                        if(type == 'module'){
                            //get updated module name
                            let updated_module_name = form.find('#module_name').val();
                            //update the title
                            $('#modue_title_'+id).html(updated_module_name);
                            $('#module_'+id+'_name_title').html(updated_module_name);
                            //update the data params of submodule buttons under this module
                            $('.submodule_module_data_'+id).attr("data-module_name", updated_module_name);
                        }
                        
                    }else{
                        // Request was unsuccessful
                        $('#btn_'+ id + '_' + type).prop('disabled', false);
                        showAjaxError(message = resp.message , fullid);
                    }
                   
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Error occurred during the request
                    console.error('Error:', textStatus, errorThrown);
                    showAjaxError(message = "Something went wrong" , fullid);
                }
                });
            });
        });
    </script>

    {{-- EndPoint --}}
    <script>
        $('.action_endpoint').on('click', function(){
            $('#endpoint_details').modal('show');
            $('#tr-cont-ep').empty();
            // $('.modal-btns').css('cursor', 'not-allowed');
            $('.modal-btns').prop('disabled', true);
            
            // get data from on button element
            let id = this.id;
            let module_id = $(this).data('module');
            let submodule_id = $(this).data('submodule');
            let submodule_name = $(this).data('submodule_name');
            let module_name = $(this).data('module_name');
            let endpoint = $(this).data('endpoint');
            // add module and sub module id to sub module details the form in modal
            $('#ep_module_id').val(module_id);
            $('#ep_submodule_id').val(submodule_id);
            // $('#ep_submod_id').val(submodule_id);
            // $('#ep_submodule_name').val(submodule_name);
            // $('#ep_trigger_btn').val(id);
            // if(use_module){$('#use_module').prop('checked', true);}
            $('#ep_submodule_name_title').html(submodule_name);
            $('#ep_module_name_title').empty();
            $('#ep_module_name_title').html(module_name);
            // clear local storage
            localStorage.removeItem('ajax-data');
            // show loader
            $('#ep_ajax-loading').css('display', 'block');
            //get submodule details data
            $.ajax({
                url: '/super-admin/view/endpoint/details/'+module_id+'/'+submodule_id+'/'+endpoint,
                type: 'POST',
                data: 'nth',
                success: function(resp) {
                    localStorage.setItem('ajax-data', JSON.stringify(resp));
                    let count = $('.tr').length;
                    $('#ep_ajax-loading').css('display', 'none');
                    $('.modal-btns').prop('disabled', false);
                    // $('.modal-btns').css('cursor', 'pointer');
                    if(resp){
                        if(resp.endpoints && resp.endpoints.length > 0){
                            resp.endpoints.forEach(list => {
                                let x = ++count
                                $('#tr-cont-ep').append(`
                                    <tr class="ep-tr" id="ep-tr-${x}">
                                        <th scope="row">
                                            ${x}
                                            <input type="hidden" name="endpoint_details_id[]" value="${list.id}">
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="endpoint_name[]" id="" value="${list.endpoint_name}" >
                                        </td>
                                        <td>
                                            <select class="form-select" name="endpoint_type[]" id="endpoint_${x}">
                                                
                                            </select>
                                        </td>
                                        <td>
                                            ${list.id}
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input" name="delete[]" value="${list.id}" id="formCheck2">
                                        </td>
                                    </tr>
                                `);
                            
                                if(resp.endpoint_status){
                                    resp.endpoint_status.forEach(endpoint => {
                                        let option;
                                        if(endpoint.value == list.endpoint_id){
                                            option = `<option value="${endpoint.value}" selected="">${endpoint.label}</option>`;
                                        }else{
                                            option = `<option value="${endpoint.value}">${endpoint.label}</option>`;
                                        }
                                        $('#endpoint_'+x).append(option);
                                    });
                                }
                            });
                        }else{
                            $('#ep_ajax-nodata').css('display', 'block');
                            setTimeout(() => {
                                $('#ep_ajax-nodata').css('display', 'none');
                            }, 2000);
                        }
                        
                    }else{
                        $('#ep_ajax-nodata').css('display', 'block');
                        setTimeout(() => {
                            $('#ep_ajax-nodata').css('display', 'none');
                        }, 2000);
                    }
                   
                }
            });
        });

        $('#add_eprow').on('click', function(){
            let count = $('.ep-tr').length;
            let x = ++count;
            let data = JSON.parse(localStorage.getItem('ajax-data'));
           
            $('#tr-cont-ep').append(`
                <tr class="ep-tr" id="ep-tr-${x}">
                    <th scope="row">
                        ${x}
                        <input type="hidden" name="endpoint_details_id[]" value="">
                    </th>
                    <td>
                        <input type="text" class="form-control" name="endpoint_name[]" id="" value="" >
                    </td>
                    <td>
                        <select class="form-select" name="endpoint_type[]" id="endpoint_${x}">
                            
                        </select>
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        <button type="button"  data-tr="${x}" data-type="ep" class="btn btn-danger btn-remove btn-sm waves-effect waves-light">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                    <td>
                        <input type="checkbox" class="form-check-input" name="delete[]" value="" id="formCheck2">
                    </td>
                </tr>
            `);
            
            if(data.endpoint_status){
                data.endpoint_status.forEach(endpoint => {
                    let option;
                    option = `<option value="${endpoint.value}">${endpoint.label}</option>`;
                    $('#endpoint_'+x).append(option);
                });
            }
           
        });
    </script>
   

@stop