<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

trait UsersTrait {
    
    /**
     * 
     * @return type current User
     * 
     */
    public function getCurrentUser(){
 
        if(Auth::check()){
           return Auth::user();
        }        
    }

    public function sendCode($mobile, $email, $code, $method = "SMS"){

        //$payload = array('mobile' => $mobile,'smscode' => $code,'method' => $method,'email' => $email);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.taxon.co.il/api/ws/send-app-code.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('mobile' => $mobile,'method' => $method,'email' => $email,'smscode' => $code),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
        //dd( $response);

    }
}
?>