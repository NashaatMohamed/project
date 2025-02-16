<?php

use App\Http\Controllers\Application\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\Application\Settings\VariationController;



//-----------------------------------------//
//             INSTALLER ROUTES            //
//-----------------------------------------//
Route::group(['namespace' => 'Installer'], function () {
    Route::get('/install', 'InstallController@welcome')->name('installer.welcome');
    Route::get('/install/requirements', 'InstallController@requirements')->name('installer.requirements');
    Route::get('/install/permissions', 'InstallController@permissions')->name('installer.permissions');
    Route::get('/install/environment', 'InstallController@environment')->name('installer.environment');
    Route::post('/install/environment/save', 'InstallController@save_environment')->name('installer.environment.save');
    Route::get('/install/database', 'InstallController@database')->name('installer.database');
    Route::get('/install/final', 'InstallController@finish')->name('installer.final');

    // Updated
    Route::get('/update', 'UpdateController@welcome')->name('updater.welcome');
    Route::get('/update/overview', 'UpdateController@overview')->name('updater.overview');
    Route::get('/update/database', 'UpdateController@database')->name('updater.database');
    Route::get('/update/final', 'UpdateController@finish')->name('updater.final');
});

// Static Pages
Route::get('/pages/{slug}', 'PageController@index')->name('pages');

// Landing
Route::get('/', 'HomeController@index')->name('home');
Route::get('/demo', 'HomeController@demo')->name('demo');
Route::get('/change-language/{locale}', 'HomeController@change_language')->name('change_language');

// Auth routes
Route::middleware(ProtectAgainstSpam::class)->group(function () {
    Auth::routes();
});
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// PDF Views
Route::get('/viewer/invoice/{invoice}/pdf', 'Application\PDFController@invoice')->name('pdf.invoice');
Route::get('/viewer/credit_note/{credit_note}/pdf', 'Application\PDFController@credit_note')->name('pdf.credit_note');
Route::get('/viewer/estimate/{estimate}/pdf', 'Application\PDFController@estimate')->name('pdf.estimate');
Route::get('/viewer/payment/{payment}/pdf', 'Application\PDFController@payment')->name('pdf.payment');

// Webhooks
Route::post('/order/checkout/{plan}/mollie/webhook', 'Application\OrderController@mollie_webhook')->name('order.payment.mollie.webhook');
Route::match(['get', 'post'], '/order/paymob/webhook', 'Application\PaymobController@webhook')->name('order.payment.paymob.webhook');

// Super Admin Panel
Route::group(['namespace' => 'SuperAdmin', 'prefix' => '/admin', 'middleware' => ['auth', 'super_admin']], function () {
    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('super_admin.dashboard');

    // Users
    Route::get('/users', 'UserController@index')->name('super_admin.users');
    Route::get('/users/create', 'UserController@create')->name('super_admin.users.create');
    Route::post('/users/store', 'UserController@store')->name('super_admin.users.store');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('super_admin.users.edit');
    Route::post('/users/{user}/edit', 'UserController@update')->name('super_admin.users.update');
    Route::get('/users/{user}/delete', 'UserController@delete')->name('super_admin.users.delete');
    Route::get('/users/{user}/impersonate', 'UserController@impersonate')->name('super_admin.users.impersonate');

    // Plans
    Route::get('/plans', 'PlanController@index')->name('super_admin.plans');
    Route::get('/plans/create', 'PlanController@create')->name('super_admin.plans.create');
    Route::post('/plans/store', 'PlanController@store')->name('super_admin.plans.store');
    Route::get('/plans/{plan}/edit', 'PlanController@edit')->name('super_admin.plans.edit');
    Route::post('/plans/{plan}/edit', 'PlanController@update')->name('super_admin.plans.update');
    Route::get('/plans/{plan}/delete', 'PlanController@delete')->name('super_admin.plans.delete');

    // Pages
    Route::get('/pages', 'PageController@index')->name('super_admin.pages');
    Route::get('/pages/create', 'PageController@create')->name('super_admin.pages.create');
    Route::post('/pages/store', 'PageController@store')->name('super_admin.pages.store');
    Route::get('/pages/{page}/edit', 'PageController@edit')->name('super_admin.pages.edit');
    Route::post('/pages/{page}/edit', 'PageController@update')->name('super_admin.pages.update');
    Route::get('/pages/{page}/delete', 'PageController@delete')->name('super_admin.pages.delete');

    // Subscriptions
    Route::get('/subscriptions', 'SubscriptionController@index')->name('super_admin.subscriptions');
    Route::get('/subscriptions/{subscription}/cancel', 'SubscriptionController@cancel')->name('super_admin.subscriptions.cancel');

    // Orders
    Route::get('/orders', 'OrderController@index')->name('super_admin.orders');

    // Languages
    Route::get('/languages', 'LanguageController@index')->name('super_admin.languages');
    Route::get('/languages/create', 'LanguageController@create')->name('super_admin.languages.create');
    Route::post('/languages/create', 'LanguageController@store')->name('super_admin.languages.store');
    Route::get('/languages/{language}/default', 'LanguageController@set_default')->name('super_admin.languages.set_default');
    Route::get('/languages/{language}/translations', 'LanguageTranslationController@index')->name('super_admin.languages.translations');
    Route::post('/languages/{language}', 'LanguageTranslationController@update')->name('super_admin.languages.translations.update');

    // Withdraw Requests
    Route::get('/withdraw-requests', 'WithdrawRequestController@index')->name('super_admin.withdraw_requests');
    Route::get('/withdraw-requests/{withdraw_request}/edit', 'WithdrawRequestController@edit')->name('super_admin.withdraw_requests.edit');
    Route::get('/withdraw-requests/{withdraw_request}/approve', 'WithdrawRequestController@approve')->name('super_admin.withdraw_requests.approve');
    Route::post('/withdraw-requests/{withdraw_request}/decline', 'WithdrawRequestController@decline')->name('super_admin.withdraw_requests.decline');

    // Banks
    Route::get('/banks', 'BankController@index')->name('super_admin.banks');
    Route::get('/banks/create', 'BankController@create')->name('super_admin.banks.create');
    Route::post('/banks/store', 'BankController@store')->name('super_admin.banks.store');
    Route::get('/banks/{bank}/edit', 'BankController@edit')->name('super_admin.banks.edit');
    Route::post('/banks/{bank}/edit', 'BankController@update')->name('super_admin.banks.update');
    Route::get('/banks/{bank}/delete', 'BankController@delete')->name('super_admin.banks.delete');

    // Settings
    Route::get('/settings/application', 'SettingController@application')->name('super_admin.settings.application');
    Route::post('/settings/application', 'SettingController@application_update')->name('super_admin.settings.application.update');

    Route::get('/settings/mail', 'SettingController@mail')->name('super_admin.settings.mail');
    Route::post('/settings/mail', 'SettingController@mail_update')->name('super_admin.settings.mail.update');

    Route::get('/settings/payment', 'SettingController@payment')->name('super_admin.settings.payment');
    Route::post('/settings/payment', 'SettingController@payment_update')->name('super_admin.settings.payment.update');

    Route::get('/settings/cron', 'SettingController@cron')->name('super_admin.settings.cron');

    Route::get('/settings/custom-css-js', 'SettingController@custom_css_js')->name('super_admin.settings.custom_css_js');
    Route::post('/settings/custom-css-js', 'SettingController@custom_css_js_update')->name('super_admin.settings.custom_css_js.update');

    Route::get('/settings/currencies', 'SettingController@currencies')->name('super_admin.settings.currencies');
    Route::get('/settings/currencies/{code}/enable', 'SettingController@currencies_enable')->name('super_admin.settings.currencies.enable');
    Route::get('/settings/currencies/{code}/disable', 'SettingController@currencies_disable')->name('super_admin.settings.currencies.disable');

    Route::get('/settings/theme/{theme}', 'ThemeSettingController@edit')->name('super_admin.settings.theme');
    Route::post('/settings/theme/{theme}', 'ThemeSettingController@update')->name('super_admin.settings.theme.update');
    Route::get('/settings/theme/{theme}/activate', 'ThemeSettingController@activate')->name('super_admin.settings.theme.activate');
});

// Customer Portal Routes
Route::group(['namespace' => 'CustomerPortal', 'prefix' => '/portal/{customer}', 'middleware' => ['customer_portal']], function () {
    // Dashboard
    Route::get('/', 'DashboardController@index');
    Route::get('/dashboard', 'DashboardController@index')->name('customer_portal.dashboard');

    // Invoices
    Route::get('/invoices', 'InvoiceController@index')->name('customer_portal.invoices');
    Route::get('/invoices/{invoice}', 'InvoiceController@show')->name('customer_portal.invoices.details');

    // Credit Notes
    Route::get('/credit-notes', 'CreditNoteController@index')->name('customer_portal.credit_notes');
    Route::get('/credit-notes/{credit_note}', 'CreditNoteController@show')->name('customer_portal.credit_notes.details');

    // PaypalExpress Checkout
    Route::post('/invoices/{invoice}/paypal/payment', 'Checkout\PaypalExpressController@payment')->name('customer_portal.invoices.paypal.payment');
    Route::get('/invoices/{invoice}/paypal/completed', 'Checkout\PaypalExpressController@completed')->name('customer_portal.invoices.paypal.completed');
    Route::get('/invoices/{invoice}/paypal/cancelled', 'Checkout\PaypalExpressController@cancelled')->name('customer_portal.invoices.paypal.cancelled');

    // Mollie Checkout
    Route::get('/invoices/{invoice}/mollie/payment', 'Checkout\MollieController@payment')->name('customer_portal.invoices.mollie.payment');
    Route::post('/invoices/{invoice}/mollie/webhook', 'Checkout\MollieController@webhook')->name('customer_portal.invoices.mollie.webhook');
    Route::get('/invoices/{invoice}/mollie/completed', 'Checkout\MollieController@completed')->name('customer_portal.invoices.mollie.completed');

    // Razorpay Checkout
    Route::get('/invoices/{invoice}/razorpay/checkout', 'Checkout\RazorpayController@checkout')->name('customer_portal.invoices.razorpay.checkout');
    Route::post('/invoices/{invoice}/razorpay/callback', 'Checkout\RazorpayController@callback')->name('customer_portal.invoices.razorpay.callback');

    // Stripe Checkout
    Route::get('/invoices/{invoice}/stripe/checkout', 'Checkout\StripeController@checkout')->name('customer_portal.invoices.stripe.checkout');
    Route::post('/invoices/{invoice}/stripe/payment', 'Checkout\StripeController@payment')->name('customer_portal.invoices.stripe.payment');
    Route::get('/invoices/{invoice}/stripe/completed', 'Checkout\StripeController@completed')->name('customer_portal.invoices.stripe.completed');

    // Paymob Checkout
    Route::get('/invoices/{invoice}/paymob/checkout', 'Checkout\PaymobController@checkout')->name('customer_portal.invoices.paymob.checkout');
    Route::post('/invoices/{invoice}/paymob/wallet', 'Checkout\PaymobController@wallet_payment')->name('customer_portal.invoices.paymob.wallet_payment');

    // Estimates
    Route::get('/estimates', 'EstimateController@index')->name('customer_portal.estimates');
    Route::get('/estimates/{estimate}', 'EstimateController@show')->name('customer_portal.estimates.details');
    Route::get('/estimates/{estimate}/mark/{status?}', 'EstimateController@mark')->name('customer_portal.estimates.mark');

    // Payment
    Route::get('/payments', 'PaymentController@index')->name('customer_portal.payments');
    Route::get('/payments/{payment}', 'PaymentController@show')->name('customer_portal.payments.details');
});

// Application Routes
Route::group(['namespace' => 'Application', 'prefix' => '/{company_uid}', 'middleware' => ['auth', 'dashboard']], function () {
    // Company Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Customers
    Route::get('/customers', 'CustomerController@index')->name('customers');
    Route::get('/customers/create', 'CustomerController@create')->name('customers.create');
    Route::post('/customers/create', 'CustomerController@store')->name('customers.store');
    Route::get('/customers/{customer}/details', 'CustomerController@details')->name('customers.details');
    Route::get('/customers/{customer}/edit', 'CustomerController@edit')->name('customers.edit');
    Route::post('/customers/{customer}/edit', 'CustomerController@update')->name('customers.update');
    Route::get('/customers/{customer}/delete', 'CustomerController@delete')->name('customers.delete');

    // Products & Services
    Route::get('/products', 'ProductController@index')->name('products');
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::post('/products/create', 'ProductController@store')->name('products.store');
    Route::get('/products/{product}/edit', 'ProductController@edit')->name('products.edit');
    Route::post('/products/{product}/edit', 'ProductController@update')->name('products.update');
    Route::get('/products/{product}/delete', 'ProductController@delete')->name('products.delete');
    Route::get('/products/{product}/show', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/update_variation', 'ProductController@updateVariation')->name('products.update_variation');
    

    // Invoices
    Route::get('/invoices/create', 'InvoiceController@create')->name('invoices.create');
    Route::post('/invoices/create', 'InvoiceController@store')->name('invoices.store');
    Route::get('/invoices/{invoice}/details', 'InvoiceController@show')->name('invoices.details');
    Route::get('/invoices/{invoice}/edit', 'InvoiceController@edit')->name('invoices.edit');
    Route::post('/invoices/{invoice}/edit', 'InvoiceController@update')->name('invoices.update');
    Route::get('/invoices/{invoice}/delete', 'InvoiceController@delete')->name('invoices.delete');
    Route::get('/invoices/{invoice}/send', 'InvoiceController@send')->name('invoices.send');
    Route::get('/invoices/{invoice}/mark/{status?}', 'InvoiceController@mark')->name('invoices.mark');
    Route::get('/invoices/{tab?}', 'InvoiceController@index')->name('invoices');

    // Credit Notes
    Route::get('/credit-notes', 'CreditNoteController@index')->name('credit_notes');
    Route::get('/credit-notes/create', 'CreditNoteController@create')->name('credit_notes.create');
    Route::post('/credit-notes/create', 'CreditNoteController@store')->name('credit_notes.store');
    Route::get('/credit-notes/{credit_note}/details', 'CreditNoteController@show')->name('credit_notes.details');
    Route::get('/credit-notes/{credit_note}/edit', 'CreditNoteController@edit')->name('credit_notes.edit');
    Route::post('/credit-notes/{credit_note}/edit', 'CreditNoteController@update')->name('credit_notes.update');
    Route::get('/credit-notes/{credit_note}/delete', 'CreditNoteController@delete')->name('credit_notes.delete');
    Route::get('/credit-notes/{credit_note}/send', 'CreditNoteController@send')->name('credit_notes.send');
    Route::get('/credit-notes/{credit_note}/mark/{status?}', 'CreditNoteController@mark')->name('credit_notes.mark');
    Route::get('/credit-notes/{credit_note}/refund', 'CreditNoteController@refund')->name('credit_notes.refund');
    Route::post('/credit-notes/{credit_note}/refund', 'CreditNoteController@refund_store')->name('credit_notes.refund.store');
    Route::get('/credit-notes/{credit_note}/refund/{refund}/delete', 'CreditNoteController@refund_delete')->name('credit_notes.refund.delete');

    // Estimates
    Route::get('/estimates/create', 'EstimateController@create')->name('estimates.create');
    Route::post('/estimates/create', 'EstimateController@store')->name('estimates.store');
    Route::get('/estimates/{estimate}/details', 'EstimateController@show')->name('estimates.details');
    Route::get('/estimates/{estimate}/edit', 'EstimateController@edit')->name('estimates.edit');
    Route::post('/estimates/{estimate}/edit', 'EstimateController@update')->name('estimates.update');
    Route::get('/estimates/{estimate}/delete', 'EstimateController@delete')->name('estimates.delete');
    Route::get('/estimates/{estimate}/send', 'EstimateController@send')->name('estimates.send');
    Route::get('/estimates/{estimate}/convert', 'EstimateController@convert')->name('estimates.convert');
    Route::get('/estimates/{estimate}/mark/{status?}', 'EstimateController@mark')->name('estimates.mark');
    Route::get('/estimates/{tab?}', 'EstimateController@index')->name('estimates');

    // Payments
    Route::get('/payments', 'PaymentController@index')->name('payments');
    Route::get('/payments/create', 'PaymentController@create')->name('payments.create');
    Route::post('/payments/create', 'PaymentController@store')->name('payments.store');
    Route::get('/payments/{payment}/edit', 'PaymentController@edit')->name('payments.edit');
    Route::post('/payments/{payment}/edit', 'PaymentController@update')->name('payments.update');
    Route::get('/payments/{payment}/delete', 'PaymentController@delete')->name('payments.delete');

    // Expenses
    Route::get('/expenses', 'ExpenseController@index')->name('expenses');
    Route::get('/expenses/create', 'ExpenseController@create')->name('expenses.create');
    Route::post('/expenses/create', 'ExpenseController@store')->name('expenses.store');
    Route::get('/expenses/{expense}/edit', 'ExpenseController@edit')->name('expenses.edit');
    Route::post('/expenses/{expense}/edit', 'ExpenseController@update')->name('expenses.update');
    Route::get('/expenses/{expense}/receipt', 'ExpenseController@download_receipt')->name('expenses.download_receipt');
    Route::get('/expenses/{expense}/delete', 'ExpenseController@delete')->name('expenses.delete');

    // Earnings
    Route::get('/earnings', 'EarningController@index')->name('earnings');
    Route::get('/earnings/statements', 'EarningController@statements')->name('earnings.statements');
    Route::get('/earnings/withdraw/{code}', 'EarningController@withdraw')->name('earnings.withdraw');
    Route::post('/earnings/withdraw/{code}', 'EarningController@withdraw_store')->name('earnings.withdraw.store');

    // Vendors
    Route::get('/vendors', 'VendorController@index')->name('vendors');
    Route::get('/vendors/create', 'VendorController@create')->name('vendors.create');
    Route::post('/vendors/create', 'VendorController@store')->name('vendors.store');
    Route::get('/vendors/{vendor}/details', 'VendorController@details')->name('vendors.details');
    Route::get('/vendors/{vendor}/edit', 'VendorController@edit')->name('vendors.edit');
    Route::post('/vendors/{vendor}/edit', 'VendorController@update')->name('vendors.update');
    Route::get('/vendors/{vendor}/delete', 'VendorController@delete')->name('vendors.delete');

    // Reports
    Route::get('/reports/customer-sales', 'ReportController@customer_sales')->name('reports.customer_sales');
    Route::get('/reports/customer-sales/pdf', 'PDFReportController@customer_sales')->name('reports.customer_sales.pdf');
    Route::get('/reports/product-sales', 'ReportController@product_sales')->name('reports.product_sales');
    Route::get('/reports/product-sales/pdf', 'PDFReportController@product_sales')->name('reports.product_sales.pdf');
    Route::get('/reports/profit-loss', 'ReportController@profit_loss')->name('reports.profit_loss');
    Route::get('/reports/profit-loss/pdf', 'PDFReportController@profit_loss')->name('reports.profit_loss.pdf');
    Route::get('/reports/expenses', 'ReportController@expenses')->name('reports.expenses');
    Route::get('/reports/expenses/pdf', 'PDFReportController@expenses')->name('reports.expenses.pdf');
    Route::get('/reports/vendors', 'ReportController@vendors')->name('reports.vendors');
    Route::get('/reports/vendors/pdf', 'PDFReportController@vendors')->name('reports.vendors.pdf');

    // Setting Routes
    Route::group(['namespace' => 'Settings', 'prefix' => 'settings'], function () {
        // Settings>Account Settings
        Route::get('/account', 'AccountController@index')->name('settings.account');
        Route::post('/account', 'AccountController@update')->name('settings.account.update');

        // Settings>Account Settings
        Route::get('/membership', 'MembershipController@index')->name('settings.membership');
        Route::get('/membership/{order_id}/invoice', 'MembershipController@order_invoice')->name('settings.membership.invoice');

        // Settings>Notification Settings
        Route::get('/notifications', 'NotificationController@index')->name('settings.notifications');
        Route::post('/notifications', 'NotificationController@update')->name('settings.notifications.update');

        // Settings>Company Settings
        Route::get('/company', 'CompanyController@index')->name('settings.company');
        Route::post('/company', 'CompanyController@update')->name('settings.company.update');

        // Settings>Preferences
        Route::get('/preferences', 'PreferenceController@index')->name('settings.preferences');
        Route::post('/preferences', 'PreferenceController@update')->name('settings.preferences.update');

        // Settings>Invoice Settings
        Route::get('/invoice', 'InvoiceController@index')->name('settings.invoice');
        Route::post('/invoice', 'InvoiceController@update')->name('settings.invoice.update');

        // Settings>Estimate Settings
        Route::get('/estimate', 'EstimateController@index')->name('settings.estimate');
        Route::post('/estimate', 'EstimateController@update')->name('settings.estimate.update');

        // Settings>Payment Settings
        Route::get('/payment', 'PaymentController@index')->name('settings.payment');
        Route::post('/payment', 'PaymentController@update')->name('settings.payment.update');
        Route::get('/payment/account/create', 'PaymentAccountController@create')->name('settings.payment.account.create');
        Route::post('/payment/account/create', 'PaymentAccountController@store')->name('settings.payment.account.store');
        Route::get('/payment/account/{account}/edit', 'PaymentAccountController@edit')->name('settings.payment.account.edit');
        Route::post('/payment/account/{account}/edit', 'PaymentAccountController@update')->name('settings.payment.account.update');
        Route::get('/payment/account/{account}/delete', 'PaymentAccountController@delete')->name('settings.payment.account.delete');
        Route::get('/payment/type/create', 'PaymentTypeController@create')->name('settings.payment.type.create');
        Route::post('/payment/type/create', 'PaymentTypeController@store')->name('settings.payment.type.store');
        Route::get('/payment/type/{type}/edit', 'PaymentTypeController@edit')->name('settings.payment.type.edit');
        Route::post('/payment/type/{type}/edit', 'PaymentTypeController@update')->name('settings.payment.type.update');
        Route::get('/payment/type/{type}/delete', 'PaymentTypeController@delete')->name('settings.payment.type.delete');

        // Settings>Product Settings
        Route::get('/product', 'ProductController@index')->name('settings.product');
        Route::post('/product', 'ProductController@update')->name('settings.product.update');
        Route::get('/product/unit/create', 'ProductUnitController@create')->name('settings.product.unit.create');
        Route::post('/product/unit/create', 'ProductUnitController@store')->name('settings.product.unit.store');
        Route::get('/product/unit/{product_unit}/edit', 'ProductUnitController@edit')->name('settings.product.unit.edit');
        Route::post('/product/unit/{product_unit}/edit', 'ProductUnitController@update')->name('settings.product.unit.update');
        Route::get('/product/unit/{product_unit}/delete', 'ProductUnitController@delete')->name('settings.product.unit.delete');

        Route::get('/product/category/create', 'ProductCategoryController@create')->name('settings.product.category.create');
        Route::post('/product/category/create', 'ProductCategoryController@store')->name('settings.product.category.store');
        Route::get('/product/category/{product_category}/edit', 'ProductCategoryController@edit')->name('settings.product.category.edit');
        Route::post('/product/category/{product_category}/edit', 'ProductCategoryController@update')->name('settings.product.category.update');
        Route::get('/product/category/{product_category}/delete', 'ProductCategoryController@delete')->name('settings.product.category.delete');

        Route::get('/product/brand/create', 'ProductBrandController@create')->name('settings.product.brand.create');
        Route::post('/product/brand/create', 'ProductBrandController@store')->name('settings.product.brand.store');
        Route::get('/product/brand/{product_brand}/edit', 'ProductBrandController@edit')->name('settings.product.brand.edit');
        Route::post('/product/brand/{product_brand}/edit', 'ProductBrandController@update')->name('settings.product.brand.update');
        Route::get('/product/brand/{product_brand}/delete', 'ProductBrandController@delete')->name('settings.product.brand.delete');


        // Settings>Tax Types
        Route::get('/tax-types', 'TaxTypeController@index')->name('settings.tax_types');
        Route::get('/tax-types/create', 'TaxTypeController@create')->name('settings.tax_types.create');
        Route::post('/tax-types/create', 'TaxTypeController@store')->name('settings.tax_types.store');
        Route::get('/tax-types/{tax_type}/edit', 'TaxTypeController@edit')->name('settings.tax_types.edit');
        Route::post('/tax-types/{tax_type}/edit', 'TaxTypeController@update')->name('settings.tax_types.update');
        Route::get('/tax-types/{tax_type}/delete', 'TaxTypeController@delete')->name('settings.tax_types.delete');

        // Settings>Custom Fields
        Route::get('/custom-fields', 'CustomFieldController@index')->name('settings.custom_fields');
        Route::get('/custom-fields/create', 'CustomFieldController@create')->name('settings.custom_fields.create');
        Route::post('/custom-fields/create', 'CustomFieldController@store')->name('settings.custom_fields.store');
        Route::get('/custom-fields/{custom_field}/edit', 'CustomFieldController@edit')->name('settings.custom_fields.edit');
        Route::post('/custom-fields/{custom_field}/edit', 'CustomFieldController@update')->name('settings.custom_fields.update');
        Route::get('/custom-fields/{custom_field}/delete', 'CustomFieldController@delete')->name('settings.custom_fields.delete');

        // Settings>Expense Categories
        Route::get('/expense-categories', 'ExpenseCategoryController@index')->name('settings.expense_categories');
        Route::get('/expense-categories/create', 'ExpenseCategoryController@create')->name('settings.expense_categories.create');
        Route::post('/expense-categories/create', 'ExpenseCategoryController@store')->name('settings.expense_categories.store');
        Route::get('/expense-categories/{expense_category}/edit', 'ExpenseCategoryController@edit')->name('settings.expense_categories.edit');
        Route::post('/expense-categories/{expense_category}/edit', 'ExpenseCategoryController@update')->name('settings.expense_categories.update');
        Route::get('/expense-categories/{expense_category}/delete', 'ExpenseCategoryController@delete')->name('settings.expense_categories.delete');


                // Settings>warehouse
        Route::get('/warehouse', 'WarehouseController@index')->name('settings.warehouse');
        Route::get('/warehouse/create', 'WarehouseController@create')->name('settings.warehouse.create');
        Route::post('/warehouse/create', 'WarehouseController@store')->name('settings.warehouse.store');
        Route::get('/warehouse/{warehouse}/edit', 'WarehouseController@edit')->name('settings.warehouse.edit');
        Route::post('/warehouse/{warehouse}/edit', 'WarehouseController@update')->name('settings.warehouse.update');
        Route::get('/warehouse/{warehouse}/delete', 'WarehouseController@delete')->name('settings.warehouse.delete');

        // Settings>variation_group
        Route::get('/variation_group', 'VariationGroupController@index')->name('settings.variation_group');
        Route::get('/variation_group/create', 'VariationGroupController@create')->name('settings.variation_group.create');
        Route::post('/variation_group/create', 'VariationGroupController@store')->name('settings.variation_group.store');
        Route::get('/variation_group/{variation_group}/edit', 'VariationGroupController@edit')->name('settings.variation_group.edit');
        Route::post('/variation_group/{variation_group}/edit', 'VariationGroupController@update')->name('settings.variation_group.update');
        Route::get('/variation_group/{variation_group}/delete', 'VariationGroupController@delete')->name('settings.variation_group.delete');
        Route::post('/variation_group/{variation_group}/update_variations', 'VariationGroupController@updateVariations')->name('settings.group_variation.update');

        // Route::get('/variation', 'VariationController@index')->name('settings.variation');
        // Route::get('/variation/create', 'VariationController@create')->name('settings.variation.create');
        // Route::post('/variation/create', 'VariationController@store')->name('settings.variation.store');
        // Route::get('/variation/{variation}/edit', 'VariationController@edit')->name('settings.variation.edit');
        // Route::post('/variation/{variation}/edit', 'VariationController@update')->name('settings.variation.update');
        // Route::get('/variation/{variation}/delete', 'VariationController@delete')->name('settings.variation.delete');
        // Route::post('/attributes/{variation}/update_attributes', 'VariationController@updateAttributes')->name('settings.attributes.update');


        // routes/web.php

       

    // مجموعة المسارات لإدارة التغييرات
    
        // عرض قائمة التغييرات
        Route::get('/variation', [VariationController::class, 'index'])->name('settings.variation');

        // عرض نموذج إنشاء تغيير جديد
        Route::get('/variation/create', [VariationController::class, 'create'])->name('settings.variation.create');

        // تخزين التغيير الجديد
        Route::post('/variation/store', [VariationController::class, 'store'])->name('settings.variation.store');

        // عرض نموذج تحرير التغيير
        Route::get('/variation/edit/{variation}', [VariationController::class, 'edit'])->name('settings.variation.edit');

        // تحديث التغيير
        Route::put('/variation/update/{variation}', [VariationController::class, 'update'])->name('settings.variation.update');

        // حذف التغيير
        Route::delete('/variation/delete/{variation}', [VariationController::class, 'delete'])->name('settings.variation.delete');

        // تحديث خصائص التغيير
        Route::post('/variation/update-attributes/{variation}', [VariationController::class, 'updateAttributes'])->name('settings.variation.updateAttributes');


        // Settings>Team
        Route::get('/team', 'TeamController@index')->name('settings.team');
        Route::get('/team/add-member', 'TeamController@createMember')->name('settings.team.createMember');
        Route::post('/team/add-member', 'TeamController@storeMember')->name('settings.team.storeMember');
        Route::get('/team/{member}/edit', 'TeamController@editMember')->name('settings.team.editMember');
        Route::post('/team/{member}/edit', 'TeamController@updateMember')->name('settings.team.updateMember');
        Route::get('/team/{member}/delete', 'TeamController@deleteMember')->name('settings.team.deleteMember');

        // Settings>Email Templates
        Route::get('/email-templates', 'EmailTemplateController@index')->name('settings.email_template');
        Route::post('/email-templates', 'EmailTemplateController@update')->name('settings.email_template.update');
    });

    // Ajax requests
    Route::get('/ajax/products', 'AjaxController@products')->name('ajax.products');
    Route::get('/ajax/customers', 'AjaxController@customers')->name('ajax.customers');
    Route::get('/ajax/invoices', 'AjaxController@invoices')->name('ajax.invoices');
    Route::get('/ajax/get_variations_tree', 'AjaxController@get_variations_tree')->name('ajax.get_variations_tree');
    Route::get('/ajax/get_group_variations', 'AjaxController@get_group_variations')->name('ajax.get_group_variations');
    Route::get('/ajax/get_var_attruibutes', 'AjaxController@get_var_attruibutes')->name('ajax.get_var_attruibutes');
    Route::get('/ajax/categories', 'AjaxController@categories')->name('ajax.categories');
});

// Order & Checkout Routes
Route::group(['namespace' => 'Application', 'middleware' => ['auth', 'dashboard']], function () {
    // Orders
    Route::get('/order/plans', 'OrderController@plans')->name('order.plans');
    Route::get('/order/checkout/{plan}', 'OrderController@checkout')->name('order.checkout');
    Route::get('/order/processing', 'OrderController@order_processing')->name('order.processing');

    // Paymob
    Route::get('/order/checkout/{plan}/paymob/payment', 'PaymobController@payment')->name('order.payment.paymob');
    Route::post('/order/checkout/{plan}/paymob/wallet', 'PaymobController@wallet_payment')->name('order.payment.paymob.wallet_payment');

    // PaypalExpress Checkout
    Route::post('/order/checkout/{plan}/paypal/payment', 'OrderController@paypal')->name('order.payment.paypal');
    Route::get('/order/checkout/{plan}/paypal/completed', 'OrderController@paypal_completed')->name('order.payment.paypal.completed');
    Route::get('/order/checkout/{plan}/paypal/cancelled', 'OrderController@paypal_cancelled')->name('order.payment.paypal.cancelled');

    // Mollie Checkout
    Route::get('/order/checkout/{plan}/mollie/payment', 'OrderController@mollie')->name('order.payment.mollie');
    Route::get('/order/checkout/{plan}/mollie/completed', 'OrderController@mollie_completed')->name('order.payment.mollie.completed');

    // Razorpay Checkout
    Route::post('/order/checkout/{plan}/razorpay', 'OrderController@razorpay')->name('order.payment.razorpay');

    // Stripe Checkout
    Route::post('/order/checkout/{plan}/stripe', 'OrderController@stripe')->name('order.payment.stripe');
    Route::get('/order/checkout/{plan}/stripe/completed', 'OrderController@stripe_completed')->name('order.payment.stripe.completed');
});