@extends('layouts.app', ['page' => 'products'])

@section('title', __('messages.product_details'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a></li>
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
                    <strong>{{ __('messages.name') }}:</strong> {{ $product->name }} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.code') }}:</strong> {{ $product->code }} <br>
                </p>
                <p class="mb-1">
{{--                    <strong>{{ __('messages.price') }}:</strong> {!! money($product->price, $currentCompany->currency) !!} <br>--}}
                    <strong>{{ __('messages.price') }}:</strong> {{$product->price}} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.unit') }}:</strong> {{ $product->unit->name ?? 'N/A' }} <br>
                </p>
            </div>
            <div class="col-12 col-md-3 mt-4 mb-4">
                <h5>{{ __('messages.description') }}</h5>
                <p>
                    {{ $product->description }}
                </p>
            </div>
            <div class="col-12 col-md-3 mt-4 mb-4">
                <h5>{{ __('messages.stock') }}</h5>
                <p class="mb-1">
                    <strong>{{ __('messages.opening_stock') }}:</strong> {{ $product->opening_stock }} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.quantity_alarm') }}:</strong> {{ $product->quantity_alarm }} <br>
                </p>
            </div>
            <div class="col-12 col-md-3 text-right mt-4 mb-4">
                <a href="{{ route('products.edit', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-primary">
                    <i class="material-icons">edit</i>
                    {{ __('messages.edit') }}
                </a>
                <a href="{{ route('products.delete', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-danger delete-confirm">
                    <i class="material-icons">delete</i>
                    {{ __('messages.delete') }}
                </a>
            </div>
        </div>
    </div>

    <nav class="nav nav-pills nav-justified w-100" role="tablist">
        <a href="#variations" class="h6 nav-item nav-link bg-secondary text-white active show" data-toggle="tab" role="tab" aria-selected="true">{{ __('messages.variations') }}</a>
        <a href="#activities" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.activities') }}</a>
    </nav>

    <div class="tab-content">
        <div class="tab-pane active show" id="variations">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.variation_name') }}</th>
                            <th>{{ __('messages.price') }}</th>
{{--                            <th>{{ __('messages.tax') }}</th>--}}
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th>{{ __('messages.colors') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product->latestProductVariations as $variation)
                            <tr>
                                <td>
                                    {{$variation->getFullProductName()}}
                                </td>
{{--                                <td>{!! money($variation->price, $currentCompany->currency) !!}</td>--}}
                                <td>{{$variation->price}}</td>
{{--                                <td>--}}
{{--                                    @foreach($variation->taxes as $tax)--}}
{{--                                        {{ $tax }}<br>--}}
{{--                                    @endforeach--}}
{{--                                </td>--}}
                                <td>{{ $variation->quantity }}</td>
                                <td>{{ $variation->sku }}</td>
                                <td>
                                    @if(isset($variation->colors_quantity))
                                        @foreach($variation->colors_quantity as $color => $quantity)
                                            <span class="badge badge-primary">{{ $color }}: {{ $quantity }}</span><br>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
{{--        <div class="tab-pane" id="activities">--}}
{{--            <div class="container-fluid page__container">--}}
{{--                <p class="text-dark-gray d-flex align-items-center mt-3">--}}
{{--                    <i class="material-icons icon-muted mr-2">dvr</i>--}}
{{--                    <strong>{{ __('messages.activities') }}</strong>--}}
{{--                </p>--}}

{{--                @if($product->activities->count() > 0)--}}
{{--                    @foreach($product->activities as $activity)--}}
{{--                        <div class="row align-items-center projects-item mb-1">--}}
{{--                            <div class="col-sm-auto mb-1 mb-sm-0">--}}
{{--                                <div class="text-dark-gray">{{ $activity->created_at->format($currentCompany->getSetting('date_format')) }}</div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm">--}}
{{--                                <div class="card m-0">--}}
{{--                                    <div class="px-4 py-3">--}}
{{--                                        <div class="row align-items-center">--}}
{{--                                            <div class="col mw-300px">--}}
{{--                                                <div class="d-flex align-items-center">--}}
{{--                                                    <a class="text-body">--}}
{{--                                                        @if($activity->description == 'viewed')--}}
{{--                                                            <strong class="text-15pt mr-2">{{  __('messages.viewed_log', ['display_name' => $activity->causer ? $activity->causer->display_name : '#DELETED#']) }}</strong>--}}
{{--                                                        @else--}}
{{--                                                            <strong class="text-15pt mr-2">{{ $activity->description }}</strong>--}}
{{--                                                        @endif--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                @else--}}
{{--                    <div class="row align-items-center projects-item mb-1">--}}
{{--                        <div class="col-sm-auto mb-1 mb-sm-0"></div>--}}
{{--                        <div class="col-sm">--}}
{{--                            <div class="card m-0">--}}
{{--                                <div class="px-4 py-3">--}}
{{--                                    <div class="row align-items-center">--}}
{{--                                        <div class="col mw-300px">--}}
{{--                                            <div class="d-flex align-items-center">--}}
{{--                                                <a class="text-body">--}}
{{--                                                    <strong class="text-15pt mr-2">{{ __('messages.no_activities_yet') }}</strong>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection