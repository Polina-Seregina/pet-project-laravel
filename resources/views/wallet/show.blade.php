@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            WALLET
        </h3>
    </div>
</section>

<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" style="text-align: center;" >
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

            @if (session('status') === 'success')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Кошелек пополнен успешно') }}</p>
                
                <div style="height: 30px;"></div>
            @endif

            @if (session('status') !== 'success')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-red-500"
                >{{ session('status') }}</p>

                <div style="height: 30px;"></div>
            @endif

            <div style="display: flex; width: 100%;">
                <div style="width: 100%; display: flex; align-items: center;">
                    <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                        <blockquote class="blockquote mt-10 mb-30 ml-10">
                            <p style="font-weight: normal; font-size: 30px;" class="name"> Баланс </p>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <p class="name ml-10"> 
                                    @if ($balanceInNewCurrency)
                                        {{$balanceInNewCurrency}}
                                    @else
                                        {{ $wallet->balance }} 
                                    @endif
                                    @include('currency.exchangeWindowWallet') 
                                </p> 
                            </div>
                        </blockquote>
                    </section>
                </div>
            </div>
        </div>    

        <button class="ht-btn bs-style mb-2 ml-0" type="submit">
            <a href="{{ route('wallet.replenishment.form') }}"> Пополнить кошелёк </a>
        </button>

        <button class="ht-btn bs-style mb-2 ml-10" type="submit">
            <a href="{{ route('transaction.history') }}"> История операций </a>
        </button>
    </div>
</div>


@include('partials.footer')

@endsection