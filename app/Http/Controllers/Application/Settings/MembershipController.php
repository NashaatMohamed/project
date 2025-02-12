<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Order;
use Illuminate\Http\Request;
use PDF;

class MembershipController extends Controller
{
    /**
     * Display Membership Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $subscription = $currentCompany->subscription('main');
        $orders = Order::where('company_id', $currentCompany->id)->latest()->get();
        $dateFormat = CompanySetting::getSetting('date_format', $currentCompany->id);

        return view('application.settings.membership.index', [
            'subscription' => $subscription,
            'orders' => $orders,
            'dateFormat' => $dateFormat,
        ]);
    }

    /**
     * Display Invoice for Order
     *
     * @return \Illuminate\Http\Response
     */
    public function order_invoice(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $order = Order::where('order_id', $request->order_id)->firstOrFail();
        $subscription = $currentCompany->subscription('main');
        $dateFormat = CompanySetting::getSetting('date_format', $currentCompany->id);

        $pdf = PDF::loadView('pdf.order.invoice', [
            'order' => $order,
            'subscription' => $subscription,
            'dateFormat' => $dateFormat,
        ]);
  
        //Render
        return $pdf->stream('invoice.pdf');
    }
}
