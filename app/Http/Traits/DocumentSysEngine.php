<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Models\UserCategory;
use App\Models\UserSubCategory;
use App\Models\Branch;
use App\Models\DocumentProduct;
use App\Models\DocumentApplication;
use App\Models\DocumentChecklist;
use App\Models\DocumentsProductsChecklist;
use App\Models\DocumentApplicationsLog;
use App\Models\DocumentSetup;
use App\Models\DocumentWorkflow;
use App\Models\DocumentSetupDetail;
use App\Models\Document;
use App\Models\DocumentType;
use App\Http\CustomClasses\ApplicationStatusClass;
use App\Models\DocumentImage;
use App\Models\UserAssignedFunction;
use Carbon\Carbon;
use Auth;
use DB;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\DefaultEmailTemplate;
use App\Models\EmailTemplate;
use App\Models\ShareDocumentLog;
use App\Models\DocumentsLog;
use App\Mail\EmailBlueprint;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Department;
use App\Models\Message;
use App\Models\CustomerComplaint;
use App\Models\Config; 
use App\Models\UserBranch;

use App\Models\DocumentApplicationsComment;


trait DocumentSysEngine {

  public function initiateCheckList($setupId, $requestId)
  {
      $main = DocumentApplication::find($requestId);

      $checklistCount = DocumentChecklist::where([
          'tbl_document_applications_id' => $requestId,
          'deleted' => 0,
      ])->count();

      if ($checklistCount === 0) {
          $records = DocumentsProductsChecklist::where([
              'tbl_documents_products_id' => $main->tbl_documents_products_id,
              'deleted' => 0,
          ])->get();

          $i = 1;
          foreach ($records as $record) {
              $arr = [
                  'tbl_document_setup_id' => $setupId,
                  'tbl_document_applications_id' => $requestId,
                  'tbl_document_type_id' => $record->tbl_document_type_id,
                  'tbl_checklist_status_id' => 1,
                  'sort' => $i,
                  'mandatory_yn' => $record->mandatory_yn,
                  'tbl_documents_products_checklist_id' => $record->id,
              ];

              DocumentChecklist::create($arr);

              $i++;
          }
      }
  }

  public function scannedRequestLogs($id, $reference, $action, $workflowType = 0)
  {
      $arr = [
          'tbl_document_applications_id' => $id > 0 ? $id : 0,
          'tbl_document_workflow_type_id' => $workflowType > 0 ? $workflowType : 0,
          'reference' => $reference,
          'log_action' => $action,
      ];

      DocumentApplicationsLog::create($arr);
  }

function passComments($id, $user_id, $status, $message)
{
    DocumentApplicationsComment::create([
        'message' => $message,
        'createdby' => $user_id,
        'status' => $status,
        'tbl_document_applications_id' => $id
    ]);
}

  public function generateDocumentWorkflow($id)
  {
    $documentApplication = DocumentApplication::findOrFail($id);
    $docSetup = $this->getDocumentWorkflowByProduct($documentApplication->tbl_documents_products_id);

    $reqsQuery = DocumentSetupDetail::where('tbl_document_setup_id', $docSetup)->where('deleted', 0);

    if ($documentApplication->source == 'WEBSITE') {
        $reqsQuery->whereIn('tbl_document_workflow_type_id', [1, 2, 3, 5]);
    }

    $reqs = $reqsQuery->get();
    $i = 1;

    foreach ($reqs as $req) {
        $arr = [
            'sort' => $i,
            'tbl_document_applications_id' => $id,
            'tbl_document_workflow_type_id' => $req->tbl_document_workflow_type_id,
            'tbl_document_setup_details_id' => $req->id,
            'deleted' => 0,
        ];

        $workflow = DocumentWorkflow::where($arr)->first();

        if ($workflow) {
            $workflow->update($arr);
        } else {
            DocumentWorkflow::create($arr);
        }

        $i++;
    }

    DocumentWorkflow::where(['sort' => 1, 'deleted' => 0])->update(['started_yn' => 1]);
  }

  public function getDocumentWorkflowByProduct($id)
  {
      $result = DocumentSetup::where('tbl_documents_products_id', $id)
          ->where('deleted', 0)
          ->value('id');
  
      return $result ?? 0;
  } 

  public function checkCheckList($id, $setup_id)
  {
      // Assuming you have Eloquent models for your tables
      $documentApplication = DocumentApplication::findOrFail($id);
      $documentChecklists = DocumentChecklist::with('tbl_document_type')->where('tbl_document_applications_id', $id)
          ->where('deleted', 0)
          ->get();
    
      foreach ($documentChecklists as $documentChecklist) {
          $document = Document::where('tbl_document_applications_id', $id)
              ->where('tbl_document_type_id', $documentChecklist->tbl_document_type_id)
              ->where('deleted', 0)
              ->first();
              
          if ($document) {
              $documentChecklist->update([
                  'tbl_checklist_status_id' => 2,
                  'reason' => $documentChecklist->tbl_document_type->document_name . ' attached to request',
              ]);
          } else {
              $documentChecklist->update([
                  'tbl_checklist_status_id' => 3,
                  'reason' => 'No ' . $documentChecklist->tbl_document_type->document_name . ' form attached to request',
              ]);
              $documentApplication->update(['tbl_application_status_id' => 75]);
          }
      }

      $mandatoryCheck = DocumentChecklist::where('tbl_document_applications_id', $id)
          ->where('mandatory_yn', 1)
          ->whereIn('tbl_checklist_status_id', [1, 3])
          ->get();

      if ($mandatoryCheck->isEmpty()) {
        
          $documentApplication->update([
              'tbl_application_status_id' => 66,
              'form_filled' => 1,
          ]);

          $this->passWorkflowDocuments($id);
      }
  }

  public function passWorkflowDocuments($id, $bypass = 0)
  {
      // Assuming you have Eloquent models for your tables

      $record = DocumentApplication::findOrFail($id);

      $status = new ApplicationStatusClass(11, 16);
      $pending_payment_status = $status->getStatusbyEndpoint(2);

      if (in_array($record->tbl_application_status_id, [66]) && $record->form_filled == 1) {
          $workflows = DocumentWorkflow::where('tbl_document_applications_id', $record->id)
              ->where('deleted', 0)
              ->orderBy('sort')
              ->get();

          foreach ($workflows as $workflow) {
              if ($workflow->started_yn == 1 && $workflow->completed_yn == 0) {
                  $attempt = $workflow->attempt + 1;

                  if ($workflow->tbl_document_workflow_type_id == 1) {
                      // Department
                      $workflow->update(['tbl_system_status_id' => 2, 'tbl_workflow_status_id' => 3, 'attempt' => $attempt, 'done_yn' => 0]);
                      $this->sendDocToDepartmentDocument($record->id, $workflow->tbl_document_setup_details->reference, $workflow->id, $bypass);
                  } elseif ($workflow->tbl_document_workflow_type_id == 2) {
                      // Request Document
                      $workflow->update(['tbl_system_status_id' => 2, 'tbl_workflow_status_id' => 2, 'attempt' => $attempt, 'done_yn' => 0]);
                      $this->sendRequestDocumentsDocument($record->id, $workflow->tbl_document_setup_details->reference, $workflow->id);
                  } elseif ($workflow->tbl_document_workflow_type_id == 3) {
                      // Staff
                      $workflow->update(['tbl_system_status_id' => 2, 'tbl_workflow_status_id' => 3, 'attempt' => $attempt, 'done_yn' => 0]);
                      $this->sendDocToStaffDocument($record->id, $workflow->tbl_document_setup_details->reference, $workflow->id);
                    } elseif ($workflow->tbl_document_workflow_type_id == 4) {
                      // Team Lead
                      $workflow->update(['tbl_system_status_id' => 2, 'tbl_workflow_status_id' => 3, 'attempt' => $attempt, 'done_yn' => 0]);
                      $this->sendToTeamManagerDocuments($record->id, $workflow->tbl_document_setup_details->reference, $workflow->id);
                  } elseif ($workflow->tbl_document_workflow_type_id == 5) {
                      // Processed Document
                      $workflow->update([
                          'tbl_system_status_id' => 3,
                          'tbl_workflow_status_id' => 12,
                          'attempt' => $attempt,
                          'completed_yn' => 1,
                          'processed_date' => Carbon::now(),
                          'processed_by' => 'SYSTEM',
                          'done_yn' => 0,
                      ]);

                      $record->update(['completed_yn' => 1, 'tbl_document_status_id' => 2, 'tbl_application_status_id' => $pending_payment_status->id]);
                  }
              }
          }
      }
  }

function WorkflowActionDocuments($action, $id, $workflow_id)
{
    $bs = DocumentApplication::findOrFail($id);
    $rs = DocumentWorkflow::findOrFail($workflow_id);

    $status = new ApplicationStatusClass(11, 16);
    $approved_status = $status->getStatusbyEndpoint(2);
    $declined_status = $status->getStatusbyEndpoint(4);
    $pending_status = $status->getStatusbyEndpoint(1);

    if ($bs->completed_yn == 0) {
        $get_next_id = $rs->sort + 1;
        $attempt = $rs->attempt + 1;

        if ($action == 'approved') {
            $bs->update([
                'tbl_application_status_id' => $pending_status['id'],
                'last_updated_date' => now(),
            ]);

            if ($rs->tbl_document_workflow_type_id == 2) {
                $rs->update([
                    'completed_yn' => 1,
                    'tbl_system_status_id' => 3,
                    'tbl_workflow_status_id' => 7,
                    'attempt' => $attempt,
                    'processed_date' => now(),
                ]);
            } else {
                $rs->update([
                    'completed_yn' => 1,
                    'tbl_system_status_id' => 3,
                    'tbl_workflow_status_id' => 5,
                    'attempt' => $attempt,
                    'processed_date' => now(),
                ]);
            }

            $get_next = DocumentWorkflow::where('tbl_document_applications_id', $id)
                ->where('deleted', 0)
                ->where('sort', $get_next_id)
                ->first();

            if ($get_next) {
                $get_next->update([
                    'started_yn' => 1,
                    'completed_yn' => 0,
                    'tbl_system_status_id' => 2,
                ]);
            } else {
                $bs->update([
                    'completed_yn' => 1,
                    'tbl_document_status_id' => 2,
                    'tbl_application_status_id' => $approved_status['id'],
                    'last_updated_date' => now(),
                ]);

                $message = 'Dear ' . strtoupper($bs->tbl_users['full_name']) . ',
                Your ' . $bs->tbl_documents_products['product_name'] . ' Request has been approved';

                if ($bs->tbl_users['phone_no'] !== '') {
                    $this->sendSMS('OLDMUTUAL', '0' . substr($bs->tbl_users['phone_no'], -9), $message);
                }
            }
        }

        if ($action == 'declined') {
            $rs->update([
                'completed_yn' => 1,
                'tbl_system_status_id' => 3,
                'tbl_workflow_status_id' => 4,
                'attempt' => $attempt,
                'processed_date' => now(),
            ]);

            $bs->update([
                'completed_yn' => 1,
                'tbl_document_status_id' => 3,
                'tbl_application_status_id' => $declined_status['id'],
                'last_updated_date' => now(),
            ]);
        }
    }
}

function validateAccessWorkflow($workflow, $user_id)
{
  $status = 0;
    // return $workflow->tbl_document_workflow_type_id;
  if ($workflow->tbl_document_workflow_type_id == 1) { // DEPARTMENT
      $records = UserAssignedFunction::where('tbl_users_id', $user_id)->where('deleted', 0)->get();
      $userDepartments = $records->pluck('tbl_departments_id')->toArray();
      // return $records;
      if (in_array($workflow->tbl_document_setup_details->reference, $userDepartments)) {
          $status = 1;
      }
  } elseif ($workflow->tbl_document_workflow_type_id == 3) { // STAFF
      if ($workflow->tbl_document_setup_details->reference == $user_id) {
          $status = 1;
      }
  } elseif ($workflow->tbl_document_workflow_type_id == 4) { // TEAM LEAD
      if ($workflow->tbl_document_applications->tbl_users->team_leader_id == $user_id) {
          $status = 1;
      }
  }

  return $status;
}

function getAllUserFunctions($id)
{
    $records = UserAssignedFunction::where('tbl_users_id', $id)->where('deleted', 0)->pluck('tbl_departments_id')->toArray();
    return $records;
}

  public function updateCheckList($request_id, $type, $status, $reason = null)
  {
      if ($status == 'PASSED') {
        $get_status = 2;
      }
      if ($status == 'FAILED') {
        $get_status = 3;
      }

      // Using Eloquent to update the checklist record
      $rs = DocumentChecklist::where([
          'tbl_document_applications_id' => $request_id,
          'tbl_document_type_id' => $type
      ])->first();

      if ($rs) {
          $rs->update([
              'tbl_checklist_status_id' => $get_status,
              'reason' => $reason,
          ]);
      }
  }

  public function saveFileOnS3_($file_name, $body)
  {
      $region = config('filesystems.disks.s3.region');
      $bucket_name = config('filesystems.disks.s3.bucket');
        try {
            // Save file to S3
                Storage::disk('s3')->put($file_name, $body);

            // Generating the file path URL
                //   $file_path = 'https://' . $bucket_name . '.s3.' . $region . '.amazonaws.com/' . $file_name;
                $file_path = Storage::disk('s3')->url($file_name);

            return $file_path;
        } catch (\Throwable $th) {
            echo 'Error connecting to S3 bucket: ' . $th->getMessage();
        }
     
  }

 public function saveFileOnS3($filePath, $s3FilePath)
{
    try {
        // Get the contents of the file
        $fileContents = Storage::get($filePath);

        // Store the file on S3
        $status  = Storage::disk('s3')->put($s3FilePath, $fileContents);

        // Optionally, delete the file from the local storage after uploading to S3
        Storage::delete($filePath);

        // Return the path on S3 where the file was stored
        // return  $status.'_'.$s3FilePath ;
    } catch (\Throwable $th) {
        throw $th;
    }
}

public function generateSignatureFromBase64($id, $model, $base64_encoded_image )
{
    // return $model;
    $image_parts = explode(";base64,", $base64_encoded_image);

    $image_type_aux = explode("image/", $image_parts[0]);

    $image_type = $image_type_aux[1];

    $image_base64 = base64_decode($image_parts[1]);

    $sig_file = uniqid() . time() . '.' . $image_type;

    // $target_dir = storage_path('app/public/document/signatures/');
    // file_put_contents($target_dir . $sig_file, $image_base64);

    // Determine the storage path
    $storagePath = 'public/signatures/signed/';
    // $filePathw = $image_base64->storeAs($storagePath, $uploadedFileName);
    // return $filePathw;
    Storage::put($storagePath . $sig_file, $image_base64);
    
    //locally stored the file
    $filePath = 'public/signatures/signed/'. $sig_file;

    // Determine the path on S3 where you want to store the file
    $s3FilePath = 'signatures/' . $sig_file;
    // return $filePath;
    // upload files to S3
    $tt = $this->saveFileOnS3($filePath, $s3FilePath);
    // return $tt;
    //update
    $model::where('tbl_document_applications_id', $id)
            ->update(['uploaded_signature' => $sig_file]);
}


  public function getImageDoc($doc_type)
  {
      return DocumentImage::where('document_type', $doc_type)
          ->where('deleted', 0)
          ->first();
  }
  

public function generateUniqueFilename($targetDir, $filename)
{
    $newFilename = Str::slug(pathinfo($filename, PATHINFO_FILENAME));
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    try {
        $counter = 1;
        while (Storage::disk('s3')->exists($targetDir . $newFilename . '.' . $extension)) {
            $newFilename = Str::slug(pathinfo($filename, PATHINFO_FILENAME)) . '_' . $counter;
            $counter++;
        }
        return $newFilename . '.' . $extension;
    } catch (\Throwable $th) {
        echo 'Error connecting to S3 bucket: ' . $th->getMessage();
    }

   
}

//   public function saveFileOnS3($file_name, $body)
//   {
//       $region = env('AWS_REGION'); // Assuming you have AWS credentials in your config files
//       $bucket_name = env('AWS_DOCUMENTS_BUCKET_NAME'); // Replace with your actual configuration key

//       // Upload file to S3
//       Storage::disk('s3')->put($file_name, $body);

//       // Get the file path on S3
//       $file_path = Storage::disk('s3')->url($file_name);

//       return $file_path;
//   }
//   use App\Models\DocumentImage; // Replace with the actual namespace and model name

//   public function getImageDoc($docType)
//   {
//       $imageDoc = DocumentImage::where('document_type', $docType)
//           ->where('deleted', 0)
//           ->first();

//       return $imageDoc;
//   }

//   public function getDocumentWorkflowByProduct($id)
// {
//     $documentSetup = DocumentSetup::where('tbl_documents_products_id', $id)
//         ->where('deleted', 0)
//         ->first();

//     return $documentSetup ? $documentSetup->id : 0;
// }

public function numberTowords($num)
{
  $ones = array(
    0 => "ZERO",
    1 => "ONE",
    2 => "TWO",
    3 => "THREE",
    4 => "FOUR",
    5 => "FIVE",
    6 => "SIX",
    7 => "SEVEN",
    8 => "EIGHT",
    9 => "NINE",
    10 => "TEN",
    11 => "ELEVEN",
    12 => "TWELVE",
    13 => "THIRTEEN",
    14 => "FOURTEEN",
    15 => "FIFTEEN",
    16 => "SIXTEEN",
    17 => "SEVENTEEN",
    18 => "EIGHTEEN",
    19 => "NINETEEN",
    "014" => "FOURTEEN"
  );
  $tens = array(
    0 => "ZERO",
    1 => "TEN",
    2 => "TWENTY",
    3 => "THIRTY",
    4 => "FORTY",
    5 => "FIFTY",
    6 => "SIXTY",
    7 => "SEVENTY",
    8 => "EIGHTY",
    9 => "NINETY"
  );
  $hundreds = array(
    "HUNDRED",
    "THOUSAND",
    "MILLION",
    "BILLION",
    "TRILLION",
    "QUARDRILLION"
  ); /*limit t quadrillion */
  $num = number_format($num, 2, ".", ",");
  $num_arr = explode(".", $num);
  $wholenum = $num_arr[0];
  $decnum = $num_arr[1];
  $whole_arr = array_reverse(explode(",", $wholenum));
  krsort($whole_arr, 1);
  $rettxt = "";
  foreach ($whole_arr as $key => $i) {

    while (substr($i, 0, 1) == "0")
      $i = substr($i, 1, 5);
    if ($i < 20) {
      /* echo "getting:".$i; */
      $rettxt .= $ones[$i];
    } elseif ($i < 100) {
      if (substr($i, 0, 1) != "0")  $rettxt .= $tens[substr($i, 0, 1)];
      if (substr($i, 1, 1) != "0") $rettxt .= " " . $ones[substr($i, 1, 1)];
    } else {
      if (substr($i, 0, 1) != "0") $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0] . '';
      if (substr($i, 1, 1) != "0") $rettxt .= " " . $tens[substr($i, 1, 1)];
      if (substr($i, 2, 1) != "0") $rettxt .= " " . $ones[substr($i, 2, 1)];
    }

    if ($key > 0) {
      $rettxt .= " " . $hundreds[$key] . "";
    }
  }
  if ($decnum > 0) {
    $rettxt .= " CEDIS AND ";
    if ($decnum < 20) {
      $rettxt .= $ones[$decnum] . ' ';
    } elseif ($decnum < 100) {
      $rettxt .= $tens[substr($decnum, 0, 1)] . '';
      $rettxt .= " " . $ones[substr($decnum, 1, 1)] . '';
    }
    $rettxt .= ' PESEWAS';
  }
  $check_cedis = explode(' ', $rettxt);
  if (in_array('CEDIS', $check_cedis)) {
    $rettxt = $rettxt;
  } else {
    $rettxt = $rettxt . ' CEDIS';
  }
  return $rettxt . ' ';
}

function format_title_status_for_slams($title)
{
//   $title = strtolower(trim($title));
if (strpos(strtolower($title), strtolower("miss")) !== false) {
    return 1;
} else if (strpos(strtolower($title), strtolower("mr")) !== false) {
    return 2;
} else if (strpos(strtolower($title), strtolower("mrs")) !== false) {
    return 3;
} else if (strpos(strtolower($title), strtolower("dr")) !== false) {
    return 4;
} else if (strpos(strtolower($title), strtolower("prof")) !== false) {
    return 5;
} else if (strpos(strtolower($title), strtolower("rev")) !== false) {
    return 6;
} else if (strpos(strtolower($title), strtolower("eng")) !== false) {
    return 7;
} else if (strpos(strtolower($title), strtolower("pastor")) !== false) {
    return 8;
} else if (strpos(strtolower($title), strtolower("hon")) !== false) {
    return 9;
} else if (strpos(strtolower($title), strtolower("alhaji")) !== false) {
    return 10;
} else if (strpos(strtolower($title), strtolower("ms")) !== false) {
    return 11;
} else if (strpos(strtolower($title), strtolower("madam")) !== false) {
    return 12;
}

}

function format_source_of_income_for_slams($source)
{
    if (strpos(strtolower($source), strtolower("salary")) !== false) {
        return 1;
    } else if (strpos(strtolower($source), strtolower("investment")) !== false) {
        return 2;
    } else if (strpos(strtolower($source), strtolower("remittance")) !== false) {
        return 3;
    } else {
        return 5;
    }
}

function getRegionId($regionName) {
    $regions = [
        ["Id" => 1, "Description" => "ASHANTI"],
        ["Id" => 2, "Description" => "GREATER ACCRA"],
        ["Id" => 3, "Description" => "NORTH EAST"],
        ["Id" => 4, "Description" => "UPPER-EAST"],
        ["Id" => 5, "Description" => "UPPER-WEST"],
        ["Id" => 6, "Description" => "EASTERN"],
        ["Id" => 7, "Description" => "BONO EAST"],
        ["Id" => 8, "Description" => "WESTERN"],
        ["Id" => 9, "Description" => "VOLTA"],
        ["Id" => 10, "Description" => "CENTRAL"],
        ["Id" => 11, "Description" => "OTI"],
        ["Id" => 12, "Description" => "SAVANNAH"],
        ["Id" => 13, "Description" => "WESTERN NORTH"],
        ["Id" => 14, "Description" => "BONO"],
        ["Id" => 15, "Description" => "AHAFO"],
        ["Id" => 16, "Description" => "NORTHERN"]
    ];
    
    // Convert the input region name to lowercase
    $regionNameLowercase = strtolower($regionName);
    
    foreach ($regions as $region) {
        // Convert each region description to lowercase before comparison
        if (strtolower($region["Description"]) === $regionNameLowercase) {
            return $region["Id"];
        }
    }
    
    // If the region name is not found, return null or any other default value as needed
    return null;
}

function getAgebyDOB($birthDate)
{

  $birthDate = date('Y-m,-d', strtotime($birthDate));

  // Current date
  $currentDate = date("Y-m-d");

  // Calculate age
  $diff = date_diff(date_create($birthDate), date_create($currentDate));
  $age = $diff->format("%y");

  // Output age
  return $age;
}

function calculateMaturityDate($maturity_age, $date_of_birth) {
    // // Create a DateTime object from the date of birth
    // $dob = new DateTime($date_of_birth);
  
    // // Add the maturity age to the date of birth
    // $dob->modify("+$maturity_age years");
  
    // // Format the result as a date string
    // return $dob->format('Y-m-d');

    $dob = Carbon::createFromFormat('Y-m-d', $date_of_birth);
  
    // Add the maturity age to the date of birth
    $dob->addYears($maturity_age);
  
    // Format the result as a date string
    return $dob->format('Y-m-d');
}

function getpercentageId_from_slam($perc_value){
    if($perc_value == 5){
      return 3;
    }else if($perc_value == 10){
      return 4;
    }else if($perc_value == 15){
      return 5;
    }else if($perc_value == 20){
      return 8;
    }else if($perc_value == 25){
      return 7;
    }else{
      //for value = 0
      return 1;
    }
}

function format_title_status_for_stak_from_slams($title)
{
  $title = strtolower(trim($title));
  if ($title == 1) {
    return "MISS";
  } else if ($title == 2) {
    return "MR";
  } else if ($title == 3) {
    return "MRS";
  } else if ($title == 4) {
    return "DR";
  } else if ($title == 5) {
    return "PROF";
  } else if ($title == 6) {
    return "REV";
  } else if ($title == 7) {
    return "ENG";
  } else if ($title == 8) {
    return "PASTOR";
  } else if ($title == 9) {
    return "HON";
  } else if ($title == 10) {
    return "ALHAJI";
  } else if ($title == 11) {
    return "MS";
  } else if ($title == 12) {
    return "MADAM";
  }
}

function format_marriage_status_for_slams($marriage_status)
{
//   $marriage_status = strtoupper(trim($marriage_status));
  if (strpos(strtolower($marriage_status), strtolower("DIVORCED")) !== false) {
    return 1;
} else if (strpos(strtolower($marriage_status), strtolower("MARRIED")) !== false) {
    return 2;
} else if (strpos(strtolower($marriage_status), strtolower("WIDOWER")) !== false) {
    return 3;
} else if (strpos(strtolower($marriage_status), strtolower("SINGLE")) !== false) {
    return 4;
} else if (strpos(strtolower($marriage_status), strtolower("WIDOW")) !== false) {
    return 5;
} else {
    return 6;
}
}


function format_marriage_status_for_stak_from_slams($marriage_status)
{
  $marriage_status = strtoupper(trim($marriage_status));
  if ($marriage_status == 1) {
    return "DIVORCED";
  } else if ($marriage_status == 2) {
    return "MARRIED";
  } else if ($marriage_status == 3) {
    return "WIDOWER";
  } else if ($marriage_status == 4) {
    return "SINGLE";
  } else if ($marriage_status == 5) {
    return "WIDOW";
  }
}

function format_phone_number_for_slams($mobile, $in_ghana)
{

  if ($in_ghana == "1") {
    return "233" . substr($mobile, 1);
  } else {
    return $mobile;
  }
}

function format_id_type_for_slams($id_type)
{
//   $id_type = strtoupper(trim($id_type));
  if (strpos(strtolower($id_type), strtolower("AFFIDAVIT")) !== false) {
    return "AF";
} else if (strpos(strtolower($id_type), strtolower("BIRTH CERTIFICATE")) !== false) {
    return "BC";
} else if (strpos(strtolower($id_type), strtolower("NATIONAL ID")) !== false || strpos(strtolower($id_type), strtolower("GHANA CARD")) !== false) {
    return "ID";
} else if (strpos(strtolower($id_type), strtolower("DRIVER'S LICENSE")) !== false || strpos(strtolower($id_type), strtolower("DRIVERS LICENSE")) !== false) {
    return "LIC";
} else if (strpos(strtolower($id_type), strtolower("NHIS")) !== false) {
    return "NHI";
} else if (strpos(strtolower($id_type), strtolower("PASSPORT")) !== false) {
    return "PP";
} else if (strpos(strtolower($id_type), strtolower("SSNIT")) !== false) {
    return "SSN";
} else if (strpos(strtolower($id_type), strtolower("VOTER ID")) !== false) {
    return "VT";
}

}

function format_relationship_for_slams($rel)
{
//   $rel = strtoupper(trim($rel));
  if (strpos(strtolower($rel), strtolower("SELF")) !== false) {
    return "SF";
} else if (strpos(strtolower($rel), strtolower("SON")) !== false) {
    return "SN";
} else if (strpos(strtolower($rel), strtolower("WIFE")) !== false) {
    return "WF";
} else if (strpos(strtolower($rel), strtolower("HUSBAND")) !== false) {
    return "HU";
} else if (strpos(strtolower($rel), strtolower("MOTHER")) !== false) {
    return "MO";
} else if (strpos(strtolower($rel), strtolower("FATHER")) !== false) {
    return "FA";
} else if (strpos(strtolower($rel), strtolower("DAUGHTER")) !== false) {
    return "DT";
} else if (strpos(strtolower($rel), strtolower("BROTHER")) !== false) {
    return "BR";
} else if (strpos(strtolower($rel), strtolower("SISTER")) !== false) {
    return "SS";
} else if (strpos(strtolower($rel), strtolower("FATHER-IN-LAW")) !== false) {
    return "FI";
} else if (strpos(strtolower($rel), strtolower("MOTHER-IN-LAW")) !== false) {
    return "MI";
}

}

function format_id_type_for_stak_from_slams($id_type)
{
  $id_type = strtoupper(trim($id_type));
  if ($id_type == "AF") {
    return "AFFIDAVIT";
  } else if ($id_type == "BC") {
    return "BIRTH CERTIFICATE";
  } else if ($id_type == "ID") {
    return "NATIONAL ID";
  } else if ($id_type == "LIC") {
    return "DRIVERS LICENSE";
  } else if ($id_type == "NHIS") {
    return "NHIS";
  } else if ($id_type == "PP") {
    return "PASSPORT";
  } else if ($id_type == "SSN") {
    return "SSNIT";
  } else if ($id_type == "VT") {
    return "VOTER ID";
  }
}

function format_gender_for_slams($gender)
{

  $gender = strtolower(trim($gender));
  if ($gender == "female") {
    return "F";
  } else if ($gender == "male") {
    return "M";
  } else {
    return "X";
  }
}

function format_product_type_slams($plan_name_on_stak)
{
    if (strpos(strtolower($plan_name_on_stak), strtolower("educator")) !== false) {
        return "9";
    } else if (strpos(strtolower($plan_name_on_stak), strtolower("life insurance")) !== false || 
            strpos(strtolower($plan_name_on_stak), strtolower("term")) !== false || 
            strpos(strtolower($plan_name_on_stak), strtolower("term assurance")) !== false) {
        return "13";
    } else if (strpos(strtolower($plan_name_on_stak), strtolower("personal accident")) !== false) {
        return "8";
    } else if (strpos(strtolower($plan_name_on_stak), strtolower("sip")) !== false || 
            strpos(strtolower($plan_name_on_stak), strtolower("special investment plan")) !== false) {
        return "12";
    } else if (strpos(strtolower($plan_name_on_stak), strtolower("tpp")) !== false || 
            strpos(strtolower($plan_name_on_stak), strtolower("transition plus plan")) !== false) {
        return "19";
    } else if (strpos(strtolower($plan_name_on_stak), strtolower("travel insurance")) !== false) {
        return "5";
    } else if (strpos(strtolower($plan_name_on_stak), strtolower("sipf")) !== false) {
        return "27";
    } else {
        return "0";
    }

}

function format_payment_method_for_slams($payment_method_on_stak)
{
    if (strpos(strtolower($payment_method_on_stak), strtolower("Cash/Cheque")) !== false) {
        return "1";
    } else if (strpos(strtolower($payment_method_on_stak), strtolower("CAG Deductions")) !== false || strpos(strtolower($payment_method_on_stak), strtolower("Stop Order")) !== false) {
        return "7";
    } else if (strpos(strtolower($payment_method_on_stak), strtolower("Mobile Money")) !== false) {
        return "5";
    } else if (strpos(strtolower($payment_method_on_stak), strtolower("Debit Order")) !== false) {
        return "3";
    } else {
        return "8";
    }
}


// function format_telco_for_slams($payment_method_on_stak)
// {
//   if ($payment_method_on_stak == "MTN Mobile Money") {
//     return "00465";
//   } else if ($payment_method_on_stak == "Vodafone Cash") {
//     return "VO439";
//   } else if ($payment_method_on_stak == "Airtel Tigo Money") {
//     return "AI438";
//   } else {
//     return "0";
//   }
// }

function format_telco_for_slams($payment_method_on_stak) //strpos($lowercase_payment_method, "mobile") !== false
{
    if (strpos(strtolower($payment_method_on_stak), strtolower("MTN MOBILE")) !== false) { //MTN MOBILE MONEY
        return "00465";
    } else if (strpos(strtolower($payment_method_on_stak), strtolower("VODAFONE")) !== false) { //VODAFONE MOBILE MONEY
        return "VO439";
    } else if (strpos(strtolower($payment_method_on_stak), strtolower("EMERGENT PAY")) !== false) { //MOBILE MONEY - EMERGENT PAY
        return "MO511";
    } else if (strpos(strtolower($payment_method_on_stak), strtolower("AIRTEL TIGO")) !== false) { //AIRTEL TIGO MOBILE MONEY
        return "AI438";
    } else {
        return "0";
    }
}

function get_age_from_date_of_birth($birthDate)
{
//   $from = new DateTime($birthDate);
//   $to   = new DateTime('today');
//   return $from->diff($to)->y;

  $from = Carbon::createFromFormat('Y-m-d', $birthDate);
    $to = Carbon::today();
    
    return $from->diffInYears($to);
}

function format_claim_type($claim_type)
{

    if (strpos(strtolower($claim_type), strtolower("Partial Withdrawal")) !== false) {
        return "PWD";
    } else if (strpos(strtolower($claim_type), strtolower("Surrender")) !== false) {
        return "SUR";
    } else if (strpos(strtolower($claim_type), strtolower("Refund")) !== false) {
        return "REF";
    } else if (strpos(strtolower($claim_type), strtolower("Cashback")) !== false) {
        return "CBK";
    } else if (strpos(strtolower($claim_type), strtolower("Maturity")) !== false) {
        return "MAT";
    } else if (strpos(strtolower($claim_type), strtolower("Death")) !== false) {
        return "DTH";
    } else if (strpos(strtolower($claim_type), strtolower("Personal Accident")) !== false) {
        return "PA";
    } else if (strpos(strtolower($claim_type), strtolower("Travel")) !== false) {
        return "TME";
    } else {
        return "";
    }
    
}


function format_claim_type_for_stak_from_slams($claim_type)
{

  if ($claim_type == "PWD") {
    return "Partial Withdrawal";
  } else if ($claim_type == "SUR") {
    return "Surrender";
  } else if ($claim_type == "REF") {
    return "Refund";
  } else if ($claim_type == "CBK") {
    return "Cashback";
  } else if ($claim_type == "MAT") {
    return "Maturity";
  } else if ($claim_type == "DTH") {
    return "Death";
  } else if ($claim_type == "PA") {
    return "Personal Accident";
  } else if ($claim_type == "TME") {
    return "Travel";
  } else {
    return "";
  }
}

function format_reason_for_partial_withdrawal($reason_for_claim)
{

  if ($reason_for_claim == "MISSELLING") {
    return "1";
  } else if ($reason_for_claim == "AFFORDABILITY ISSUES") {
    return "2";
  } else if ($reason_for_claim == "SCHOOL FEES") {
    return "3";
  } else if ($reason_for_claim == "PERSONAL REASONS") {
    return "4";
  } else if ($reason_for_claim == "FINANCIAL CONSTRAINTS") {
    return "5";
  } else if ($reason_for_claim == "PART WITHDRAWAL NOT DUE BUT URGENT NEED FOR MONEY") {
    return "6";
  } else if ($reason_for_claim == "CURRENT FINANCIAL EVENTS") {
    return "7";
  } else if ($reason_for_claim == "OTHER") {
    return "8";
  } else if ($reason_for_claim == "CHILD SUPPORT") {
    return "9";
  } else if ($reason_for_claim == "CLIENT HAS TO TRAVEL A GOOD DISTANCE IN ORDER TO ACCESS A CLAIM") {
    return "10";
  } else if ($reason_for_claim == "TRAVEL PURPOSE") {
    return "11";
  } else if ($reason_for_claim == "TRAVELLING") {
    return "12";
  } else if ($reason_for_claim == "FAMILY SUPPORT") {
    return "13";
  } else if ($reason_for_claim == "BUILDING PROJECT") {
    return "14";
  } else if ($reason_for_claim == "MEDICAL PURPOSE") {
    return "15";
  } else {
    return "8";
  }
}

function format_reason_for_claim($reason_for_claim)
{

    if (strpos(strtolower($reason_for_claim), strtolower("ACCIDENT")) !== false) {
        return "1";
    } else if (strpos(strtolower($reason_for_claim), strtolower("HEART ATTACK")) !== false) {
        return "2";
    } else if (strpos(strtolower($reason_for_claim), strtolower("MALARIA")) !== false) {
        return "3";
    } else if (strpos(strtolower($reason_for_claim), strtolower("RETRENCHED")) !== false) {
        return "4";
    } else if (strpos(strtolower($reason_for_claim), strtolower("NATURAL DEATH")) !== false) {
        return "5";
    } else if (strpos(strtolower($reason_for_claim), strtolower("H.I.V AIDS")) !== false) {
        return "6";
    } else if (strpos(strtolower($reason_for_claim), strtolower("CARDIO PULMONARY")) !== false) {
        return "7";
    } else if (strpos(strtolower($reason_for_claim), strtolower("PTB")) !== false) {
        return "8";
    } else if (strpos(strtolower($reason_for_claim), strtolower("STROKE")) !== false) {
        return "9";
    } else if (strpos(strtolower($reason_for_claim), strtolower("KIDNEY FAILURE")) !== false) {
        return "10";
    } else if (strpos(strtolower($reason_for_claim), strtolower("CANCER")) !== false) {
        return "11";
    } else if (strpos(strtolower($reason_for_claim), strtolower("PARAPLEGIA")) !== false) {
        return "12";
    } else if (strpos(strtolower($reason_for_claim), strtolower("DIETEBES")) !== false) {
        return "13";
    } else {
        return "";
    }
    
}

function getDefaultEmailTemplate($section)
{
    $template = DefaultEmailTemplate::where('template_name', $section)
        ->where('deleted', 0)
        ->first();
    return $template;
}

function getTemplatebyCategoryAndVariable($category, $email_group){
  $template = EmailTemplate::where('category', $category)
        ->where('email_group', $email_group)
        ->first();
  return $template;
}

function shareDocumentViaEmail($id, $url)
{
    $template_header = $this->getDefaultEmailTemplate('header');
    $template_footer = $this->getDefaultEmailTemplate('footer');

    $userData = ShareDocumentLog::with('tbl_users')->find($id);

    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'share_document');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];

    $productName = $userData->documentApplication->documentProduct->product_name;

    $ex = explode(',', $userData['email_to']);

    if (count($ex) > 0) {
        foreach ($ex as $recipient) {
          $placeholders_val = [
                'COMPANY_NAME' => env('COMPANY_NAME'),
                'DOCUMENT_TYPE' => $productName,
                'SHARER' => $userData->tbl_users->firstname .' '. $userData->tbl_users->lastname, 
                'DOCUMENT_NAME' => $productName,
                'URL' => $url,
                'SITE_NAME' => env('APP_NAME'),
                'SITE_URL' => url('/'),
                'INTRODUCTION_MESSAGE' => 'Dear Customer,',
                'LOGO' => url('assets/images/logo.png'),
                'STAK_LOGO' => url('assets/images/logo.png'),
                'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
                'SUPPORT_URL' => env('SUPPORT_URL'),
                'WEBSITE_URL' => url('/'),
                'HELP_URL' => env('HELP_URL'),
                'TWITTER_URL' => env('TWITTER_URL'),
                'FACEBOOK_URL' => env('FACEBOOK_URL')
            ];

            $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
            $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

            $options = [
                'html' => true,
                'sender' => env('MAIL_FROM_EMAIL'),
            ];

            $this->sendEmail($recipient, $email_subject, $email_body, $options);
            $this->logDocumentsTrail($userData->id, Auth::user()->full_name, 'Shared Document via Email', $recipient);
        }
    }
}

function sendDocumentReview($id, $dept_id, $workflow_id)
{
    $userData = DocumentApplication::with('tbl_users')->with('tbl_documents_products')->find($id);

    
    if ($dept_id == 0) {
        $dept = User::find($userData->tbl_users_id);
        $email = $dept->email;
        $dept_name = $dept->firtname.' '.$dept->lastname;
    } elseif ($dept_id > 0) {
        $dept = Department::find($dept_id);
        $email = $dept->mailing_list;
        $dept_name = $dept->department_name;
    }
    // return $email;
    $workflow = DocumentWorkflow::find($workflow_id);
    $user = User::find($workflow->processedby);

    $template_header = $this->getDefaultEmailTemplate('header');
    $template_footer = $this->getDefaultEmailTemplate('footer');
    
    if (!empty($email)) {
        
        $html = $template_header['body'];
        $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'send_review_document');
        $html .= $email_template['template_body'];
        $html .= $template_footer['body'];

        $placeholders_val = [
            'REVIEWER' => $workflow->processed_by,
            'REQUEST_NO' => $userData->request_no,
            'POLICY_NO' => $userData->policy_no,
            'BRANCH' => $userData->tbl_branch ? $userData->tbl_branch->branch_name : '',
            'MOBILE_NO' => $userData->sms,
            'SOURCE' => $userData->source,
            'PROCESSED_BY' => $userData->tbl_users->full_name,
            'PROCESSED_ON' => date('d-M-Y', strtotime($userData->createdon)),
            'COMMENTS' => $workflow->comments,
            'DATE' => date('d-M-Y', strtotime($userData->createdon)),
            'APPLICATION_TYPE' => $userData->tbl_documents_products->product_name,
            'NAME' => $userData->tbl_users->full_name,
            'DOCUMENT_TYPE' => $userData->tbl_documents_products->product_name,
            'DEPARTMENT' => $dept_name,
            'VIEW_DOCUMENT' => url('document/view/request?token=' . trim($userData->token)),
            'APPROVE_URL' => url('document/action/request?key=' . base64_encode('approved-review') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
            'DECLINE_URL' => url('document/action/request?key=' . base64_encode('declined-review') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
            'SITE_NAME' => env('APP_NAME'),
            'SITE_URL' => url('/'),
            'INTRODUCTION_MESSAGE' => 'Attention ' . $dept_name . ' ,', 
            'LOGO' => url('assets/images/logo.png'),
            'STAK_LOGO' => url('assets/images/logo.png'),
            'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
            'SUPPORT_URL' => env('SUPPORT_URL'),
            'WEBSITE_URL' => url('/'),
            'HELP_URL' => env('HELP_URL'),
            'TWITTER_URL' => env('TWITTER_URL'),
            'FACEBOOK_URL' => env('FACEBOOK_URL')
        ];

        $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
        $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

        $options = [
            'html' => true,
            'sender' => env('MAIL_FROM_EMAIL'),
        ];

        $this->sendEmail($email, $email_subject, $email_body, $options);
        if ($dept_id == 0) {
          $this->LogMessages($userData['tbl_users_id'], $email_template['subject'], $email_body, auth()->user()?->id, 0);
        }
    }
}

function sendProductRequest($id, $email)
{
    $template_header = $this->getDefaultEmailTemplate('header');
    $template_footer = $this->getDefaultEmailTemplate('footer');

    $record = DocumentApplication::with('tbl_users')->with('tbl_documents_products')->find($id);

    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'send_product_request');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];

    $productName = $record->tbl_documents_products->product_name;


    if ($email) {
        $placeholders_val = [
              'COMPANY_NAME' => env('COMPANY_NAME'),
              'DOCUMENT_TYPE' => $productName,
              'SHARER' => $record->tbl_users->firstname .' '. $record->tbl_users->lastname, 
              'DOCUMENT_NAME' => $productName,
              'URL' => url('document/external/fill/proposal?token='. $record['token']),
              'SENDER' => $record->tbl_users['full_name'],
              'REQUEST_NO' => $record->request_no,
              'DOCUMENT_TYPE' => $productName,
              'URL_NAME' => 'Fill ' . $productName . ' form',
              'COMMENT' => ($record->comment <> '') ? 'Comment : ' . $record->comment . '' : '&nbsp;',
              'SITE_NAME' => env('APP_NAME'),
              'SITE_URL' => url('/'),
              'INTRODUCTION_MESSAGE' => 'Dear Customer,',
              'LOGO' => url('assets/images/logo.png'),
              'STAK_LOGO' => url('assets/images/logo.png'),
              'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
              'SUPPORT_URL' => env('SUPPORT_URL'),
              'WEBSITE_URL' => url('/'),
              'HELP_URL' => env('HELP_URL'),
              'TWITTER_URL' => env('TWITTER_URL'),
              'FACEBOOK_URL' => env('FACEBOOK_URL'),
          ];

          $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
          $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

          $options = [
              'html' => true,
              'sender' => env('MAIL_FROM_EMAIL'),
          ];

          $this->sendEmail($email, $email_subject, $email_body, $options);
        
    }
}

public function sendRequireEvidence($id, $workflow_id){
  $userData = DocumentApplication::with('tbl_users')->with('tbl_documents_products')->find($id);

  $url = url('document/request-camera-evidence?token=' . $userData['token'] . '&wk=' . base64_encode($workflow_id));
  $msg = '1) ' . $userData->tbl_document_setup['name'] . ' with Reference No {{REQUEST_NO}}<br>';

  $template_header = $this->getDefaultEmailTemplate('header');
  $template_footer = $this->getDefaultEmailTemplate('footer');

  if (!empty($userData->tbl_users['email'])) {
    $email = $userData->tbl_users['email'];
    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'send_evidence');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];
  
    $placeholders_val = [
      'NAME' => $userData->tbl_users['full_name'],
      'REQUEST_NO' => $userData['request_no'],
      'APPLICATION_TYPE' => $userData->tbl_documents_products['product_name'],
      'REQUESTER_NAME' => $userData->createdby->full_name,
      'URL' => $url,
      'URL_NAME' => 'Upload Requested Document(s)',
      'REQUESTED_DOCS' => $msg,
      'SITE_NAME' => env('APP_NAME'),
      'COMPANY_NAME' => env('COMPANY_NAME'),
      'SITE_NAME' => env('APP_NAME'),
      'SITE_URL' => url('/'),
      'INTRODUCTION_MESSAGE' => 'Hello ' . $userData->tbl_users['full_name'] . ',',
      'LOGO' => url('assets/images/logo.png'),
      'STAK_LOGO' => url('assets/images/logo.png'),
      'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
      'SUPPORT_URL' => env('SUPPORT_URL'),
      'WEBSITE_URL' => url('/'),
      'HELP_URL' => env('HELP_URL'),
      'TWITTER_URL' => env('TWITTER_URL'),
      'FACEBOOK_URL' => env('FACEBOOK_URL')
    ];

    $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
    $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

    $options = [
        'html' => true,
        'sender' => env('MAIL_FROM_EMAIL'),
    ];

    $this->sendEmail($email, $email_subject, $email_body, $options);

  }
 
}
public function sendDocToDepartmentDocument($id, $dept_id, $workflow_id, $bypass){
  $userData = DocumentApplication::with('tbl_users')->with('tbl_documents_products')->find($id);
  $dept = Department::find($dept_id);

  if (!empty($dept->mailing_list)) {

    $template_header = $this->getDefaultEmailTemplate('header');
    $template_footer = $this->getDefaultEmailTemplate('footer');

    if ($bypass > 0) {
      $workflow = DocumentWorkflow::find($workflow_id);
      $workflow->update(array('reference' => 'Bypass of Security and Forensics'));
      $this->WorkflowActionDocuments('approved', $userData['id'], $workflow_id);
      $this->passWorkflowDocuments($userData['id']);
    } else {
      $email = $dept->mailing_list;
    
      $html = $template_header['body'];
      $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'send_department_document');
      $html .= $email_template['template_body'];
      $html .= $template_footer['body'];

      $placeholders_val = [
        'REQUEST_NO' => $userData->request_no,
        'POLICY_NO' => $userData->policy_no,
        'BRANCH' => $userData->tbl_branch ? $userData->tbl_branch->branch_name : '',
        'MOBILE_NO' => $userData->sms,
        'SOURCE' => $userData->source,
        'PROCESSED_BY' => $userData->tbl_users->full_name,
        'PROCESSED_ON' => date('d-M-Y', strtotime($userData->createdon)),
        'DATE' => date('d-M-Y', strtotime($userData->createdon)),
        'APPLICATION_TYPE' => $userData->tbl_documents_products->product_name,
        'NAME' => $userData->tbl_users->full_name,
        'DOCUMENT_TYPE' => $userData->tbl_documents_products->product_name,
        'DEPARTMENT' => $dept->department_name,
        'VIEW_DOCUMENT' => url('document/view/request?token=' . trim($userData->token)),
        'APPROVE_URL' => url('document/action/request?key=' . base64_encode('approved-review') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
        'DECLINE_URL' => url('document/action/request?key=' . base64_encode('declined-review') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
        'SITE_NAME' => env('APP_NAME'),
        'SITE_URL' => url('/'),
        'INTRODUCTION_MESSAGE' => 'Attention ' . $dept->department_name . ' department ,',
        'LOGO' => url('assets/images/logo.png'),
        'STAK_LOGO' => url('assets/images/logo.png'),
        'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
        'SUPPORT_URL' => env('SUPPORT_URL'),
        'WEBSITE_URL' => url('/'),
        'HELP_URL' => env('HELP_URL'),
        'TWITTER_URL' => env('TWITTER_URL'),
        'FACEBOOK_URL' => env('FACEBOOK_URL')
      ];

      $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
      $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

      $options = [
          'html' => true,
          'sender' => env('MAIL_FROM_EMAIL'),
      ];

      $this->sendEmail($email, $email_subject, $email_body, $options);

    }
  }
}
public function sendRequestDocumentsDocument($id, $doc_id, $workflow_id){
  $userData = DocumentApplication::with('tbl_users')->with('tbl_documents_products')->find($id);
  $documents = DocumentType::find($doc_id);

  if ($documents->require_camera > 0) {
    $url = url('document/request-camera-document?token=' . $userData->token . '&wk=' . base64_encode($workflow_id));
  } else {
    $url = url('document/request-document?token=' . $userData->token . '&wk=' . base64_encode($workflow_id));
  }

  $html .= '1) ' . $documents->document_name. '<br>';


  if (!empty($userData->tbl_users['email'])) {

    //dumpscreen($msgget, true);
    $email = $userData->tbl_users['email'];

    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'request_document_document');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];

    $placeholders_val = [
      'NAME' => $userData->tbl_users['full_name'],
      'REQUEST_NO' => $userData->request_no,
      'POLICY_NO' => $userData->policy_no,
      'BRANCH' => $userData->tbl_branch ? $userData->tbl_branch->branch_name : '',
      'MOBILE_NO' => $userData->sms,
      'SOURCE' => $userData->source,
      'PROCESSED_BY' => $userData->tbl_users->full_name,
      'PROCESSED_ON' => date('d-M-Y', strtotime($userData->createdon)),
      'APPLICATION_TYPE' => $userData->tbl_documents_products->product_name,
      'REQUESTER_NAME' => $userData->createdby->full_name,
      'URL' => $url,
      'URL_NAME' => 'Upload Requested Document(s)',
      'REQUESTED_DOCS' => '1) ' . $documents['document_name'] . '<br>',
      'SITE_NAME' => $APP_NAME,
      'COMPANY_NAME' => $COMPANY_NAME,
      'SITE_NAME' => env('APP_NAME'),
      'SITE_URL' => url('/'),
      'INTRODUCTION_MESSAGE' => 'Hello ' . $userData->tbl_users['full_name'] . ',',
      'SITE_NAME' => env('APP_NAME'),
      'SITE_URL' => url('/'),
      'LOGO' => url('assets/images/logo.png'),
      'STAK_LOGO' => url('assets/images/logo.png'),
      'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
      'SUPPORT_URL' => env('SUPPORT_URL'),
      'WEBSITE_URL' => url('/'),
      'HELP_URL' => env('HELP_URL'),
      'TWITTER_URL' => env('TWITTER_URL'),
      'FACEBOOK_URL' => env('FACEBOOK_URL')
    ];
      $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
      $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

      $options = [
          'html' => true,
          'sender' => env('MAIL_FROM_EMAIL'),
      ];

      $this->sendEmail($email, $email_subject, $email_body, $options);
    
  }
}
public function sendDocToStaffDocument($id, $dept_id, $workflow_id){

  $userData = DocumentApplication::with('tbl_users')->with('createdby')->with('tbl_documents_products')->find($id);
  $dept = Department::find($dept_id);
  $workflow = DocumentWorkflow::find($workflow_id);
  $to_go = User::find($dept_id);
  if (!empty($to_go['email'])) {
    $email = $to_go['email'];
    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'send_department_staff');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];

    $placeholders_val = [
      'DATE' => date('d-M-Y', strtotime($userData->createdon)),
      'DOCUMENT_TYPE' => $userData->tbl_documents_products->product_name,
      'NAME' => $userData->customer_name,
      'REQUEST_NO' => $userData->request_no,
      'POLICY_NO' => $userData->policy_no,
      'BRANCH' => $userData->tbl_branch ? $userData->tbl_branch->branch_name : '',
      'MOBILE_NO' => $userData->sms,
      'SOURCE' => $userData->source,
      'PROCESSED_BY' => $userData->tbl_users->full_name,
      'PROCESSED_ON' => date('d-M-Y', strtotime($userData->createdon)),
      'REQUESTER' => $userData->tbl_users['full_name'],
      'VIEW_DOCUMENT' => url('document/view-documents-request?token=' . trim($userData->token)),
      'APPROVE_URL' => url('document/action-document?key=' . base64_encode('approved') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
      'DECLINE_URL' => url('document/action-document?key=' . base64_encode('declined') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
      'REVIEW_URL' => url('document/action-document?key=' . base64_encode('reviewed') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
      'SITE_NAME' => env('APP_NAME'),
      'SITE_URL' => url('/'),
      'INTRODUCTION_MESSAGE' => 'Dear ' . $to_go['full_name'] . ' ,',
      'LOGO' => url('assets/images/logo.png'),
      'STAK_LOGO' => url('assets/images/logo.png'),
      'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
      'SUPPORT_URL' => env('SUPPORT_URL'),
      'WEBSITE_URL' => url('/'),
      'HELP_URL' => env('HELP_URL'),
      'TWITTER_URL' => env('TWITTER_URL'),
      'FACEBOOK_URL' => env('FACEBOOK_URL')
    ];

  $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
  $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

  $options = [
      'html' => true,
      'sender' => env('MAIL_FROM_EMAIL'),
  ];

  $this->sendEmail($email, $email_subject, $email_body, $options);
}
}
public function sendToTeamManagerDocuments($id, $dept_id, $workflow_id){
  $userData = DocumentApplication::with('tbl_users')->with('tbl_documents_products')->find($id);
    
  // $workflow = DocumentWorkflow::find($workflow_id);
  $user = User::find($userData->tbl_users->reports_to);

  $template_header = $this->getDefaultEmailTemplate('header');
  $template_footer = $this->getDefaultEmailTemplate('footer');

  if (!empty($user->email)) {
    $email = $user->email;
    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'send_teamleader_documents');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];

    $placeholders_val = [ 
      'INITIATOR' => $userData->tbl_users['full_name'],
      'REQUEST_NO' => $userData->request_no,
      'POLICY_NO' => $userData->policy_no,
      'BRANCH' => $userData->tbl_branch ? $userData->tbl_branch->branch_name : '',
      'MOBILE_NO' => $userData->sms,
      'SOURCE' => $userData->source,
      'PROCESSED_BY' => $userData->tbl_users['full_name'],
      'PROCESSED_ON' => date('d-M-Y', strtotime($userData['createdon'])),
      'DATE' => date('d-M-Y', strtotime($userData->createdon)),
      'APPLICATION_TYPE' => $userData->tbl_documents_products->product_name,
      'NAME' => $userData->customer_name,
      'DOCUMENT_TYPE' => $userData->tbl_documents_products->product_name,
      // 'DEPARTMENT' =>  $dept_name,
      'VIEW_DOCUMENT' => url('document/view/request?token=' . trim($userData->token)),
      'APPROVE_URL' => url('document/action/request?key=' . base64_encode('approved') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
      'DECLINE_URL' => url('document/action/request?key=' . base64_encode('declined') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
      'REVIEW_URL' => url('document/action/request?key=' . base64_encode('reviewed') . '&token=' . $userData->token . '&uid=' . base64_encode($workflow_id) . '&auth=1'),
      'SITE_NAME' => env('APP_NAME'),
      'SITE_URL' => url('/'),
      'INTRODUCTION_MESSAGE' => 'Dear ' . $user->full_name . ',',
      'LOGO' => url('assets/images/logo_small_1.png'),
      'STAK_LOGO' => url('assets/images/logo_small_1.png'),
      'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
      'SUPPORT_URL' => env('SUPPORT_URL'),
      'WEBSITE_URL' => url('/'),
      'HELP_URL' => env('HELP_URL'),
      'TWITTER_URL' => env('TWITTER_URL'),
      'FACEBOOK_URL' => env('FACEBOOK_URL')
    ];

    $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
    $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

    $options = [
        'html' => true,
        'sender' => env('MAIL_FROM_EMAIL'),
    ];

    $this->sendEmail($email, $email_subject, $email_body, $options);
    // if ($dept_id == 0) {
      $this->LogMessages($userData['tbl_users_id'], $email_template['subject'], $email_body, auth()->user()?->id, 0);
    // }
  } else {
    // $workflow = DocumentWorkflow::find($workflow_id);
    $workflow->update(array('reference' => 'No Team Leader Assigned'));
    $this->WorkflowActionDocuments('approved', $userData['id'], $workflow_id);
    $this->passWorkflowDocuments($userData['id']);
  }

}

function sendRequestDocumentsCustomProducts($id, $url, $checklist_id, $email = null)
{

  $record = DocumentApplication::with('tbl_users')->find($id);
  $checklist = DocumentChecklist::find($checklist_id);
  $doc = '';
  $doc .= '1) ' . $checklist->tbl_document_type['document_name'] . '<br>';

  $template_header = $this->getDefaultEmailTemplate('header');
  $template_footer = $this->getDefaultEmailTemplate('footer');
  $tmp_html = '';

  if ($email == '') {
    $email = $checklist['email'];
  } else {
    $email = $email;
  }

  $html = $template_header['body'];
  $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'request_document');
  $html .= $email_template['template_body'];
  $html .= $template_footer['body'];

  $placeholders_val = array(
    'FULL_NAME' => 'Customer',
    'NAME' => 'Customer',
    'REQUESTER_NAME' => $record->tbl_users['full_name'],
    'URL' => $url,
    'URL_NAME' => 'Upload Requested Document(s)',
    'REQUESTED_DOCS' => $doc,
    'COMPANY_NAME' => env('COMPANY_NAME'),
    'SITE_NAME' => env('APP_NAME'),
    'SITE_URL' => url('/'),
    'INTRODUCTION_MESSAGE' => 'Dear Customer,',
    'LOGO' => url('assets/images/logo.png'),
    'STAK_LOGO' => url('assets/images/logo.png'),
    'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
    'SUPPORT_URL' => env('SUPPORT_URL'),
    'WEBSITE_URL' => url('/'),
    'HELP_URL' => env('HELP_URL'),
    'TWITTER_URL' => env('TWITTER_URL'),
    'FACEBOOK_URL' => env('FACEBOOK_URL'),
  );

  $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
  $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);

  $options = [
      'html' => true,
      'sender' => env('MAIL_FROM_EMAIL'),
  ];

  $this->sendEmail($email, $email_subject, $email_body, $options);

}

function sendCustomerComplaint($rec, $user, $comments)
{
  $template_header = $this->getDefaultEmailTemplate('header');
  $template_footer = $this->getDefaultEmailTemplate('footer');
  
  if (!empty($user->email)) {

    $email = $user->email;
    $html = $template_header['body'];
    $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'assign_complaint');
    $html .= $email_template['template_body'];
    $html .= $template_footer['body'];

    $placeholders_val = array(
      'ASSIGNOR' => auth()->user()?->fullname,
      'CUSTOMER_NAME' => $rec['name'],
      'REQUEST_NO' => $rec['request_no'],
      'PHONE_NUMBER' => $rec['phone_number'],
      'EMAIL' => $rec['email'],
      'DESCRIPTION' => $rec['description'],
      'COMMENT' => 'Comment:' . $comments,
      'CATEGORY' => $rec->tbl_complaints_categories['name'],
      'POLICY_NUMBER' => $rec['policy_number'],

      'COMPLETED_URL' => url('customer-complaint/web-api?mode=' . base64_encode('completed') . '&token=' . $rec['token'] . ''),
      'REASSIGN_URL' => url('customer-complaint/web-api?mode=' . base64_encode('reassign') . '&token=' . $rec['token'] . ''),
      'PEND_URL' => url('customer-complaint/web-api?mode=' . base64_encode('pend') . '&token=' . $rec['token'] . ''),
     
      'COMPANY_NAME' => env('COMPANY_NAME'),
      'SITE_NAME' => env('APP_NAME'),
      'SITE_URL' => url('/'),
      'INTRODUCTION_MESSAGE' => 'Hello ' . ucfirst(strtolower($user['full_name'])) . ',',
      'LOGO' => url('assets/images/logo.png'),
      'STAK_LOGO' => url('assets/images/logo.png'),
      'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
      'SUPPORT_URL' => env('SUPPORT_URL'),
      'WEBSITE_URL' => url('/'),
      'HELP_URL' => env('HELP_URL'),
      'TWITTER_URL' => env('TWITTER_URL'),
      'FACEBOOK_URL' => env('FACEBOOK_URL'),
    );

    $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
    $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);
  
    $options = [
        'html' => true,
        'sender' => env('MAIL_FROM_EMAIL'),
    ];
  
    $this->sendEmail($email, $email_subject, $email_body, $options);
  }
}

function shareDocument2($id, $url)
{

  $userData = ShareDocumentLog::with(['tbl_document_applications', 'tbl_document_type'])->find($id);

  $html = $template_header['body'];
  $email_template = $this->getTemplatebyCategoryAndVariable('Account', 'share_document');
  $html .= $email_template['template_body'];
  $html .= $template_footer['body'];

  $recipients = explode(',', $userData['email_to']);

  if (sizeof($recipients) > 0) {
    foreach ($recipients as $recipient) {
      $placeholders_val = array(
        'COMPANY_NAME' => $COMPANY_NAME,
        'DOCUMENT_TYPE' => $userData->tbl_document_type['document_name'],
        'SHARER' => $userData->tbl_users['full_name'],
        'DOCUMENT_NAME' => $userData->tbl_document_applications->tbl_documents_products['product_name'],
        'URL' => $url,
        'SITE_NAME' => env('APP_NAME'),
        'SITE_URL' => url('/'),
        'INTRODUCTION_MESSAGE' => 'Dear Customer,',
        'LOGO' => url('assets/images/logo.png'),
        'STAK_LOGO' => url('assets/images/logo.png'),
        'HELP_CENTRE_URL' => env('HELP_CENTRE_URL'),
        'SUPPORT_URL' => env('SUPPORT_URL'),
        'WEBSITE_URL' => url('/'),
        'HELP_URL' => env('HELP_URL'),
        'TWITTER_URL' => env('TWITTER_URL'),
        'FACEBOOK_URL' => env('FACEBOOK_URL'),
      );
      
    $email_body = $this->replaceEmailPlaceholders($html, $placeholders_val);
    $email_subject = $this->replaceEmailPlaceholders($email_template['subject'], $placeholders_val);
  
    $options = [
        'html' => true,
        'sender' => env('MAIL_FROM_EMAIL'),
    ];
  
    $this->sendEmail($recipient[$i], $email_subject, $email_body, $options);
    $this->logDocumentsTrail($userData['id'], auth()->user()?->fullname, 'Shared Document via Email', $recipient[$i]);
    }
  }
  //  LogMessages($userData['id'], $msgget['subject'], $msg, 'System');

}

function LogMessages($user_id, $subject, $message, $from, $department_id = 0)
{
    $serialized_message = base64_encode(serialize($message));

    Message::create([
        'tbl_users_id' => $user_id,
        'message' => $serialized_message,
        'subject' => $subject,
        'message_from' => $from,
        'tbl_departments_id' => $department_id
    ]);
}



function replaceEmailPlaceholders($template, $placeholders)
{
    foreach ($placeholders as $placeholder => $value) {
        $template = str_replace('{{' . strtoupper($placeholder) . '}}', $value, $template);
    }
    return $template;
}
function logDocumentsTrail($id, $user, $action, $ref)
{
    $log = DocumentsLog::create([
        'tbl_documents_id' => $id,
        'user' => $user,
        'log_action' => $action,
        'reference' => $ref
    ]);
}

function sendEmail($recipient, $subject, $body, $options)
{
  Mail::to($recipient)->send(new EmailBlueprint($subject, $body, $options));
}

function sendSMS($to, $message, $sender) {
  //withoutVerifying()-> //remove in production
  $response = Http::withoutVerifying()->get(env('ARKESEL_SMS_URL'), [
    'action' => 'send-sms',
    'api_key' => env('ARKESEL_SMS_KEY'),
    'to' => $to,
    'from' => $sender,
    'sms' => $message
  ]);
  return $response;
} 

function searchCustomerComplaints($search = null)
{
    $query = CustomerComplaint::where('deleted', 0)
        ->where('completed_yn', 0)
        ->where('createdby', Auth::user()->id); 

    if (is_array($search)) {
        if (!empty($search['bn'])) {
            $query->where('tbl_branch_id', $search['bn']);
        }
        if (!empty($search['categ'])) {
            $query->where('tbl_complaints_categories_id', $search['categ']);
        }
        if (!empty($search['reqnum'])) {
            $query->where('request_no', $search['reqnum']);
        }
        if (!empty($search['assigned_to'])) {
            $query->where('assigned_to', $search['assigned_to']);
        }
        if (!empty($search['name'])) {
            $query->whereRaw('LOWER(name) like ?', [strtolower($search['name'])]);
        }
        if (!empty($search['phone_number'])) {
            $query->where('phone_number', 'like', '%' . $search['phone_number'] . '%');
        }
        if (isset($search['status'])) {
            $query->whereIn('tbl_application_status_id', $search['status']);
        }
    } else {
        $status = new ApplicationStatusClass(2, 2);
        $statuses = $status->getStatusbyStage([1, 2, 3])->pluck('id')->toArray();
        $query->whereIn('tbl_application_status_id', $statuses);
    }

    return $query->orderByDesc('id')->get();
}

function limitBranchAccess($limit = null)
{
    if (is_null($limit)) {
        $limit = is_numeric($this->getConfigbyKey('global_restrict_branch')) ? $this->getConfigbyKey('global_restrict_branch') : 1;
    }

    $query = Branch::query()->where('deleted', 0);

    if ($limit) {
        $userBranches = $this->getAccessPriv(Auth::user()->id)->pluck('tbl_branch_id');
        $query->whereIn('id', $userBranches);
    }

    return $query->orderBy('id')->get();
}

function getConfigbyKey($key)
{
    $config = Config::whereRaw('lower(config_key) = ?', [strtolower($key)])->first();

    return $config ? $config->value : false;
}

function getAccessPriv($id)
{
    return UserBranch::where('tbl_users_id', $id)->get();
}



}
?>