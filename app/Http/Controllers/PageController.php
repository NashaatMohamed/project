<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Services\Language\Drivers\Translation;
use App\Models\SystemSetting;

class PageController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * Show the page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $theme = SystemSetting::getSetting('theme');
        $languages = $this->translation->allLanguages();

        $page = Page::where('slug', $request->slug)->firstOrFail();

        return view('themes.'.$theme.'.page', [
            'languages' => $languages,
            'page_title' => $page->name,
            'page_content' => $page->content
        ]);
    } 
}
