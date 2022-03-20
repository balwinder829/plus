<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $table = "jobs";

    public $timestamps = true;
    
    public function updateUserJobInfo($data, $id){

        $job = Jobs::find($id);
        if($job){
            $data['updated_at'] = Jobs::freshTimestamp();
            return $job->update($data);
        }else{
            return false;
        }

    }

    public function insertUserJobInfo($data){
        $data['created_at'] = Jobs::freshTimestamp();
        $data['updated_at'] = Jobs::freshTimestamp();
        return Jobs::create($data);
    }
}
