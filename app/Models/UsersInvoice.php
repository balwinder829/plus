<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersInvoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public $timestamps = true;

    protected $fillable = ['id', 'user_id', 'foryear_id', 'invoice_type_id', 'paid', 'file_link', 'start_date', 'end_date', 'created_at', 'updated_at', 'insertdate']; 

    function insertInvoice($data){

        return UsersInvoice::insertGetId($data);
    }

    function InvoicesByUserId($user_id, $per_page, $order_by, $order){

        $query = UsersInvoice::where("user_id", $user_id);

        $query =  $query->orderBy($order_by, $order);

        if($per_page > 0){
           return $query->paginate($per_page);
        }
        return $query->get();
    }
}
