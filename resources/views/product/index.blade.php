@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            КАТАЛОГ
        </h3>
    </div>
</section>

<section class="max-w-7xl mx-auto sm:px-8">
@if (count($products) !== 0)
    <div style='display: flex; justify-content: center; align-items: center;'>
        @foreach ($products as $product)
            <div style="width:40% " class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="column product-wrapper text-center mb-50">
                        <div class="product-img mb-10" style="display: flex; align-items: center; justify-content: center;">
                            <a href="{{ route('products.show', ['product' => $product]) }}">
                                <img width="200" src="{{ Storage::disk('s3')->url($product->image) }}" alt="wheel">
                            </a>
                        </div>
                        <h4 class="product-title mt-9 mb-0"><a href="{{ route('products.show', ['product' => $product]) }}"> {{ $product->name }} </a></h4>
                        <a class="pr-price" href="{{ route('products.show', ['product' => $product]) }}">{{ $product->price }} $ </a>
                        <h4 class="product-title mb-0"><a href="{{ route('products.show', ['product' => $product]) }}"> {{ $product->user->profile->nickname }} </a></h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $products->links() }}
@else
    <div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
        <div class="p-2 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="column product-wrapper text-center mb-50">
                <div style="height: 30px;"></div>
                <h4 class="product-title mt-9 mb-0"><a href=""> Пока здесь пусто — стань первым автором! </a></h4>
                <div style="height: 30px;"></div>
            </div>
        </div>
    </div>
@endif
</section>

@include('partials.footer')

@endsection