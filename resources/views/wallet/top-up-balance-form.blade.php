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

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <section class="centered-section">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                <blockquote class="blockquote mt-10 mb-30 ml-10">
                    <p style="font-weight: normal; font-size: 20px;" class="name"> Balance </p> 
                    <p style="font-size: 30px" class="name"> {{ $wallet->balance }} </p> 
                </blockquote>
            </section>

            <form method="post" action="{{ route('wallet.replenishment') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                @method('patch')

                <blockquote class="mt-10 mb-30 ml-10">
                    <div>
                        <x-input-label for="amount" :value="__('Amount')" />
                        <input id="amount" name="amount" type="number" class="mt-1 block" min="0" step="0.01"/>
                    </div>
                </blockquote>

                <div class="flex items-center gap-4 mt-10 mb-30 ml-10">
                    <x-primary-button>{{ __('Top Up') }}</x-primary-button>
                </div>
            </form>
        </div> 
    </section>
</div>

@include('partials.footer')

@endsection