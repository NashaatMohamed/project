<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display Dashboard Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();
   
        // Dashboard Stats
        $customersCount = Customer::findByCompany($company->id)->count();
        $invoicesCount = Invoice::findByCompany($company->id)->count();
        $estimatesCount = Estimate::findByCompany($company->id)->count();
        $totalDueAmount = Invoice::findByCompany($company->id)->active()->sum('due_amount');

        // Due Invoices and Estimates 
        $dueInvoices = Invoice::findByCompany($company->id)->unpaid()->nonDraft()->whereDate('due_date', '>=', Carbon::now())->take(5)->orderBy('due_date')->get();
        $dueEstimates = Estimate::findByCompany($company->id)->active()->take(5)->latest('expiry_date')->get();

        // Financial Year Starts-Ends
        $financialYearStarts = $company->getSetting('financial_month_starts');
        $financialYearEnds = $company->getSetting('financial_month_ends');

        // Create Carbon Instances from Financial Year
        $dateStarts = Carbon::now()->month($financialYearStarts)->startOfMonth();
        $dateEnds = Carbon::now()->month($financialYearEnds)->endOfMonth();

        // if the date ends is smaller than date start, add one year to date ends
        if($dateEnds->lt($dateStarts)){
            $dateEnds->addYear(1)->endOfMonth();
        }

        // Create Period from given dates
        $period = CarbonPeriod::since($dateStarts)->months(1)->until($dateEnds);

        // Arrays for Expenses Chart
        $expense_stats_label = [];
        $expense_stats = [];

        // Iterate over the Date Period 
        foreach ($period as $date) {
            // Add month as label
            $month = $date->format('M');
            array_push($expense_stats_label, $month);

            // Add Expense amount for current month
            $expense = Expense::findByCompany($company->id)
                ->get(['amount', 'expense_date'])
                ->whereBetween('expense_date', [$date->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')])
                ->sum('amount');
            array_push($expense_stats, $expense/100);
        }

        // Arrays for Income Chart
        $income_stats_label = [];
        $income_stats = [];

        // Iterate over the Date Period 
        foreach ($period as $date) {
            // Add month as label
            $month = $date->format('M');
            array_push($income_stats_label, $month);

            // Add Income amount for current month
            $income = Payment::findByCompany($company->id)
                ->get(['amount', 'payment_date', 'credit_note_id'])
                ->whereNull('credit_note_id')
                ->whereBetween('payment_date', [$date->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')])
                ->sum('amount');
            array_push($income_stats, $income/100);
        }

        // Flash paymob message if exists
        if (session()->exists('paymob-message')) {
            session()->flash('alert-info', session('paymob-message'));
            session()->forget('paymob-message');
        }

        // return dashboard view with params
        return view('application.dashboard.index', [
            'customersCount' => $customersCount,
            'invoicesCount' => $invoicesCount,
            'estimatesCount' => $estimatesCount,
            'totalDueAmount' => $totalDueAmount,
            'dueInvoices' => $dueInvoices,
            'dueEstimates' => $dueEstimates,
            'expense_stats_label' => $expense_stats_label,
            'expense_stats' => $expense_stats,
            'income_stats' => $income_stats,
            'currency_code' => $company->currency->short_code,
        ]);
    }
}
