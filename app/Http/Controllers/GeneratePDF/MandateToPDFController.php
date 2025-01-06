<?php

namespace App\Http\Controllers\GeneratePDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentApplication;
use App\Models\DocumentProduct;
use App\Models\CompanyProfile;
use App\Http\Traits\GenerateDocument;
use App\Models\Document;
use PDF;
use Auth;
use Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;

class MandateToPDFController extends Controller
{
    use GenerateDocument;
    public function generatePDF(Request $request)
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
        $mandate = DocumentProduct::where([['template_link', '=' ,'mandate'], ['deleted', '=', 0]])->first();

        $dataMap = $this->generateMandate($token);

        // Replace placeholders with actual data
        $previewContent = str_replace(array_keys($dataMap), array_values($dataMap), $mandate->template);

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
                'createdby' => (Auth::user()->id > 0) ? Auth::user()->id : 0,
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
        
            $this->scannedRequestLogs($document->id, Auth::user()->firstname.' '.Auth::user()->lastname, 'Attached Mandate Form', 3);
            $this->updateCheckList( $document_application['id'], 2, 'PASSED', 'Mandate form attached to request');
              
            return response([
                'status' => 'success',
                'filename' => $newFileName
            ], 200);
        }

    }
}
