<?php

namespace App\Http\Controllers\Stripe;

use Exception;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Token;
use App\Http\Controllers\Controller;
use Dotenv\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Models\User;
class PaymentController extends Controller
{
    //



    public function createCustomer(Request $request)
    {

        // Validate the request
        // $request->validate([
        //     'stripeToken' => 'required|string',
        // ]);

        // Set the Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));



        try {
            // Create a new customer or update the existing customer
            $customer =  Customer::create([
                'email' => $request->email,
                'source' => $request->stripeToken,
            ]);
           
            $user = new User();
            $user->name ='';
            $user->email = $request->email;
            $user->password =  bcrypt($request->password);
            $user->role ='customer'; 
            $user->stripe_id = $customer->id;
            $user->save() ;

            return response()->json(['data'=>$customer,'message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function showUserCard(Request $request)
    {
        $stripe = new \Stripe\StripeClient('sk_test_51OEwiGAJYTcDHNxRQuQfZBiSTNY3lMqm2eVq8OvkOVEgQni68paiQR0XRBSHzQhceyZOYMyCQbzwMSLEo8nav6y400XzA3gcGv');
        return $stripe->customers->allPaymentMethods(
            'cus_P32xevvTdHmq57',
            ['type' => 'card']
        );
    }

    public function payment_intents(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_51OEwiGAJYTcDHNxRQuQfZBiSTNY3lMqm2eVq8OvkOVEgQni68paiQR0XRBSHzQhceyZOYMyCQbzwMSLEo8nav6y400XzA3gcGv');
        return $intent = \Stripe\PaymentIntent::create([
            'customer' => 'cus_P32xevvTdHmq57',
            'setup_future_usage' => 'off_session',
            'amount' => 1099,
            'currency' => 'usd',
            // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
            'automatic_payment_methods' => [
                'enabled' => 'true',
            ],
        ]);
    }

    public function generateCardToken(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
            $cardSource = $stripe->tokens->create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            return response()->json(['card_source' => $cardSource], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function saveCard(Request $request)
    {
        try {
            $customer_id = $request->customer_id;
            // Set your Stripe API key
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            // Add the card number to the customer
            $cardSource = $stripe->customers->createSource(
                $customer_id,
                [
                    'source' => $request->cardToken,
                ]
            );

            return response()->json(['card_source' => $cardSource], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function chargeUser(Request $request)
    {

        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $cardToken = $request->cardToken;
            $amount = $request->amount;
            $customer_id = $request->customer_id;
            $charge = $stripe->charges->create([
                'amount' => 100,
                'currency' => 'usd',
                'customer' => $customer_id,
                'source' => $cardToken,
            ]);

            return response()->json(['charge' => $charge], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
