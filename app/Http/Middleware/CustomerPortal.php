<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use App\Services\Language\Drivers\Translation;

class CustomerPortal
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Share Current Customer with All Blade Views
        $currentCustomer = Customer::findByUid($request->customer);
        view()->share('currentCustomer', $currentCustomer);

        // Languages
        $languages = $this->translation->allLanguages();
        view()->share('languages', $languages);

        return $next($request);
    }
}
