<table width="100%" class="items-table" cellspacing="0" border="0">
    <tr class="item-table-heading-row">
        <th width="2%" class="pr-20 text-right item-table-heading">#</th>
        @if($credit_note->tax_per_item)
            <th width="30%" class="pl-0 text-left item-table-heading">{{ __('messages.product') }}</th>
        @else
            <th width="40%" class="pl-0 text-left item-table-heading">{{ __('messages.product') }}</th>
        @endif
        <th class="pr-20 text-right item-table-heading">{{ __('messages.quantity') }}</th>
        <th class="pr-20 text-right item-table-heading">{{ __('messages.price') }}</th>
        @if($credit_note->tax_per_item)
            <th width="20%" class="pl-10 text-right item-table-heading">{{ __('messages.tax') }}</th>
        @endif
        @if($credit_note->discount_per_item)
            <th class="pl-10 text-right item-table-heading">{{ __('messages.discount') }}</th>
        @endif
        <th class="text-right item-table-heading">{{ __('messages.amount') }}</th>
    </tr>
    @php
        $index = 1
    @endphp
    @foreach ($credit_note->items as $item)
        <tr class="item-row">
            <td class="pr-20 text-right item-cell"style="vertical-align: top;">
                {{ $index }}
            </td>
            <td class="pl-0 text-left item-cell" style="vertical-align: top;">
                <span>{{ $item->product->name }}</span><br>
                <span class="item-description">{!! nl2br(htmlspecialchars($item->product->description)) !!}</span>
            </td>
            <td class="pr-20 text-right item-cell" style="vertical-align: top;">
                {{ $item->quantity }}
            </td>
            <td class="pr-20 text-right item-cell" style="vertical-align: top;">
                {!! money($item->price, $credit_note->currency_code)->format() !!}
            </td>

            @if($credit_note->tax_per_item)
                <td class="pl-10 text-right item-cell" style="vertical-align: top;">
                    @foreach ($item->getTotalPercentageOfTaxesWithNames() as $key => $value)
                        {{$key . ' ('. $value. '%' .')'}} <br>
                    @endforeach
                </td>
            @endif

            @if($credit_note->discount_per_item)
                <td class="pl-10 text-right item-cell" style="vertical-align: top;">
                    {{ $item->discount_val }}%
                </td>
            @endif

            <td class="text-right item-cell" style="vertical-align: top;">
                {!! money($item->total, $credit_note->currency_code)->format() !!}
            </td>
        </tr>
        @php
            $index += 1
        @endphp
    @endforeach
</table>

<hr class="item-cell-table-hr">

<div class="total-display-container">
    <table width="100%" cellspacing="0px" border="0" class="total-display-table @if(count($credit_note->items) > 12) page-break @endif">
        <tr>
            <td class="border-0 total-table-attribute-label"><h4>{{ __('messages.sub_total') }}</h4></td>
            <td class="py-2 border-0 item-cell total-table-attribute-value">
                @if($credit_note->tax_per_item  == false)
                    {!! money($credit_note->sub_total, $credit_note->currency_code)->format() !!}
                @else
                    {!! money($credit_note->getItemsSubTotalByBasePrice(), $credit_note->currency_code)->format() !!}
                @endif
            </td>
        </tr>

        @if($credit_note->tax_per_item  == false)
            @foreach ($credit_note->getTotalPercentageOfTaxesWithNames() as $key => $value)
                <tr>
                    <td class="border-0 total-table-attribute-label">
                        <h4>{{$key . ' ('. $value. '%' .')'}}</h4>
                    </td>
                    <td class="border-0 item-cell total-table-attribute-value">
                        {!! money(($value / 100) * $credit_note->sub_total, $credit_note->currency_code)->format() !!}
                    </td>
                </tr>
            @endforeach
        @else
            @foreach ($credit_note->getItemsTotalPercentageOfTaxesWithNames() as $key => $value)
                <tr>
                    <td class="border-0 total-table-attribute-label">
                        <h4>{{$key}}</h4>
                    </td>
                    <td class="border-0 item-cell total-table-attribute-value">
                        {!! money($value, $credit_note->currency_code)->format() !!}
                    </td>
                </tr>
            @endforeach
        @endif

        @if ($credit_note->discount_per_item == false)
            @if($credit_note->discount_val > 0)
                <tr>
                    <td class="border-0 total-table-attribute-label">
                        <h4>{{ __('messages.discount') . ' (' . $credit_note->discount_val . '%)' }}</h4>
                    </td>
                    <td class="py-2 border-0 item-cell total-table-attribute-value">
                        - {!! money(($credit_note->discount_val / 100) * $credit_note->sub_total, $credit_note->currency_code)->format() !!}
                    </td>
                </tr>
            @endif
        @else
            @php $discount_val = $credit_note->getItemsTotalDiscount() @endphp
            @if($discount_val > 0)
                <tr>
                    <td class="border-0 total-table-attribute-label">
                        <h4>{{ __('messages.discount') }}</h4>
                    </td>
                    <td class="py-2 border-0 item-cell total-table-attribute-value">
                        - {!! money($discount_val, $credit_note->currency_code)->format() !!}
                    </td>
                </tr>
            @endif
        @endif

        <tr>
            <td class="py-3"></td>
        </tr>
        <tr>
            <td class="border-0 total-border-left total-table-attribute-label">
                <h3>{{ __('messages.total') }}</h3>
            </td>
            <td class="py-8 border-0 total-border-right item-cell total-table-attribute-value">
                <h3>{!! money($credit_note->total, $credit_note->currency_code)->format() !!}</h3>
            </td>
        </tr>

        @if (count($credit_note->applied_payments) > 0)
            @foreach ($credit_note->applied_payments as $payment)
                <tr>
                    <td class="border-0 total-table-attribute-label"><h4>{{ $payment->invoice->display_name }}</h4></td>
                    <td class="py-2 border-0 item-cell total-table-attribute-value">
                        - {!! money($payment->amount, $payment->currency_code)->format() !!}
                    </td>
                </tr>
            @endforeach
        @endif

        @if (count($credit_note->refunds) > 0)
            @foreach ($credit_note->refunds as $refund)
                <tr>
                    <td class="border-0 total-table-attribute-label"><h4>{{ __('messages.refund') }}</h4></td>
                    <td class="py-2 border-0 item-cell total-table-attribute-value">
                        - {!! money($refund->amount, $refund->currency_code)->format() !!}
                    </td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td class="py-3"></td>
        </tr>
        <tr>
            <td class="border-0 total-table-attribute-label"><h4>{{ __('messages.remaining_balance') }}</h4></td>
            <td class="py-2 border-0 item-cell total-table-attribute-value">
                {!! money($credit_note->remaining_balance, $credit_note->currency_code)->format() !!}
            </td>
        </tr>
    </table>
</div> 
