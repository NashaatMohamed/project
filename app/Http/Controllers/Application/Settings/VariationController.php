<?php

// app/Http/Controllers/Application/Settings/VariationController.php

namespace App\Http\Controllers\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\Variations;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Settings\Variation\Store;
use App\Http\Requests\Application\Settings\Variation\Update;

class VariationController extends Controller
{
    /**
     * عرض صفحة إعدادات التغييرات
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // الحصول على التغييرات الخاصة بالشركة
        $variations = Variations::findByCompany($currentCompany->id)->paginate(15);

        return view('application.settings.variation.index', [
            'variations' => $variations,
        ]);
    }

    /**
     * عرض نموذج إنشاء تغيير جديد
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $variation = new Variations();

        // تعبئة النموذج بالبيانات السابقة في حالة وجودها
        if (!empty($request->old())) {
            $variation->fill($request->old());
        }

        // تعيين العلاقة إلى مجموعة فارغة لتجنب خطأ foreach
        $variation->setRelation('variationAttributes', collect());

        return view('application.settings.variation.create', [
            'variation' => $variation,
        ]);
    }

    /**
     * تخزين التغيير الجديد في قاعدة البيانات
     *
     * @param \App\Http\Requests\Application\Settings\Variation\Store $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // إنشاء التغيير وتخزينه في قاعدة البيانات
        $variation = Variations::create([
            'name' => $request->main_name,
            'company_id' => $currentCompany->id,
        ]);

        session()->flash('alert-success', __('messages.variation_added'));

        // إضافة الخصائص للتغيير
        $this->addAttributes($request, $variation);



        // إعادة التوجيه إلى صفحة تحرير التغيير
        return redirect()->route('settings.variation', [
            'variation' => $variation->id,
            'company_uid' => $currentCompany->uid
        ]);
    }

    /**
     * عرض نموذج تحرير التغيير
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $variation = Variations::findOrFail($request->variation);

        return view('application.settings.variation.edit', [
            'variation' => $variation,
        ]);
    }

    /**
     * تحديث التغيير في قاعدة البيانات
     *
     * @param \App\Http\Requests\Application\Settings\Variation\Update $request
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $variation = Variations::findOrFail($request->variation);

        // تحديث بيانات التغيير في قاعدة البيانات
        $variation->update([
            'name' => $request->main_name,
            'description' => $request->description
        ]);

        session()->flash('alert-success', __('messages.variation_updated'));

         // إضافة الخصائص للتغيير
        $this->addAttributes($request, $variation);

        return redirect()->route('settings.variation', [
            'company_uid' => $currentCompany->uid
        ]);
    }

    /**
     * حذف التغيير من قاعدة البيانات
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {
            $user = $request->user();
            $currentCompany = $user->currentCompany();

            $variation = Variations::findOrFail($request->variation);

            // حذف الخصائص المرتبطة بالتغيير
            $variation->variationAttributes()->delete();

            // حذف التغيير من قاعدة البيانات
            $variation->delete();

            session()->flash('alert-success', __('messages.variation_deleted'));

            return redirect()->route('settings.variation', [
                'company_uid' => $currentCompany->uid
            ]);

        } catch (\Throwable $exception) {
            session()->flash('alert-danger', __('messages.can_not_delete_used_variations'));
            return redirect()->back();
        }
    }

    /**
     * تحديث خصائص التغيير
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAttributes(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $variation = Variations::findOrFail($request->variation);

        // حذف الخصائص الحالية
        $variation->variationAttributes()->delete();

        // إنشاء خصائص جديدة
        foreach ($request->name as $index => $record) {
            $variation->variationAttributes()->create([
                'name' => $record,
                'company_id' => $currentCompany->id,
                'sort' => $index + 1
            ]);
        }

        session()->flash('alert-success', __('messages.variation_attributes_updated'));

        return redirect()->route('settings.variation', [
            'company_uid' => $currentCompany->uid
        ]);
    }

    /**
     * إضافة خصائص للتغيير
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Variations $variationObject
     * @return void
     */
    public function addAttributes(Request $request, $variationObject)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $variation = Variations::findOrFail($variationObject->id);

        // حذف الخصائص الحالية
        $variation->variationAttributes()->delete();

        // إنشاء خصائص جديدة
        foreach ($request->name as $index => $record) {
            $variation->variationAttributes()->create([
                'name' => $record,
                'company_id' => $currentCompany->id,
                'sort' => $index + 1
            ]);
        }
    }

}