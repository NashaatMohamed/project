<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Models\Plan;
use App\Models\SystemSetting;
use App\Services\Gateways\Mollie;
use App\Services\Gateways\PaypalExpress;
use App\Services\Gateways\Razorpay;
use App\Services\Gateways\Stripe;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display Plans
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function plans()
    {
        // Flash paymob message if exists
        if (session()->exists('paymob-message')) {
            session()->flash('alert-info', session('paymob-message'));
            session()->forget('paymob-message');
        }
        
        return view('application.order.plans');
    }

    /**
     * Display Checkout
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        // Find plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        $currentSubscription = $currentCompany->subscription('main');
 
        // If the plan is free subscribe user directly
        if ($plan->isFree()) {
            // First cancel current subscription if any
            if ($currentSubscription) {
                // If the plan is same as before prevent them to change
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                }
                // Change plan
                $currentSubscription->changePlan($plan);
                // Set trial to null
                $currentSubscription->update(['trial_ends_at' => null]); 
            } else { 
                // Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }

            // Redirect user to dashboard
            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => rand()]));
            return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
        }

        // If plan has free trial subscribe user for a time of free trial interval
        $isSubscribedBefore = $currentCompany->subscriptions->isNotEmpty();
        if ($plan->hasTrial() & !$isSubscribedBefore) {
            // Create new subscription
            $currentCompany->newSubscription('main', $plan, true);

            // Redirect user to dashboard
            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => rand()]));
            return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
        }

        // Razorpay Setting
        $razorpay_order = [];
        $razorpay_callbackUrl = '';
        $orderId = $currentCompany->id.strtoupper(str_replace('.', '', uniqid('', true)));
        if (SystemSetting::isRazorpayActive()) {
            // Get Razorpay Service
            $razorpay = new Razorpay(null, true);

            // Create Razorpay Order
            $razorpay_order = $razorpay->create([
                'receipt' => $orderId,
                'amount' => $plan->price,
                'currency' => $plan->currency
            ]);
 
            // Get callback url
            $razorpay_callbackUrl = route('order.payment.razorpay', ['plan' => $plan->slug, 'orderId' => $orderId]);
        }

        // Return checkout form
        return view('application.order.checkout', [
            'plan' => $plan,
            'orderId' => $orderId,
            'razorpay_order' => $razorpay_order,
            'razorpay_callbackUrl' => $razorpay_callbackUrl,
        ]);
    }

    /**
     * Display Order Processing
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function order_processing(Request $request)
    {
        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Order Id
        $orderId = $request->orderId;

        // Find order 
        $order = Order::where('order_id', $orderId)->where('payment_status', 'COMPLETED')->first();
 
        // Check the order is exists
        if ($order) {
            $plan = $order->plan;

            $currentSubscription = $currentCompany->subscription('main');
            // Renew old one
            if ($currentCompany->subscription('main')) {
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                } else {
                    $currentCompany->subscription('main')->changePlan($plan);
                }
            } else {
                // or Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }

            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $request->orderId]));
            return redirect()->route('home');
        }

        // Find failed order 
        $order_failed = Order::where('order_id', $orderId)->where('payment_status', 'FAILED')->first();

        // Check the order is exists
        if ($order_failed) {
            session()->flash('alert-danger', __('messages.payment_failed', ['payment_number' => $request->orderId]));
            return redirect()->route('order.plans');
        }

        return view('application.order.processing', ['orderId' => $orderId]);
    }

    /**
     * Paypal Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function paypal(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Get PaypalExpress Service (saas)
        $paypal = new PaypalExpress(null, true);
 
        // Make the Payment Request
        $response = $paypal->purchase([
            'amount' => $paypal->formatAmount($plan->price),
            'transactionId' => $request->orderId,
            'currency' => $plan->currency,
            'cancelUrl' => route('order.payment.paypal.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
            'returnUrl' => route('order.payment.paypal.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
        ]);

        // Redirect customer to Paypal website
        if ($response->isRedirect()) {
            $response->redirect();
        }
 
        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Paypal Complete Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function paypal_completed(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();
 
        // Get PaypalExpress Service (saas)
        $paypal = new PaypalExpress(null, true);

        // Complete the Payment Request
        $response = $paypal->complete([
            'amount' => $paypal->formatAmount($plan->price),
            'transactionId' => $request->orderId,
            'currency' => $plan->currency,
            'cancelUrl' => route('order.payment.paypal.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
            'returnUrl' => route('order.payment.paypal.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
        ]);
 
        // If payment was successful then save payment and return user to success page
        if ($response->isSuccessful()) {
            // Create and Save Payment to Database
            $order = Order::create([
                'company_id' => $currentCompany->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'card_number' => '',
                'card_exp_month' => '',
                'card_exp_year' => '',
                'price' => $plan->price,
                'currency' => $plan->currency,
                'transaction_id' => $response->getTransactionReference(),
                'payment_type' => 'PAYPAL',
                'payment_status' => 'COMPLETED',
                'order_id' => $request->orderId,
            ]);

            // Renew old one
            $currentSubscription = $currentCompany->subscription('main');
            if ($currentCompany->subscription('main')) {
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                } else {
                    $currentCompany->subscription('main')->changePlan($plan);
                }
            } else {
                // or Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }
     
            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $request->orderId]));
            return redirect()->route('home');
        }

        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Paypal Cancelled Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function paypal_cancelled(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        session()->flash('alert-danger', __('messages.payment_cancelled_paypal'));
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Stripe Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function stripe(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get Stripe Service
        $stripe = new Stripe(null, true);
 
        // Make the Payment Request
        $response = $stripe->purchase([
            'amount' => $stripe->formatAmount($plan->price),
            'currency' => $plan->currency,
            'paymentMethod' => $request->paymentMethodId,
            'description' => $plan->description, 
            'returnUrl' => route('order.payment.stripe.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
            'confirm' => true,
        ]);

        // If payment was successful then save payment and return user to success page
        if ($response->isSuccessful()) {
            // Create and Save Payment to Database
            $order = Order::create([
                'company_id' => $currentCompany->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'card_number' => '',
                'card_exp_month' => '',
                'card_exp_year' => '',
                'price' => $plan->price,
                'currency' => $plan->currency,
                'transaction_id' => $response->getPaymentIntentReference(),
                'payment_type' => 'STRIPE',
                'payment_status' => 'COMPLETED',
                'order_id' => $request->orderId,
            ]);

            // Renew old one
            $currentSubscription = $currentCompany->subscription('main');
            if ($currentCompany->subscription('main')) {
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                } else {
                    $currentCompany->subscription('main')->changePlan($plan);
                }
            } else {
                // or Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }

            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $request->orderId]));
            return redirect()->route('home');
        } 
        // If stripe needs additional redirect like 3d secure then redirect the customer
        else if ($response->isRedirect()) {
            $response->redirect();
        }
        
        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Stripe Complete Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function stripe_completed(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get Stripe Service
        $stripe = new Stripe(null, true);

        // Complete the Payment Request
        $response = $stripe->complete([
            'paymentIntentReference' => $request->payment_intent,
            'returnUrl' => route('order.payment.stripe.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
        ]);
 
        // If payment was successful then save payment and return user to success page
        if ($response->isSuccessful()) {
            // Create and Save Payment to Database
            $order = Order::create([
                'company_id' => $currentCompany->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'card_number' => '',
                'card_exp_month' => '',
                'card_exp_year' => '',
                'price' => $plan->price,
                'currency' => $plan->currency,
                'transaction_id' => $response->getPaymentIntentReference(),
                'payment_type' => 'STRIPE',
                'payment_status' => 'COMPLETED',
                'order_id' => $request->orderId,
            ]);

            // Renew old one or create one
            $currentSubscription = $currentCompany->subscription('main');
            if ($currentCompany->subscription('main')) {
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                } else {
                    $currentCompany->subscription('main')->changePlan($plan);
                }
            } else {
                // or Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }

            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $request->orderId]));
            return redirect()->route('home');
        }

        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Razorpay Payment Callback
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function razorpay(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();
 
        // Get Razorpay Service
        $razorpay = new Razorpay(null, true);

        // Check if the signature is correct or not
        $check = $razorpay->checkSignature($request->only('razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'));
 
        // If payment was successful then save payment and return user to success page
        if ($check) {
            // Create and Save Payment to Database
            $order = Order::create([
                'company_id' => $currentCompany->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'card_number' => '',
                'card_exp_month' => '',
                'card_exp_year' => '',
                'price' => $plan->price,
                'currency' => $plan->currency,
                'transaction_id' => $request->razorpay_order_id,
                'payment_type' => 'RAZORPAY',
                'payment_status' => 'COMPLETED',
                'order_id' => $request->orderId,
            ]);

            // Renew old one
            $currentSubscription = $currentCompany->subscription('main');
            if ($currentCompany->subscription('main')) {
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                } else {
                    $currentCompany->subscription('main')->changePlan($plan);
                }
            } else {
                // or Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }
            
            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $request->orderId]));
            return redirect()->route('home');
        }

        // Something else happend, go back to invoice details
        session()->flash('alert-danger', __('messages.error_while_proccessing_payment'));
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Mollie Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function mollie(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Auth User & Company
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get Mollie Service (saas)
        $mollie = new Mollie(null, true);
 
        // Make the Payment Request
        $response = $mollie->purchase([
            'amount' => $mollie->formatAmount($plan->price),
            'transactionId' => $request->orderId,
            'currency' => $plan->currency,
            'description' => $request->orderId,
            'notifyUrl' => route('order.payment.mollie.webhook', ['plan' => $plan->slug, 'orderId' => $request->orderId, 'company' => $currentCompany->uid]),
            'returnUrl' => route('order.payment.mollie.completed', ['plan' => $plan->slug, 'orderId' => $request->orderId]),
        ]);

        // Redirect customer to Mollie website
        if ($response->isRedirect()) {
            $response->redirect();
        }
 
        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('order.checkout', ['plan' => $plan->slug]);
    }

    /**
     * Mollie Webhook
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function mollie_webhook(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Company
        $currentCompany = Company::findByUid($request->company);

        // Payment Id
        $paymentId = $request->id;

        // Get Mollie Service (saas)
        $mollie = new Mollie(null, true);
 
        // Fetch mollie transaction
        $params = [
            'transactionReference' => $paymentId,
        ];

        $response = $mollie->complete($params);

        if ($response->isPaid()) {
            // Create and Save Payment to Database
            Order::create([
                'company_id' => $currentCompany->id,
                'user_id' => $currentCompany->owner_id,
                'plan_id' => $plan->id,
                'card_number' => '',
                'card_exp_month' => '',
                'card_exp_year' => '',
                'price' => $plan->price,
                'currency' => $plan->currency,
                'transaction_id' => $paymentId,
                'payment_type' => 'MOLLIE',
                'payment_status' => 'COMPLETED',
                'order_id' => $request->orderId,
            ]);

            // Renew old one
            $currentSubscription = $currentCompany->subscription('main');
            if ($currentCompany->subscription('main')) {
                if ($currentSubscription->plan_id === $plan->id) {
                    $currentSubscription->renew();
                    return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
                } else {
                    $currentCompany->subscription('main')->changePlan($plan);
                }
            } else {
                // or Create new subscription
                $currentCompany->newSubscription('main', $plan);
            }
        } else {
            Order::create([
                'company_id' => $currentCompany->id,
                'user_id' => $currentCompany->owner_id,
                'plan_id' => $plan->id,
                'card_number' => '',
                'card_exp_month' => '',
                'card_exp_year' => '',
                'price' => $plan->price,
                'currency' => $plan->currency,
                'transaction_id' => $paymentId,
                'payment_type' => 'MOLLIE',
                'payment_status' => 'FAILED',
                'order_id' => $request->orderId,
            ]);
        }

        return 'ok';
    }

    /**
     * Mollie Completed
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function mollie_completed(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Order Id
        $orderId = $request->orderId;

        // Find order 
        $order = Order::where('order_id', $orderId)->where('payment_status', 'COMPLETED')->first();

        // Check the order is exists
        if ($order) {
            $company = Company::where('id', $order->company_id)->first();
            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $request->orderId]));
            return redirect()->route('dashboard', ['company_uid' => $company->uid]); 
        }

        // Find failed order 
        $order_failed = Order::where('order_id', $orderId)->where('payment_status', 'FAILED')->first();

        // Check the order is exists
        if ($order_failed) {
            session()->flash('alert-danger', __('messages.payment_failed', ['payment_number' => $request->orderId]));
            return redirect()->route('order.checkout', ['plan' => $plan->slug]);
        }

        // Something else happend, go back to invoice details
        session()->flash('alert-info', __('messages.mollie_processing'));
        return redirect()->route('order.processing', ['orderId' => $orderId]);
    }
}
