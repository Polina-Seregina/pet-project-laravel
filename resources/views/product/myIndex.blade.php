@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            MY ARTWORKS
        </h3>
    </div>
</section>

<section class="max-w-7xl mx-auto sm:px-8">
    <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
        <section class="theme-banner-one">
            <div class="title-one text-center ">
                <a href=" {{ route('product.create') }}"> 
                    <img style="float: left;" width="15" src="{{asset('images/shape/shape-4.png')}}"> 
                        Create new Art 
                    <img style="float: right;" width="15" src="{{asset('images/shape/shape-4.png')}}"> 
                </a>
            </div>
        </section>
    </div>

    @if (count($products) !== 0)

    <div style='display: flex; '>
        @foreach ($products as $product)
            <div style="width:40% " class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="column product-wrapper text-center mb-50">
                        <div class="product-img mb-10" style="display: flex; align-items: center; justify-content: center;">
                            <a class="pr-price" href="{{ route('product.show', ['productId' => $product->id]) }}">
                                <img class="ml-10" width="200" src="{{ Storage::disk('s3')->url($product->image) }}" alt="wheel">
                            </a>
                        </div>
                        <h4 class="product-title mt-9 mb-0"><a href="{{ route('product.show', ['productId' => $product->id]) }}"> {{ $product->name }} </a></h4>
                        <a class="pr-price" href="{{ route('product.show', ['productId' => $product->id]) }}">{{ $product->price }}</a>
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
                <h4 class="product-title mt-9 mb-0"><a href=""> You have no art </a></h4>
                <div style="height: 30px;"></div>
            </div>
        </div>
    </div>
    @endif
</section>

@include('partials.footer')

@endsection