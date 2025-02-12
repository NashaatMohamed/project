@extends('layouts.installer')

@section('title', __('Installation Finished'))

@section('content')
	@if(session('message')['dbOutputLog'])
		<p><strong><small>{{ __('Migration &amp; Seed Console Output:') }}</small></strong></p>
		<kbd>
			<pre><code>{{ session('message')['dbOutputLog'] }}</code></pre>
		</kbd>
	@endif

	<p><strong><small>{{ __('Application Console Output:') }}</small></strong></p>
	<kbd>
		<pre><code>{{ $finalMessages }}</code></pre>
	</kbd>

	<p><strong><small>{{ __('Installation Log Entry:') }}</small></strong></p>
	<kbd>
		<pre><code>{{ $finalStatusMessage }}</code></pre>
	</kbd>

	<a class="btn btn-primary px-4 fs-6" href="{{ url('/') }}">
		{{ __('Click here to exit') }}
	</a>
@endsection
