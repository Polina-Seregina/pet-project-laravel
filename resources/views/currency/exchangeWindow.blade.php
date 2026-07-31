
<div class="ht-plan-inner">
    <div class="ht-plan-header">
        <div style="text-align: center; ">
            <h3 class="plan-title" style="font-size: 20px;"> Обменник </h3>
            <h2 class="plan-price" style="font-size: 25px;"> {{ $balanceInNewCurrency}} {{ $currency }} <span class="month d-none">/m</span></h2>
        </div>
        <div class="price-border" style="margin-bottom: 0"></div>
    </div>
    <div style="text-align: center;">
    <form method="post" action="{{route('currency')}}" class="mt-6 space-y-6">
        @csrf
        <p class="plan-desc" style="font-size: 18px; "> Будь в курсе </p>
        <div class="footer-widget mb-30" style="text-align: center; font-size: 18px; padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px;">
            <div class="subscribe-form sub-form-2 mt-1">
                <select name="currency" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="{{ App\Enums\CurrencyEnum::RUB }}"> {{ App\Enums\CurrencyEnum::RUB }} </option>
                    <option value="{{ App\Enums\CurrencyEnum::EUR }}"> {{ App\Enums\CurrencyEnum::EUR }} </option>
                    <option value="{{ App\Enums\CurrencyEnum::CNY }}"> {{ App\Enums\CurrencyEnum::CNY }} </option>
                </select>
            </div> 

            <div style="height: 30px;"></div>

            <div style="text-align: center;">
                <button type="submit"> <a class="ht-btn"> Перевести </a> </button>
            </div>
        </div>
    </form>
    </div>
</div>