<div class="card-header bg-white p-0">
    <div class="row no-gutters flex nav">
        <a href="{{ route('earnings', ['company_uid' => $currentCompany->uid]) }}" class="col-2 border-right dashboard-area-tabs__tab card-body text-center {{ $tab == 'index' ? 'active' : '' }}">
            <span class="card-header__title m-0">
                {{ __('messages.earnings') }}
            </span>
        </a>
        <a href="{{ route('earnings.statements', ['company_uid' => $currentCompany->uid]) }}" class="col-2 border-right dashboard-area-tabs__tab card-body text-center {{ $tab == 'statements' ? 'active' : '' }}">
            <span class="card-header__title m-0">
                {{ __('messages.statements') }}
            </span>
        </a>
    </div>
</div>