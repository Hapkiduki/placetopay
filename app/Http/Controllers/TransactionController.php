<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Functions;

class TransactionController extends Controller
{
    public function getBankList() {
        $client = Functions::getClient();
        
    }
    
}
