<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Services\Language\Drivers\Translation;

class HomeController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // If user is authenticated
        if ($user) {
            if ($user->hasRole('super_admin')) {
                return redirect()->route('super_admin.dashboard');
            }

            $currentCompany = $user->currentCompany();
            return redirect()->route('dashboard', ['company_uid' => $currentCompany->uid]);
        }

        $theme = SystemSetting::getSetting('theme');

        $languages = $this->translation->allLanguages();

        return view('themes.'.$theme.'.home', ['languages' => $languages]);
    } 

    /**
     * Show the application demo page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function demo(Request $request)
    {
        // If demo mode is not active then deactivate demo page
        if (config('app.is_demo')) {
            return view('layouts.demo');
        };

        return redirect('/');
    } 

    /**
     * Change language and store the locale pref in session
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function change_language(Request $request){
        app()->setlocale($request->locale);
        session()->put('locale', $request->locale); 

        return redirect()->back();
    }
}
