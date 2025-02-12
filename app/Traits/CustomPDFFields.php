<?php 

namespace App\Traits;

use App\Models\CompanySetting;

trait CustomPDFFields
{
    /**
     * Get From Field for PDF views
     * 
     * @param string $key
     * 
     * @return string
     */
    public function getField($key)
    {
        return $this->replaceFieldTags(CompanySetting::getSetting($key, $this->company->id));
    }

    /**
     * Build the from field.
     *
     * @return $this
     */
    public function replaceFieldTags($text) {
        $tag_list = [
            '{company.name}' => $this->company->name,
            '{company.vat_number}' => $this->company->vat_number ?? '',
            '{company.billing.address_1}' => $this->company->billing ? $this->company->billing->address_1 : '',
            '{company.billing.address_2}' => $this->company->billing ? $this->company->billing->address_2 : '',
            '{company.billing.city}' => $this->company->billing ? $this->company->billing->city : '',
            '{company.billing.state}' => $this->company->billing ? $this->company->billing->state : '',
            '{company.billing.country}' => $this->company->billing ? ($this->company->billing->country ? $this->company->billing->country->name : '') : '',
            '{company.billing.phone}' => $this->company->billing ? $this->company->billing->phone : '',
            '{company.billing.zip}' => $this->company->billing ? $this->company->billing->zip : '',

            '{customer.name}' => $this->customer->display_name,
            '{customer.vat_number}' => $this->customer->billing ? $this->customer->vat_number : '',
            '{customer.billing.address_1}' => $this->customer->billing ? $this->customer->billing->address_1 : '',
            '{customer.billing.address_2}' => $this->customer->billing ? $this->customer->billing->address_2 : '',
            '{customer.billing.city}' => $this->customer->billing ? $this->customer->billing->city : '',
            '{customer.billing.state}' => $this->customer->billing ? $this->customer->billing->state : '',
            '{customer.billing.country}' => $this->customer->billing ? ($this->customer->billing->country ? $this->customer->billing->country->name : '') : '',
            '{customer.billing.phone}' => $this->customer->billing ? $this->customer->billing->phone : '',
            '{customer.billing.zip}' => $this->customer->billing ? $this->customer->billing->zip : '',

            '{customer.shipping.address_1}' => $this->customer->shipping ? $this->customer->shipping->address_1 : '',
            '{customer.shipping.address_2}' => $this->customer->shipping ? $this->customer->shipping->address_2 : '',
            '{customer.shipping.city}' => $this->customer->shipping ? $this->customer->shipping->city : '',
            '{customer.shipping.state}' => $this->customer->shipping ? $this->customer->shipping->state : '',
            '{customer.shipping.country}' => $this->customer->shipping ? ($this->customer->shipping->country ? $this->customer->shipping->country->name : '') : '',
            '{customer.shipping.phone}' => $this->customer->shipping ? $this->customer->shipping->phone : '',
            '{customer.shipping.zip}' => $this->customer->shipping ? $this->customer->shipping->zip : '',
        ];
        foreach ($tag_list as $tag => $value) {
            $text = str_replace($tag, $value, $text);
        }
        return $text;
    }
}
