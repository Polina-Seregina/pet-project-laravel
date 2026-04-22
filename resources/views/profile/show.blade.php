@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            PROFILE
        </h3>
    </div>
</section>

<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class = 'row' class="row max-w-xl">
                <div class='col'>
                    <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                        <blockquote class="blockquote mt-10 mb-30 ml-10">
                            <p style="font-weight: normal; font-size: 20px;" class="name"> Nickname </p> 
                            <p class="name"> {{ $user->profile->nickname ?? 'Your nickname' }} </p> 
                        </blockquote>
                
                        <blockquote class="blockquote mb-10 ml-10">
                            <p style="font-weight: normal; font-size: 20px;" class="name"> Birthday </p> 
                            <p style="font-size: 22px;" class="name"> {{ $user->profile->birthday ?? 'add information about your birthday' }} </p>
                        </blockquote>
                    </section>
                </div>
                <div class='col'>
                    <section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60">
                        <img class="ml-10" width="275" src="{{ $avatar }}">
                    </section>
                </div>
            </div>
        </div>    
    <button class="ht-btn bs-style mb-2 ml-10" type="submit">
        <a href="{{ route('profile.edit') }}"> Edit profile </a>
    </button>
            
        <div class="row align-items-center">
            <div class="col-xl-7">
                <div class="d-md-flex align-items-center ">
                    <form action="logout" method="post">
                        @csrf                            
                        <button class="ht-btn bs-style mb-2 ml-10" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection