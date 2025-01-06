<?php
namespace App\Http\CustomClasses;
use Illuminate\Http\Request;
use App\Models\ApplicationStatus;
use Auth;
use DB;

class ApplicationStatusClass {
    // private $table = 'tbl_application_status';
    // private $approval_table = 'tbl_action_sub_module_approvers';
    protected $module;
    protected $submodule;

    public function __construct($Module, $SubModule)
    {
        $this->module = $Module;
        $this->submodule = $SubModule;
    }

    public function getStatusList()
    {
        return ApplicationStatus::where([
            'tbl_actions_module_id' => $this->module,
            'tbl_actions_sub_module_id' => $this->submodule,
        ])->get();
    }

    public function getStatusbyEndpoint($id, $module = null, $submodule = null){
      
        $module = $module ?? $this->module;
        $submodule = $submodule ?? $this->submodule;

        $query = ApplicationStatus::where([
            'tbl_application_status_endpoints_id' => $id,
            'tbl_actions_module_id' => $module,
            'tbl_actions_sub_module_id' => $submodule,
        ]);

        return is_array($id) ? $query->get() : $query->first();
    }

    public function getStatusbyStage($id, $module = null, $submodule = null) {
      
        $module = $module ?? $this->module;
        $submodule = $submodule ?? $this->submodule;

        $query = ApplicationStatus::where([
            'tbl_application_status_stage_id' => $id,
            'tbl_actions_module_id' => $module,
            'tbl_actions_sub_module_id' => $submodule,
        ])->orderBy('workflow_no');

    
        return is_array($id) ? $query->get() : $query->first();
    }
}

?>