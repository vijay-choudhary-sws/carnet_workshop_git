<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Payment;

class PaymentController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function index()
    {
        return view('payment');
    }

    public function createOrFetchCustomer(Request $request)
    {
        // echo "<pre>";print_r($request->all());die;
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $existingCustomer = Customer::where('email', $request->email)
                ->orWhere('contact', $request->contact)
                ->first();

            if ($existingCustomer) {
                return response()->json([
                    'success' => true,
                    'customer_id' => $existingCustomer->razorpay_customer_id,
                    'message' => 'Customer already exists',
                ]);
            }

            $customer = $api->customer->create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
            ]);

            $savedCustomer = Customer::create([
                'razorpay_customer_id' => $customer->id,
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
            ]);

            return response()->json([
                'success' => true,
                'customer_id' => $customer->id,
                'message' => 'Customer created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {

            $order = $api->order->create([
                'amount' => $request->amount * 100,
                'currency' => 'INR',
                'receipt' => 'receipt_' . time(),
                'customer_id' => $request->customer_id,
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'amount' => $request->amount * 100,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function storePayment(Request $request)
    {
        $data = $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        try {

            $attributes = [
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ];

            $this->api->utility->verifyPaymentSignature($attributes);


            $payment = Payment::create([
                'payment_id' => $data['razorpay_payment_id'],
                'order_id' => $data['razorpay_order_id'],
                'status' => 'success',
                'amount' => $request->input('amount') / 100,
                'user_id' => $request->input('user'),
            ]);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
        ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function success(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $orderId = $request->query('order_id');
        $amount = $request->query('amount');
        $customParam = $request->query('custom_param');

        // return view('payment-success', [
        //     'payment_id' => $paymentId,
        //     'order_id' => $orderId,
        //     'amount' => $amount,
        //     'custom_param' => $customParam,
        // ]);

        return true;
    }


    public function failed()
    {
        return view('payment-failed');
    }
}
