<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

use App\Http\Traits\AmountToWords;


class TestS3Controller extends Controller
{
    use AmountToWords;

    public function saveFileOnS3(Request $request){
        $previewContent = null;
       
        // $pdf = PDF::loadView('pdf.pdf-blueprint',compact('previewContent'))->setPaper('a4')->setOptions([
        //     'tempDir' => public_path(),
        //     'chroot' => public_path(),
        // ]);
        $pdf = Pdf::loadView('pdf.pdf-blueprint')->setPaper('a4')->setOptions([
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
            $fullName = 'mandate-doc-' . $uuid . '.' . $extension;
            $targetDir = 'documents/';
            
            // Store the file with the unique filename on S3
            $status = Storage::disk('s3')->putFileAs($targetDir, $tempFilePath, $fullName);
            
            // Delete the temporary file
            unlink($tempFilePath);
            //hereeeeeee
            //
            //
            return $status;
        }
        return 44;









        $uploadedFile = $request->file('file');
        // Assuming $uploadedFile contains the uploaded file instance
        $extension = $uploadedFile->getClientOriginalExtension(); // Get the original extension

        // Generate a unique filename using UUID
        $uuid = Str::uuid()->toString();

        // Concatenate the UUID and extension to create the unique filename
        $fullName = 'uploaded-'.$uuid . '.' . $extension;
        $targetDir = 'signatures/';
        // Store the file with the unique filename on S3
        $status  =  Storage::disk('s3')->put($targetDir . $fullName, file_get_contents($uploadedFile));
        // $path  = Storage::disk('s3')->put($s3file_path, $uploadedFile);
        return $status;
        dd($path);
        abort(400, $path);
        // return $request;
        // dd(config('filesystems.disks'));
        // Storage::disk('s3')->directories();
        // $url = Storage::disk('s3')->url('uut');
        // dd($url);

        $uploadedFile = $request->file('file');
        // $targetDir = 'uploaded';
        // $path  = Storage::disk('s3')->putFile($targetDir, $uploadedFile);
        // $path = $uploadedFile->store($targetDir, 's3', 'public');;

        $targetDir = 'signatures';
        $uploadedFileName = $uploadedFile->getClientOriginalName();

        if (Storage::exists($targetDir .'/'.$uploadedFileName)) {
            // return 33;
            $fileName = pathinfo($uploadedFileName, PATHINFO_FILENAME);
            $fileExtension = $uploadedFile->getClientOriginalExtension();
            $i = 1;
            while (Storage::exists($targetDir . $fileName . '_' . $i . '.' . $fileExtension)) {
                $i++;
            }
            $uploadedFileName = $fileName . '_' . $i . '.' . $fileExtension;
        }

        $s3file_path = $targetDir .'/'.$uploadedFileName;

        // $path  = Storage::disk('s3')->putFile($targetDir, $uploadedFile);
        $path  = Storage::disk('s3')->put($s3file_path, $uploadedFile);

        dd($path);
        abort(400, $path);
        $url = Storage::disk('s3')->url($path);

        abort(400, $url); 
    }

    public function convertAmount(Request $request)
    {
        $amount = 1230.34;
        
        // Call the amountToWords method from the trait
        $amountInWords = $this->amountToWords($amount);
        
        return response()->json(['amount_in_words' => $amountInWords]);
    }

    public function generatePdf(Request $request)
    {

        $fileContent = Storage::disk('public')->get('company_profile/DhYlsP79yo.png');

        return $logo = '<img src="data:image/png;base64,'.base64_encode($fileContent).'" alt="" width="200"/>';
        return base64_encode($fileContent);
    }


    public function testNiaApi(Request $request){
        return view('test-nia-api');
    }
    public function testNiaApiPost(Request $request){
        // return $request;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
           
            $extension = $request->file('image')->getClientOriginalExtension();

            $authHeader = [
                // 'Authorization' => $token_slams,
                'Accept' => 'application/form-data',
            ];
    
            $data = [
                "pinNumber" => $request->pinNumber,
                "image"=> $request->image,
                "dataType"=> $extension,
                "center"=> "BRANCHLESS",
                "merchantKey"=> "b6d77534-e0c6-48d1-8c93-06cf092a2c8a"
            ];

            $url = "https://selfietest.imsgh.org:9020/api/v1/third-party/verification/test";
            $response = Http::withoutVerifying()->withHeaders($authHeader)->post($url, $data);
    
            return $response;
        }

        // $token_slams = "Bearer " . $auth_response["access_token"];
     
    }

   
}
