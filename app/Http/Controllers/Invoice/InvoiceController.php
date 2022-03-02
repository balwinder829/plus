<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\BaseController;
use JWTAuth;
use App\Models\PlusUser;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Traits\UsersTrait;

class InvoiceController extends BaseController
{
    use UsersTrait;

    protected $PlusUserMod, $UsersInvoice;

    public function __construct(){
        $this->PlusUserMod = new \App\Models\PlusUser();   
        $this->UsersInvoice = new \ App\Models\UsersInvoice();   
    }

    public function AddInvoice(Request $request){

        $validator = Validator::make($request->all(), 
        [ 
            'amount' => 'required|min:1',
            'document_name' => 'required|string',
            'document' => 'required|mimes:jpeg,png|max:2048',
        ]);   

        if ($validator->fails()) {          
            return $this->sendError($validator->messages());                        
        }  

        extract($request->all());

        $user = $this->getCurrentUser();
             
        //store file into document folder
        $filePath = $request->file("document")->store('public/documents/'.$user->id);
        //"document_name" => $document_name
        $toBeInserted = array("user_id" => $user->id, "paid" => $amount, "file_link" => $filePath);
        
        $res = $this->UsersInvoice->insertInvoice($toBeInserted);
        if($res > 0){
            return $this->sendResponse($res, __("messages.invoice.invoice_inserted"));
        }else{
            return $this->sendError(__("messages.something_wrong"));
        }
        

        //return $this->sendResponse($request->all(), __("messages.success"));
    }


    function getMyInvoices(Request $request){

        $user = $this->getCurrentUser();
        $per_page = !empty($per_page) ? $per_page : 5;
        $order_by = !empty($order_by) ? $order_by : "id";
        $order = !empty($order) ? $order : "asc";
        $invoices = $this->UsersInvoice->InvoicesByUserId($user->id, $per_page, $order_by, $order);
        return $this->sendResponse($invoices, __("message.success"));

    }
}
