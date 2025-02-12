<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WithdrawRequestController extends Controller
{
    /**
     * Display Super Admin Withdraw Requests Page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get Withdraw Requests and Filters
        $withdraw_requests = QueryBuilder::for(WithdrawRequest::class)
            ->allowedFilters([
                AllowedFilter::partial('status'),
            ])
            ->latest()
            ->paginate()
            ->appends(request()->query());

        return view('super_admin.withdraw_requests.index', [
            'withdraw_requests' => $withdraw_requests
        ]);
    }

    /**
     * Display the Form for Editing Withdraw Request
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $withdraw_request = WithdrawRequest::findOrFail($request->withdraw_request);
        $company = $withdraw_request->company;
        $commissions = $company->getCommissions();

        return view('super_admin.withdraw_requests.edit', [
            'withdraw_request' => $withdraw_request,
            'commissions' => $commissions,
        ]);
    }

    /**
     * Update the Withdraw Request in Database
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function decline(Request $request)
    {
        $admin = $request->user();

        $withdraw_request = WithdrawRequest::findOrFail($request->withdraw_request);
        
        // Update
        $withdraw_request->update([
            'status' => WithdrawRequest::STATUS_DECLINED,
            'declined_at' => now(),
            'declined_reason' => $request->declined_reason,
            'approved_by' => $admin->id,
        ]);

        // Send notification to company
        try {
            Notification::send($withdraw_request->requested_by_user, new GeneralNotification(__('messages.withdraw_request_was_declined'), $request->declined_reason));
        } catch (\Exception $th) {}

        session()->flash('alert-success', __('messages.request_declined'));
        return redirect()->route('super_admin.withdraw_requests');
    }

    /**
     * Update the Withdraw Request in Database
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request)
    {
        $admin = $request->user();

        $withdraw_request = WithdrawRequest::findOrFail($request->withdraw_request);

        // Decrease money from wallet
        $wallet = $withdraw_request->wallet;
        $wallet->withdraw($withdraw_request->amount_to_decrease, [
            'order_id' => uniqid(),
            'currency' => $wallet->name,
            'fee' => $withdraw_request->fee, 
            'invoice' => 'BANK: ***' . substr(optional($withdraw_request->withdraw_account)->iban, -4),
            'description' => 'withdraw_approved_description',
        ]);
        
        // Update
        $withdraw_request->update([
            'status' => WithdrawRequest::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        // Send notification to company
        try {
            Notification::send($withdraw_request->requested_by_user, 
                new GeneralNotification(
                    __('messages.withdraw_request_was_approved'), 
                    __('messages.withdraw_request_was_approved_content', ['amount' => $withdraw_request->amount_to_deposit . ' ' . $withdraw_request->wallet_currency])
                )
            );
        } catch (\Exception $th) {}

        session()->flash('alert-success', __('messages.request_approved'));
        return redirect()->route('super_admin.withdraw_requests');
    }
}
