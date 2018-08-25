<?php
namespace App\Helpers;

class Functions
{
    private static $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";
    
    public static function getAuth()
    {
        $seed = date('c');
        $tranKey = env('PSE_TRANSKEY');
        $hashString = sha1($seed . $tranKey, false);
        return [
            'login' => env('PSE_TRANSLOGIN'),
            'tranKey' => $hashString,
            'seed' => $seed
        ];
    }

    public static function getClient()
    {
        return new \SoapClient(self::$wsdl, ["trace" => true]);
    }

   

}