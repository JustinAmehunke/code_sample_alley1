<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentProduct;
use App\Models\DocumentsProductsChecklist;
use Illuminate\Support\Facades\DB;

class DocumentProductsController extends Controller
{
    public function documentCreateUpdateProduct(Request $request)
    {
        $requestData = request()->all();
        // return  $requestData;
        $message = '';
    
        // Check for existence of request data
        if (!empty($requestData)) {
            $productData = [
                'product_name' => strtoupper($requestData['product_name']),
                'template_link' => $requestData['template_link'],
                'website_link' => $requestData['website_link'],
                'require_mandate' => isset($requestData['mandate_document_yn']),
                'require_product' => isset($requestData['product_document_yn']),
            ];
    
            DB::beginTransaction();
    
            try {
                if (!empty($requestData['id'])) {
                    $id = $requestData['id'];
    
                    // Update product
                    $product = DocumentProduct::findOrFail($id);
                    $product->update($productData);
    
                    // Soft delete existing checklist items
                    // $product->checklist()->update(['deleted' => 1]);
                    $xx = DocumentsProductsChecklist::where('tbl_documents_products_id', $id)->get();
                    if ($xx->isNotEmpty()) {
                        foreach ($xx as $record) {
                            $record->update(['deleted' => 1]);
                        }
                    }
    
                    // Insert or update checklist items
                    if(isset($requestData['tbl_document_type_id'])){
                        foreach ($requestData['tbl_document_type_id'] as $key => $document_type_id) {
                            DocumentsProductsChecklist::updateOrCreate(
                                ['tbl_documents_products_id' => $id, 'tbl_document_type_id' => $document_type_id],
                                ['mandatory_yn' => $requestData['mandatory'][$key], 'deleted' => 0]
                            );
                        }
                    }
                   
    
                    $message = 'Product updated successfully';
                } else {
                    // Insert new product
                    $product = DocumentProduct::create($productData);
    
                    // Insert checklist items
                   if(isset($requestData['tbl_document_type_id'])){
                    foreach ($requestData['tbl_document_type_id'] as $key => $document_type_id) {
                        $product->checklist()->create([
                            'tbl_document_type_id' => $document_type_id,
                            'mandatory_yn' => $requestData['mandatory'][$key],
                        ]);
                    }
                   }
    
                    $message = 'Product added successfully';
                }
    
                DB::commit();
                return redirect()->back()->with('success_message', $message);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
                return redirect()->back()->with('error_message', 'An error occurred while processing the request.');
            }
        } else {
            return redirect()->back()->with('error_message', 'No data received.');
        }
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
