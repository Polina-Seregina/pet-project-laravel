

    <div style="text-align: center;">
    <form method="post" action="{{ route('products.currency', ['product' => $product]) }}" class="mt-6 space-y-6">
        @csrf
        <div class="footer-widget mb-30" style="text-align: center; font-size: 18px; padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px;">
            <div style="display: flex; justify-content: center; align-items: center;" >
                <select name="currency" style="margin: 0px 0px 0px 0px; font-size: 15px;" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="{{ App\Enums\CurrencyEnum::RUB }}"> {{ App\Enums\CurrencyEnum::RUB }} </option>
                    <option value="{{ App\Enums\CurrencyEnum::EUR }}"> {{ App\Enums\CurrencyEnum::EUR }} </option>
                    <option value="{{ App\Enums\CurrencyEnum::CNY }}"> {{ App\Enums\CurrencyEnum::CNY }} </option>
                </select>
                <button type="submit" style="background: none;"> <img width="50" src="{{ asset('images/shape/mark.png') }}"> </button>
            </div> 

            <div style="height: 30px;"></div>
        </div>
    </form>
    </div>