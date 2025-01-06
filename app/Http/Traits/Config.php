<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use DB;


trait Config {

public function get_auth_token_from_slams(){
    $clientId = env('SLAMS_CLIENT_ID');
    $clientSecret = env('SLAMS_CLIENT_SECRET');

    $authHeader = [
        'Accept' => 'application/form-data',
    ];

    $response = Http::withoutVerifying()->post(env('SLAMS_URL_TO_GET_AUTH_TOKEN'), [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ], $authHeader);

    $responseData = $response->json();
    // return  $responseData;

    if (!isset($responseData["access_token"])) {
        $responseData["error"] = true;
    }

    return $responseData;
}

public function CallAPI($method, $url, $data, array $header = [])
{
    //remove in production withoutVerifying()->
    
    switch ($method) {
        case "POST":
            $response = Http::withoutVerifying()->withHeaders($header)->post($url, $data);
            break;
        case "PUT":
            $response = Http::withoutVerifying()->withHeaders($header)->put($url, $data);
            break;
        default:
            $response = Http::withoutVerifying()->withHeaders($header)->get($url, $data );
            break;
    }

    return $response;
}





}
?>