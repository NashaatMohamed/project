@if($products->count() > 0)
    <div class="table-responsive" data-toggle="lists">
        <table class="table mb-0 thead-border-top-0 table-striped">
            <thead>
            <tr>
                <th class="text-center w-30px">{{ __('messages.#id') }}</th>
                <th>{{ __('messages.product') }}</th>
                <th>{{ __('messages.quantity') }}</th>
                <th>{{ __('messages.colors') }}</th>
{{--                <th>{{ __('messages.unit') }}</th>--}}
                <th class="text-center">{{ __('messages.price') }}</th>
{{--                <th class="text-center width: 120px;">{{ __('messages.created_at') }}</th>--}}
                <th class="w-50px">{{ __('messages.actions') }}</th>
            </tr>
            </thead>
            <tbody class="list" id="products">
{{--            @foreach ($products as $product)--}}
            @foreach ($products as $variation)
                <tr>
                    <td>
                        <div class="badge badge-light">
                            <a class="mb-0" href="{{ route('products.show', ['product' => $variation->product->id, 'company_uid' => $currentCompany->uid]) }}">
                                #{{ $variation->product->id }}
                            </a>
                        </div>
                    </td>
                    <td>
                        <a class="h6 mb-0" href="{{ route('products.show', ['product' => $variation->product->id, 'company_uid' => $currentCompany->uid]) }}">
                            <strong>{{ $variation->getFullProductName() }}</strong>
                        </a>
                    </td>

                    <td class="text-center w-80px">
                        {{ $variation->quantity ?? 0 }}
                    </td>

{{--                    <td class="text-center w-80px">--}}
{{--                        {{ $product->unit->name ?? '-' }}--}}
{{--                    </td>--}}

                    <td>
                        @if(isset($variation->productVariationColors))
                            @foreach($variation->productVariationColors as $productVariationColor)
                                <span class="badge badge-primary">{{ $productVariationColor->color }}: {{ $productVariationColor->quantity }}</span><br>
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="text-center w-80px">
                        {!! money($variation->price, $variation->currency_code) !!}
                    </td>
{{--                    <td class="text-center">--}}
{{--                        {{ $product->formatted_created_at }}--}}
{{--                    </td>--}}
                    <td class="text-center">
                        <!-- View Icon (First Variation) -->
                        <a href="{{ route('products.show.first.variation', ['variation' => $variation->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-sm btn-link" data-toggle="tooltip" title="{{ __('messages.view_first_variation') }}">
                            <i class="material-icons icon-16pt">visibility</i>
                        </a>

{{--                        <!-- Edit Icon -->--}}
{{--                        <a href="{{ route('products.edit', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-sm btn-link" data-toggle="tooltip" title="{{ __('messages.edit') }}">--}}
{{--                            <i class="material-icons icon-16pt">edit</i>--}}
{{--                        </a>--}}

{{--                        <!-- Delete Icon -->--}}
{{--                        <a href="{{ route('products.delete', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-sm btn-link text-danger delete-confirm" data-toggle="tooltip" title="{{ __('messages.delete') }}">--}}
{{--                            <i class="material-icons icon-16pt">delete</i>--}}
{{--                        </a>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="row card-body pagination-light justify-content-center text-center">
        {{ $products->links() }}
    </div>
@else
    <div class="row justify-content-center card-body pb-0 pt-5">
        <i class="material-icons fs-64px">store</i>
    </div>
    <div class="row justify-content-center card-body pb-5">
        <p class="h4">{{ __('messages.no_products_yet') }}</p>
    </div>
@endif