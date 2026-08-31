@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            АРТ
        </h3>
    </div>
</section>

<section>

<div class="title-one text-center"> Название </div>

<div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
    <section class="theme-banner-one">
        <div class="title-one text-center ">
             <h1 class="main-title z-2"> {{ $product->name }} </h1>
        </div>
    </section>

    <div class="column text-center mb-50">
        @if (session('status') === 'success')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600"
            >{{ __("Поздравляем! Вы купили новый арт.") }}</p>
        @endif

        @if (session('status') !== 'success')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600"
            >{{ session('status') }}</p>
        @endif
    </div>

<div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">

    <div style="display: flex;">
        <div style="flex: 1">
            <div style="height: 30px;"></div>
            <div style="width:80% " class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mx-auto">
                <div class="title-one text-center"> 
                    Описание 
                </div>
                <div style="height: 30px;"></div>
                <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
                    <section class="theme-banner-one">
                        <div class="title-one text-center ">
                            {{ $product->description }} 
                        </div>
                    </section>
                </div>

                <div class="title-one text-center"> 
                    Автор
                </div>
                <div style="height: 30px;"></div>
                <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
                    <section class="theme-banner-one">
                        <div class="title-one text-center ">
                            {{ $product->author->profile->nickname ?? 'данных нет' }} 
                        </div>
                    </section>
                </div>

                <div class="title-one text-center"> 
                    Стоимость
                </div>
                <div style="height: 30px;"></div>
                <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
                    <section class="theme-banner-one">
                        <div class="title-one text-center" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                            @if ($priceInNewCurrency)
                                {{ $priceInNewCurrency}} 
                            @else
                                {{ $product->price }} 
                            @endif
                            @include('currency.exchangeWindowProduct')
                        </div>
                    </section>
                </div>

                <div class="title-one text-center"> 
                    Владелец 
                </div>
                <div style="height: 30px;"></div>
                <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
                    <section class="theme-banner-one">
                        <div class="title-one text-center ">
                                <a href="">{{ $product->user->profile->nickname }}</a>
                        </div>
                    </section>
                </div>
                <div class="title-one text-center ">
                    @if ($user->id === $product->user->id)
                    <div class="title-one text-center"> 
                        Статус
                    </div>
                    <div style="height: 30px;"></div>
                    <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
                        <section class="theme-banner-one">
                            <div class="title-one text-center ">
                                <a style="color: #cc722d;" class="pr-price">{{ $product->status->label() }} </a>
                            </div>
                        </section>
                    </div>
                    
                    <button class="ht-btn bs-style mb-2" type="submit">
                        <a href="{{ route('products.edit', ['product' => $product]) }}">  Редактировать арт {{ $product->name }}</a>
                    </button>

                    <form method="post" action="{{ route('products.destroy', ['product' => $product]) }}" class="p-6">
                        @csrf
                        @method('delete')
                    
                        <x-danger-button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Вы уверены, что хотите удалить арт?')"
                            >Удалить
                        </x-danger-button>
                    </form>

                    @else
                    <form method="post" action="{{ route('products.buy', ['product' => $product]) }}" class="mt-6 space-y-6">
                        @csrf

                        <button class="ht-btn bs-style mb-2" type="submit">
                            Купить {{ $product->name }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div style="flex: 1; display: flex; ">
            <div class="column mb-50" style="display: flex; align-items: center; justify-content: center;">
                <img class="ml-10" width="400" src="{{ Storage::disk('s3')->url($product->image) }}">
            </div>
        </div>
    </div>
    <div style="height: 30px;"></div>
    <div style="height: 30px;"></div>

    <div class="title-one text-center ">
        <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
            <a href="javascript:history.back()"> <- Назад </a>
        </p>
    </div>
</div>

</section>

@include('partials.footer')

@endsection