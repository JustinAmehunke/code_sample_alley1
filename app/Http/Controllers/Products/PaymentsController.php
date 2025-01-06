<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
// use GuzzleHttp\Client;
use DB;

class PaymentsController extends Controller
{
    public function saveOrUpdate(){

        DB::beginTransaction();
        if ($condition) {
            //UPDATE
            try {
                
    
                DB::commit();
                // return redirect()->route('payment.page');
                return response( [
                    'status' => 'success',
                    'message' => 'Changes updated successfully',
                ], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
    
                // throw $th;
                return response( [
                    'status' => 'failed',
                    'error'=> $th->getMessage(),
                    'message' => 'Something went wrong',
                ], 200);
            }
          
        }else{
            //SAVE
            try {
                
    
                DB::commit();
                // return redirect()->route('payment.page');
                return response( [
                    'status' => 'success',
                    'message' => 'Changes saved successfully',
                ], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
    
                // throw $th;
                return response( [
                    'status' => 'failed',
                    'error'=> $th->getMessage(),
                    'message' => 'Something went wrong',
                ], 200);
            }
        }
      }
}
