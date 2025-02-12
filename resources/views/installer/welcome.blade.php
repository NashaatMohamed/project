@extends('layouts.installer')

@section('title', __('Application Installer'))

@section('content')
    <p class="text-center">
        {{ __('Easy Installation and Setup Wizard.') }}
    </p>
    <p class="text-center">
        <a class="btn btn-primary px-4 fs-6" href="{{ route('installer.requirements') }}">
            {{ __('Check Requirements') }}
        </a>
    </p>
@endsection
