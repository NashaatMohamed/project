<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\Language\Drivers\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LanguageTranslationController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }

    public function index(Request $request, $language)
    {
        if ($request->has('language') && $request->get('language') !== $language) {
            return redirect()
                ->route('super_admin.languages.translations', ['language' => $request->get('language'), 'group' => $request->get('group'), 'filter' => $request->get('filter')]);
        }

        $languages = $this->translation->allLanguages();
        $groups = $this->translation->getGroupsFor(config('app.locale'))->merge('single');
        $translations = $this->translation->filterTranslationsFor($language, $request->get('filter'));

        if ($request->has('group') && $request->get('group')) {
            if ($request->get('group') === 'single') {
                $translations = $translations->get('single');
                $translations = new Collection(['single' => $translations]);
            } else {
                $translations = $translations->get('group')->filter(function ($values, $group) use ($request) {
                    return $group === $request->get('group');
                });

                $translations = new Collection(['group' => $translations]);
            }
        }

        return view('super_admin.languages.translations.index', compact('language', 'languages', 'groups', 'translations'));
    }

    public function update(Request $request, $language)
    {
        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            return ['success' => false];
        };

        if (! Str::contains($request->get('group'), 'single')) {
            $this->translation->addGroupTranslation($language, $request->get('group'), $request->get('key'), $request->get('value') ?: '');
        } else {
            $this->translation->addSingleTranslation($language, $request->get('group'), $request->get('key'), $request->get('value') ?: '');
        }

        return ['success' => true];
    }
}
