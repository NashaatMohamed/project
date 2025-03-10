<div id="header" class="mdk-header bg-dark js-mdk-header m-0">
    <div class="mdk-header__bg">
        <div class="mdk-header__bg-front"></div>
        <div class="mdk-header__bg-rear"></div>
    </div>
    <div class="mdk-header__content">
        <div class="navbar navbar-expand-sm navbar-main navbar-light bg-white pr-0 mdk-header--fixed" id="navbar" data-primary="data-primary">
            <div class="container p-0">
                <a href="{{ route('customer_portal.dashboard', $currentCustomer->uid) }}" class="navbar-brand">
                    @if(get_system_setting('application_logo'))
                        <img class="navbar-brand-icon" src="{{ get_system_setting('application_logo') }}" width="125" alt="{{ get_system_setting('application_name') }}">
                    @else
                        <span>{{ get_system_setting('application_name') }}</span>
                    @endif
                </a>

                <div class="navbar navbar-secondary navbar-light navbar-expand-sm p-0">
                    <button class="navbar-toggler navbar-toggler-right" data-toggle="collapse" data-target="#portalNav"
                        type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="portalNav">
                        <ul class="nav navbar-nav">
                            <li class="nav-item">
                                <a href="{{ route('customer_portal.dashboard', $currentCustomer->uid) }}"
                                    class="nav-link {{ $page == 'dashboard' ? 'active' : '' }}">
                                    {{ __('messages.dashboard') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer_portal.invoices', $currentCustomer->uid) }}"
                                    class="nav-link {{ $page == 'invoices' ? 'active' : '' }}">
                                    {{ __('messages.invoices') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer_portal.credit_notes', $currentCustomer->uid) }}"
                                    class="nav-link {{ $page == 'credit_notes' ? 'active' : '' }}">
                                    {{ __('messages.credit_notes') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer_portal.estimates', $currentCustomer->uid) }}"
                                    class="nav-link {{ $page == 'estimates' ? 'active' : '' }}">
                                    {{ __('messages.estimates') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer_portal.payments', $currentCustomer->uid) }}"
                                    class="nav-link {{ $page == 'payments' ? 'active' : '' }}">
                                    {{ __('messages.payments') }}
                                </a>
                            </li>
                            @if (count($languages) > 1)
                                <li>
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="changeLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ app()->getLocale() }}
                                        </button>
                                        <div class="dropdown-menu overflow-hidden" aria-labelledby="changeLanguage">
                                            @foreach ($languages as $language => $name)
                                                <a class="dropdown-item" href="/change-language/{{ $language }}">{{ $name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
