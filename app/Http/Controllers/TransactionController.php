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
            'banks' => $this->getBankList(),
        ]);
    }

   public function getBankList()
    {
        $last_updated = Bank::get()->max('updated_at');
        $today = date('Y-m-d 00:00:00');

        if (!$last_updated < $today) {
            return Bank::pluck('bankName', 'bankCode')->toArray();
        }

        $client = Functions::getClient();
        try {
            $banks = $client->getBankList(['auth' => Functions::getAuth()]);
            

            if (count($banks->getBankListResult->item) > 0) {
                # code...
                Bank::whereNotNull('id')->delete();
            }
            
             /*echo "<pre>";
            print_r ($banks);
            echo "</pre>";
            die; */
            foreach ($banks->getBankListResult->item as $item) {
                if (strlen($item->bankCode) < 7) {
                    
                    $bank = new Bank;
                    $bank->bankCode = $item->bankCode;
                    $bank->bankName = $item->bankName;
                    $bank->save();
                }
            }

            return Bank::pluck('bankName', 'bankCode')->toArray();

        } catch (Exception $e) {
            return $e;
        }
    }

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
            //1461482768 transaction id
            //1461343 transaction id
            //1461483264 transaction id
            session(['PSETransactionID' => $transaction->createTransactionResult->transactionID]);

            return redirect($transaction->createTransactionResult->bankURL);
           // dd($transaction);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function resultTransaction(Request $request) {
        $transactionID = session('PSETransactionID');
        dd($transactionID);
    }
    

}
