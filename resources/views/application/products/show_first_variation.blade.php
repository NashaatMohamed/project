@extends('layouts.app', ['page' => 'products'])

@section('title', __('messages.product_details'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.product_details') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.product_details') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="row pl-4 pr-4">
            <div class="col-12 col-md-3 mt-4 mb-4">
                <h5>{{ __('messages.details') }}</h5>
                <p class="mb-1">
                    <strong>{{ __('messages.name') }}:</strong> {{ $product_variation->getFullProductName() }} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.quantity') }}:</strong> {{ $product_variation->quantity }} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.price') }}:</strong> {{ $product_variation->price }} <br>
                </p>
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.unit') }}:</strong> {{ $product->unit->name ?? 'N/A' }} <br>--}}
{{--                </p>--}}
            </div>
            <div class="col-12 col-md-3 mt-4 mb-4">
                <h5>{{ __('messages.description') }}</h5>
                <p>
                    {{ $product_variation->product->description }}
                </p>
            </div>
            <div class="col-12 col-md-3 mt-4 mb-4">
                <h5>{{ __('messages.stock') }}</h5>
                <p class="mb-1">
                    <strong>{{ __('messages.quantity') }}:</strong> {{ $product_variation->quantity }} <br>
                </p>
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.quantity_alarm') }}:</strong> {{ $product->quantity_alarm }} <br>--}}
{{--                </p>--}}
            </div>
            <div class="col-12 col-md-3 text-right mt-4 mb-4">
{{--                <a href="{{ route('products.edit', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-primary">--}}
{{--                    <i class="material-icons">edit</i>--}}
{{--                    {{ __('messages.edit') }}--}}
{{--                </a>--}}
{{--                <a href="{{ route('products.delete', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-danger delete-confirm">--}}
{{--                    <i class="material-icons">delete</i>--}}
{{--                    {{ __('messages.delete') }}--}}
{{--                </a>--}}
            </div>
        </div>
    </div>

{{--    <div class="card">--}}
{{--        <div class="table-responsive">--}}
{{--            <table class="table mb-0 thead-border-top-0 table-striped">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>{{ __('messages.variation_name') }}</th>--}}
{{--                    <th>{{ __('messages.price') }}</th>--}}
{{--                    <th>{{ __('messages.quantity') }}</th>--}}
{{--                    <th>{{ __('messages.sku') }}</th>--}}
{{--                    <th>{{ __('messages.colors') }}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @if($product->ProductVariations->count() > 0)--}}
{{--                    @foreach($product->ProductVariations as $variation)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $variation->getFullProductName() }}</td>--}}
{{--                            <td>{{ $variation->price }}</td>--}}
{{--                            <td>{{ $variation->quantity }}</td>--}}
{{--                            <td>{{ $variation->sku }}</td>--}}
{{--                            <td>--}}
{{--                                @if(isset($variation->productVariationColors))--}}
{{--                                    @foreach($variation->productVariationColors as $productVariationColor)--}}
{{--                                        <span class="badge badge-primary">{{ $productVariationColor->color }}: {{ $productVariationColor->quantity  }}</span><br>--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    N/A--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                @else--}}
{{--                    <tr>--}}
{{--                        <td colspan="5" class="text-center">{{ __('messages.no_variations_found') }}</td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection