@extends('layouts.installer')

@section('title', __('Step 1 | Server Requirements'))

@section('content')
    @foreach($requirements['requirements'] as $type => $requirement)
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item text-uppercase">
                {{ ucfirst($type) }}
                @if($type == 'php')
                    <small>
                        (Min. {{ $phpSupportInfo['minimum'] }})
                    </small>
                    <span class="float-right {{ $phpSupportInfo['supported'] ? 'text-success' : 'text-danger' }}">
                        <strong>
                            {{ $phpSupportInfo['current'] }}
                        </strong>
                        <i class="fa fa-fw fa-{{ $phpSupportInfo['supported'] ? 'check-circle' : 'exclamation-circle' }}" aria-hidden="true"></i>
                    </span>
                @endif
            </li>
            @foreach($requirements['requirements'][$type] as $extention => $enabled)
                <li class="list-group-item text-uppercase">
                    {{ $extention }}
                    <span class="float-right">
                        <i class="fa fa-fw fa-{{ $enabled ? 'check-circle text-success' : 'exclamation-circle text-danger' }}" aria-hidden="true"></i>
                    </span>
                </li>
            @endforeach
        </ul>
    @endforeach

    @if (!isset($requirements['errors']) && $phpSupportInfo['supported'] )
        <a class="btn btn-primary px-4 fs-6" href="{{ route('installer.permissions') }}">
            {{ __('Check Permissions') }}
        </a>
    @endif
@endsection