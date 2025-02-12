@extends('layouts.app', ['page' => 'super_admin.languages'])

@section('title', __('messages.languages'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('messages.languages') }}</a></li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.languages') }}</h1>
        </div>
        <a href="{{ route('super_admin.languages.create') }}" class="btn btn-success ml-3"><i class="material-icons">add</i> {{ __('messages.add_new') }}</a>
    </div>
@endsection

@section('content') 
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 thead-border-top-0 table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.locale') }}</th>
                        <th class="w-50px">{{ __('messages.edit') }}</th>
                    </tr> 
                </thead> 
                <tbody class="list" id="languages">
                    @foreach($languages as $language => $name)
                        <tr>
                            <td>
                                {{ $name }}
                                @if (Config::get('app.locale') == $language)
                                    <div class="badge badge-primary fs-0-9rem ml-3">
                                        {{ __('messages.default') }}
                                    </div>
                                @endif
                            </td>
                            <td> 
                                <a href="{{ route('super_admin.languages.translations', $language) }}">
                                    {{ $language }}
                                </a>
                            </td>
                            <td><a href="{{ route('super_admin.languages.translations', $language) }}" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">arrow_forward</i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection