<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Language\Drivers\Translation;

class SuperAdmin
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

        // Check if the user is super_admin
        if (!$user->hasRole('super_admin')) {
            return redirect()->route('home');
        }

        // Company based preferences
        share([
            'company_currency' => $currentCompany->currency,
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
