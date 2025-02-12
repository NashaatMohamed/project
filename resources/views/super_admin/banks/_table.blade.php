@if($banks->count() > 0)
    <div class="table-responsive">
        <table class="table mb-0 thead-border-top-0 table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.#id') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th class="text-center width: 120px;">{{ __('messages.created_at') }}</th>
                    <th class="w-50px">{{ __('messages.edit') }}</th>
                </tr> 
            </thead>
            <tbody class="list" id="banks">
                @foreach ($banks as $bank)
                    <tr>
                        <td>
                            <a href="{{ route('super_admin.banks.edit', $bank->id) }}" class="badge">#{{ $bank->id }}</a>
                        </td>
                        <td> 
                            <a href="{{ route('super_admin.banks.edit', $bank->id) }}" class="mb-0">{{ $bank->name }}</a>
                        </td>
                        <td class="text-center"><i class="material-icons icon-16pt text-muted-light mr-1">today</i> {{ $bank->created_at->format('Y-m-d') }}</td>
                        <td><a href="{{ route('super_admin.banks.edit', $bank->id) }}" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">arrow_forward</i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row card-body pagination-light justify-content-center text-center">
        {{ $banks->links() }}
    </div>
@else
    <div class="row justify-content-center card-body pb-0 pt-5">
        <i class="material-icons fs-64px">account_box</i>
    </div>
    <div class="row justify-content-center card-body pb-5">
        <p class="h4">{{ __('messages.no_banks_yet') }}</p>
    </div>
@endif