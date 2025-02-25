<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\ProductCategories;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\ProductCategory\Store;
use App\Http\Requests\Application\Settings\ProductCategory\Update;

class ProductCategoryController extends Controller
{ 

    public function create(Request $request)
    {
        $product_category = new ProductCategories();

        // Fill model with old input
        if (!empty($request->old())) {
            $product_category->fill($request->old());
        }

        return view('application.settings.product.category.create', [
            'product_category' => $product_category,
        ]);
    }
 

    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Create Product category and Store in Database
        ProductCategories::create([
            'name' => $request->name,
            'company_id' => $currentCompany->id,
        ]);
 
        session()->flash('alert-success', __('messages.product_category_added'));
        return redirect()->route('settings.product', ['company_uid' => $currentCompany->uid]);
    }

    public function edit(Request $request)
    {
        $product_category = ProductCategories::findOrFail($request->product_category);
 
        return view('application.settings.product.category.edit', [
            'product_category' => $product_category,
        ]);
    }

    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $product_category = ProductCategories::findOrFail($request->product_category);
        
        // Update Product category in Database
        $product_category->update([
            'name' => $request->name,
            'company_id' => $currentCompany->id,
        ]);
 
        session()->flash('alert-success', __('messages.product_category_updated'));
        return redirect()->route('settings.product', ['company_uid' => $currentCompany->uid]);
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        
        $product_category = ProductCategories::findOrFail($request->product_category);
        
        // Delete Product category from Database
        $product_category->delete();

        session()->flash('alert-success', __('messages.product_category_deleted'));
        return redirect()->route('settings.product', ['company_uid' => $currentCompany->uid]);
    }
}
