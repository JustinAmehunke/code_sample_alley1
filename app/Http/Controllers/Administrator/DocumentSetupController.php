<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentSetupDetail;
use App\Models\DocumentSetup;

class DocumentSetupController extends Controller
{
    public function saveUpdateDocumentSetup(Request $request){
        // return $request;

        $document_setup = DocumentSetup::where('id', $request->document_setup_id)->where('deleted', 0)->get();
        if(count($document_setup)>0){
            DocumentSetupDetail::where('tbl_document_setup_id', $request->document_setup_id)->update([
                'deleted' => 1
            ]);
        }
        
        try {
            foreach ($request->workflow_type_id as $key => $value) {
                if(isset($request->document_setup_detail_id[$key])){
                    $exist = DocumentSetupDetail::find($request->document_setup_detail_id[$key])->get();
                    if(count($exist)>0){
                        DocumentSetupDetail::findOrFail($request->document_setup_detail_id[$key])->update([
                            'tbl_document_setup_id' => $request->document_setup_id,
                            'tbl_document_workflow_type_id' => $request->workflow_type_id[$key],
                            'reference' => isset($request->reference[$key])?$request->reference[$key]:'',
                            'deleted' => 0,
                            'require_evidence' => $request->require_evidence[$key]
                        ]);
                    }else{
                        DocumentSetupDetail::insert([
                            'tbl_document_setup_id' => $request->document_setup_id,
                            'tbl_document_workflow_type_id' => $request->workflow_type_id[$key],
                            'reference' => isset($request->reference[$key])?$request->reference[$key]:'',
                            'deleted' => 0,
                            'createdby' => Auth()->id(),
                            'require_evidence' => $request->require_evidence[$key]
                        ]);
                    }
                }else{
                    DocumentSetupDetail::insert([
                        'tbl_document_setup_id' => $request->document_setup_id,
                        'tbl_document_workflow_type_id' => $request->workflow_type_id[$key],
                        'reference' => isset($request->reference[$key])?$request->reference[$key]:'',
                        'deleted' => 0,
                        'createdby' => Auth()->id(),
                        'require_evidence' => $request->require_evidence[$key]
                    ]);
                }
               
            }

            return redirect()->back()->with('success_message', 'Changes saved successfully.');;
        } catch (\Throwable $th) {
            throw $th;
        }
      
    }

    public function newWorkflow(Request $request){
          
        $request->validate([
            'name' => 'required|string|max:255',
            'product' => 'required|integer|max:255',
        ], [
            'name.required' => 'Workflow name is Required.',
            'product.required' => 'Product is required.',
        ]);

        $exist = DocumentSetup::where('tbl_documents_products_id', $request->product)->where('deleted', 0)->get();
        if(count($exist)>0){
            return redirect()->back()->with('error_message', 'Product already has a workflow attached');
        }else{
            DocumentSetup::insert([
                "name" => $request->name,
                "tbl_documents_products_id" => $request->product,
                "createdby" => Auth()->id()
            ]);
            return redirect()->back()->with('success_message', 'Workflow saved successfully.');
        }
    }
}
