<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\SystemSetting;

class EnableCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $egp = Currency::where('short_code', 'EGP')->first(); 
        if ($egp) {
            $egp->enabled = true;
            $egp->save();
        }
        SystemSetting::setEnvironmentValue([
            'CS_CURRENCY_ID' => $egp->id,
            'DEFAULT_CURRENCY' => $egp->short_code,
        ]);
    }
}
