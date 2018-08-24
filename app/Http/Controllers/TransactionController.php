<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Functions;
use SoapClient;
use App\Bank;

class TransactionController extends Controller
{
    public function getBankList() {
        $last_updated = Bank::get()->max('updated_at');
        $today = date('Y-m-d 00:00:00');
        
        if (!$last_updated < $today) {
            return Bank::all()->lists('bankCode', 'bankName');
        }
        
        $client = Functions::getClient();
        try{
            $banks = $client->getBankList(['auth' => Functions::getAuth()]);
            
            echo "<pre>";
            print_r ($banks);
            echo "</pre>";

            Bank::whereNotNull('id')->delete();
            foreach ($banks as $item) {
                $bank = new Bank;
                $bank->bankCode = $item->bankCode;
                $bank->bankName = $item->bankName;
                $bank->save();
            }

            return Bank::all()->lists('bankCode', 'bankName');
            
        }catch(Exception $e){
            return $e;
        }
    }
    
}
