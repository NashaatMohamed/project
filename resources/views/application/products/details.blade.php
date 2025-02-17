{{--@extends('layouts.app', ['page' => 'products'])--}}

{{--@section('title', __('messages.product_details'))--}}

{{--@section('page_header')--}}
{{--    <div class="page__heading d-flex align-items-center">--}}
{{--        <div class="flex">--}}
{{--            <nav aria-label="breadcrumb">--}}
{{--                <ol class="breadcrumb mb-0">--}}
{{--                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>--}}
{{--                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a></li>--}}
{{--                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.product_details') }}</li>--}}
{{--                </ol>--}}
{{--            </nav>--}}
{{--            <h1 class="m-0">{{ __('messages.product_details') }}</h1>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@section('content')--}}
{{--    <div class="card">--}}
{{--        <div class="row pl-4 pr-4">--}}
{{--            <div class="col-12 col-md-3 mt-4 mb-4">--}}
{{--                <h5>{{ __('messages.details') }}</h5>--}}
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.name') }}:</strong> {{ $product->name }} <br>--}}
{{--                </p>--}}
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.code') }}:</strong> {{ $product->code }} <br>--}}
{{--                </p>--}}
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.price') }}:</strong> {{ $product->price }} <br>--}}
{{--                </p>--}}
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.unit') }}:</strong> {{ $product->unit->name ?? 'N/A' }} <br>--}}
{{--                </p>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-md-3 mt-4 mb-4">--}}
{{--                <h5>{{ __('messages.description') }}</h5>--}}
{{--                <p>--}}
{{--                    {{ $product->description }}--}}
{{--                </p>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-md-3 mt-4 mb-4">--}}
{{--                <h5>{{ __('messages.stock') }}</h5>--}}
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.opening_stock') }}:</strong> {{ $product->opening_stock }} <br>--}}
{{--                </p>--}}
{{--                <p class="mb-1">--}}
{{--                    <strong>{{ __('messages.quantity_alarm') }}:</strong> {{ $product->quantity_alarm }} <br>--}}
{{--                </p>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-md-3 text-right mt-4 mb-4">--}}
{{--                <a href="{{ route('products.edit', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-primary">--}}
{{--                    <i class="material-icons">edit</i>--}}
{{--                    {{ __('messages.edit') }}--}}
{{--                </a>--}}
{{--                <a href="{{ route('products.delete', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-danger delete-confirm">--}}
{{--                    <i class="material-icons">delete</i>--}}
{{--                    {{ __('messages.delete') }}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <nav class="nav nav-pills nav-justified w-100" role="tablist">--}}
{{--        <a href="#variations" class="h6 nav-item nav-link bg-secondary text-white active show" data-toggle="tab" role="tab" aria-selected="true">{{ __('messages.variations') }}</a>--}}
{{--    </nav>--}}

{{--    <div class="tab-content">--}}
{{--        <div class="tab-pane active show" id="variations">--}}
{{--            <div class="card">--}}
{{--                <div class="table-responsive">--}}
{{--                    <table class="table mb-0 thead-border-top-0 table-striped">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>{{ __('messages.variation_name') }}</th>--}}
{{--                            <th>{{ __('messages.price') }}</th>--}}
{{--                            <th>{{ __('messages.quantity') }}</th>--}}
{{--                            <th>{{ __('messages.sku') }}</th>--}}
{{--                            <th>{{ __('messages.colors') }}</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @if($product->ProductVariations->count() > 0)--}}
{{--                            @foreach($product->ProductVariations as $variation)--}}
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    {{ $variation->getFullProductName() }}--}}
{{--                                </td>--}}
{{--                                <td>{{ $variation->price }}</td>--}}
{{--                                <td>{{ $variation->quantity }}</td>--}}
{{--                                <td>{{ $variation->sku }}</td>--}}
{{--                                <td>--}}
{{--                                    @if(isset($variation->productVariationColors))--}}
{{--                                        @foreach($variation->productVariationColors as $productVariationColor)--}}
{{--                                            <span class="badge badge-primary">{{ $productVariationColor->color }}: {{ $productVariationColor->quantity  }}</span><br>--}}
{{--                                        @endforeach--}}
{{--                                    @else--}}
{{--                                        N/A--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        @else--}}
{{--                            <tr>--}}
{{--                                <td colspan="5" class="text-center">{{ __('messages.no_variations_found') }}</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}




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
    <!-- Group Buttons -->
    <div class="card mb-4">
        <div class="card-body text-right">
            <div class="btn-group" role="group">
                <!-- Edit Product Button -->
                <a href="{{ route('products.edit', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-primary">
                    <i class="material-icons">edit</i> {{ __('messages.edit_product') }}
                </a>
                <!-- Transfer Product Button -->
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#transferProductModal">
                    <i class="material-icons">swap_horiz</i> {{ __('messages.transfer_product') }}
                </button>
                <!-- Delete Product Button -->
                <a href="{{ route('products.delete', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-danger delete-confirm">
                    <i class="material-icons">delete</i> {{ __('messages.delete_product') }}
                </a>
                <!-- Add/Subtract Stock Button -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#stockModal">
                    <i class="material-icons">add</i> {{ __('messages.add_subtract_stock') }}
                </button>
                <!-- Manage Barcode/Model Button -->
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#barcodeModal">
                    <i class="material-icons">qr_code</i> {{ __('messages.manage_barcode') }}
                </button>
                <!-- Print Product Label Button -->
                <button type="button" class="btn btn-success" onclick="window.print()">
                    <i class="material-icons">print</i> {{ __('messages.print_label') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ __('messages.product_details') }}</h5>
                    <p class="mb-1">
                        <strong>{{ __('messages.name') }}:</strong> {{ $product->name }} <br>
                    </p>
                    <p class="mb-1">
                        <strong>{{ __('messages.code') }}:</strong> {{ $product->code }} <br>
                    </p>
{{--                    <p class="mb-1">--}}
{{--                        <strong>{{ __('messages.category') }}:</strong> {{ $product->category->name ?? 'N/A' }} <br>--}}
{{--                    </p>--}}
                    <p class="mb-1">
                        <strong>{{ __('messages.unit') }}:</strong> {{ $product->unit->name ?? 'N/A' }} <br>
                    </p>
                    <p class="mb-1">
                        <strong>{{ __('messages.brand') }}:</strong> {{ $product->brand->name ?? 'N/A' }} <br>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>{{ __('messages.stock_details') }}</h5>
                    <p class="mb-1">
                        <strong>{{ __('messages.incoming_stock') }}:</strong> {{ $product->incoming_stock }} <br>
                    </p>
                    <p class="mb-1">
                        <strong>{{ __('messages.outgoing_stock') }}:</strong> {{ $product->outgoing_stock }} <br>
                    </p>
                    <p class="mb-1">
                        <strong>{{ __('messages.current_stock') }}:</strong> {{ $product->current_stock }} <br>
                    </p>
                    <p class="mb-1">
                        <strong>{{ __('messages.price') }}:</strong> {{ $product->price }} <br>
                    </p>
                    <p class="mb-1">
                        <strong>{{ __('messages.tax') }}:</strong> {{ $product->tax->name ?? 'N/A' }} <br>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <nav class="nav nav-pills nav-justified w-100 mb-4" role="tablist">
        <a href="#variations" class="nav-item nav-link" data-toggle="tab" role="tab">{{ __('messages.variations') }}</a>
        <a href="#incoming" class="nav-item nav-link active" data-toggle="tab" role="tab">{{ __('messages.incoming_stock') }}</a>
        <a href="#outgoing" class="nav-item nav-link" data-toggle="tab" role="tab">{{ __('messages.outgoing_stock') }}</a>
        <a href="#movements" class="nav-item nav-link" data-toggle="tab" role="tab">{{ __('messages.stock_movements') }}</a>
        <a href="#customers" class="nav-item nav-link" data-toggle="tab" role="tab">{{ __('messages.customers') }}</a>
    </nav>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Incoming Stock Tab -->
        <div class="tab-pane fade show active" id="incoming">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.supplier') }}</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        @forelse($product->incomingStock as $stock)--}}
                        @forelse([] as $stock)
                            <tr>
                                <td>{{ $stock->date }}</td>
                                <td>{{ $stock->quantity }}</td>
                                <td>{{ $stock->supplier->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('messages.no_incoming_stock_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Outgoing Stock Tab -->
        <div class="tab-pane fade" id="outgoing">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.customer') }}</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        @forelse($product->outgoingStock as $stock)--}}
                        @forelse([] as $stock)
                            <tr>
                                <td>{{ $stock->date }}</td>
                                <td>{{ $stock->quantity }}</td>
                                <td>{{ $stock->customer->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('messages.no_outgoing_stock_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock Movements Tab -->
        <div class="tab-pane fade" id="movements">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.notes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        @forelse($product->stockMovements as $movement)--}}
                        @forelse([] as $movement)
                            <tr>
                                <td>{{ $movement->date }}</td>
                                <td>{{ $movement->type }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('messages.no_stock_movements_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customers Tab -->
        <div class="tab-pane fade" id="customers">
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
{{--                        @forelse($product->getCustomers() as $customer)--}}
                        @forelse([] as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->quantity }}</td>
                                <td>{{ $customer->total_amount }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('messages.no_customers_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Variations Tab -->
        <div class="tab-pane fade" id="variations">
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
                                                <span class="badge badge-primary">{{ $productVariationColor->color }}: {{ $productVariationColor->quantity  }}</span><br>
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
    </div>

{{--    <!-- Modals -->--}}
{{--    @include('products.modals.transfer')--}}
{{--    @include('products.modals.stock')--}}
{{--    @include('products.modals.barcode')--}}
@endsection









































