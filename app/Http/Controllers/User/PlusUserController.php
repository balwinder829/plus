<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use JWTAuth;
use App\Models\PlusUser;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Traits\UsersTrait;
use Illuminate\Support\Facades\DB;

class PlusUserController extends BaseController
{
    use UsersTrait;

    protected $PlusUserMod;

    public function __construct(){
        $this->PlusUserMod = new \App\Models\PlusUser();   
    }

    public function register(Request $request)
    {
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        //Validate data      
        $data = $request->only('first_name', 'last_name', 'email', 'mobile','smscode','smsCodeExpriration','insetTime','lastupdate','status','taxon_status','password');
        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email'     => 'required|email',
            'mobile'    => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        extract($request->all());
        //Request is valid, create new user
        $fname = !empty($first_name) ? $first_name : "";
        $lname = !empty($last_name) ? $last_name : "";
        $fullname =  $fname." ".$lname;

        /** If email already exists in database return error message */
        $isEmailAlreadyExists = $this->PlusUserMod->getUserByEmail($email);
        if(!empty($isEmailAlreadyExists)){
           // return $this->sendError(__("messages.user.email_already_registerd", ["email" => $email]));
        }

        /** If phone already exists in database return error message */ 
        $getUserByPhone = $this->PlusUserMod->getUserByPhone($mobile);
        if(!empty($getUserByPhone)){
            //return $this->sendError(__("messages.user.phone_already_exists", ["phone" => $mobile]));
        }

        $otp = substr(rand(100999,1000099990), 0, 5);
        $newDateTime = Carbon::now()->addMinutes(4);

        /*try{
            $smsResponse = $this->sendCode($mobile, $email, $otp, "SMS");
			return $this->sendResponse($smsResponse, __("messages.user.registered"));
        }catch(Exception $e){

            return $this->sendError($e, __("messages.error"));
        }*/

        $user = PlusUser::create([
            'full_name' => $fullname,
            'first_name' => $fname,
            'last_name' => $lname,
            'email'     => $email,
            'mobile'    => $mobile,
            'smscode'   => $otp,
            'smsCodeExpriration' => $newDateTime,
            'insetTime' => !empty($insetTime) ? $insetTime : "",
            'lastupdate'=> Carbon::now(),
            'status'    => 'disabled',
            'taxon_status' => !empty($taxon_status) ? $taxon_status : ""
        ]);

        $smsResponse = $this->sendCode($mobile, $email, $otp, "SMS");

        $user->smsResponse = $smsResponse;
        //User created, return success response
        return $this->sendResponse($user, __("messages.user.registered"));

    }
 
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'otp' => 'required|string|min:5|max:5'
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
 
    public function emailOtp(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        extract($request->all());
        $otp = substr(rand(100999,1000099990), 0, 5);
        $newDateTime = Carbon::now()->addMinutes(4);

        $isEmailExists = $this->PlusUserMod->getUserByEmail($email);

        if(empty($isEmailExists)){
            return $this->sendError($isEmailExists, __("messages.user.user_not_found", ["email" => $email]));
        }

        $res = $this->PlusUserMod->insertNewOtp($isEmailExists->id, $otp, $newDateTime);

        if($res){

            try{
                /*$details = [
                    'title' => '+Plus Login/Verify OTP',
                    'body' => 'Please use '.$otp.' to login/Verfy your Identity',
                    'otp' => $otp
                ];
            
                \Mail::to($email)->send(new \App\Mail\OtpEmail($details));*/

            }catch(Exception $e){

            }

            $mobile = $isEmailExists->mobile;

            $smsResponse = $this->sendCode($mobile, $email, $otp, "EMAIL");
            //dd($smsResponse);
            // /$user->smsResponse = $smsResponse;

            return $this->sendResponse($res, __("messages.user.otp_email_sent", ["email" => $email]));
        }else{
            return $this->sendError(__("messages.something_wrong"));
        }

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

    public function verifyOtp(Request $request){

        //valid credential
        $validator = Validator::make($request->only('otp'), [
            'otp' => 'required|string|min:5|max:5'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        extract($request->all());
        
        $user = $this->PlusUserMod->getUserByOTP($otp);
        if($user){
            $user->update(["email_verified_at", Carbon::now()]);
            $myTTL = 300; //minutes
            JWTAuth::factory()->setTTL($myTTL);
            $token = JWTAuth::fromUser($user);
            return $this->sendResponse(compact('token', 'user'), __("messages.user.otp_verified"));
        }else{
            return $this->sendError(__("messages.user.otp_not_matched"));
        }
        
    }
    
    public function updateRooms(Request $request){

        //valid credential
        $validator = Validator::make($request->only('rooms'), [
            'rooms' => 'required|min:1|max:3'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        extract($request->all());
        $user = $this->getCurrentUser();

        if(!empty($user->id)){

            $data = array("rooms" => $rooms);
            $this->PlusUserMod->updateUser($user->id, $data);

            return $this->sendResponse($rooms, __("messages.user.set_rooms", ["rooms" => $rooms]));

        }else{
            return $this->sendError(__("messages.user.not_set_rooms"));
        }

    }
	
	public function updateUserProfile(Request $request){

        //valid credential
        $user = $this->getCurrentUser();
        $validator = Validator::make($request->only('first_name', 'last_name', 'email', 'mobile', 'rooms'), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'unique:plus_users,email,'.$user->id,
            'mobile' => 'required',
            'rooms' => 'required',
        ]);


        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError($validator->messages());
        }

        extract($request->all());
        

        if(!empty($user->id)){

            $data = array('first_name' => $first_name, 'last_name' => $last_name, 'full_name' => $first_name." ".$last_name, 'email' => $email, 'rooms' => $rooms, 'mobile' => $mobile);
            $updated = $this->PlusUserMod->updateUser($user->id, $data);

            return $this->sendResponse($updated, __("messages.user.user_updated"));

        }else{
            return $this->sendError(__("messages.something_wrong"));
        }

    }
}
