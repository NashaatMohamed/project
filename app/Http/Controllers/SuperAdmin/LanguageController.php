<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Language\Store;
use App\Models\SystemSetting;
use App\Services\Language\Drivers\Translation;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * List all languages
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = $this->translation->allLanguages();

        return view('super_admin.languages.index', ['languages' => $languages]);
    }

    /**
     * Add new language
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.languages.create');
    }

    /**
     * Store new language
     *
     * @return \Illuminate\Http\Redirect
     */
    public function store(Store $request)
    {
        $this->translation->addLanguage($request->locale, $request->name);

        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('super_admin.languages');
        };

        session()->flash('alert-success', __('messages.language_added'));
        return redirect()->route('super_admin.languages');
    }

    /**
     * Store new language
     *
     * @return \Illuminate\Http\Redirect
     */
    public function set_default(Request $request)
    {
        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('super_admin.languages');
        };
        
        SystemSetting::setEnvironmentValue([
            'APP_LOCALE' => $request->language
        ]);

        return redirect()->route('super_admin.languages');
    }
}
