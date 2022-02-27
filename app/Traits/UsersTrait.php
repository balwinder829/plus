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

}
?>