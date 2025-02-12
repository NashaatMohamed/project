@extends('layouts.installer')

@section('title', __('Step 2 | Permissions'))

@section('content')
    <ul class="list-group list-group-flush mb-4">
        @foreach($permissions['permissions'] as $permission)
            <li class="list-group-item {{ $permission['isSet'] ? 'success' : 'error' }}">
                {{ $permission['folder'] }}
                <span class="float-right">
                    <i class="fa fa-fw fa-{{ $permission['isSet'] ? 'check-circle text-success' : 'exclamation-circle text-danger' }}"></i>
                    {{ $permission['permission'] }}
                </span>
            </li>
        @endforeach
    </ul>

    @if (!isset($permissions['errors']))
        <a class="btn btn-primary px-4 fs-6" href="{{ route('installer.environment') }}">
            {{ __('Configure Application') }}
        </a>
    @endif
@endsection
