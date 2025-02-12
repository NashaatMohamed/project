@if($withdraw_requests->count() > 0)
    <div class="table-responsive">
        <table class="table mb-0 thead-border-top-0 table-striped">
            <thead>
                <tr>
                    <th class="w-30px" class="text-center">{{ __('messages.#id') }}</th>
                    <th>{{ __('messages.company') }}</th>
                    <th>{{ __('messages.requested_by') }}</th>
                    <th>{{ __('messages.currency') }}</th>
                    <th>{{ __('messages.amount_to_deposit') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.requested_at') }}</th>
                    <th class="w-50px">{{ __('messages.view') }}</th>
                </tr> 
            </thead> 
            <tbody class="list" id="withdraw_requests">
                @foreach ($withdraw_requests as $withdraw_request)
                    <tr>
                        <td>
                            <a class="mb-0" href="{{ route('super_admin.withdraw_requests.edit', $withdraw_request->id) }}">{{ $withdraw_request->id }}</a>
                        </td>
                        <td>
                            <p class="mb-0">{{ $withdraw_request->company->name }}</p>
                        </td>
                        <td>
                            <p class="mb-0">{{ $withdraw_request->requested_by_user->full_name }}</p>
                        </td>
                        <td>
                            <p class="mb-0">{{ $withdraw_request->wallet_currency }}</p>
                        </td>
                        <td>
                            <p class="mb-0">{{ $withdraw_request->amount_to_deposit }} {{ $withdraw_request->wallet_currency }}</p>
                        </td>
                        <td>
                            {!! $withdraw_request->html_status !!}
                        </td>
                        <td class="text-center">
                            <i class="material-icons icon-16pt text-muted-light mr-1">today</i> 
                            {{ $withdraw_request->created_at->format('Y-m-d') }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('super_admin.withdraw_requests.edit', $withdraw_request->id) }}" class="btn btn-sm btn-link">
                                <i class="material-icons icon-16pt">arrow_forward</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(method_exists($withdraw_requests, 'links'))
        <div class="row card-body pagination-light justify-content-center text-center">
            {{ $withdraw_requests->links() }}
        </div>
    @endif
@else
    <div class="row justify-content-center card-body pb-0 pt-5">
        <i class="material-icons fs-64px">account_box</i>
    </div>
    <div class="row justify-content-center card-body pb-5">
        <p class="h4">{{ __('messages.no_withdraw_requests_yet') }}</p>
    </div>
@endif