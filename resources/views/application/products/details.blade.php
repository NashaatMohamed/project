@extends('layouts.app', ['page' => 'products'])

@section('title', __('messages.product_details'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.product_details') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.product_details') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <!-- Product Details Card -->
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
                    <strong>{{ __('messages.price') }}:</strong> {{ $product->price }} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.unit') }}:</strong> {{ $product->unit->name ?? 'N/A' }} <br>
                </p>

                <p class="mb-1">
                    <strong>{{ __('messages.category') }}:</strong> {{ $product->category->name ?? 'N/A' }} <br>
                </p>

                <p class="mb-1">
                    <strong>{{ __('messages.brand') }}:</strong> {{ $product->brand->name ?? 'N/A' }} <br>
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
                    <strong>{{ __('messages.incoming_stock') }}:</strong> {{ $product->incomingStock->sum("quantity") }} <br>
                </p>
                <p class="mb-1">
                    <strong>{{ __('messages.outgoing_stock') }}:</strong> {{ $product->outgoingStock->sum("quantity") }} <br>
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

    <!-- Tabs Navigation -->
    <nav class="nav nav-pills nav-justified w-100" role="tablist">
        <a href="#variations" class="h6 nav-item nav-link bg-secondary text-white active show" data-toggle="tab" role="tab" aria-selected="true">{{ __('messages.variations') }}</a>
        <a href="#incoming_stock" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.incoming_stock') }}</a>
        <a href="#outgoing_stock" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.outgoing_stock') }}</a>
        <a href="#stock_movements" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.stock_movements') }}</a>
        <a href="#customers" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.customers') }}</a>
    </nav>

    <!-- Tabs Content -->
    <div class="tab-content">
        <!-- Variations Tab -->
        <div class="tab-pane active show" id="variations">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.variation_name') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th>{{ __('messages.colors') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($product->ProductVariations->count() > 0)
                            @foreach($product->ProductVariations as $variation)
                                <tr>
                                    <td>{{ $variation->getFullProductName() }}</td>
                                    <td>{{ $variation->price }}</td>
                                    <td>{{ $variation->quantity }}</td>
                                    <td>{{ $variation->sku }}</td>
                                    <td>
                                        @if(isset($variation->productVariationColors))
                                            @foreach($variation->productVariationColors as $productVariationColor)
                                                <span class="badge badge-primary">{{ $productVariationColor->color }}: {{ $productVariationColor->quantity }}</span><br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">{{ __('messages.no_variations_found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Incoming Stock Tab -->
        <div class="tab-pane" id="incoming_stock">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{__("messages.product_variation")}}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{__('messages.reference')}}</th>
                            <th>{{__('messages.reference_type')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($product->incomingStock) && $product->incomingStock->count() > 0)
                            @foreach($product->incomingStock as $stock)
                                <tr>
                                    <td>{{$stock->productVariation->getFullProductName()}}</td>
                                    <td>{{ $stock->created_at->format($currentCompany->getSetting('date_format')) }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->reference}}</td>
                                    <td>{{\App\Factory\StockTypeFactory::getStockMovementType($stock->reference_type)}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">{{ __('messages.no_incoming_stock_found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Outgoing Stock Tab -->
        <div class="tab-pane" id="outgoing_stock">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{__("messages.product_variation")}}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{__('messages.reference')}}</th>
                            <th>{{__('messages.reference_type')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($product->outgoingStock) && $product->outgoingStock->count() > 0)
                            @foreach($product->outgoingStock as $stock)
                                <tr>
                                    <td>{{$stock->productVariation->getFullProductName()}}</td>
                                    <td>{{ $stock->created_at->format($currentCompany->getSetting('date_format')) }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->reference}}</td>
                                    <td>{{\App\Factory\StockTypeFactory::getStockMovementType($stock->reference_type)}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">{{ __('messages.no_outgoing_stock_found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Stock Movements Tab -->
        <div class="tab-pane" id="stock_movements">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{__("messages.product_variation")}}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{__('messages.reference')}}</th>
                            <th>{{__('messages.reference_type')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($product->stockMovements) && $product->stockMovements->count() > 0)
                            @foreach($product->stockMovements as $movement)
                                <tr>
                                    <td>{{$movement->productVariation->getFullProductName()}}</td>
                                    <td>{{ $movement->created_at->format($currentCompany->getSetting('date_format')) }}</td>
                                    <td>{{ $movement->type }}</td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->reference}}</td>
                                    <td>{{\App\Factory\StockTypeFactory::getStockMovementType($movement->reference_type)}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">{{ __('messages.no_stock_movements_found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Customers Tab -->
        <div class="tab-pane" id="customers">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.customer_name') }}</th>
                            <th>{{ __('messages.quantity_purchased') }}</th>
                            <th>{{ __('messages.total_amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($product->getCustomers() !== null && is_array($product->getCustomers()) && count($product->getCustomers()) > 0)
                            @foreach($product->getCustomers() as $customer)
                                <tr>
                                    <td>{{ $customer["customer"]->display_name }}</td>
                                    <td>{{ $customer['quantity_purchased'] }}</td>
                                    <td>{{ $customer['total_paid'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">{{ __('messages.no_customers_found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection