@extends('layouts.admin')

@section('content')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            Административная панель
        </h3>
    </div>
    <div class="copyright-wrap pb-xl-0 pb-5"> </div>
</section>

<header style=" padding-bottom: 10px; padding-top: 10px; display: flex; justify-content: center; align-items: center; " 
    class="theme-main-menu theme-menu-one">
    <div class="row align-items-center justify-content-between">
        <div class="col-md-auto d-flex align-items-center justify-content-end d-lg-inline-block d-none">
            <div class="main-menu d-none d-lg-block">
                <nav id="mobile-menu">
                    <ul class="menu-list">
                        <li> <a href="{{ route('admin.panel') }}"> Вернуться к меню выбора </a> </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<div style="height: 30px;"></div>

<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                <blockquote style="text-align: center;">
                    @if (session('status'))
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600"
                        >{{ session('status') }}</p>
                    @endif
    
                    <div style="margin: 20px 140px 20px 140px;" class="info-box2">
                        <div style="display: flex; justify-content: center; align-items: center;" class="info-content">
                            <img class="ml-10" width="275" src="{{ $avatar }}">
                        </div>
                    </div>
                        <h3 class="semi-title mb-25 mt-5 pt-4">{{$user->profile->nickname}}</h3>
                        <div style="height: 30px;"></div>
                    <div>
                        <div class="feature-list-four">
                            <span class="icon"><i class="bi bi-check-lg"></i></span>
                            <span class="feature-title"> Никнейм: {{ $user->profile->nickname }}</span>
                        </div>

                        <div class="feature-list-four">
                            <span class="icon"><i class="bi bi-check-lg"></i></span>
                            <span class="feature-title"> День Рождения: {{$user->profile->birthday ?? 'Не указан'}}</span>
                        </div>

                        <div class="feature-list-four">
                            <span class="icon"><i class="bi bi-check-lg"></i></span>
                            <span class="feature-title"> Дата регистрации: {{$user->created_at }}</span>
                        </div>

                        <div class="feature-list-four">
                            <span class="icon"><i class="bi bi-check-lg"></i></span>
                            <span class="feature-title"> Текущая роль: {{$user->getRoleNames()->first()}} </span>
                        </div>
                    </div>

                    @if ($user->email !== config('app.admin-email'))
                        @include('admin.changeRoleBlock')
                    @endif
                </blockquote>
                


            <div class="title-one text-center ">
                <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                    <a href="javascript:history.back()"> <- Back </a>
                </p>
            </div>
            </section>
        </div>    
    </div>
</div>

@endsection