<?php

namespace App\Http\Controllers;

use App\Models\Amount;
use App\Models\Customer;
use App\Models\ErrorResponse;
use App\Models\Payment;
use App\Models\Response;
use App\Models\Transaction;
use App\Models\User;
use Codevirtus\Payments\Pesepay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $pesepay;

    const BASE_URL = "https://api.pesepay.com/api/payments-engine";
    /**
     * Check payment status API endpoint
    */
    const CHECK_PAYMENT_URL = self::BASE_URL.'/v1/payments/check-payment';
    
    /**
     * Make Seamless payment API Endpoint
    */
    const MAKE_SEAMLESS_PAYMENT_URL = self::BASE_URL.'/v2/payments/make-payment';
    
    /**
     * Initiate payment API Endpoint
    */
    const INITIATE_PAYMENT_URL = self::BASE_URL.'/v1/payments/initiate';

    const ALGORITHM = 'AES-256-CBC';

    const INIT_VECTOR_LENGTH = 16;

    private $integrationKey;

    private $encryptionKey;

    public $resultUrl;
    
    public $returnUrl;

    public function __construct()
    {
        $url = "http://127.0.0.1:8000";
        // $this->pesepay = new Pesepay("1b68b1d9-ab23-494c-8102-003995ccb832", "ec700a0c0e4f4860959f29702491294e");
        // $this->pesepay = new Pesepay("8b001025-9e97-4ca6-9383-2cf038f8e87d", "e95952bb79cf46abad7e1634af54d2ed");
        $this->integrationKey = "8b001025-9e97-4ca6-9383-2cf038f8e87d";
        $this->encryptionKey = "e95952bb79cf46abad7e1634af54d2ed";
        // Set return and result urls
        $this->returnUrl = $url . "/payments/return";
        $this->resultUrl = $url . "/payments/result";
    }

    public function pay(Request $request)
    {

        $rate_usd = 1;
        $rate_zwl = 1;
        $amount = 24;
        $user = User::where('id', 1)->first();
        // $rate = PaymentRate::orderby('id', 'DESC')->first();



        // if ($rate) {
        //     $rate_usd = $rate->usd;
        //     $rate_zwl = $rate->zwl;
        // }

        // dd($user);
        if ($user) {
            $user_id = $user->id;
            Auth::loginUsingId($user_id, $remember = true);
            if (Auth::check()) {

                Log::channel('payment')->info('Payment', ['Status :' => 'Initiating Card Payment']);
                if ($request->currency == "USD") {
                    $amount *= $rate_usd;
                } else {
                    $amount *= $rate_zwl;
                }

                $rand = rand(1000, 9999);

                # Create a transaction
                $transaction = $this->pesepay->createTransaction($amount, "USD", 'Subscription', $rand);

                # Initiate the transaction
                $response = $this->pesepay->initiateTransaction($transaction);

                if ($response->success()) {
                    Log::channel('payment')->info('Payment', ['Status :' => 'Processing payment']);
                    # Save the reference number and/or poll url (used to check the status of a transaction)
                    $referenceNumber = $response->referenceNumber();
                    $pollUrl         = $response->pollUrl();
                    $redirectUrl     = $response->redirectUrl();
                    // $save = Payment::create([
                    //     'user_id'       => $user_id,
                    //     'type'          => "Online",
                    //     'order_number'  => $rand,
                    //     'amount'        => $amount,
                    //     'ref_number'    => $referenceNumber,
                    //     'poll_url'      => $pollUrl,
                    //     'payment_date'  => date('Y-m-d'),
                    //     'expiry_date'   => $this->get_next_date(date('Y-m-d')),
                    //     'status'        => 0,
                    //     'approved_by'   => 0,
                    // ]);

                    // if ($save) {
                    //     $admin_tokens = (array) User::where('role', 'AD')->get('fb_token');

                    //     $this->notification(
                    //         "New Payment Initiated",
                    //         "A new payment has been initiated from a user",
                    //         $admin_tokens
                    //     );

                    return redirect()->away($redirectUrl);
                    // } else {
                    //     return redirect('payments/error')->with('error', 'Failed to save transaction details');
                    // }

                } else {
                    # Get error message
                    $errorMessage = $response->message();
                    return redirect('payments/error')->with('error', 'Operation failed :' . $errorMessage);
                }
            } else {
                return redirect('payments/error')->with('error', 'Authorization failed. Please contact System Admin');
            }
        } else {
            return redirect('payments/error')->with('error', 'Faled to verify your account. Please try again or contact System Admin');
        }
    }


    /**
     * This method receives payment details from Pesepay - Payment status etc , update  your database accordingly
     */
    public function payment_result(Request $request)
    {
        $data = (object) $request;

        Log::channel('payment')->info(
            'Payment',
            [
                'Ref :' => $data->referenceNumber,
                'Status :' => $data->transactionStatus
            ]
        );

        // $payment = Payment::where('ref_number', $data->referenceNumber)->first();
        // $user = User::where('id', $payment->user_id)->first();

        if ($data->transactionStatus == "SUCCESS") {

            return response($data);

            // $payment_update = Payment::where('ref_number', $data->referenceNumber)->update([
            //     'status' => 1,
            // ]);

            // $user_update = User::where('id', $payment->user_id)->update(['payment_status' => 1]);

            // if ($payment_update && $user_update) {
            //     // Send update to admin && user
            //     $this->notification(
            //         "Payment Successful",
            //         "Your payment was successful. Please enjoy SafeSpace",
            //         $user->fb_token
            //     );
            // } else {
            //     // Send update to admin && user
            //     $this->notification(
            //         "Payment Failed",
            //         "We couldnt finish processing your payment. Please try again",
            //         $user->fb_token
            //     );
            // }
        } else {
            $this->notification(
                "Payment Failed",
                "We couldnt finish processing your payment. Please try again",
                // $user->fb_token
            );
        }
    }

    public function payment_return()
    {
        return view('modules.payments.return')->with('success', 'Your subscription being processed. We will update your account and notify you when the payment has been verified');
    }

    public function payment_error()
    {
        return view('modules.payments.error');
    }

    public function seamlessPayment(Request $request)
    {
        # Create the payment
        $payment = $this->createPayment('USD', 'PZW211', $request->email, $request->phoneNumber, $request->name);
        
        $requiredFields = [
            'fieldType' => 'TEXT',
            'customerPhoneNumber' => $request->phoneNumber,
            'displayName' => $request->phoneNumber,
            'optional' => false
        ];

        # Send Payment
        $response = $this->makeSeamlessPayment($payment, 'Online Transaction', $request->amount, $requiredFields, '7442');
        return $response;
        if ($response->success()) {
            # Save the reference number and/or poll url (used to check the status of a transaction)
            $referenceNumber = $response->referenceNumber();
            $pollUrl = $response->pollUrl();
        } else {
            #Get Error Message
            // $errorMessage = $response->message();
            // return response($errorMessage);
        }
    }

    public function makeSeamlessPayment($payment, $reasonForPayment, $amount, $requiredFields = null) {
        if ($this->resultUrl == null)
            throw new \InvalidArgumentException('Result url has not beeen specified.');
        
        $payment->resultUrl = $this->resultUrl;
        $payment->returnUrl = $this->returnUrl;
        $payment->reasonForPayment = $reasonForPayment;
        $payment->amountDetails = new Amount($amount, $payment->currencyCode);

        $payment->setRequiredFields($requiredFields);
        
        $encryptedData = $this->encrypt(json_encode($payment));
        
        $payload = json_encode(['payload'=>$encryptedData]);

        $response = $this->initCurlRequest("POST", self::MAKE_SEAMLESS_PAYMENT_URL, $payload);

        if ($response instanceof ErrorResponse) 
            return $response;

        $decryptedData = $this->decrypt($response['payload']);

        $jsonDecoded = json_decode($decryptedData, true);

        $referenceNumber = $jsonDecoded['referenceNumber'];
        $pollUrl = $jsonDecoded['pollUrl'];
        $paymentDate = date('Y-m-d HH:mm:ss');
        $user_id = 1;
        $type = $jsonDecoded['amountDetails']['currencyCode'];
        $order_number = '1000101';
        $amount = $jsonDecoded['amountDetails']['merchantAmount'];

        DB::table('payments')->insert([
            'user_id' => $user_id,
            'type' => $type,
            'order_number' => $order_number,
            'ref_number' => $referenceNumber,
            'poll_url' => $pollUrl,
            'payment_date' => $paymentDate,
            'amount' => $amount,
        ]);

        return $jsonDecoded;

        // return new Response($referenceNumber, $pollUrl);
    }

    private function initCurlRequest($requestType, $url, $payload = null) {
        $headers = [
            'Authorization: '.$this->integrationKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'curl'
        ]);

        if ($requestType == "POST") {
            curl_setopt($curl,CURLOPT_POSTFIELDS, $payload);
        }
        
        $response = curl_exec($curl);

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $result = json_decode($response, true);

        if ($status_code == 200) {
            return $result;
        } else {
            $message = $result['message'];
            return new ErrorResponse($message);
        }
    }

    /**
     * Encrypt input text by AES-256-CBC algorithm
     *
     * @param string $secretKey 16/24/32 -characters secret key
     * @param string $plainText Text for encryption
     *
     */

    private function encrypt($plainText)

    {
        // echo $this->encryptionKey;   
        try {
            // Check secret length
            if (!$this->isKeyLengthValid($this->encryptionKey)) {
                throw new \InvalidArgumentException("Secret key's length must be 128, 192 or 256 bits");
            }
            // Get initialization vector
            $initVector = substr($this->encryptionKey, 0, self::INIT_VECTOR_LENGTH);
            // Encrypt input text
            $raw = openssl_encrypt(
                $plainText,
                self::ALGORITHM,
                $this->encryptionKey,
                0,
                $initVector
            );
            // Return successful encoded object
            return $raw;
        } catch (\Exception $e) {
            // Operation failed
            echo $e;
            return new static(isset($initVector), null, $e->getMessage());
        }
    }

    /**
     * Decrypt encoded text by AES-256-CBC algorithm
     *
     * @param string $secretKey  16/24/32 -characters secret password
     * @param string $cipherText Encrypted text
     *
     */

    private function decrypt($cipherText)

    {
        try {
            // Check secret length
            if (!$this->isKeyLengthValid($this->encryptionKey)) {
                throw new \InvalidArgumentException("Secret key's length must be 128, 192 or 256 bits");
            }

            // Get raw encoded data
            $encoded = base64_decode($cipherText);

            // Slice initialization vector using the secret key
            $initVector = substr($this->encryptionKey, 0, self::INIT_VECTOR_LENGTH);

            // Trying to get decrypted text
            $decoded = openssl_decrypt(
                $encoded,
                self::ALGORITHM,
                $this->encryptionKey,
                OPENSSL_RAW_DATA,
                $initVector
            );

            //$initVector OPENSSL_RAW_DATA
            if ($decoded === false) {
                // Operation failed
                return new static(isset($initVector), null, openssl_error_string());
            }

            // Return successful decoded object
            return $decoded;

        } catch (\Exception $e) {
            // Operation failed
            return new static(isset($initVector), null, $e->getMessage());
        }

    }



    /**
     * Check that secret password length is valid
     *
     * @param string $secretKey 16/24/32 -characters secret password
     *
     * @return bool
     */

    private function isKeyLengthValid($secretKey)
    {
        $length = strlen($secretKey);
        return $length == 16 || $length == 24 || $length == 32;
    }

    
    public function createTransaction($amount, $currencyCode, $paymentReason, $merchantReference = null) {
        return new Transaction($amount, $currencyCode, $paymentReason, $merchantReference);
    }

    public function createPayment($currencyCode, $paymentMethodCode, $email, $phone = null, $name = null) {

        $customer = new Customer($email, $phone, $name);
        
        return new Payment($currencyCode, $paymentMethodCode, $customer);
    }

    public function index() {
        $payments = DB::table('payments')->get();
        return view('modules.payments.index', compact('payments'));
    }
}
