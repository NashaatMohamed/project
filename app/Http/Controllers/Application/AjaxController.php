<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\GroupVariation;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProductVariation;
use App\Models\VariationAttributes;
use App\Models\VariationGroup;
use App\Models\Variations;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    /**
     * Get Customers Ajax Request
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return json
     */
    public function customers(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $search = $request->search;


        if ($search == '') {
            $customers = Customer::findByCompany($currentCompany->id)->limit(5)->get();
        } else {
            $customers = Customer::findByCompany($currentCompany->id)
                ->where(function ($w) use ($search) {
                    $w->where('display_name', 'like', '%' . $search . '%');
                    $w->OrWhere('phone', 'like', '%' . $search . '%');
                })
                ->limit(5)->get();
        }

        $response = collect();
        foreach ($customers as $customer) {
            $response->push([
                "id" => $customer->id,
                "text" => $customer->display_name,
                "currency" => $customer->currency,
                "billing_address" => $customer->displayLongAddress('billing'),
                "shipping_address" => $customer->displayLongAddress('shipping'),
            ]);
        }

        return response()->json($response);
    }

    /**
     * Get Invoices Ajax Request
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return json
     */
    public function invoices(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $invoices = Invoice::findByCompany($currentCompany->id)
            ->findByCustomer($request->customer_id)
            ->unpaid()
            ->where('due_amount', '>', 0)
            ->select('id', 'invoice_number AS text', 'due_amount')
            ->get();

        return response()->json($invoices);
    }


    public function products(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

//        $products = Product::findByCompany($currentCompany->id)
//            ->select('id', 'name AS text', 'price')
//            ->where('hide', false)
//            ->with('taxes')
//            ->get();

        $products = ProductVariation::query()->whereCompanyId($currentCompany->id)
            ->get()
            ->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'text' => $variation->getFullProductName(),
                    'price' => $variation->price,
                    'taxes' => $variation->taxes ?? []
                ];
            });


        return response()->json($products);
    }


//    public function get_variations_tree(Request $request)
//    {
//        $groupId = $request->variation_group_id;
//        $user = $request->user();
//        $currentCompany = $user->currentCompany();
//
//        if ($groupId == 0)
//        {
//            $variations = Variations::query()->findByCompany($currentCompany->id)
//                ->with('variationAttributes')
//                ->orderBy('sort', 'asc')
//                ->get();
//        }else{
//            // جلب جميع التغييرات والخصائص الخاصة بالشركة
//            $variations = Variations::query()->whereIn("id", GroupVariation::where("variation_group_id", $groupId)
//                ->pluck("variations_id"))->findByCompany($currentCompany->id)
//                ->with('variationAttributes')
//                ->orderBy('sort', 'asc')
//                ->get();
//        }
//
//
//        // بناء الشجرة
//        $tree = [];
//        foreach ($variations as $variation) {
//            $attributes = [];
//            foreach ($variation->variationAttributes as $attribute) {
//                $attributes[] = [
//                    'id' => $attribute->id,
//                    'text' => $attribute->name,
//                ];
//            }
//            $tree[] = [
//                'text' => $variation->name,
//                'children' => $attributes,
//            ];
//        }
//
//        return response()->json($tree);
//    }



    public function get_variations_tree(Request $request)
    {
        $groupId = $request->variation_group_id;
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get all variation groups (for frontend use)
        $variationGroups = VariationGroup::where('company_id', $currentCompany->id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        // Fetch all variations if no group is selected (0 or null)
        if (empty($groupId) || $groupId == 0) {
            $variations = Variations::query()
                ->findByCompany($currentCompany->id)
                ->with('variationAttributes')
                ->orderBy('sort', 'asc')
                ->get();
        } else {
            // Fetch variations linked to the selected group
            $variations = Variations::query()
                ->whereIn("id", GroupVariation::where("variation_group_id", $groupId)->pluck("variations_id"))
                ->findByCompany($currentCompany->id)
                ->with('variationAttributes')
                ->orderBy('sort', 'asc')
                ->get();
        }

        // Construct variations tree
        $tree = [];
        foreach ($variations as $variation) {
            $attributes = [];
            foreach ($variation->variationAttributes as $attribute) {
                $attributes[] = [
                    'id' => $attribute->id,
                    'text' => $attribute->name,
                ];
            }
            $tree[] = [
                'text' => $variation->name,
                'children' => $attributes,
            ];
        }

        return response()->json([
            'variation_groups' => $variationGroups, // Always include all variation groups
            'variations_tree' => $tree, // Filtered variations based on selection
        ]);
    }


    public function get_group_variations(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $products = Variations::
        whereHas("GroupVariations", function ($q) use ($request) {

            $q->where("variation_group_id", $request->variation_group_id);
        })->
        findByCompany($currentCompany->id)
            ->select('id', 'name AS text')
            ->get();

        return response()->json($products);
    }

    public function get_var_attruibutes(Request $request)
    {
        $products = VariationAttributes:: leftJoin('variations', 'variations.id', '=', 'variation_attributes.variation_id')
            ->where("variation_id", $request->variation_id)
            ->select(['variation_attributes.id', 'variation_attributes.name AS text', 'variations.id as variation_id', 'variations.name as variation_text'])
            ->get();

        return response()->json($products);
    }

    /**
     * Get categories Ajax Request
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return json
     */
    public function categories(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $categories = ProductCategories::findByCompany($currentCompany->id)
            ->findByBrand($request->brand_id)
            ->select('id', 'name AS text')
            ->get();

        return response()->json($categories);
    }

}
