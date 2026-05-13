@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            ABOUT ART
        </h3>
    </div>
</section>

<section>

<div class="title-one text-center"> Name of the art </div>

<div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
    <section class="theme-banner-one">
        <div class="title-one text-center ">
             <h1 class="main-title z-2"> {{ $product->name }} </h1>
        </div>
    </section>

<div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">

    <div style="display: flex;">
        <div style="flex: 1">
            <div style="height: 30px;"></div>
            <div style="width:80% " class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mx-auto">
                <div class="title-one text-center"> 
                    Description 
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
                    Price 
                </div>
                <div style="height: 30px;"></div>
                <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
                    <section class="theme-banner-one">
                        <div class="title-one text-center ">
                            {{ $product->price }} $
                        </div>
                    </section>
                </div>

                <div class="title-one text-center"> 
                    Owner 
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
                    <button class="ht-btn bs-style mb-2" type="submit">
                        <a href="{{ route('product.edit', ['productId' => $product->id]) }}"> Edit art {{ $product->name }}</a>
                    </button>

                    <form method="post" action="{{ route('product.destroy', ['productId' => $product->id]) }}" class="p-6">
                        @csrf
                        @method('delete')
                    
                        <x-danger-button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Are you sure you want to delete your art?')"
                            >Delete the art
                        </x-danger-button>
                    </form>

                    @else
                    <button class="ht-btn bs-style mb-2" type="submit">
                        <a href=""> Buy art {{ $product->name }}</a>
                    </button>
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
            <a href="javascript:history.back()"> <- Back </a>
        </p>
    </div>
</div>

</section>

@include('partials.footer')

@endsection