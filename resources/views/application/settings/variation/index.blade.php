@extends('layouts.app', ['page' => 'settings'])

@section('title', __('messages.variation_groups'))

@section("css_custom")
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f5f5f5;
        }

        .sortable-chosen {
            background: #f9f9f9;
        }

        .sortable-drag {
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="page__heading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">business</i></a></li>
                <li class="breadcrumb-item">{{ __('messages.settings') }}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.variations') }}</li>
            </ol>
        </nav>
        <h1 class="m-0">{{ __('messages.variations') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-3">
            @include('application.settings._aside', ['tab' => 'variation'])
        </div>
        <div class="col-lg-9">
            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col card-form__body card-body bg-white">

                        <div class="form-row align-items-center mb-4">
                            <div class="col">
                                <p class="h4 mb-0">
                                    <strong class="headings-color">{{ __('messages.variations') }}</strong>
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('settings.variation.create', ['company_uid' => $currentCompany->uid]) }}" class="btn btn-primary text-white">
                                    {{ __('messages.add_variation') }}
                                </a>
                            </div>
                        </div>

                        @if($variations->count() > 0)
                            <div class="table-responsive" data-toggle="lists">
                                <table class="table table-xl mb-0 thead-border-top-0 table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th class="w-30">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list" id="variation">
                                        @foreach($variations as $variation)
                                            <tr>
                                                <td class="sortable-handle">
                                                    <i class="fas fa-grip-vertical "></i>
                                                </td>
                                                <td class="h6">
                                                    <a href="{{ route('settings.variation.edit', ['variation' => $variation->id, 'company_uid' => $currentCompany->uid]) }}">
                                                        <strong class="h6">
                                                            {{ $variation->name }}
                                                        </strong>
                                                    </a>
                                                </td>

                                                <td class="h6">
                                                    <a href="{{ route('settings.variation.edit', ['variation' => $variation->id, 'company_uid' => $currentCompany->uid]) }}" class="btn text-primary">
                                                        <i class="material-icons icon-16pt">edit</i>
                                                        {{ __('messages.edit') }}
                                                    </a>
                                                    <a href="{{ route('settings.variation.delete', ['variation' => $variation->id, 'company_uid' => $currentCompany->uid]) }}" class="btn text-danger delete-confirm">
                                                        <i class="material-icons icon-16pt">delete</i>
                                                        {{ __('messages.delete') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row card-body pagination-light justify-content-center text-center">
                                {{ $variations->links() }}
                            </div>
                        @else
                            <div class="row justify-content-center card-body pb-0 pt-5">
                                <i class="material-icons fs-64px">account_balance_wallet</i>
                            </div>
                            <div class="row justify-content-center card-body pb-5">
                                <p class="h4">{{ __('messages.no_variation') }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("page_body_scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {


            // Initialize sortable
            var sortable = new Sortable(document.querySelector('#variation'), {
                handle: '.sortable-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
            });


        });
    </script>
@endsection
