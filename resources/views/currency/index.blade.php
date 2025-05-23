<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="currency"
        :url="route('admin.currency.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'sort' => __('main.attr_sort'),
            'name' => __('main.attr_name'),
            'slug' => __('main.attr_slug'),
            'is_default' => __('main.attr_is_default'),
        ]"
        :default="['sort', 'name', 'slug']"
        :defaultSort="['sort' => 'asc']"
        :title="__('main.list_currency')"
        :actions="[
            'create' => route('admin.currency.create'),
            'delete' => route('admin.currency.delete'),
        ]"
    />

    <p class="header text-big">{{ __('main.exchange_rates') }}</p>

    @if ($rates)
        <table class="info">
            <thead>
            <tr>
                <th class="col-3"><span class="text-small">{{ __('main.currency') }}</span></th>
                <th class="col-3"><span class="text-small">{{ __('main.exchange_rate') }}</span></th>
                <th class="col-6"><span class="text-small">{{ __('main.attr_updated_at') }}</span></th>
                <th class="col-3"><span class="text-small">{{ __('main.exchange_provider') }}</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rates as $rate)
                <tr>
                    <td><span class="text">{{ $rate['from'] }}</span></td>
                    <td><span class="text">{{ $rate['to'] }}</span></td>
                    <td><span class="text">{{ $rate['date'] }}</span></td>
                    <td><span class="text">{{ $rate['provider'] }}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="text">
            {{ __('main.general_list_empty') }}
        </p>
    @endif
</x-cms::layout.admin>
