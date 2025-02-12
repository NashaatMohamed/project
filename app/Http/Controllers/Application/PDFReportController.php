<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class PDFReportController extends Controller
{
    /**
     * Get Customer Sales Report Pdf
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function customer_sales(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();

        // Fetch Customers with Invoices
        $from = Carbon::createFromFormat('Y-m-d', isset($request->from) ? $request->from : Carbon::now()->format('Y-m-d'));
        $to = Carbon::createFromFormat('Y-m-d', isset($request->to) ? $request->to : Carbon::now()->addMonth()->format('Y-m-d'));

        $customers = Customer::with(['invoices' => function ($query) use ($from, $to) {
            $query->whereBetween(
                'invoice_date',
                [$from->format('Y-m-d'), $to->format('Y-m-d')]
            );
        }])->where('company_id', $company->id)->get();

        // Total Amount
        $totalAmount = 0;
        foreach ($customers as $customer) {
            $customerTotalAmount = 0;
            foreach ($customer->invoices as $invoice) {
                $customerTotalAmount += $invoice->total;
            }
            $customer->totalAmount = $customerTotalAmount;
            $totalAmount += $customerTotalAmount;
        }

        $pdf = PDF::loadView('pdf.reports.customer_sales', [
            'company' => $company,
            'from' => $from,
            'to' => $to,
            'customers' => $customers,
            'totalAmount' => $totalAmount,
        ]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download('CUSTOMER_SALES_REPORT.pdf');
        } else if($request->has('csv')) {
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=CUSTOMER_SALES_REPORT.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array('Customer', 'Invoice', 'Due Date', 'Total');
            $callback = function() use($customers, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
    
                foreach ($customers as $customer) {
                    foreach ($customer->invoices as $invoice) {
                        $row['Customer']  = $customer->display_name;
                        $row['Invoice']    = $invoice->invoice_number;
                        $row['Due Date']  = $invoice->due_date;
                        $row['Total']    = $invoice->currency_code . ' ' . number_format($invoice->total / 100, 2);
                    
                        fputcsv($file, array($row['Customer'], $row['Invoice'], $row['Due Date'], $row['Total']));
                    }
                }
    
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        } else {
            return $pdf->stream('CUSTOMER_SALES_REPORT.pdf');
        }
    }

    /**
     * Get Product Sales Report Pdf
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function product_sales(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();

        // Fetch Customers with Invoices
        $from = Carbon::createFromFormat('Y-m-d', isset($request->from) ? $request->from : Carbon::now()->format('Y-m-d'));
        $to = Carbon::createFromFormat('Y-m-d', isset($request->to) ? $request->to : Carbon::now()->addMonth()->format('Y-m-d'));

        // Products
        $products = Product::with(['invoice_items' => function ($query) use ($from, $to) {
            $query->whereHas('invoice', function ($query) use ($from, $to) {
                $query->whereBetween(
                    'invoice_date',
                    [$from->format('Y-m-d'), $to->format('Y-m-d')]
                );
            });
        }])->where('company_id', $company->id)->get();

        // Total Amount
        $totalAmount = 0;
        foreach ($products as $product) {
            $product->totalAmount = collect($product->invoice_items)->sum('total');
            $totalAmount += $product->totalAmount;
        }

        $pdf = PDF::loadView('pdf.reports.product_sales', [
            'company' => $company,
            'from' => $from,
            'to' => $to,
            'products' => $products,
            'totalAmount' => $totalAmount,
        ]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download('PRODUCT_SALES_REPORT.pdf');
        } else if($request->has('csv')) {
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=PRODUCT_SALES_REPORT.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array('Product', 'Total Sales');
            $callback = function() use($products, $company, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
    
                foreach ($products as $product) {
                    $row['Product'] = $product->name;
                    $row['Total Sales'] = $company->currency->short_code . ' ' . number_format($product->totalAmount / 100, 2);
                
                    fputcsv($file, array($row['Product'], $row['Total Sales']));
                }
    
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        } else {
            return $pdf->stream('PRODUCT_SALES_REPORT.pdf');
        }
    }

    /**
     * Get Profit & Loss Report Pdf
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function profit_loss(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();

        // Fetch Customers with Invoices
        $from = Carbon::createFromFormat('Y-m-d', isset($request->from) ? $request->from : Carbon::now()->format('Y-m-d'));
        $to = Carbon::createFromFormat('Y-m-d', isset($request->to) ? $request->to : Carbon::now()->addMonth()->format('Y-m-d'));

        // Invoices
        $invoices_total = Invoice::from($from)->to($to)->paid()->sum('total');

        // Expense Categories
        $expense_categories = ExpenseCategory::with(['expenses' => function ($query) use ($from, $to) {
            $query->whereBetween(
                'expense_date',
                [$from->format('Y-m-d'), $to->format('Y-m-d')]
            );
        }])->where('company_id', $company->id)->get();

        // Total Expenses
        $total_loss = 0;
        foreach ($expense_categories as $expense_category) {
            // Add Report Items 
            $expense_category->total_expense = collect($expense_category->expenses)->sum('amount');
            $total_loss += $expense_category->total_expense;
        }
        
        $pdf = PDF::loadView('pdf.reports.profit_loss', [
            'company' => $company,
            'from' => $from,
            'to' => $to,
            'invoices_total' => $invoices_total,
            'expense_categories' => $expense_categories,
            'total_loss' => $total_loss,
        ]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download('PROFIT_LOSS_REPORT.pdf');
        } else if($request->has('csv')) {
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=PROFIT_LOSS_REPORT.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array('Expense Category', 'Total Expense');
            $callback = function() use($expense_categories, $company, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
    
                foreach ($expense_categories as $expense_category) {
                    $row['Expense Category'] = $expense_category->name;
                    $row['Total Expense'] = $company->currency->short_code . ' ' . number_format($expense_category->total_expense / 100, 2);
                
                    fputcsv($file, array($row['Expense Category'], $row['Total Expense']));
                }
    
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        } else {
            return $pdf->stream('PROFIT_LOSS_REPORT.pdf');
        }
    }

    /**
     * Get Profit & Expenses Pdf
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function expenses(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();

        // Fetch Customers with Invoices
        $from = Carbon::createFromFormat('Y-m-d', isset($request->from) ? $request->from : Carbon::now()->format('Y-m-d'));
        $to = Carbon::createFromFormat('Y-m-d', isset($request->to) ? $request->to : Carbon::now()->addMonth()->format('Y-m-d'));

        // Expense Categories
        $expense_categories = ExpenseCategory::with(['expenses' => function ($query) use ($from, $to) {
            $query->whereBetween(
                'expense_date',
                [$from->format('Y-m-d'), $to->format('Y-m-d')]
            );
        }])->where('company_id', $company->id)->get();

        // Total Expenses
        $total_loss = 0;
        foreach ($expense_categories as $expense_category) {
            // Add Report Items 
            $expense_category->total_expense = collect($expense_category->expenses)->sum('amount');
            $total_loss += $expense_category->total_expense;
        }
        
        $pdf = PDF::loadView('pdf.reports.expenses', [
            'company' => $company,
            'from' => $from,
            'to' => $to,
            'expense_categories' => $expense_categories,
            'total_loss' => $total_loss,
        ]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download('EXPENSES_REPORT.pdf');
        } else if($request->has('csv')) {
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=EXPENSES_REPORT.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array('Expense Category', 'Total Expense');
            $callback = function() use($expense_categories, $company, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
    
                foreach ($expense_categories as $expense_category) {
                    $row['Expense Category'] = $expense_category->name;
                    $row['Total Expense'] = $company->currency->short_code . ' ' . number_format($expense_category->total_expense / 100, 2);
                
                    fputcsv($file, array($row['Expense Category'], $row['Total Expense']));
                }
    
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        } else {
            return $pdf->stream('EXPENSES_REPORT.pdf');
        }
    }

    /**
     * Get Vendor Report Pdf
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function vendors(Request $request)
    {
        $user = $request->user();
        $company = $user->currentCompany();

        // Fetch Customers with Invoices
        $from = Carbon::createFromFormat('Y-m-d', isset($request->from) ? $request->from : Carbon::now()->format('Y-m-d'));
        $to = Carbon::createFromFormat('Y-m-d', isset($request->to) ? $request->to : Carbon::now()->addMonth()->format('Y-m-d'));

        // Vendors
        $vendors = Vendor::with(['expenses' => function ($query) use ($from, $to) {
            $query->whereBetween(
                'expense_date',
                [$from->format('Y-m-d'), $to->format('Y-m-d')]
            );
        }])->where('company_id', $company->id)->get();

        // Total Expenses
        $total_loss = 0;
        foreach ($vendors as $vendor) {
            // Add Report Items 
            $vendor->total_expense = collect($vendor->expenses)->sum('amount');
            $total_loss += $vendor->total_expense;
        }
        
        $pdf = PDF::loadView('pdf.reports.vendor_expenses', [
            'company' => $company,
            'from' => $from,
            'to' => $to,
            'vendors' => $vendors,
            'total_loss' => $total_loss,
        ]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download('VENDOR_REPORT.pdf');
        } else if($request->has('csv')) {
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=VENDOR_REPORT.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
            $columns = array('Vendor', 'Total Expense');
            $callback = function() use($vendors, $company, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
    
                foreach ($vendors as $vendor) {
                    $row['Vendor'] = $vendor->display_name;
                    $row['Total Expense'] = $company->currency->short_code . ' ' . number_format($vendor->total_expense / 100, 2);
                
                    fputcsv($file, array($row['Vendor'], $row['Total Expense']));
                }
    
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        } else {
            return $pdf->stream('VENDOR_REPORT.pdf');
        }
    }
}
