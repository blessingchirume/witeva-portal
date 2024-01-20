<?php

namespace App\Http\Controllers;

use App\Models\User;
use Codevirtus\Payments\Pesepay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $pesepay;

    public function __construct()
    {
        $url = "http://127.0.0.1:8000";
        // $this->pesepay = new Pesepay("1b68b1d9-ab23-494c-8102-003995ccb832", "ec700a0c0e4f4860959f29702491294e");
        $this->pesepay = new Pesepay("8b001025-9e97-4ca6-9383-2cf038f8e87d", "e95952bb79cf46abad7e1634af54d2ed");

        // Set return and result urls
        $this->pesepay->returnUrl = $url . "/payments/return";
        $this->pesepay->resultUrl = $url . "/payments/result";
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

    public function init(Request $request)
    {

        $rate_usd = 1;
        $rate_zwl = 1;
        $amount = 24;
        $user = User::where('id', 1)->first();

        if ($user) {
            $user_id = $user->id;


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

    public function seamlessPayment()
    {
        # Create the payment
        $payment = $this->pesepay->createPayment('USD', 'PZW211', 'chirume37@gmail.com', '0783123519', 'Blessing Chirume');
        // dd($payment);
        $requiredFields = [
            'fieldType' => 'TEXT',
            'customerPhoneNumber' => '0783123519',
            'displayName' => '0783123519',
            'optional' => false
        ];

        # Send Payment
        $response = $this->pesepay->makeSeamlessPayment($payment, 'Online Transaction', 12, $requiredFields, '7442');
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
}
