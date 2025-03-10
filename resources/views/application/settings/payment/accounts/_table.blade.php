@if($withdraw_accounts->count() > 0)
    <div class="table-responsive" data-toggle="lists">
        <table class="table table-xl mb-0 thead-border-top-0 table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.name') }}</th> 
                    <th class="w-30">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="list" id="withdraw_accounts">
                @foreach($withdraw_accounts as $withdraw_account)
                    <tr>
                        <td class="h6">
                            <a href="{{ route('settings.payment.type.edit', ['type' => $withdraw_account->id, 'company_uid' => $currentCompany->uid]) }}">
                                <strong class="h6">
                                    {{ $withdraw_account->bank->name }} - {{ $withdraw_account->full_name }}
                                </strong>
                            </a>
                        </td>
                        <td class="h6">
                            <a href="{{ route('settings.payment.account.edit', ['account' => $withdraw_account->id, 'company_uid' => $currentCompany->uid]) }}" class="btn text-primary">
                                <i class="material-icons icon-16pt">edit</i>
                                {{ __('messages.edit') }}
                            </a>
                            <a href="{{ route('settings.payment.account.delete', ['account' => $withdraw_account->id, 'company_uid' => $currentCompany->uid]) }}" class="btn text-danger delete-confirm">
                                <i class="material-icons icon-16pt">delete</i>
                                {{ __('messages.delete') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="row justify-content-center card-body pb-0 pt-5">
        <i class="material-icons fs-64px">payment</i>
    </div>
    <div class="row justify-content-center card-body pb-5">
        <p class="h4">{{ __('messages.no_withdraw_accounts_yet') }}</p>
    </div>
@endif