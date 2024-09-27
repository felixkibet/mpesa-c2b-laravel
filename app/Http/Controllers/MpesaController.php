<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Exception;
use GuzzleHttp\Exception\RequestException;


class MpesaController extends Controller
{
    private $consumerKey;
    private $consumerSecret;
    private $shortCode;
    private $passkey;
    private $confirmation_url;
    private $validation_url;

    public function __construct()
    {
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->shortCode = env('MPESA_SHORTCODE');
        $this->passkey = env('MPESA_PASSKEY');
        $this->confirmation_url = env('MPESA_CONFIRMATION_URL');
        $this->validation_url = env('MPESA_VALIDATION_URL');
    }

    public function index()
    {
        return view("mpesa-form");
    }

    public function getAccessToken(Request $request)
    {
        try {
            $client = new Client();
            $response = $client->request("GET", "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials", [
                "auth" => [$this->consumerKey, $this->consumerSecret]
            ]);

            $token = json_decode($response->getBody()->getContents(), true)["access_token"];

            return response()->json(['token' => $token], 200);
        } catch (Exception $e) {
            Log::error('Token generation error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to generate token'], 400);
        }
    }

    public function registerUrls(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        try {
            $token = $request->token;
            Log::info('Generated Token:', ['token' => $token]);
            $client = new Client();

            $response = $client->request("POST", "https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl", [
                "headers" => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                "json" => [
                    'ShortCode' => $this->shortCode,
                    'ResponseType' => 'Completed',
                    'ConfirmationURL' => env('MPESA_TEST_URL') . '/confirm',
                    'ValidationURL' => env('MPESA_TEST_URL') . '/validate'
                ]
            ]);

            Log::info('Register URL Response:', ['response' => $response->getBody()->getContents()]);
            return response()->json(['success' => 'URLs successfully registered'], 200);
        } catch (RequestException $e) { // Use RequestException here
            if ($e->hasResponse()) {
                Log::error('Safaricom Register URL Error', ['error' => $e->getResponse()->getBody()->getContents()]);
            } else {
                Log::error('Safaricom Register URL Error', ['error' => $e->getMessage()]);
            }
            return response()->json(['error' => 'Failed to register URLs'], 400);
        }
    }


    public function simulateTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone_number' => 'required|regex:/^2547\d{8}$/', // Ensure correct phone number format
        ]);

        try {
            $token = $request->token;
            $client = new Client();

            $response = $client->request('POST', 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate', [
                "headers" => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                "json" => [
                    'ShortCode' => $this->shortCode,
                    'CommandID' => 'CustomerPayBillOnline',
                    'Amount' => $request->amount,
                    'Msisdn' => $request->phone_number,
                    'BillRefNumber' => 'Test'
                ]
            ]);

            Log::info('Simulate Transaction Response:', ['response' => $response->getBody()->getContents()]);
            return response()->json(['success' => 'Transaction simulated successfully'], 200);
        } catch (Exception $e) {
            Log::error('Simulate Transaction Error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to simulate transaction'], 400);
        }
    }

    public function validateTransaction(Request $request)
    {
        Log::info('Validation received:', $request->all());

        return response()->json([
            "ResultCode" => 0,
            "ResultDesc" => "Accepted"
        ]);
    }

    public function confirmTransaction(Request $request)
    {
        Log::info('Confirmation received:', $request->all());

        Transaction::create([
            'transaction_type' => $request->TransactionType,
            'trans_id' => $request->TransID,
            'trans_time' => \Carbon\Carbon::createFromFormat('YmdHis', $request->TransTime),
            'trans_amount' => $request->TransAmount,
            'business_short_code' => $request->BusinessShortCode,
            'bill_ref_number' => $request->BillRefNumber,
            'msisdn' => $request->MSISDN,
            'first_name' => $request->FirstName,
            'middle_name' => $request->MiddleName,
            'last_name' => $request->LastName,
            'org_account_balance' => $request->OrgAccountBalance,
        ]);

        return response()->json([
            "ResultCode" => 0,
            "ResultDesc" => "Success"
        ]);
    }
}
