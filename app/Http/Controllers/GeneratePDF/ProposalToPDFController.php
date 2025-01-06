<?php

namespace App\Http\Controllers\GeneratePDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Models\DocumentProduct;
use App\Models\CompanyProfile;
use App\Http\Traits\GenerateDocument;
use App\Http\Traits\DocumentSysEngine;
use App\Models\Document;
use PDF;
use Auth;
use Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;

class ProposalToPDFController extends Controller
{
    use GenerateDocument;
    use DocumentSysEngine;
    public function generatePDF_(Request $request)
    {
        // return $request;
        $token = $request->token;

        // $data = ['title' => 'domPDF in Laravel 10'];
        // $pdf = PDF::loadView('pdf.document', $data);
        // return $pdf->download('document.pdf');


        $document_application = DocumentApplication::where('token', $token)->first();
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        $company_profile = CompanyProfile::where('deleted', 0)->first();

        $dataMap = $this->generateProposal($token);

        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $document_product->template);

        // $pdf = PDF::loadHTML($previewContent);
        $pdf = PDF::loadView('pdf.pdf-blueprint', ['previewContent'=>$previewContent]);

        // Generate a new file name (e.g., proposal_timestamp.pdf)
        $newFileName = 'proposal_' . time() . '.pdf';

        // storage path to save proposals to
        $storagePath = storage_path('app/public/proposals/' . $newFileName);

        // Save the PDF to the storage location
        $pdf->save($storagePath);

        // Get the URL of the saved PDF file
        $url = Storage::url('proposals/' . $newFileName);

        // Return the URL
        return $url;
    }

    public function generatePDF(Request $request)
    {
        $token = $request->token;

        // $data = ['title' => 'domPDF in Laravel 10'];
        // $pdf = PDF::loadView('pdf.document', $data);
        // return $pdf->download('document.pdf');


        $document_application = DocumentApplication::where('token', $token)->first();
        $document_product = DocumentProduct::find($document_application->tbl_documents_products_id);
        //Note: $templateData data depends on the type of product (Educator, Tpp, Sip ...)
        $templateData = $document_product->product_model::where('tbl_document_applications_id', $document_application->id)->first();
        $company_profile = CompanyProfile::where('deleted', 0)->first();
        
        $serverside = true;
        $dataMap = $this->generateProposal($token, $serverside);
        // return $dataMap;

        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $document_product->template);

        $pdf = PDF::loadView('pdf.pdf-blueprint',compact('previewContent'))->setPaper('a4')->setOptions([
            'tempDir' => public_path(),
            'chroot' => public_path(),
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
                'createdby' => (Auth::user()->id > 0) ? Auth::user()->id : 0,
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
        
            $this->scannedRequestLogs($document->id, Auth::user()->firstname.' '.Auth::user()->lastnname, 'Attached Proposal Form', 3);
            // DumpScreen($record['id'], true);
            if( $document_application['tbl_documents_products_id'] == 14){
                $this->updateCheckList( $document_application['id'], 21, 'PASSED', 'Proposal form attached to request');
            }else{
                $this->updateCheckList( $document_application['id'], 1, 'PASSED', 'Proposal form attached to request');
            }

            return response([
                'status' => 'success',
                'filename' => $newFileName
            ], 200);
        }   

        }

    public function generatePDF__()
{
    // Load the PDF view
    $html = view('pdf.pdf')->render();

    // Initialize DomPDF options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);

    // Create DomPDF instance
    $dompdf = new Dompdf($options);

    // Load HTML content
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF
    $dompdf->render();

    // Get the PDF content
    $pdfContent = $dompdf->output();

    // Generate a new file name (e.g., proposal_timestamp.pdf)
    $newFileName = 'proposal_' . time() . '.pdf';

    // Storage path to save proposals to
    $storagePath = storage_path('app/public/proposals/' . $newFileName);

    // Save the PDF to the storage location
    file_put_contents($storagePath, $pdfContent);

    // Get the URL of the saved PDF file
    $url = Storage::url('proposals/' . $newFileName);

    // Return the URL
    return $url;
}
}
