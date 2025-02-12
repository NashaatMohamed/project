<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\VariationGroup;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\VariationGroup\Store;
use App\Http\Requests\Application\Settings\VariationGroup\Update;

class VariationGroupController extends Controller
{
    /**
     * Display Warehousee Settings Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get Warehouses Categories by Company
        $variation_groups = VariationGroup::findByCompany($currentCompany->id)->paginate(15);

        return view('application.settings.variation_group.index', [
            'variation_groups' => $variation_groups,
        ]);
    }

    /**
     * Display the Form for Creating New variation_group Category
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $variation_group = new VariationGroup();

        // Fill model with old input
        if (!empty($request->old())) {
            $variation_group->fill($request->old());
        }

        return view('application.settings.variation_group.create', [
            'variation_group' => $variation_group,
        ]);
    }

    /**
     * Store the variation_group Category in Database
     *
     * @param \App\Http\Requests\Application\Settings\VariationGroup\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Create variation_group Category and Store in Database
        $variation_group = VariationGroup::create([
            'name' => $request->name,
            'company_id' => $currentCompany->id,
        ]);

        session()->flash('alert-success', __('messages.variation_group_added'));


        return view('application.settings.variation_group.edit', [
            'variation_group' => $variation_group,
        ]);

        //        return redirect()->route('settings.variation_group', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Form for Editing variation_group Category
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $variation_group = VariationGroup::findOrFail($request->variation_group);

        return view('application.settings.variation_group.edit', [
            'variation_group' => $variation_group,
        ]);
    }

    /**
     * Update the variation_group Category
     *
     * @param \App\Http\Requests\Application\Settings\VariationGroup\Update $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $variation_group = VariationGroup::findOrFail($request->variation_group);

        // Update VariationGroup   in Database
        $variation_group->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        session()->flash('alert-success', __('messages.variation_group_updated'));
        return redirect()->route('settings.variation_group', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Delete the VariationGroup
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        try {

            $user = $request->user();
            $currentCompany = $user->currentCompany();

            $variation_group = VariationGroup::findOrFail($request->variation_group);

            // Delete VariationGroup   from Database
            $variation_group->delete();

            session()->flash('alert-success', __('messages.variation_group_deleted'));
            return redirect()->route('settings.variation_group', ['company_uid' => $currentCompany->uid]);
        }catch (\Throwable $exception){
            session()->flash('alert-danger', __('messages.can_not_delete_used_variations'));
            return redirect()->back();
        }

    }


    public function updateVariations(Request $request)
    {
        $user = $request->user();

        $currentCompany = $user->currentCompany();

        $variation_group = VariationGroup::findOrFail($request->variation_group);

        $variation_group->GroupVariations()->delete();

        foreach ($request->variations as $index=>$variation)
            $variation_group->GroupVariations()->create([
                "variations_id" => $variation,
                "sort" =>$index+1
            ]);

        session()->flash('alert-success', __('messages.group_variations_updated'));

        return redirect()->route('settings.variation_group', ['company_uid' => $currentCompany->uid]);
    }
}
