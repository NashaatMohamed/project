<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Language\Drivers\Translation;

class Dashboard
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
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Redirect super_user
        if ($user->hasRole('super_admin')) {
            return redirect()->route('super_admin.dashboard');
        }

        // Redirect user if there is no subscription or ended or cancelled
        $subscription = filled($currentCompany) ? $currentCompany->subscription('main') : null;
        $route = $request->route()->getName();

        // If there is no subscription at all
        if (substr($route, 0, 6) !== 'order.' && !$subscription) {
            return redirect()->route('order.plans');
        }

        // If there is a subscription but not an active subscription
        if (substr($route, 0, 6) !== 'order.' && !$subscription->active()) {
            return redirect()->route('order.plans');
        }

        // Company based preferences
        share([
            'company_currency' => filled($currentCompany) ?  $currentCompany->currency : null,
        ]);

        // Languages
        $languages = $this->translation->allLanguages();

        // Share Current Company with All Blade Views
        view()->share('currentCompany', $currentCompany);
        view()->share('authUser', $user);
        view()->share('languages', $languages);

        return $next($request);
    }
}
