@extends('layouts.installer')

@section('title', __('Update Overview'))

@section('content')
    <p class="text-center">
        {{ __('There are :number updates.', ['number' => $numberOfUpdatesPending]) }}
    </p>

    <p class="text-center">
        <a class="btn btn-primary px-4 fs-6" href="{{ route('updater.database') }}">
            {{ __('Install Updates') }}
        </a>
    </p>
@endsection
