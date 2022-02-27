<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class PlusUser extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password','mobile','rooms','smscode','smsCodeExpriration','insetTime','lastupdate','status','taxon_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'smscode'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getUserByOTP($otp){
       return  PlusUser::where("smscode", $otp)->first();
    }

    public function getUserByEmail($email){
        return  PlusUser::where("email", $email)->first();
    }

    public function getUserById($id){
        return  PlusUser::where("id", $id)->first();
    }

    public function getUserByPhone($phone){
        return  PlusUser::where("mobile", $phone)->first();
    }

    public function insertNewOtp($id, $otp, $newDateTime){

        $data = array('smscode' => $otp, 'smsCodeExpriration' => $newDateTime);
        return PlusUser::where( 'id', $id )
                    ->limit(1)
                    ->update($data);
    }

    public function updateUser($id, $data){

        return PlusUser::where( 'id', $id )
                    ->limit(1)
                    ->update($data);
    }

}
