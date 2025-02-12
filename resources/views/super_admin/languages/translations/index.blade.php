@extends('layouts.app', ['page' => 'super_admin.languages'])

@section('title', __('messages.languages'))
    
@section('page_head_scripts') 
    <link type="text/css" href="{{ asset('assets/css/language.css') }}" rel="stylesheet">
@endsection

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex" style="display: block;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('messages.languages') }}</a></li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.languages') }}</h1>
        </div>
        <a href="{{ route('super_admin.languages.set_default', ['language' => $language]) }}" class="btn btn-success ml-3">{{ __('messages.set_as_default') }}</a>
    </div>  
@endsection 
 
@section('content') 
    <div id="app">
        <form action="{{ route('super_admin.languages.translations', ['language' => $language]) }}" method="get">
            <div class="panel">
                <div class="panel-header">
                    {{ __('messages.translations') }}
                    <div class="flex flex-grow justify-end items-center">
                        @include('super_admin.languages.translations._search', ['name' => 'filter', 'value' => Request::get('filter')])
                        @include('super_admin.languages.translations._select', ['name' => 'language', 'items' => $languages, 'submit' => true, 'selected' => $language])
                    </div>
                </div>

                <div class="panel-body">
                    @if(count($translations))
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-1/5 uppercase font-thin d-none">{{ __('messages.group_single') }}</th>
                                    <th class="w-1/5 uppercase font-thin d-none">{{ __('messages.key') }}</th>
                                    <th class="uppercase font-thin">{{ config('app.locale') }}</th>
                                    <th class="uppercase font-thin">{{ $language }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($translations as $type => $items)
                                    @foreach($items as $group => $translations)
                                        @foreach($translations as $key => $value)
                                            @if(!is_array($value[config('app.locale')]))
                                                <tr>
                                                    <td class="d-none">{{ $group }}</td>
                                                    <td class="d-none">{{ $key }}</td>
                                                    <td>{{ $value[config('app.locale')] }}</td>
                                                    <td>
                                                        <translation-input 
                                                            initial-translation="{{ $value[$language] }}" 
                                                            language="{{ $language }}" 
                                                            group="{{ $group }}" 
                                                            translation-key="{{ $key }}" 
                                                            route="admin/languages">
                                                        </translation-input>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@section('page_body_scripts') 
    <script src="{{ asset('assets/js/language.js') }}"></script>
@endsection