<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests\SuperAdmin\Page\Store;
use App\Http\Requests\SuperAdmin\Page\Update;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display Super Admin Pages Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get Pages
        $pages = QueryBuilder::for(Page::class)
            ->orderBy('order')
            ->paginate()
            ->appends(request()->query());

        return view('super_admin.pages.index', [
            'pages' => $pages
        ]);
    }

    /**
     * Display the Form for Creating New Page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $page = new Page();

        // Fill model with old input
        if (!empty($request->old())) {
            $page->fill($request->old());
        }
 
        return view('super_admin.pages.create', [
            'page' => $page,
        ]);
    }

    /**
     * Store the page in Database
     *
     * @param \App\Http\Requests\SuperAdmin\Page\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('super_admin.pages');
        };

        // Slug
        $slug = Str::slug($request->name, '-');

        // Check slug
        if (Page::where('slug', $slug)->exists()) {
            $slug = uniqid($slug);
        };

        // Create new Page
        Page::create([
            'slug' => $slug,
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
            'is_active' => true,
            'order' => $request->order ? $request->order : 0
        ]);

        session()->flash('alert-success', __('messages.page_created'));
        return redirect()->route('super_admin.pages');
    }

    /**
     * Display the Form for Editing Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $page = Page::findOrFail($request->page);
        
        // Fill model with old input
        if (!empty($request->old())) {
            $page->fill($request->old());
        }

        return view('super_admin.pages.edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the Page in Database
     *
     * @param \App\Http\Requests\SuperAdmin\Page\Update $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('super_admin.pages');
        };

        $page = Page::findOrFail($request->page);

        // Update the Page
        $page->update($request->validated());
 
        session()->flash('alert-success', __('messages.page_updated'));
        return redirect()->route('super_admin.pages.edit', $page->id);
    }

    /**
     * Delete the Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $page = Page::findOrFail($request->page);

        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('super_admin.pages');
        };

        // Delete page
        if ($page->is_deletable) {
            $page->delete();
        }
            
        session()->flash('alert-success', __('messages.page_deleted'));
        return redirect()->route('super_admin.pages');
    }
}
