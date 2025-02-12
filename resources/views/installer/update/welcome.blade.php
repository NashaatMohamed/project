@extends('layouts.installer')

@section('title', __('Welcome To The Updater'))

@section('content')
    <p class="text-center">
        {{ __('Welcome to the update wizard.') }}
    </p>

    <p class="text-center">
        <a class="btn btn-primary px-4 fs-6" href="{{ route('updater.overview') }}">
            {{ __('Next') }}
        </a>
    </p>
@endsection
