<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BankController extends Controller
{
    /**
     * Display Super Admin Banks
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get Banks
        $banks = QueryBuilder::for(Bank::class)
            ->paginate()
            ->appends(request()->query());

        return view('super_admin.banks.index', [
            'banks' => $banks
        ]);
    }

    /**
     * Display the Form for Creating New bank
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $bank = new Bank();

        // Fill model with old input
        if (!empty($request->old())) {
            $bank->fill($request->old());
        }
 
        return view('super_admin.banks.create', [
            'bank' => $bank,
        ]);
    }

    /**
     * Store the bank in Database
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Create new bank
        $bank = Bank::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Upload Logo File
        if ($request->logo) {
            $request->validate(['logo' => 'required|image|mimes:png,jpg|max:2048']);
            $path = $request->logo->storeAs('bank-logos', 'logo-'. $bank->id .'.'.$request->logo->getClientOriginalExtension(), 'public_dir');
            $bank->logo = '/uploads/'.$path;
            $bank->save();
        }

        session()->flash('alert-success', __('messages.bank_created'));
        return redirect()->route('super_admin.banks');
    }

    /**
     * Display the Form for Editing bank
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $bank = Bank::findOrFail($request->bank);
        
        return view('super_admin.banks.edit', [
            'bank' => $bank,
        ]);
    }

    /**
     * Update the bank in Database
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $bank = Bank::findOrFail($request->bank);

        // Update the Bank
        $bank->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Upload Logo File
        if ($request->logo) {
            $request->validate(['logo' => 'required|image|mimes:png,jpg|max:2048']);
            $path = $request->logo->storeAs('bank-logos', 'logo-'. $bank->id .'.'.$request->logo->getClientOriginalExtension(), 'public_dir');
            $bank->logo = '/uploads/'.$path;
            $bank->save();
        }
 
        session()->flash('alert-success', __('messages.bank_updated'));
        return redirect()->route('super_admin.banks.edit', $bank->id);
    }

    /**
     * Delete the bank
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $bank = Bank::findOrFail($request->bank);
        $bank->delete();
            
        session()->flash('alert-success', __('messages.bank_deleted'));
        return redirect()->route('super_admin.banks');
    }
}
