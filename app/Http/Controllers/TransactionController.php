<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Functions;
use SoapClient;
use App\Bank;


class TransactionController extends Controller
{
    public function index()
    {
    
        $bankInterface = [0 => 'PERSONAS', 1 => 'EMPRESAS'];
        $documentType = [
            'CC' => 'Cédula de ciudanía colombiana',
            'CE' => 'Cédula de extranjería',
            'TI' => 'Tarjeta de identidad',
            'PPN' => 'Pasaporte',
            'NIT' => 'Número de identificación tributaria',
            'SSN' => 'Social Security Number',
        ];
        return view('welcome', [
            'bankInterface' => $bankInterface,
            'documentType' => $documentType,
        ]);
    }

   /*public function getBankList()
    {
        $last_updated = Bank::get()->max('updated_at');
        $today = date('Y-m-d 00:00:00');

        if (!$last_updated < $today) {
            return Bank::all()->lists('bankCode', 'bankName');
        }

        $client = Functions::getClient();
        try {
            $banks = $client->getBankList(['auth' => Functions::getAuth()]);
            

            Bank::whereNotNull('id')->delete();
            foreach ($banks as $item) {
                $bank = new Bank;
                $bank->bankCode = $item->bankCode;
                $bank->bankName = $item->bankName;
                $bank->save();
            }

            return Bank::all()->lists('bankCode', 'bankName');

        } catch (Exception $e) {
            return $e;
        }
    }*/

    public function sendTransaction()
    {
        $client = Functions::getClient();
        try {
            $params = [
                'bankCode' => '1022',
                'bankInterface' => '0',
                'returnURL' => url('resultTransaction'),
                'reference' => 'YoTAS',//md5(date('YmdHis')),
                'description' => 'pago PlaceToPay',
                'language' => 'ES',
                'currency' => 'COP',
                'totalAmount' => 2500.0,
                'taxAmount' => 0.0,
                'devolutionBase' => 0.0,
                'tipAmount' => 0.0,
                'payer' => [
                    'document' => '1094963299',
                    'documentType' => 'CC',
                    'firstName' => 'Andrés Felipe',
                    'lastName' => 'Corrales Ortiz',
                    'company' => 'Belt',
                    'emailAddress' => 'tenkan.af@gmail.com',
                    'address' => 'Calle luna calle sol',
                    'city' => 'Armenia',
                    'province' => 'Quindío',
                    'country' => 'CO',
                    'phone' => '4442221',
                    'mobile' => '3336105711'
                ],
                'buyer' => [
                    'document' => '1094963299',
                    'documentType' => 'CC',
                    'firstName' => 'Andrés Felipe',
                    'lastName' => 'Corrales Ortiz',
                    'company' => 'Belt',
                    'emailAddress' => 'tenkan.af@gmail.com',
                    'address' => 'Calle luna calle sol',
                    'city' => 'Armenia',
                    'province' => 'Quindío',
                    'country' => 'CO',
                    'phone' => '4442221',
                    'mobile' => '3336105711'
                ],
                'shipping' => [
                    'document' => '1094963299',
                    'documentType' => 'CC',
                    'firstName' => 'Andrés Felipe',
                    'lastName' => 'Corrales Ortiz',
                    'company' => 'Belt',
                    'emailAddress' => 'tenkan.af@gmail.com',
                    'address' => 'Calle luna calle sol',
                    'city' => 'Armenia',
                    'province' => 'Quindío',
                    'country' => 'CO',
                    'phone' => '4442221',
                    'mobile' => '3336105711'
                ],
                'ipAddress' => request()->ip(),
                'userAgent' => $_SERVER['HTTP_USER_AGENT'],
            ];

            $transaction = $client->createTransaction([
                'auth' => Functions::getAuth(),
                'transaction' => $params
            ]);
            Session::put('PSETransactionID', $transaction->createTransactionResult->transactionID);
            dd($transaction);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function resultTransaction() {
        $transactionID = Session::get('PSETransactionID');
        dd($transactionID);
    }
    

}
