<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Mail\SendOTP;
use Exception;
use Mail;
use Illuminate\Support\Facades\Hash;


class OTPController extends Controller
{

    public function generateCode($user_id, $email_phone)
    {
       
        $code = mt_rand(1000, 9999); // Generate a random code
    
      if(is_numeric($email_phone)){

        // Send SMS via Arkesel
        $to = $email_phone; // Replace with the recipient's phone number
        $sender = 'OLDMUTUAL'; // Replace with your sender ID
        $message = 'Your authentication code is: '.$code; // Replace with your message
        //withoutVerifying()-> //remove in production
        // Quick Fix
        $response = Http::get(env('ARKESEL_SMS_URL'), [
            'action' => 'send-sms',
            'api_key' => env('ARKESEL_SMS_KEY'),
            'to' => $to,
            'from' => $sender,
            'sms' => $message
        ]);

        // return $response;
        if ($response->successful()) {
            
          
            User::findOrFail($user_id)->update([
                "password" => Hash::make($password),
                "created_at" => Carbon::now(),
                // "expires_at" => Carbon::now()->addMinutes(5),
            ]);

            return $code;
        } 
      }else{
        try {
  
            $details = [
                'title' => 'Mail Sent from Websolutionstuff',
                'code' => $code
            ];
             
            Mail::to(auth()->user()->email)->send(new SendEmailCode($details));
    
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
      }
    }

    public function resetCode(Request $request){
        $validatedData = $request->validate([
            'phone_number' => 'required|numeric', 
        ]);
        $user = User::where('phone', $request->phone_number)->first();
        if(!$user){
            return redirect()->route('signup')->with('error', 'Phone Number does not exist in our records. Please Sign up');
        }else{

            $phoneNumber = $request->phone_number;
            $code = mt_rand(1000, 9999); // Generate a random code
    
            // Send SMS via Arkesel
            $to = $phoneNumber; // Replace with the recipient's phone number
            $sender = 'AGFORM'; // Replace with your sender ID
            $message = 'Your new authentication code is: '.$code; // Replace with your message
            //withoutVerifying()-> //remove in production
            // Quick Fix
            $response = Http::get(env('ARKESEL_SMS_URL'), [
                'action' => 'send-sms',
                'api_key' => env('ARKESEL_SMS_KEY'),
                'to' => $to,
                'from' => $sender,
                'sms' => $message
            ]);
    
            // return $response;
            if ($response->successful()) {
              
                $user_id = User::findOrFail($user->id)->update([
                    "auth_code" =>  $code,
                    "updated_at" => Carbon::now(),
                ]);
    
                return redirect()->route('authenticate')->with('success', 'Authentication code reset successfully. Sign in with the new code you just received');
                // return response()->json(['message' => 'Verification code sent']);
            } else {
                return redirect()->route('og-form')->with('error', 'Something went wrong');
                return response()->json(['message' => 'Failed to send verification code'], 500);
            }
        }
    }
    
    public function signupUser(Request $request)
    {
        // Validate the phone number input
        $validatedData = $request->validate([
            'phone_number' => 'required|numeric', // Add any other validation rules as needed
            'full_name' => 'required|max:150', //
        ]);
        $user = User::where('phone', $request->phone_number)->first();
        if($user){
            return redirect()->route('signup')->with('error', 'Phone Number already exists');
        }else{

            $phoneNumber = $request->phone_number;
            $full_name = $request->full_name;
            $code = mt_rand(1000, 9999); // Generate a random code
    
            // Send SMS via Arkesel
            $to = $phoneNumber; // Replace with the recipient's phone number
            $sender = 'AGFORM'; // Replace with your sender ID
            $message = 'Your authentication code is: '.$code; // Replace with your message
            //withoutVerifying()-> //remove in production
            // Quick Fix
            $response = Http::get(env('ARKESEL_SMS_URL'), [
                'action' => 'send-sms',
                'api_key' => env('ARKESEL_SMS_KEY'),
                'to' => $to,
                'from' => $sender,
                'sms' => $message
            ]);
    
            // return $response;
            if ($response->successful()) {
                
              
                $user_id = User::insert([
                    "auth_code" =>  $code,
                    "phone" =>  $phoneNumber,
                    "name" => $full_name,
                    "created_at" => Carbon::now(),
                ]);
    
                return redirect()->route('og-form')->with('success', 'Success');
            } 
        }
        
    }
}
