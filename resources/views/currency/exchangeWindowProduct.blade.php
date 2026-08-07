
    <form method="post" action="{{ route('products.currency', ['product' => $product]) }}" >
        @csrf
        <select name="currency" onchange="this.form.submit()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="USD" selected> USD </option>

            <option value="{{ App\Enums\CurrencyEnum::RUB }}"
                @if ($currency === App\Enums\CurrencyEnum::RUB->value)
                    selected
                @endif
            > {{ App\Enums\CurrencyEnum::RUB }} </option>

            <option value="{{ App\Enums\CurrencyEnum::EUR }}"
                @if ($currency  === App\Enums\CurrencyEnum::EUR->value)
                    selected
                @endif
            > {{ App\Enums\CurrencyEnum::EUR }} </option>

            <option value="{{ App\Enums\CurrencyEnum::CNY }}"
                @if ($currency  === App\Enums\CurrencyEnum::CNY->value)
                    selected
                @endif
            > {{ App\Enums\CurrencyEnum::CNY }} </option>
        </select>
    </form>
