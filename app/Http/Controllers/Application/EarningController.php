<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\User;
use App\Models\WithdrawAccount;
use App\Models\WithdrawRequest;
use App\Notifications\GeneralNotification;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class EarningController extends Controller
{
    /**
     * Display Earnings Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();
        $wallets = $company->wallets;

        // return view
        return view('application.earnings.index', [
            'tab' => 'index',
            'wallets' => $wallets,
        ]);
    }

    /**
     * Display Statements Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function statements(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();
        $statements = Transaction::where('payable_type', 'App\Models\Company')->where('payable_id', $company->id)->latest('id')->paginate();

        // return view
        return view('application.earnings.statements', [
            'tab' => 'statements',
            'statements' => $statements,
        ]);
    }

    /**
     * Display Withdrawal Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();
        $commissions = $company->getCommissions();
        $wallet = $company->getWallet($request->code);
        $currency = Currency::whereShortCode($wallet->name)->first();
 
        // Redirects if the wallet does not exists
        if (!$wallet || !$currency) return redirect()->route('earnings', ['company_uid' => $company->uid]);

        // Company withdraw accounts
        $withdraw_accounts = WithdrawAccount::findByCompany($company->id)->active()->get();

        // Share currency settings with view for price input
        share([
            'withdraw_currency' => $currency,
        ]);

        // Amount to deposit calculation
        $balance = number_format($wallet->balance/100, 2, '.', '');
        // Reduce percentage fee
        $percent = 0;
        $withdraw_percent_fee = number_format($commissions['withdraw_percent_fee'], 2, '.', '');
        if ($withdraw_percent_fee) {
            $percent = ($balance * $withdraw_percent_fee) / 100;
        }
        // Reduce fixed fee
        $fixed = 0;
        $withdraw_fixed_fee = number_format($commissions['withdraw_fixed_fee'], 2, '.', '');
        if ($withdraw_fixed_fee) {
            $fixed = $withdraw_fixed_fee;
        }
        $amount_to_deposit = number_format($balance - $percent - $fixed, 2, '.', '');

        // Check the amount to deposit value to be positive
        $error_msg = null;
        $withdraw_limit = number_format($commissions['withdraw_limit'], 2, '.', '');
        if ($amount_to_deposit < 0) $error_msg = __('messages.amount_to_deposit_is_below_zero');
        if ($balance < $withdraw_limit) $error_msg = __('messages.your_balance_is_under_withdraw_limit');

        // Check there is an existing withdraw request going
        $withdraw_request = WithdrawRequest::where('wallet_id', $wallet->id)->active()->first();
        if ($withdraw_request) $error_msg = __('messages.we_are_processing_your_withdraw');

        return view('application.earnings.withdraw', [
            'wallet' => $wallet,
            'withdraw_accounts' => $withdraw_accounts,
            'commissions' => $commissions,
            'amount_to_deposit' => $amount_to_deposit,
            'error_msg' => $error_msg,
            'withdraw_limit' => $withdraw_limit,
        ]);
    }

    /**
     * Store Withdrawal
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function withdraw_store(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();
        $commissions = $company->getCommissions();
        $wallet = $company->getWallet($request->code);
        $currency = Currency::whereShortCode($wallet->name)->first();

        // Find Withdraw account
        $withdraw_account = WithdrawAccount::find($request->account_id);
        if (!$withdraw_account) {
            session()->flash('alert-danger', __('messages.withdraw_account_required'));
            return redirect()->route('earnings.withdraw', ['company_uid' => $company->uid, 'code' => $request->code]);
        }

        // Redirects if the wallet does not exists
        if (!$wallet || !$currency) return redirect()->route('earnings', ['company_uid' => $company->uid]);

        // Amount to deposit calculation
        $balance = (float) number_format($wallet->balance/100, 2, '.', '');
        // Reduce percentage fee
        $percent = 0;
        $withdraw_percent_fee = (float) number_format($commissions['withdraw_percent_fee'], 2, '.', '');
        if ($withdraw_percent_fee > 0) {
            $percent = ($balance * $withdraw_percent_fee) / 100;
        }
        // Reduce fixed fee
        $withdraw_fixed_fee = (float) number_format($commissions['withdraw_fixed_fee'], 2, '.', '') ?? 0;

        // Amount to deposit
        $amount_to_deposit = (float) number_format($balance - $percent - $withdraw_fixed_fee, 2, '.', '');

        // Check the amount to deposit value to be positive
        $error_msg = null;
        $withdraw_limit = (float) number_format($commissions['withdraw_limit'], 2, '.', '');
        if ($amount_to_deposit < 0) $error_msg = __('messages.amount_to_deposit_is_below_zero');
        if ($balance < $withdraw_limit) $error_msg = __('messages.your_balance_is_under_withdraw_limit');

        // Check there is an existing withdraw request going
        $withdraw_request = WithdrawRequest::where('wallet_id', $wallet->id)->active()->first();
        if ($withdraw_request) $error_msg = __('messages.we_are_processing_your_withdraw');

        // Return if there are any errors
        if ($error_msg) {
            session()->flash('alert-danger', $error_msg);
            return redirect()->route('earnings.statements', ['company_uid' => $company->uid]);
        }

        // Create withdrawal request
        $fee = ($percent + $withdraw_fixed_fee) * 100;
        $withdraw_request = WithdrawRequest::create([
            'company_id' => $company->id,
            'withdraw_account_id' => $request->account_id,
            'wallet_id' => $wallet->id,
            'requested_by' => $user->id,
            'wallet_currency' => $wallet->name,
            'amount_to_deposit' => $amount_to_deposit,
            'amount_to_decrease' => $wallet->balance,
            'status' => WithdrawRequest::STATUS_REQUESTED,
            'notes' => $request->notes,
            'fee' => money((int) $fee, $wallet->name),
        ]);

        // Send Notification to Admins
        $notifyAdmins = User::role('admin')->get()->filter(function ($user) {
            return $user->getSetting('admin_get_withdraw_notification');
        });
        try {
            Notification::send($notifyAdmins, new GeneralNotification(__('messages.new_withdraw_request'), __('messages.new_withdraw_request_content')));
        } catch (\Exception $th) {}

        // Send notification to company
        try {
            Notification::send($user, new GeneralNotification(__('messages.withdraw_request_is_pending_approval'), __('messages.withdraw_request_is_pending_approval_content')));
        } catch (\Exception $th) {}

        session()->flash('alert-success', __('messages.withdraw_request_is_pending_approval'));
        return redirect()->route('earnings', ['company_uid' => $company->uid]);
    }
}
