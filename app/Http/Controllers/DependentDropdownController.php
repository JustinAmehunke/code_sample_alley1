<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Occupation;
use App\Models\Agent;
use App\Models\Bank;
use App\Models\BankBranch;
use App\Http\Traits\Config;
use Illuminate\Support\Facades\Http;

class DependentDropdownController extends Controller
{
    use Config;
    public function getDropdown(Request $request){
        // return $request;
        // return $request->action;
        // return json_encode(90);
        // {
        //     "action_page": "getOccupationList",
        //     "retrieve_type": "occupation_name",
        //     "selected": null,
        //     "original": null,
        //     "keyword": "oooo",
        //     "contain": "false"
        // }

        switch ($request->action) {
            case 'getOccupationList':

                $occupations = Occupation::select('occupation_name', 'slams_id')
                ->orderBy('occupation_name')
                ->get();
        
                $rows = $occupations->toArray();
                return json_encode($rows);

                break;
            case 'getAgentList':

                $agents = Agent::select('agent_code', 'agent_name')
                ->orderBy("agent_name")
                ->get();

                $rows = $agents->toArray();
                return json_encode($rows);

                break; 
            case 'bank_branch':

                $feed = [];

                $id = $request->type_id;

                if ($id != 0) {

                    //Fetch db
                        // $bank_branches = BankBranch::where('tbl_banks_id', $id) 
                        // ->orderBy("branch_name", "Desc")
                        // ->get();
        
                        // $rows = $bank_branches->toArray();
                        // return json_encode($rows);
                    //
                    //// Fetch from slams
                    $rs = Bank::find($id);
                    $auth_response = $this->get_auth_token_from_slams();

                    if (isset($auth_response["error"])) {
                        return response()->json(['state' => 0, 'msg' => 'Failed']);
                    }

                    $token_slams = "Bearer " . $auth_response["access_token"];
                    $authHeader = [
                        'Authorization' => $token_slams,
                        'Accept' => 'application/form-data',
                    ];
                    
                    $data = ['bank_code' => $rs['slams_id']];

                    $rt = $this->CallAPI('GET', env('SLAMS_GET_BRANCHES'), $data, $authHeader);

                    $get_r = json_decode($rt, true);
                } else {
                    $rt = "";
                }

                if ($rt) {
                    $feed = ['state' => 1, 'msg' => 'Successfully done', 'data' => $get_r['BankBranches']];
                } else {
                    $feed = ['state' => 0, 'msg' => 'Failed'];
                }

                return response()->json($feed);

                    break; 
            case 'getEmployerList':
                $feed = [];

                $auth_response = $this->get_auth_token_from_slams();

                if (isset($auth_response["error"])) {
                    return response()->json(['state' => 0, 'msg' => 'Failed']);
                }

                $token_slams = "Bearer " . $auth_response["access_token"];
                $authHeader = [
                    'Authorization' => $token_slams,
                    'Accept' => 'application/form-data',
                ];

                $data = [];
                
                $rt = $this->CallAPI('GET', env('SLAMS_GET_EMPLOYERS'), $data, $authHeader);
                $get_r = json_decode($rt, true);
                
                if ($rt) {
                    $feed = $get_r['Employers'];
                } else {
                    $feed = array('state' => 0, 'msg' => 'Failed');
                }
                echo json_encode($feed);
                break; 
            case 'value':
                        # code...
                        break; 
            case 'value':
                    # code...
                    break;
            
            default:
                # code...
                break;
        }
    }
}
