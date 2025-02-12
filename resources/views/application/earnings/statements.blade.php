@extends('layouts.app', ['page' => 'earnings'])

@section('title', __('messages.statements'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.statements') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.statements') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        @include('application.earnings._tabs')

        @if($statements->count() > 0)
            <div class="table-responsive">
                <table class="table table-xl mb-0 thead-border-top-0 table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.#id') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.details') }}</th>
                            <th>{{ __('messages.fee') }}</th>
                            <th>{{ __('messages.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="statements">
                        @foreach ($statements as $statement)
                            <tr>
                                <td class="h6">
                                    {{ \Carbon\Carbon::parse($statement->created_at)->format(get_company_setting('date_format', $currentCompany->id)) }} 
                                </td>
                                <td class="h6">
                                    #{{ $statement->meta['order_id'] }}
                                </td>
                                <td class="h6">
                                    @if($statement->type == 'deposit')
                                        <div class="badge badge-dark fs-0-9rem">
                                            {{ __('messages.' . $statement->type) }}
                                        </div>
                                    @elseif($statement->type == 'withdraw')
                                        <div class="badge badge-info fs-0-9rem">
                                            {{ __('messages.' . $statement->type) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="h6 d-inline-block text-truncate maxw-13rem">
                                    {{ $statement->meta['invoice'] }}
                                </td>
                                <td class="h6">
                                    {!! array_key_exists('fee', $statement->meta) ? $statement->meta['fee'] : '-' !!}
                                </td>
                                <td class="h6">
                                    {!! money($statement->amount, $statement->meta['currency']) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row card-body pagination-light justify-content-center text-center">
                {{ $statements->links() }}
            </div>
        @else
            <div class="row justify-content-center card-body pb-0 pt-5">
                <i class="material-icons fs-64px">monetization_on</i>
            </div>
            <div class="row justify-content-center card-body pb-5">
                <p class="h4">{{ __('messages.no_statements_yet') }}</p>
            </div>
        @endif
    </div>
@endsection
