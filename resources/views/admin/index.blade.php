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
                        <li> <a href="{{ route('home') }}"> Вернуться к пользовательскому интерфейсу </a> </li>
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
                <blockquote style="text-align: center;" class="mt-10 mb-30 ml-10">
                    <div class="info-box2" style="padding: 20px 10px 10px 10px;">
                        <div class="info-content">
                            <h3 class="info-title">Список пользователей</h3>
                        </div>
                    </div>

                    @if (count($users) !== 0)
                    <table class="mb-6" style="width: 100%; border-collapse: collapse; rgb(0, 0, 0);">
                        <thead>
                            <tr>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px">  </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Роль </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Никнейм </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Почта </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Дата рождения </p> </th>
                                <th style="text-align: center; border: 1px solid"> <p style="font-size: 30px"> Аккаунт создан </p> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td style="text-align: center; border: 1px solid"> 
                                    <a href="{{route('admin.show', ['user' => $user])}}"> 
                                        <img class="shape-1 d-none d-lg-inline-block" width="50" src="{{ asset('images/shape/link.png') }}" alt="link">
                                    </a>
                                </td>
                                <td style="text-align: center; border: 1px solid"> {{ $user->getRoleNames()->first()}} </td>
                                <td style="text-align: center; border: 1px solid"> {{ $user->profile->nickname }} </td>
                                <td style="text-align: center; border: 1px solid"> {{ $user->email }} </td>
                                <td style="text-align: center; border: 1px solid"> {{ $user->profile->birthday ?? 'не указана'}} </td>
                                <td style="text-align: center; border: 1px solid"> {{ $user->created_at }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{ $users->links() }}
                    @else
                    <div style="text-align: center;" class="footer-widget mb-10">
						<p class="show-product-count text-heding fw-medium" style="text-align: center; font-size: 20px;"> Список пуст </p>
                    </div>
                    @endif
                </blockquote>
            </section>
        </div>    
    </div>
</div>

@endsection