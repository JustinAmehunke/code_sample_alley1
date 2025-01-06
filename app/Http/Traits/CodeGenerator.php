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
use Auth;
use DB;


trait CodeGenerator {
    function generateCode($type, $data, $prefix = "", $seperator = "-")
    {
      global $orm;
      $fin_year = date('Y-m-d');
      if (in_array($type, array("partner", "supplier", "user"))) { 
        // return  $data['tbl_sub_user_category_id'];
        //needs tbl_user_category_id, tbl_sub_user_category_id, id;
        $rs = UserCategory::where('id', $data['tbl_user_category_id'])->get();
        $subrs = UserSubCategory::where('id', $data['tbl_sub_user_category_id'])->get();

        return $rs[0]->prefix . $seperator . $subrs[0]->code_prefix . $seperator . $data['id'];
        
      } elseif ($type == "category_prefix") {
        return getCategoryPrefix($data);
      } elseif ($type == "customer") {
        //debugSql(1);
        $branchid = isset($data["tbl_branch_id"]) ? $data["tbl_branch_id"] : '';
        $usercat = $orm->tbl_user_category()->where('id', 6)->fetch();
        $subrs = $orm->tbl_sub_user_category()->where('id', $data['tbl_sub_user_category_id'])->fetch();
        if (empty($branchid)) {
          $branchid = getConfigbyKey("default_branch"); //set to HQ
        }
        $branch = $orm->tbl_branch[$branchid];
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $arra = array(
          //'esu_branch_id' => $branch['id'],
          'counter_name' => "customer" . $usercat['prefix'] . $subrs['code_prefix'],
          'fin_year' => $yr,
          'fin_month' => $mth,
        );
        $ref = getApplicationCounter($arra);
        $format = str_pad($ref, 4, "0", STR_PAD_LEFT);
        $code = $usercat['prefix'] . $subrs['code_prefix'] . $yr . $mth . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "costcentre") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_hrm_costcentres()->where('deleted', 0);
        $count = count($rs) + 1;
        $format = str_pad($count, 3, "0", STR_PAD_LEFT);
        $code = 'GHA' . $yr . $mth . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "receivable") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_finance_receivables()->where('deleted', 0);
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 3, "0", STR_PAD_LEFT);
        // $code = $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $format;
      } elseif ($type == "invoice") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_finance_invoice()->where(array('deleted' => 0, 'type' => array('INVOICE', 'CUSTOM INVOICE')));
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 8, "0", STR_PAD_LEFT);
        $code = 'INV' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "receipt") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_finance_payments()->where('deleted', 0);
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 5, "0", STR_PAD_LEFT);
        $code = 'RC' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "quotation") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_finance_invoice()->where(array('deleted' => 0, 'type' => 'QUOTATION'));
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 8, "0", STR_PAD_LEFT);
        $code = 'QT' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "policy_quotation") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_insurance_quotations()->where(array('deleted' => 0));
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 8, "0", STR_PAD_LEFT);
        $code = 'QT' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "ussd") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_ussd_quotations()->where(array('deleted' => 0));
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 8, "0", STR_PAD_LEFT);
        $code = 'WP' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "ussd_claim") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_ussd_claims_registraion()->where(array('deleted' => 0));
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 5, "0", STR_PAD_LEFT);
        $code = 'WPC-' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "ussd_support") {
        //debugSql(1);
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $rs = $orm->tbl_ussd_service_support()->where(array('deleted' => 0));
        // toscreen($rs,true);
        $count = count($rs) + 1;
        $format = str_pad($count, 5, "0", STR_PAD_LEFT);
        $code = 'WPS-' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "employee") {
        //debugSql(1);
        $department =  $orm->tbl_hrm_departments[$data['tbl_hrm_departments_id']];
        $branchid = isset($data["tbl_branch_id"]) ? $data["tbl_branch_id"] : '';
        $usercat = $orm->tbl_hrm_companies()->where('id', 6)->fetch();
        $subrs = $orm->tbl_sub_user_category()->where('id', $data['tbl_sub_user_category_id'])->fetch();
        if (empty($branchid)) {
          $branchid = getConfigbyKey("default_branch"); //set to HQ
        }
        $branch = $orm->tbl_branch[$branchid];
        $yr = date('Y', strtotime(current_date()));
        $mth = date('m', strtotime(current_date()));
        $arra = array(
          //'esu_branch_id' => $branch['id'],
          'counter_name' => "customer" . $usercat['prefix'] . $subrs['code_prefix'],
          'fin_year' => $yr,
          'fin_month' => $mth,
        );
        $ref = getApplicationCounter($arra);
        $format = str_pad($ref, 4, "0", STR_PAD_LEFT);
        $code = $department['prefix'] . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "homemed") {
        //debugSql(1);
        $ref = $orm->tbl_homemed_registration()->max('id');
        $new_code = $ref + 1;
        $format = str_pad($new_code, 4, "0", STR_PAD_LEFT);
        $code = 'HM' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "homemed_consult") {
        //debugSql(1);
        $ref = $orm->tbl_homemed_consult()->max('id');
        $new_code = $ref + 1;
        $format = str_pad($new_code, 4, "0", STR_PAD_LEFT);
        $code = 'CN' . $format;
        //DumpScreen(array($usercat['prefix'] ,$subrs['code_prefix'], date('Ym'), $format), true);
        return $code;
      } elseif ($type == "BranchCode") {
        // $ref = $orm->tbl_branch()->max('id');
        // $ccode = $ref + 1;
        $ccode = Branch::max('id') + 1;
        return 'CA' . $seperator  . str_pad($ccode, 3, "0", STR_PAD_LEFT);

      } elseif ($type == "church") {
        $ref = $orm->tbl_church_customers()->max('id');
        $ccode = $ref + 1;
        return 'EC' . $seperator  . str_pad($ccode, 3, "0", STR_PAD_LEFT);
      } elseif ($type == "visitor") {
        $branch = $orm->tbl_branch[$data];
        $ref = $orm->tbl_visitors()->where(array('tbl_branch_id' => $data, 'deleted' => 0))->max('id');
        $ccode = $ref + 1;
        return 'VS' . $seperator  . $branch['branch_code'] . $seperator . str_pad($ccode, 3, "0", STR_PAD_LEFT);
      } elseif ($type == "company") {
    
    
        $ccode = $ref + 1;
        return 'CP' . $seperator  . date('dmYHis');
      } elseif ($type == "DocumentGenCode") {
        $document = $orm->tbl_document_type[$data];
        $branchid = getUserContext("branch_id");
        if (empty($branchid)) {
          $branchid = getConfigbyKey("default_branch"); //set to HQ
        }
        $branch = $orm->tbl_branch[$branchid];
        $arra = array(
          'tbl_branch_id' => $branch['id'],
          'counter_name' => $document['prefix'],
          'fin_year' => date('Y', strtotime(current_date())),
          'fin_month' => date('m', strtotime(current_date())),
        );
    
        $ref = getApplicationCounter($arra);
        $branchcode = str_pad($branch['branch_code'], 3, "0", STR_PAD_LEFT);
        $ccode = $document['prefix'] . $seperator . $branchcode . $seperator . date("Ym") . str_pad($ref, 3, "0", STR_PAD_LEFT);
        return $ccode;
      } elseif ($type == 'product_request') {
    
        $code = 'R' . str_pad(DocumentApplication::max('id') + 1, 6, '0', STR_PAD_LEFT);
        return $code;

      } elseif ($type == 'datacleanup') {
    
        $ref = $orm->tbl_datacleanup()->max('id');
        $new_code = $ref + 1;
        $format = str_pad($new_code, 4, "0", STR_PAD_LEFT);
        $code = 'D' . $format;
        return $code;
      }
    }

    function generatePolicyNo($productId, $source)
    {
        $product = DocumentProduct::find($productId);

        $prefix = '';
        switch ($source) {
            case 'WEBSITE':
                $prefix = '300';
                break;
            case 'USSD':
                $prefix = '400';
                break;
            case 'STAK':
                $prefix = '200';
                break;
            default:
                $prefix = '500';
                break;
        }

        $getRec = DocumentApplication::where('tbl_documents_products_id', $productId)->max('id');
        $count = $getRec + 1;
        $format = str_pad($count, 6, "0", STR_PAD_LEFT);
        $rec = $prefix . $product->prefix . $format;

        // Check for duplicate
        $check = DocumentApplication::where('policy_no', $rec)->first();
        if ($check) {
            $count = $getRec + 2;
            $format = str_pad($count, 6, "0", STR_PAD_LEFT);
            $rec = $prefix . $product->prefix . $format;
        }

        return $rec;
    }

    // function initiateCheckList($setupId, $requestId)
    // {
    //     $main = DocumentApplication::find($requestId);

    //     $checklistCount = DocumentChecklist::where([
    //         'tbl_document_applications_id' => $requestId,
    //         'deleted' => 0,
    //     ])->count();

    //     if ($checklistCount === 0) {
    //         $records = DocumentsProductsChecklist::where([
    //             'tbl_documents_products_id' => $main->tbl_documents_products_id,
    //             'deleted' => 0,
    //         ])->get();

    //         $i = 1;
    //         foreach ($records as $record) {
    //             $arr = [
    //                 'tbl_document_setup_id' => $setupId,
    //                 'tbl_document_applications_id' => $requestId,
    //                 'tbl_document_type_id' => $record->tbl_document_type_id,
    //                 'tbl_checklist_status_id' => 1,
    //                 'sort' => $i,
    //                 'mandatory_yn' => $record->mandatory_yn,
    //                 'tbl_documents_products_checklist_id' => $record->id,
    //             ];

    //             DocumentChecklist::create($arr);

    //             $i++;
    //         }
    //     }
    // }
}
?>