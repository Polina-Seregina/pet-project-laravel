@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            ИСТОРИЯ ОПЕРАЦИЙ
        </h3>
    </div>
</section>


<div class="max-w-7xl mx-auto lg:px-8 space-y-6 ">
    <div class="p-4 bg-white shadow sm:rounded-lg  product-wrapper">
        @if (count($transactions) !== 0)
        <table class="mb-6" style="width: 100%; border-collapse: collapse; rgb(0, 0, 0);">
            <thead>
                <tr>
                    <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Тип операции </p> </th>
                    <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Сумма </p> </th>
                    <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Дата и время </p> </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <td style="text-align: center; border: 1px solid"> {{ $transaction->type->label() }} </td>
                    <td style="text-align: center; border: 1px solid"> {{ $transaction->amount }} </td>
                    <td style="text-align: center; border: 1px solid"> {{ $transaction->created_at }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $transactions->links() }}
        @else
        <div style="text-align: center;" class="mb-10">
            <p class="product-title mt-9 mb-0" style="text-align: center; font-size: 20px;"> Транзакций пока не было </p>
        </div>

        <div style="text-align: center;" class="footer-widget mb-30">
            <button class="ht-btn bs-style mb-2" type="submit">
                <a href="{{ route('wallet.replenishment.form') }}"> Пополнить баланс </a>
            </button>
        </div>
        @endif
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