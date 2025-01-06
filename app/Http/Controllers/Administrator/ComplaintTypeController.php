<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintType;

class ComplaintTypeController extends Controller
{
    public function saveComplaintsType(Request $request){
        // return $request;
        ComplaintType::insert([
            "complaint_name" => $request->complaint_name,
            "deleted" => 0,
        ]);
        return redirect()->back()->with('success_message', 'Changes saved successfully.');
    }
    public function updateComplaintsType(Request $request){
        // return $request;
        ComplaintType::findOrFail($request->id)->update([
            "complaint_name" => $request->complaint_name,
        ]);
        return redirect()->back()->with('success_message', 'Changes saved successfully.');
    }

    public function deleteComplaintsType(Request $request){
        // return $request;
        ComplaintType::findOrFail($request->id)->update([
            "deleted" => 1,
        ]);
        return redirect()->back()->with('success_message', 'Complaint Type deleted successfully.');
    }
}
