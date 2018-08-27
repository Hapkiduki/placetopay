<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Functions;
use SoapClient;
use App\Bank;
use App\Transaction;

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
        return view('transaction', [
            'bankInterface' => $bankInterface,
            'documentType' => $documentType,
            'banks' => $this->getBankList(),
            ]);
    }

    public function listTransactions()
    {
        $transactions = Transaction::orderBy('id', 'DESC')->paginate(10);
        return View('transaction_list', compact('transactions'));
    }

    private function getBankList()
    {
        $last_updated = substr(Bank::get()->max('updated_at'), 0, 10);
        $today = substr(today(), 0, 10);

        //echo "Tiempo actual es $today y la ultima fecha es $last_updated <br/>";
        if ($last_updated <! $today) {

            return Bank::pluck('bankName', 'bankCode')->toArray();
        }

        //die;
        try {
            $client = Functions::getClient();
            $banks = $client->getBankList(['auth' => Functions::getAuth()]);


            if (count($banks->getBankListResult->item) > 0) {
                # code...
                //return 'entra';
                Bank::whereNotNull('id')->delete();
            }

            /*echo "<pre>";
            print_r ($banks);
            echo "</pre>";
            die;*/
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
            $request->session()->flash('status', ['danger',$e->getMessage()]);
            return [];
        }
    }

    public function sendTransaction(Request $request)
    {
        $personParams = [
            'company' => 'Belt',
            'country' => 'CO',
        ];
        $ip = request()->ip();
        $params = [
            'returnURL' => url('resultTransaction'),
            'reference' => $ip.",".$request->input('payer.documentType').","
                .$request->input('payer.document'),
            'description' => 'pago PlaceToPay',
            'language' => 'ES',
            'currency' => 'COP',
            'totalAmount' => 2500.0,
            'taxAmount' => 0.0,
            'devolutionBase' => 0.0,
            'tipAmount' => 0.0,
            'ipAddress' => $ip,
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
        ];

        $params +=  $request->except('_token');
        $params['payer'] += $personParams;
        $params += ['buyer' => $params['payer']];
        $params += ['shipping' => $params['payer']];

        //return $params;

        try {
            $client = Functions::getClient();

            /*$params = [
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
            ];*/

            $transaction = $client->createTransaction([
                'auth' => Functions::getAuth(),
                'transaction' => $params
                ]);
                //1461482768 transaction id
                //1461343 transaction id
                //1461483264 transaction id
                if($transaction->createTransactionResult->returnCode != "SUCCESS"){
                    $request->session()->flash('status', ['danger',$transaction
                    ->createTransactionResult->responseReasonText]);
                    return back()->withInput();
                }
                session(['PSETransactionID' => $transaction->createTransactionResult->transactionID]);

                $response = $transaction->createTransactionResult;
                $trans = new Transaction;

                $trans->create((array)$response);
                return redirect($transaction->createTransactionResult->bankURL);
                //dd($transaction);
            } catch (Exception $e) {
                $request->session()->flash('status', ['danger',$e->getMessage()]);
                return back()->withInput();
            }
    }

    public function resultTransaction(Request $request) {
        $transactionID = session('PSETransactionID');
        $request->session()->forget('PSETransactionID');
        $response = $this->getTransactionInfo($transactionID);
        Transaction::where('transactionID', $transactionID)
        ->update([
            'transactionCycle' => $response->transactionCycle,
            'transactionState' => $response->transactionState,
            'responseCode' => $response->responseCode,
            'responseReasonCode' => $response->responseReasonCode,
            'responseReasonText' => $response->responseReasonText,
            ]);

            return view('transaction_detail', [
                'responseReasonText' => $response->responseReasonText,
                'transactionState' => $response->transactionState,
                'transactionID' => $transactionID,
                ]);
                //dd($transactionID);
    }

    private function getTransactionInfo($transactionID){
        $client = Functions::getClient();
        try{
            $response = $client->getTransactionInformation([
                'auth' => Functions::getAuth(),
                'transactionID' => $transactionID
                ]);
                //dd($response);
                return $response->getTransactionInformationResult;
            } catch (Exception $e) {

                $request->session()->flash('status', ['danger',$e->getMessage()]);
                //return back()->withInput();
            }
    }

}
