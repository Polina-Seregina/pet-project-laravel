@extends('layouts.app')

@section('content')

@include('partials.header')
<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <div class="main-menu d-none d-lg-block">
            <ul class="sub-menu" >
                <li>
                    <ul>
                        <li> <a href="{{ route('user.products.index') }}"> МОИ АРТЫ </a> </li>
                        <li> <a href="{{ route('wallet.show') }}">МОИ ФИНАНСЫ</a>
                            <ul class="sub-menu" style="background-color: #fbefe9;">
                                <li>
                                    <ul> 
                                        <li> <a href="{{ route('wallet.show') }}">Кошелёк </a> </li>
                                        <li> <a href="{{ route('transaction.history') }}"> История операций </a> </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li> <a href="">МОИ ЗАКАЗЫ </a> 
                            <ul class="sub-menu" style="background-color: #fbefe9;">
                                <li>
                                    <ul> 
                                        <li> <a href="{{ route('orders.purchased') }}">Приобретенные арты </a> </li>
                                        <li> <a href="{{ route('orders.sold') }}"> Проданные арты </a> </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

</section>

<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-gray-600"
        >{{ session('status') }}</p>
        <div class="max-w-7xl mb-10 mx-auto sm:px-9 lg px-5 space-y-6">
            <div style="display: flex; align-items: center;">
                <div style="flex: 1; width: 80%;">
                    <div class="p-4 sm:p-8 shadow sm:rounded-lg mx-auto" style="background-color: #fffbf9;">
                        <blockquote class="blockquote mt-10 mb-30 ml-10">
                            <p style="font-weight: normal; font-size: 20px;" class="name"> Имя </p> 
                            <p  style="font-size: 22px;" class="name"> {{ $user->name }} </p> 
                            <div style="height: 30px;"></div>
                            <p style="font-weight: normal; font-size: 20px;" class="name"> Никнейм </p> 
                            <p style="font-size: 22px;" class="name"> {{ $user->profile->nickname ?? '-' }} </p> 
                            <div style="height: 30px;"></div>
                            <p style="font-weight: normal; font-size: 20px;" class="name"> Почта </p> 
                            <p  style="font-size: 22px;" class="name"> {{ $user->email ?? '-' }} </p> 
                            @if (isset($user->profile->birthday))
                            <div style="height: 30px;"></div>
                            <p style="font-weight: normal; font-size: 20px;" class="name"> Дата рождения </p> 
                            <p style="font-size: 22px;" class="name"> {{ $user->profile->birthday }} </p>
                            <div style="height: 30px;"></div>
                            @endif
                        </blockquote>

                        <div class="main-menu col-md-auto ml-10" style="display: flex; align-items: center; justify-content: center;">
                            <ul class="menu-list">
                                <li> <a style="font-size: 17px;" href="{{ route('profile.edit') }}"> &#9999 Редактировать профиль </a> </li>
                            </ul>
                        </div>
                    </div>

                    <div style="height: 30px;"></div>
                
                    <div class="d-md-flex align-items-center" style="display: flex; align-items: center; justify-content: center;">
                        <form action="logout" method="post">
                            @csrf                            
                            <button class="ht-btn bs-style mb-2 ml-10" type="submit">Выйти</button>
                        </form>
                    </div>
                </div>

                <div style="flex: 1; display: flex;" >
                    <div class="column mb-50" style="display: flex; align-items: center; justify-content: center;">
                        <img class="ml-10" width="400" src="{{ $avatar }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection