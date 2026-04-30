@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            TRANSACTION HISTORY
        </h3>
    </div>
</section>

<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                <blockquote style="text-align: center;" class="mt-10 mb-30 ml-10">
                    @if (count($transactions) !== 0)
                    <table class="mb-6" style="width: 100%; border-collapse: collapse; rgb(0, 0, 0);">
                        <thead>
                            <tr>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Transaction type </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Amount </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Date </p> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td style="text-align: center; border: 1px solid"> {{ $transaction->type }} </td>
                                <td style="text-align: center; border: 1px solid"> {{ $transaction->amount }} </td>
                                <td style="text-align: center; border: 1px solid"> {{ $transaction->created_at }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{ $transactions->links() }}
                    @else
                    <div style="text-align: center;" class="footer-widget mb-10">
						<p class="show-product-count text-heding fw-medium" style="text-align: center; font-size: 20px;"> Транзакций пока не было</p>
                    </div>
                    <div style="text-align: center;" class="footer-widget mb-30">
                        <button class="ht-btn bs-style mb-2" type="submit">
                            <a href="{{ route('wallet.replenishment.form') }}"> Top up your balance </a>
                        </button>
					</div>
                    @endif
                </blockquote>
            </section>
        </div>    
    </div>
</div>

@include('partials.footer')

@endsection