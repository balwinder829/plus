<?php

return [

    /*
    |--------------------------------------------------------------------------
    | All Messages Language Lines
    |--------------------------------------------------------------------------
    |
    */
    'something_wrong' => 'Something went wrong!',
    'user' =>
        [
            'user_pass_not_matched' => 'These credentials do not match our records.',
            'registered' => 'Sucessfully Registered',
            'login_credentials_invalid' => 'Login credentials are invalid.',
            'logout' => 'User has been logged out',
            'cannot_logout' => 'Sorry, user cannot be logged out',
            'loggedin' => 'Logged in successfully',
            'otp_verified' => "OTP verified successfully",
            'otp_not_matched' => 'OTP not matched',
            'email_already_registerd' => ':email not available, Please try with another email ID',
            'user_not_found' => ':email not registerd with us!',
            'phone_already_exists' => ':phone already exists, Please try with another phone',
            'otp_email_sent' => 'OTP successfully sent on :email email',
            'set_rooms' => 'Set rooms :rooms successfully',
            'not_set_rooms' => 'Rooms not successfully set please try again',
            'user_updated' => 'Profile has been updated'
        ], 
        'invoice' => [
            "invoice_inserted" => "Invoice inserted successfully"
        ],
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

];
?>