@if($credit_notes->count() > 0)
    <div class="table-responsive">
        <table class="table table-xl mb-0 thead-border-top-0 table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.credit_note_number') }}</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.customer') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.total_credit') }}</th>
                    <th>{{ __('messages.remaining_balance') }}</th>
                    <th class="w-50px">{{ __('messages.view') }}</th>
                </tr>
            </thead>
            <tbody class="list" id="credit_notes">
                @foreach ($credit_notes as $credit_note)
                    <tr>
                        <td class="h6">
                            <a href="{{ route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}">
                                {{ $credit_note->credit_note_number }}
                            </a>
                        </td>
                        <td class="h6">
                            {{ $credit_note->formatted_credit_note_date }}
                        </td>
                        <td class="h6">
                            {{ $credit_note->customer->display_name }}
                        </td>
                        <td class="h6">
                            @if($credit_note->status == 'DRAFT')
                                <div class="badge badge-dark fs-0-9rem">
                                    {{ __('messages.' . $credit_note->status) }}
                                </div>
                            @elseif($credit_note->status == 'SENT')
                                <div class="badge badge-info fs-0-9rem">
                                    {{ __('messages.' . $credit_note->status) }}
                                </div>
                            @endif
                        </td>
                        <td class="h6">
                            {!! money($credit_note->total, $credit_note->currency_code) !!}
                        </td>
                        <td class="h6">
                            {!! money($credit_note->remaining_balance, $credit_note->currency_code) !!}
                        </td>
                        <td class="h6">
                            <a href="{{ route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-sm btn-link">
                                <i class="material-icons icon-16pt">arrow_forward</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row card-body pagination-light justify-content-center text-center">
        {{ $credit_notes->links() }}
    </div>
@else
    <div class="row justify-content-center card-body pb-0 pt-5">
        <i class="material-icons fs-64px">description</i>
    </div>
    <div class="row justify-content-center card-body pb-5">
        <p class="h4">{{ __('messages.no_credit_notes_yet') }}</p>
    </div>
@endif