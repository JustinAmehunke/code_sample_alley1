<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DocumentType;


class DocumentTypeController extends Controller
{
    public function saveDocumentType(Request $request){
        // return $request;
        $request->validate([
            'document_name' => 'required|string|max:255',
            'badge' => 'required|integer',
            'require_camera' => 'required|integer',
        ]);
       try {
            $exist = DocumentType::where('document_name', trim(strtoupper($request->document_name)))->where('deleted', 0)->get();
            if(count($exist)>0){
                return response( [
                    'status' => 'failed',
                    'message' => 'This Document Type already exists',
                ], 200);
            }else{
                DocumentType::insert([
                    'document_name' => strtoupper($request->document_name),
                    'require_camera' => $request->require_camera,
                    'tbl_badges_id' => $request->badge,
                    'modon' => Carbon::now(),
                    'modby' => Auth()->id(),
                ]);
                $document_types= DocumentType::with('badge')->where('deleted', 0)->get();
                return response( [
                    'status' => 'success',
                    'document_types'=> $document_types,
                    'message' => 'Changes saved successfully',
                ], 200);
            }
       } catch (\Throwable $th) {
        //throw $th;
            return response( [
                'status' => 'failed',
                'error'=> $th->getMessage(),
                'message' => 'Something went wrong',
            ], 200);
       }
       
    }
    public function updateDocumentType(Request $request){
        $request->validate([
            'document_name' => 'required|string|max:255',
            'badge' => 'required|integer',
            'id' => 'required|integer',
            'require_camera' => 'required|integer',
        ]);
       try {
            DocumentType::findOrFail($request->id)->update([
                'document_name' => strtoupper($request->document_name),
                'require_camera' => $request->require_camera,
                'tbl_badges_id' => $request->badge,
                'modon' => Carbon::now(),
                'modby' => Auth()->id(),
            ]);
            $document_types= DocumentType::with('badge')->where('deleted', 0)->get();
            return response( [
                'status' => 'success',
                'document_types'=> $document_types,
                'message' => 'Changes saved successfully',
            ], 200);
       } catch (\Throwable $th) {
        //throw $th;
            return response( [
                'status' => 'failed',
                'error'=> $th->getMessage(),
                'message' => 'Something went wrong',
            ], 200);
        }
    }
    public function softdeleteDocumentType($id){
        DocumentType::findOrFail($id)->update([
            'deleted' => 1,
        ]);
        $document_types= DocumentType::with('badge')->where('deleted', 0)->get();
        return response( [
            'status' => 'success',
            'document_types'=> $document_types,
            'message' => 'Department deleted successfully',
        ], 200);
    }
}
