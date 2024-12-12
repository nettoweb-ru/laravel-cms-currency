<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.currency.list', [], false)" id="currency" />

    @if (!empty($rates))
        <p class="header text-big">{{ __('cms-currency::main.exchange_rates') }}</p>
        <table class="info">
            <thead>
            <tr>
                <th class="col-6"><span class="text-small">{{ __('cms-currency::main.currency') }}</span></th>
                <th class="col-6"><span class="text-small">{{ __('cms-currency::main.exchange_rate') }}</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rates as $rate)
                <tr>
                    <td><span class="text">{{ $rate['from'] }}</span></td>
                    <td><span class="text">{{ $rate['to'] }}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</x-cms::layout.admin>
