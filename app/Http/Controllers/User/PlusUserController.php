<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use JWTAuth;
use App\Models\PlusUser;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class PlusUserController extends BaseController
{
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('full_name', 'email', 'mobile','smscode','smsCodeExpriration','insetTime','lastupdate','status','taxon_status','password');
        $validator = Validator::make($data, [
            'full_name' => 'required|string',
            'email'     => 'required|email|unique:users',
            'mobile'    => 'required|numeric',
            'password'  => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        //Request is valid, create new user
        $user = PlusUser::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'mobile'    => $request->mobile,
            'smscode'   => !empty($request->smscode) ? $request->smscode : "",
            'smsCodeExpriration'   => !empty($request->smsCodeExpriration) ? $request->smsCodeExpriration : "",
            'insetTime' => !empty($request->insetTime) ? $request->insetTime : "",
            'lastupdate'=> !empty($request->lastupdate) ? $request->lastupdate : "",
            'status'    => !empty($request->status) ? $request->status: "",
            'taxon_status' => !empty($request->taxon_status) ? $request->taxon_status : "",
            'password'  => bcrypt($request->password)
        ]);

        //User created, return success response
        return $this->sendResponse($user, __("messages.user.registered"));

    }
 
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError(__("messages.user.login_credentials_invalid"));
            }else{
                JWTAuth::setToken($token);
            }
        } catch (JWTException $e) {
            return $this->sendError(__("messages.something_wrong"));
        }
    
        $user = JWTAuth::authenticate($token);

        //Token created, return with success response and jwt token
        return $this->sendResponse(compact('token', 'user'), __("messages.user.registered"));

    }
 
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
            return $this->sendResponse("", __("messages.user.logout"));
        } catch (JWTException $exception) {
            return $this->sendError(__("messages.user.cannot_logout"));
        }
    }
    
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);

        return $this->sendResponse(compact('user'), __("messages.user.loggedin"));

    }
}
