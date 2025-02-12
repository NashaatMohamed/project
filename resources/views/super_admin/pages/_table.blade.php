@if($pages->count() > 0)
    <div class="table-responsive">
        <table class="table mb-0 thead-border-top-0 table-striped">
            <thead>
                <tr>
                    <th class="w-30px" class="text-center">{{ __('messages.#id') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.status') }}</th> 
                    <th>{{ __('messages.order') }}</th> 
                    <th class="text-center width: 120px;">{{ __('messages.created_at') }}</th>
                    <th class="w-50px">{{ __('messages.edit') }}</th>
                    <th class="w-50px">{{ __('messages.view') }}</th>
                </tr> 
            </thead>
            <tbody class="list" id="pages">
                @foreach ($pages as $page)
                    <tr>
                        <td>
                            <a href="{{ route('super_admin.pages.edit', $page->id) }}" class="badge">#{{ $page->id }}</a>
                        </td>
                        <td> 
                            <a href="{{ route('super_admin.pages.edit', $page->id) }}" class="mb-0">{{ $page->name }}</a>
                        </td>
                        <td>
                            @if($page->is_active)
                                <div class="badge badge-success fs-0-9-rem">
                                    {{ __('messages.enabled') }}
                                </div>
                            @else
                                <div class="badge badge-danger fs-0-9-rem">
                                    {{ __('messages.disabled') }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $page->order }}</td>
                        <td class="text-center"><i class="material-icons icon-16pt text-muted-light mr-1">today</i> {{ $page->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('super_admin.pages.edit', $page->id) }}" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">arrow_forward</i></a>
                        </td>
                        <td>
                            <a href="{{ route('pages', $page->slug) }}" target="_blank" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">visibility</i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row card-body pagination-light justify-content-center text-center">
        {{ $pages->links() }}
    </div>
@else
    <div class="row justify-content-center card-body pb-0 pt-5">
        <i class="material-icons fs-64px">account_box</i>
    </div>
    <div class="row justify-content-center card-body pb-5">
        <p class="h4">{{ __('messages.no_pages_yet') }}</p>
    </div>
@endif