<?php

namespace App\Http\Controllers;

use App\Helpers\Mpesa;
use App\Models\User;
use App\Models\Contribution;
use App\Models\Penalty;
use App\Models\Investment;
use App\Models\Vote;
use App\Models\Payment;
use App\Models\AdminLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->query('per_page') ?? 50;

        $payments = Payment::latest()->paginate($per_page);

        return Inertia::render('Payments/Index', ['payments' => $payments]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string|max:255',
            'reference_id' => 'required|uuid',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|date'
        ]);

        $payment = Payment::create($validated);
        return Inertia::render('Payments/Show', ['payment' => $payment]);
    }

    public function stkPush(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'phone' => 'required|string|max:12',
            'amount' => 'required|numeric|min:1',
            'callback' => 'required|url',
            'account_number' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }

        $phone = '254' . substr($request->phone, -9);
        $amount = $request->amount;
        $callback = $request->callback;
        $account_number = $request->account_number;
        $remarks = $request->remarks ?? 'Payment';

        $url = Mpesa::stkPushUrl();
        $token = Mpesa::accessToken();
        $password = Mpesa::password();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token));
        $curl_post_data = [
            'BusinessShortCode' => config('services.mpesa.shortcode'),
            'Password' => $password,
            'Timestamp' => Carbon::now()->format('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => config('services.mpesa.shortcode'),
            'PhoneNumber' => $phone,
            'CallBackURL' => $callback,
            'AccountReference' => $account_number,
            'TransactionDesc' => $remarks,
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);

        $responseObj = json_decode($curl_response);
        $response_details = [
            "merchant_request_id" => $responseObj->MerchantRequestID ?? null,
            "checkout_request_id" => $responseObj->CheckoutRequestID ?? null,
            "response_code" => $responseObj->ResponseCode ?? null,
            "response_desc" => $responseObj->ResponseDescription ?? null,
            "customer_msg" => $responseObj->CustomerMessage ?? null,
            "phone" => $phone,
            "amount" => $amount,
            "remarks" => $remarks
        ];

        return $response_details;
    }
}
