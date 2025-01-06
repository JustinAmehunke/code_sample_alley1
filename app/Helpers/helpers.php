<?php
// add more and run "composer dump-autoload"

use Illuminate\Support\Facades\Storage;
use App\Models\DocumentType;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\User;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\DB;
use App\Models\Config;

function isseter($fig, $alter = NULL)
{
  return isset($fig) ? $fig : $alter;
}

function yesNoOptions($name, $selected = null)
{
    $options = [
        ['value' => '1', 'label' => 'Yes'],
        ['value' => '0', 'label' => 'No'],
    ];

    $html = '<select class="form-select" name="' . $name . '">';

    foreach ($options as $option) {
        $selectedAttr = ($selected !== null && $selected == $option['value']) ? 'selected' : '';
        $html .= '<option value="' . $option['value'] . '" ' . $selectedAttr . '>' . $option['label'] . '</option>';
    }

    $html .= '</select>';

    return $html;
}

function comboBuilderNotRequired($tbl, $name, $label, $value, $selected = null, $cond = '', $sort = null)
{
    $html = '';

    $sort1 = !empty($sort) ? "ORDER BY $sort" : '';
    $sql = "SELECT * FROM $tbl $cond $sort1";

    $rs = DB::select($sql);

    if (!empty($rs)) {
        $html .= '<select name="' . $name . '" id="' . $name . '" class="form-select">';
        $html .= '<option value="">Select...</option>';
        foreach ($rs as $row) {
            $sel = $selected == $row->$value ? 'selected="selected"' : '';
            $html .= '<option value="' . $row->$value . '" ' . $sel . '>' . $row->$label . '</option>';
        }
        $html .= '</select>';
    }
    return $html;
}


function change_date($created_date, $duration_change, $return_date_format)
{
  $EndDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $created_date);
  $EndDateTime->modify($duration_change);
  return $EndDateTime->format($return_date_format);
}

function getReference($workflow_id, $id, $reference = null)
{

    // return $workflow_id."_".$id."_".$reference;
    $val = '';
    switch ($workflow_id) {
        case 1:
            $result = Department::find($id);
            if($result){$val= $result->department_name;}
            break;
        case 2:
            $result = DocumentType::find($id);
            if($result){$val= $result->document_name;}
            break;
        case 3:
            $result = User::find($id);
            if($result){$val= $result->full_name;}
            break;
        default:
            $val = $reference;
            break;
    }

    return $val;
}

function checkProposalForm($id, $document_type_id)
{
    // 
    $count = Document::where('tbl_document_applications_id', $id)
        ->where('tbl_document_type_id', $document_type_id)
        ->count();

    // Use a ternary operator to simplify the logic
    return ($count > 0) ? 1 : 0;
}


function getImageDetailsDocuments($requestId, $workflowId)
{
    return RequestedDocument::where('tbl_document_applications_id', $requestId)
        ->where('tbl_document_workflow_id', $workflowId)
        ->first();
}

function getImageDetailsImgDocument($requestId, $workflowId)
{
    $requestedDocument = RequestedDocument::where('tbl_document_applications_id', $requestId)
        ->where('tbl_document_workflow_id', $workflowId)
        ->first();

    return $requestedDocument ? $requestedDocument->tbl_document_images->images : null;
}

function getFullFilePathOnS3Download($file_name)
{
    $get_file = explode('/', $file_name);

    if (sizeof($get_file) == 2) {
        $link = $get_file[0];
        $new_file = $get_file[1];
    } elseif (sizeof($get_file) == 3) {
        $link = $get_file[1];
        $new_file = $get_file[2];
    } else {
        $link = 'documents';
        $new_file = $get_file[0];
    }

    $bucket_name = config('filesystems.disks.s3.bucket');
    $region = config('filesystems.disks.s3.region');

    // Create a temporary URL that expires after 20 minutes
    $url = Storage::disk('s3')->temporaryUrl($link . '/' . $new_file, now()->addMinutes(20));

    // Print or return the URL based on your needs
    echo $url;
    // return $url;
}

function getUserName($id)
{
    $user = User::find($id);

    if ($user) {
        return ucwords($user->full_name);
    }

    return 'Not Assigned';
}

// function confirmBin($id)
// {
//   $department_id = Auth::user()->department_id;

//   return DocumentWorkflow::where('tbl_document_applications_id', $id)
//       ->where('started_yn', 1)
//       ->where('completed_yn', 0)
//       ->where('deleted', 0)
//       ->whereIn('tbl_document_applications.tbl_application_status_id', [66, 71])
//       ->where('tbl_document_setup_details.reference', $department_id)
//       ->with('tbl_document_applications', 'tbl_document_setup_details')
//       ->first();
// }

function obfuscateEmail($email) {
    // Split the email address into username and domain parts
    list($username, $domain) = explode('@', $email);
    
    // Obfuscate username
    $obfuscatedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 3);

    // Obfuscate domain
    $domainParts = explode('.', $domain);
    $obfuscatedDomain = '';
    foreach ($domainParts as $part) {
        $obfuscatedDomain .= substr($part, 0, 1) . str_repeat('*', strlen($part) - 1) . '.';
        // $obfuscatedDomain .= substr($part, 0, 3) . str_repeat('*', strlen($part) - 3) . '.';
    }
    $obfuscatedDomain = rtrim($obfuscatedDomain, '.');

    // Construct formatted email
    $formattedEmail = $obfuscatedUsername . '@' . $obfuscatedDomain;

    return $formattedEmail;
}

function obfuscatePhoneNumber($phoneNumber) {
    // Remove non-numeric characters from the phone number
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Get the length of the phone number
    $phoneNumberLength = strlen($phoneNumber);

    // Check if the phone number has enough digits to obfuscate
    if ($phoneNumberLength < 5) {
        return $phoneNumber; // Return original phone number if it's too short
    }

    // Obfuscate the phone number
    $obfuscatedPhoneNumber = substr($phoneNumber, 0, 3) . str_repeat('*', $phoneNumberLength - 5) . substr($phoneNumber, -2);

    return $obfuscatedPhoneNumber;
}



function getConfigbyKey($key)
{
    $val = false;
    $config = Config::where('config_key', strtolower($key))->first();
    
    if ($config) {
        $val = $config->value;
    }
    
    return $val;
}

// function comboBuilderMultipleSelectID($table, $id, $name, $label, $value, $condition, $selected = NULL)
// {
//     $options = \DB::table($table)->whereRaw($condition)->pluck($label, $value);
//     $selectedOptions = collect($selected);

//     return '<select multiple data-plugin-selectTwo class="form-control populate" name="' . $name . '" id="' . $id . '" required >
//                 ' . $options->map(function($option, $key) use ($selectedOptions) {
//                     $isSelected = $selectedOptions->contains($key) ? 'selected' : '';
//                     return '<option value="' . $key . '" ' . $isSelected . '>' . $option . '</option>';
//                 })->implode('') . '
//             </select>';
// }


function YesNo($selected = null)
{
    $options = collect([
        ['id' => 0, 'label' => 'No'],
        ['id' => 1, 'label' => 'Yes']
    ]);

    $selectedValue = is_null($selected) ? 0 : intval($selected);

    return $options->map(function ($item) use ($selectedValue) {
        $selectedAttr = $item['id'] === $selectedValue ? 'selected' : '';
        return "<option value='{$item['id']}' {$selectedAttr}>{$item['label']}</option>";
    })->implode('');
}





