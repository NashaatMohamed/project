<?php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\ProductBrands;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\ProductBrand\Store;

use App\Http\Requests\Application\Settings\ProductBrand\Update;

class ProductBrandController extends Controller
{
    
    public function create(Request $request)
    {
        $product_brand = new ProductBrands();

        // Fill model with old input
        if (!empty($request->old())) {
            $product_brand->fill($request->old());
        }

        return view('application.settings.product.brand.create', [
            'product_brand' => $product_brand,
        ]);
    }

    /**
     * Store the Product brand in Database
     *
     * @param \App\Http\Requests\Application\Settings\ProductBrand\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Create Product brand and Store in Database
        $brand = ProductBrands::create([
            'name' => $request->name,
            'company_id' => $currentCompany->id
        ]);


        // Add  categories
        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $brand->categories()->create([
                    'category_id' => $category
                ]);
            }
        }



        session()->flash('alert-success', __('messages.product_brand_added'));
        return redirect()->route('settings.product', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Form for Editing Product Brand
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $product_brand = ProductBrands::findOrFail($request->product_brand);

        return view('application.settings.product.brand.edit', [
            'product_brand' => $product_brand,
        ]);
    }

    /**
     * Update the Product brand
     *
     * @param \App\Http\Requests\Application\Settings\ProductBrand\Update $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $product_brand = ProductBrands::findOrFail($request->product_brand);

        // Update Product Brand in Database
        $product_brand->update([
            'name' => $request->name,
            'company_id' => $currentCompany->id
        ]);

        // Add  categories
        // Remove old categories
        $product_brand->categories()->delete();
        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $product_brand->categories()->create([
                    'category_id' => $category
                ]);
            }
        }
        session()->flash('alert-success', __('messages.product_brand_updated'));
        return redirect()->route('settings.product', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Delete the Product brand
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $product_brand = ProductBrands::findOrFail($request->product_brand);

        // Delete Product brand from Database
        $product_brand->delete();

        session()->flash('alert-success', __('messages.product_brand_deleted'));
        return redirect()->route('settings.product', ['company_uid' => $currentCompany->uid]);
    }
}