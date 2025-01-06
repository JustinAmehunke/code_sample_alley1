<?php

namespace App\Http\Controllers\GeneratePDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Models\DocumentProduct;
use App\Models\CompanyProfile;
use App\Models\DocumentChecklist;
use App\Http\Traits\GenerateDocument;
use App\Models\Document;
use App\Http\Traits\DocumentSysEngine;

use PDF;
use Auth;
use Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;

class ProposalAndMandateToPDFController extends Controller
{
    use GenerateDocument;
    use DocumentSysEngine;
    public function generateBoth(Request $request){
        $token = $request->token;

        $document_application = DocumentApplication::where('token', $token)->first();

        $checklists = DocumentChecklist::where('tbl_document_applications_id', $document_application->id)
        ->where('deleted', 0)
        ->pluck('tbl_document_type_id')->toArray();

        // $mandate_file = null;
        // $proposal_file = null;

        // if(in_array(2, $checklists)){
            $mandate_file = $this->generateMandatePDF($token);
        // }

        // if(in_array(1, $checklists)){
            $proposal_file = $this->generateProposalPDF($token);
        // }

        // $document_application = DocumentApplication::where('token', $token)->first();
        $this->checkCheckList($document_application->id, -1);
        
        return response(['status' => 'success', 'mandate' => $mandate_file ? 1 : 0, 'proposal' => $proposal_file ? 1 : 0], 200);
    }
    public function generateMandatePDF($token)
    {

        $document_application = DocumentApplication::where('token', $token)->first();
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        $company_profile = CompanyProfile::where('deleted', 0)->first();
        $mandate = DocumentProduct::where([['template_link', '=' ,'mandate'], ['deleted', '=', 0]])->first();

        $dataMap = $this->generateMandate($token);

        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $mandate->template);

        $pdf = PDF::loadView('pdf.pdf-blueprint',compact('previewContent'))->setPaper('a4')->setOptions([
            'tempDir' => public_path(),
            'chroot' => public_path(),
            'width' => 210, // Width in millimeters (A4 width)
            'height' => 297, // Height in millimeters (A4 height)
        ]);
        
        if ($pdf) {
            $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_');
            file_put_contents($tempFilePath, $pdf->output());
            
            // Generate a unique filename using UUID
            $uuid = Str::uuid()->toString();
            $extension = 'pdf'; // PDF extension
            
            // Concatenate the UUID and extension to create the unique filename
            $newFileName = 'mandate-doc-' . $uuid . '.' . $extension;
            $targetDir = 'documents';
            
            // Store the file with the unique filename on S3
            $status = Storage::disk('s3')->putFileAs($targetDir, $tempFilePath, $newFileName);
            
            // Delete the temporary file
            unlink($tempFilePath);
            // return $status;

            $doc_arr = array(
                'tbl_document_type_id' => 2,
                'tbl_branch_id' => ($document_application['tbl_branch_id'] > 0) ? $document_application['tbl_branch_id'] : 0,
                'createdby' => auth()->user()?->id,
                'document' => $newFileName,
                'tbl_document_images_id' => 1,
                'tbl_customers_id' => 0,
                'content' => $newFileName,
                'tbl_restrictions_id' => 1,
                'document_no' => 'M' . $document_application['request_no'],
                'document_name' => 'M' . $document_application['request_no'],
                'policy_no' => $document_application['policy_no'],
                'tbl_document_applications_id' => $document_application['id']
              );
            //  DumpScreen($vals_arr, true);
            $document = Document::create($doc_arr);
        
            $this->scannedRequestLogs($document->id, auth()->user()?->firstname.' '.auth()->user()?->lastname, 'Attached Mandate Form', 3);
            $this->updateCheckList( $document_application['id'], 2, 'PASSED', 'Mandate form attached to request');
    
        }

        return $status;
    }

    public function generateProposalPDF($token)
    {
        
        $document_application = DocumentApplication::where('token', $token)->first();
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        $company_profile = CompanyProfile::where('deleted', 0)->first();
        
        $serverside = true;
        $dataMap = $this->generateProposal($token, $serverside);

        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $document_product->template);

        

        $pdf = PDF::loadView('pdf.pdf-blueprint',compact('previewContent'))->setPaper('a4')->setOptions([
            'tempDir' => public_path(),
            'chroot' => public_path(),
            'width' => 210, // Width in millimeters (A4 width)
            'height' => 297, // Height in millimeters (A4 height)
        ]);

        if ($pdf) {
            $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_');
            file_put_contents($tempFilePath, $pdf->output());
            
            // Generate a unique filename using UUID
            $uuid = Str::uuid()->toString();
            $extension = 'pdf'; // PDF extension
            
            // Concatenate the UUID and extension to create the unique filename
            $newFileName = 'proposal-doc-' . $uuid . '.' . $extension;
            $targetDir = 'documents';
            
            // Store the file with the unique filename on S3
            $status = Storage::disk('s3')->putFileAs($targetDir, $tempFilePath, $newFileName);
            
            // Delete the temporary file
            unlink($tempFilePath);
            // return $status;

            $doc_arr = array(
                'tbl_document_type_id' => ($document_application['tbl_documents_products_id'] == 14) ? 21 : 1,
                'tbl_branch_id' => ($document_application['tbl_branch_id'] > 0) ? $document_application['tbl_branch_id'] : 0,
                'createdby' => auth()->user()?->id,
                'document' => $newFileName,
                'tbl_document_images_id' => 1,
                'tbl_customers_id' => 0,
                'content' => $newFileName,
                'tbl_restrictions_id' => 1,
                'document_no' => 'P' . $document_application['request_no'],
                'document_name' => 'P' . $document_application['request_no'],
                'policy_no' => $document_application['policy_no'],
                'tbl_document_applications_id' => $document_application['id']
              );
            //  DumpScreen($vals_arr, true);
            $document = Document::create($doc_arr);
        
            $this->scannedRequestLogs($document->id, auth()->user()?->firstname.' '.auth()->user()?->lastnname, 'Attached Proposal Form', 3);
            // DumpScreen($record['id'], true);
            if( $document_application['tbl_documents_products_id'] == 14){
                $this->updateCheckList( $document_application['id'], 21, 'PASSED', 'Proposal form attached to request');
            }else{
                $this->updateCheckList( $document_application['id'], 1, 'PASSED', 'Proposal form attached to request');
            }
        }
        return $status;
    }
}
