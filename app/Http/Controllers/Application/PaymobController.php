<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\Gateways\PayMob;
use App\Traits\SavesInvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymobController extends Controller
{
    use SavesInvoicePayment;

    /**
     * Paymob Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function payment(Request $request)
    {
        // Plan
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        // Get Paymob Service
        $paymob = new PayMob();
        $auth = $paymob->auth();

        // Getting token has failed. Redirect back
        if (property_exists($auth, 'detail')) {
            session()->flash('alert-danger', __('messages.something_went_wrong'));
            return redirect()->back();
        }

        // Create order at paymob
        $paymob_order = $paymob->createOrder(
            $auth->token,
            $auth->profile->id,
            $plan->price,
            $plan->currency,
        );

        // Create payment key
        $payment_key = $paymob->createPaymentKey(
            $request->integration_id,
            $auth->token,
            $plan->price,
            $plan->currency,
            $paymob_order->id,

            // For billing data
            $request->user()->email,
            'ORD',
            $plan->id,
        );

        // Put redirect url to the user's session in order to return customer after the purchase
        session(['paymob_redirect_uri' => route('home')]);

        return view('application.order.paymob', [
            'plan' => $plan,
            'payment_key' => $payment_key,
            'iframe_id' => $request->iframe_id,
        ]);
    }

    /**
     * Paymob Wallet Payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */ 
    public function wallet_payment(Request $request)
    {
        // Get Paymob Service
        $paymob = new Paymob();

        // Put redirect url to the user's session in order to return customer after the purchase
        session(['paymob_redirect_uri' => route('home')]);

        // Generate mobile wallet payment
        $mobile_wallet_payment = $paymob->createMobileWalletPayment($request->wallet_number, $request->token);

        // Redirect to wallet service
        if (property_exists($mobile_wallet_payment, 'redirect_url') && $mobile_wallet_payment->redirect_url != '') {
            return redirect()->to($mobile_wallet_payment->redirect_url);
        }

        // Getting token has failed. Redirect back
        session()->flash('alert-danger', __('messages.please_check_your_wallet_number'));
        return redirect()->back();
    }

    /**
     * Processed callback from PayMob servers.
     * Save the route for this method in PayMob dashboard >> processed callback route.
     *
     * @param  \Illumiante\Http\Request  $request
     * @return  Response
     */
    public function webhook(Request $request)
    {
        // Statuses.
        $isSuccess  = filter_var($request['success'], FILTER_VALIDATE_BOOLEAN);
        $isVoided  = filter_var($request['is_voided'], FILTER_VALIDATE_BOOLEAN);
        $isRefunded  = filter_var($request['is_refunded'], FILTER_VALIDATE_BOOLEAN);
        $isPending  = filter_var($request['pending'], FILTER_VALIDATE_BOOLEAN);

        // Redirect if there exists payway_redirect_uri on session
        if ($request->session()->exists('paymob_redirect_uri')) {
            if ($isSuccess && !$isPending && !$isVoided && !$isRefunded) {
                session(['paymob-message' => __('messages.payment_successful', ['payment_number' => $request['order']])]);
            } else {
                session(['paymob-message' => __('messages.payment_failed', ['payment_number' => $request['order']])]);
            }
            return redirect(session('paymob_redirect_uri'));
        }

        // Statuses for post.
        $isSuccess  = filter_var($request['obj']['success'], FILTER_VALIDATE_BOOLEAN);
        $isVoided  = filter_var($request['obj']['is_voided'], FILTER_VALIDATE_BOOLEAN);
        $isRefunded  = filter_var($request['obj']['is_refunded'], FILTER_VALIDATE_BOOLEAN);
        $isPending  = filter_var($request['obj']['pending'], FILTER_VALIDATE_BOOLEAN);

        // Transcation succeeded.
        if ($isSuccess && !$isPending && !$isVoided && !$isRefunded) { 
            $type = $request['obj']['payment_key_claims']['billing_data']['first_name'];

            if ($type === 'INV') {

                $invoice = Invoice::findByUid($request['obj']['payment_key_claims']['billing_data']['last_name']);
                $transaction_id = 'TR-'.$request['obj']['id'];
                $this->savePayment($invoice, strtoupper($request['obj']['source_data']['type']), $transaction_id);

            } else if ($type === 'ORD') {

                $user = User::where('email', $request['obj']['payment_key_claims']['billing_data']['email'])->first();
                $currentCompany = $user->currentCompany();
                $plan = Plan::find($request['obj']['payment_key_claims']['billing_data']['last_name']);

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
                    'transaction_id' => $request['obj']['id'],
                    'payment_type' => strtoupper($request['obj']['source_data']['type']),
                    'payment_status' => 'COMPLETED',
                    'order_id' => $request['obj']['id'],
                ]);

                // Renew old one
                $currentSubscription = $currentCompany->subscription('main');
                if ($currentSubscription) {
                    if ($currentSubscription->plan_id === $plan->id) {
                        $currentSubscription->renew();
                    } else {
                        $currentSubscription->changePlan($plan);
                    }
                } else {
                    // or Create new subscription
                    $currentCompany->newSubscription('main', $plan);
                }
            }
        }

        return 'success';
    }
}
