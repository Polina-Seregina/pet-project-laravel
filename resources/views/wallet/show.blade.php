@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            БАЛАНС СРЕДСТВ
        </h3>
    </div>
</section>

<div style="display: flex; justify-content: center; align-items: center">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" style="text-align: center;">
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
     
        @endif
        
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <blockquote class="blockquote mt-30 mb-30 ml-12" style="padding-left: 70px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <p style="font-weight: normal; font-size: 30px;" class="name">
                        @if ($balanceInNewCurrency)
                            {{$balanceInNewCurrency}}
                        @else
                            {{ $wallet->balance }} 
                        @endif
                        @include('currency.exchangeWindowWallet') 
                    </p> 
                </div>
            </blockquote>
        </div>
        
        <div style="text-align: center;">
            <button class="ht-btn bs-style mb-2 ml-0" type="submit">
                <a href="{{ route('wallet.replenishment.form') }}"> Пополнить кошелёк </a>
            </button>
        </div>

        <div style="text-align: center;">
            <button class="ht-btn bs-style mb-2 ml-0" type="submit">
                <a href="{{ route('transaction.history') }}"> История операций </a>
            </button>
        </div>
    </div>
</div>


@include('partials.footer')

@endsection