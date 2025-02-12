<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\PaymentAccount\Store;
use App\Http\Requests\Application\Settings\PaymentAccount\Update;
use App\Models\WithdrawAccount;

class PaymentAccountController extends Controller
{
    /**
     * Display the Form for Creating New Withdraw Account
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $withdraw_account = new WithdrawAccount();

        // Fill model with old input
        if (!empty($request->old())) {
            $withdraw_account->fill($request->old());
        }

        return view('application.settings.payment.accounts.create', [
            'withdraw_account' => $withdraw_account,
        ]);
    }
 
    /**
     * Store the Withdraw Account in Database
     *
     * @param \App\Http\Requests\Application\Settings\PaymentAccount\Store $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Create Withdraw Account and Store in Database
        WithdrawAccount::create([
            'company_id' => $currentCompany->id,
            'bank_id' => $request->bank_id,
            'iban' => $request->iban,
            'full_name' => $request->full_name,
            'additional_info' => $request->additional_info,
            'status' => 1,
        ]);
 
        session()->flash('alert-success', __('messages.withdraw_account_added'));
        return redirect()->route('settings.payment', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Form for Editing Withdraw Account
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $withdraw_account = WithdrawAccount::findOrFail($request->account);
 
        return view('application.settings.payment.accounts.edit', [
            'withdraw_account' => $withdraw_account,
        ]);
    }

    /**
     * Update the Withdraw Account
     *
     * @param \App\Http\Requests\Application\Settings\PaymentAccount\Update $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $withdraw_account = WithdrawAccount::findOrFail($request->account);
        
        // Update Withdraw Account in Database
        $withdraw_account->update([
            'bank_id' => $request->bank_id,
            'iban' => $request->iban,
            'full_name' => $request->full_name,
            'additional_info' => $request->additional_info,
        ]);
 
        session()->flash('alert-success', __('messages.withdraw_account_updated'));
        return redirect()->route('settings.payment', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Delete the Withdraw Account
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $withdraw_account = WithdrawAccount::findOrFail($request->account);
         
        // Delete Withdraw Account from Database
        $withdraw_account->delete();

        session()->flash('alert-success', __('messages.withdraw_account_deleted'));
        return redirect()->route('settings.payment', ['company_uid' => $currentCompany->uid]);
    }
}
