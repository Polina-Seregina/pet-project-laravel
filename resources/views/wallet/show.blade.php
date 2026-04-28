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
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

            @if (session('status') === 'Wallet top-up completed')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Wallet top-up completed.') }}</p>
            @endif

            <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                <blockquote class="blockquote mt-10 mb-30 ml-10">
                    <p style="font-weight: normal; font-size: 30px;" class="name"> Balance </p> 
                    <p class="name"> {{ $wallet->balance }} деняк </p> 
                </blockquote>
            </section>
        </div>    

        <button class="ht-btn bs-style mb-2 ml-10" type="submit">
            <a href="{{ route('wallet.replenishment.form') }}"> Top up your balance </a>
        </button>
    </div>
</div>

@include('partials.footer')

@endsection