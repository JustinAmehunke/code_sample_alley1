<?php

namespace App\Http\Controllers\SuperAdministrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailTemplate;
use Illuminate\Support\Carbon;

class EmailTemplateController extends Controller
{
    public function createEmailTemplate(Request $request){

        // $validator = Validator::make($request->all(), [
        //     'email_variable' => 'required|string|max:255',
        //     'subject' => 'required|string|max:255',
        //     'template_body' => 'required|string|max:255',
        //     'email_group' => 'required|string|max:255',
        // ],[
        //     'email_variable.required' => 'Email Variable is required.',
        //     'subject.required' => 'Subject is Required.',
        //     'template_body.required' => 'Message is required.',
        //     'email_group.required' => 'Category is required.',
        // ]);
      
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        
        
        $request->validate([
            'email_variable' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template_body' => 'required|string|max:255',
            'email_group' => 'required|string|max:255',
        ], [
            'email_variable.required' => 'Email Variable is required.',
            'subject.required' => 'Subject is Required.',
            'template_body.required' => 'Message is required.',
            'email_group.required' => 'Category is required.',
        ]);

        EmailTemplate::insert([
            'email_group' => $request->email_variable,
            'subject' => $request->subject,	
            'template_body' => $request->template_body,
            'category' => $request->email_group,	
            'default_template'=> $request->default_item ? 1 : 0,
            'createdon' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_message', 'Email Template saved successfully.');
    }

    public function updateEmailTemplate(Request $request){
        
        $request->validate([
            'id' => 'required',
            'email_variable' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template_body' => 'required|string|max:255',
            'email_group' => 'required|string|max:255',
        ], [
            'email_variable.required' => 'Email Variable is required.',
            'subject.required' => 'Subject is Required.',
            'template_body.required' => 'Message is required.',
            'email_group.required' => 'Category is required.',
        ]);

        EmailTemplate::findOrFail($request->id)->update([
            'email_group' => $request->email_variable,
            'subject' => $request->subject,	
            'template_body' => $request->template_body,
            'category' => $request->email_group,	
            'default_template'=> $request->default_item ? 1 : 0,
            // 'createdon' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_message', 'Email Template updated successfully.');
    }
}
