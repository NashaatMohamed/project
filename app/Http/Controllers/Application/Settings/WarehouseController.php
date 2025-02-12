<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\Warehouses\Store;
use App\Http\Requests\Application\Settings\Warehouses\Update;

class WarehouseController extends Controller
{
    /**
     * Display Warehousee Settings Page
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get Warehousee Categories by Company
        $warehouses = Warehouses::findByCompany($currentCompany->id)->paginate(15);

        return view('application.settings.warehouse.index', [
            'warehouses' => $warehouses,
        ]);
    }
 
    /**
     * Display the Form for Creating New warehouse Category
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $warehouse = new Warehouses();

        // Fill model with old input
        if (!empty($request->old())) {
            $warehouse->fill($request->old());
        }

        return view('application.settings.warehouse.create', [
            'warehouse' => $warehouse,
        ]);
    }
 
    /**
     * Store the warehouse Category in Database
     *
     * @param \App\Http\Requests\Application\Settings\Warehouses\Store $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Create warehouse Category and Store in Database
        Warehouses::create([
            'name' => $request->name,
            'company_id' => $currentCompany->id,
        ]);
 
        session()->flash('alert-success', __('messages.warehouse_added'));
        return redirect()->route('settings.warehouse', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Form for Editing warehouse Category
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $warehouse = Warehouses::findOrFail($request->warehouse);
 
        return view('application.settings.warehouse.edit', [
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Update the warehouse Category
     *
     * @param \App\Http\Requests\Application\Settings\Warehouses\Update $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $warehouse = Warehouses::findOrFail($request->warehouse);
        
        // Update Warehouses   in Database
        $warehouse->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
 
        session()->flash('alert-success', __('messages.warehouse_updated'));
        return redirect()->route('settings.warehouse', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Delete the Warehouses
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        
        $warehouse = Warehouses::findOrFail($request->warehouse);
        
        // Delete Warehouses   from Database
        $warehouse->delete();

        session()->flash('alert-success', __('messages.warehouse_deleted'));
        return redirect()->route('settings.warehouse', ['company_uid' => $currentCompany->uid]);
    }
}
