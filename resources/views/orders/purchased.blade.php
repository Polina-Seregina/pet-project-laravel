@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            Приобретенные арты
        </h3>
    </div>
</section>


<section class="max-w-7xl mx-auto sm:px-8">
    <div style="height: 30px;"></div>
    <div class="copyright-wrap pb-xl-0 pb-5"> </div>
    <div style="height: 30px;"></div>
    @if (count($orders) === 0)
    <div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
        <div class="p-2 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="column product-wrapper text-center mb-50">
                <div style="height: 30px;"></div>
                <h4 class="product-title mt-9 mb-0"> Список покупок пуст :с </h4>
                <div style="height: 30px;"></div>
            </div>
        </div>
    </div>
    @endif

    @foreach ($orders as $order)
    <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
        <div style="display: flex;">
            <div class="column mb-50" style="display: flex; align-items: center; justify-content: center;">
                <img class="ml-10" width="300" src="{{ Storage::disk('s3')->url($order->purchasedProduct->image) }}">
            </div>

            <div style="flex: 1">
                <div style="width:80% " class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mx-auto">
                    <div class="title-one text-center"> Название </div>

                    <div class="max-w-7xl mb-3 mx-auto sm:px-9 lg px-5 space-y-6">
                        <section class="theme-banner-one">
                            <div class="title-one text-center "> {{ $order->purchasedProduct->name }} </div>
                        </section>
                    </div>

                    <div class="title-one text-center"> Цена </div>
                    <div class="max-w-7xl mb-3 mx-auto sm:px-9 lg px-5">
                        <section class="theme-banner-one">
                            <div class="title-one text-center "> {{ $order->purchasedProduct->price }} </div>
                        </section>
                    </div>

                    <div class="title-one text-center"> Продавец </div>
                    <div class="max-w-7xl mb-3 mx-auto sm:px-9 lg px-5 space-y-6">
                        <section class="theme-banner-one">
                            <div class="title-one text-center "> {{ $order->seller->profile->nickname }} </div>
                        </section>
                    </div>

                    <div class="title-one text-center"> Дата приобретения </div>
                    <div class="max-w-7xl mb-3 mx-auto sm:px-9 lg px-5 space-y-6">
                        <section class="theme-banner-one">
                            <div class="title-one text-center "> {{ $order->created_at }} </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-wrap pb-xl-0 pb-5"></div>
    <div style="height: 30px;"></div>

    @endforeach
{{ $orders->links() }}
</section>

@include('partials.footer')

@endsection