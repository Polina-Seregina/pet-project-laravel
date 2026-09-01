@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            ПОПОЛНИТЬ КОШЕЛЁК
        </h3>
    </div>
</section>

<div style="display: flex; justify-content: center; align-items: center">
    <div style="text-align: center;">
        <div class="pt-1 p-3 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="blockquote mt-10 mb-30 ml-10 mr-10">
                <p class="mb-2" style="font-weight: normal; font-size: 20px;" class="name"> Текущий баланс </p> 
                <p style="font-weight: normal; font-size: 30px;" class="name"> {{ $wallet->balance }} USD </p> 
            </div>
        </div>

        <div style="text-align: center;">
            <form method="post" action="{{ route('wallet.replenishment') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                @method('patch')
                    <div>
                        <x-input-label for="amount" :value="__('Введите сумму, USD')" />
                        <div style="display: flex; align-items: center; gap: 1px;">
                            <input id="amount" name="amount" type="number" min="0" step="0.01" style="display: block; margin: 0 auto;"/>
                        </div>
                    </div>

                <div style="text-align: center;">
                    <x-primary-button>{{ __('Пополнить') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>

<div style="height: 30px;"></div>

<div class="title-one text-center mt-20">
    <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
        <a href="javascript:history.back()"> <- Назад </a>
    </p>
</div>

@include('partials.footer')

@endsection