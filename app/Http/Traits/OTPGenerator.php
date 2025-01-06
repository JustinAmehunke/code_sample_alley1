<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Mail\SendOTP;
use Exception;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\DocumentSysEngine;
use Session;


trait OTPGenerator {

    use DocumentSysEngine;

   /**
    * The `generateCode` function generates a random code and sends it either via SMS or email based on
    * the type of input provided.
    * 
    * @param user_id The `user_id` parameter in the `generateCode` function is used to identify the
    * user for whom the authentication code is being generated. It is typically a unique identifier for
    * the user in the database, such as the user's ID or primary key. This parameter helps in updating
    * the user's record
    * @param email_phone The `email_phone` parameter in the `generateCode` function seems to be used to
    * pass either an email address or a phone number for sending the authentication code.
    */
    public function generateCode($user_id, $email_phone)
    {
       
        $code = mt_rand(100000, 999999); // Generate a random code
    
      if(is_numeric($email_phone)){

        // Send SMS via Arkesel
        $to = $email_phone; // Replace with the recipient's phone number
        $sender = 'OLDMUTUAL'; // Replace with your sender ID
        $message = 'Your authentication code is: '.$code; // Replace with your message
        
        $response = $this->sendSMS($to, $message, $sender);

        if ($response->successful()) {
            //success
            Session::put('user_2fa', $email_phone);

            User::findOrFail($user_id)->update([
                "auth_code" => $code,
                // "expires_at" => Carbon::now()->addMinutes(5),
            ]);
        } 
      }else{
        try {
  
            $details = [
                'title' => 'STAK authentication code',
                'code' => $code
            ];
             
            Mail::to($email_phone)->send(new SendOTP($details));
            //success
            Session::put('user_2fa', $email_phone);

            User::findOrFail($user_id)->update([
                "auth_code" => $code,
                // "expires_at" => Carbon::now()->addMinutes(5),
            ]);
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
      }
    }
}



?>