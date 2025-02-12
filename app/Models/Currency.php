<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_code',
        'code',
        'precision',
        'subunit',
        'symbol',
        'symbol_first',
        'decimal_mark',
        'thousands_separator',
        'enabled',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'symbol_first' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     * List Currencies for Select2 Javascript Library
     * 
     * @return collect
     */
    public static function getSelect2Array() {        
        $response = collect();
        foreach(self::all() as $currency){
            $response->push([
                'id' => $currency->id,
                'code' => $currency->short_code,
                'symbol' => $currency->symbol,
                'precision' => $currency->precision,
                'thousand_separator' => $currency->thousands_separator,
                'decimal_separator' => $currency->decimal_mark,
                'swap_currency_symbol' => !$currency->symbol_first,
                'text' => "{$currency->short_code} - {$currency->name}"
            ]);
        }
        return $response;
    }
} 