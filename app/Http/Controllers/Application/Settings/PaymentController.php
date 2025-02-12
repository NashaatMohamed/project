<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\Payment\Update;
use App\Models\WithdrawAccount;

class PaymentController extends Controller
{
    /**
     * Display Payment Settings Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get Payment Types by Company
        $payment_types = PaymentMethod::findByCompany($currentCompany->id)->paginate(15);

        // Get Withdraw Accounts by Company
        $withdraw_accounts = WithdrawAccount::findByCompany($currentCompany->id)->get();

        return view('application.settings.payment.index', [
            'payment_types' => $payment_types,
            'withdraw_accounts' => $withdraw_accounts
        ]);
    }

    /**
     * Update the Payment Settings
     *
     * @param \App\Http\Requests\Application\Settings\Payment\Update $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Update each settings in database
        foreach ($request->validated() as $key => $value) {
            $currentCompany->setSetting($key, $value);
        } 

        session()->flash('alert-success', __('messages.payment_settings_updated'));
        return redirect()->route('settings.payment', ['company_uid' => $currentCompany->uid]);
    }
}
