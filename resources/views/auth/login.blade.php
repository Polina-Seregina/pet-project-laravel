@extends('layouts.app')

@section('content')

@include('partials.header')

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" class="mt-20 contact-form form-3" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="input-wrapper mb-20">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="input-wrapper mb-20">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center" style="display: flex; justify-content: space-between; width: 100%; box-sizing: border-box;">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600" style="width: 50%; text-align: left;">{{ __('Запомни меня') }}</span>
                <span style="width: 50%; text-align: right;">
                    @if (Route::has('password.request'))
                        <a class="text-heding" href="{{ route('password.request') }}">
                            {{ __('Забыл пароль?') }}
                        </a>
                    @endif
                </span>
            </label>
        </div>

        <div style="display: flex; justify-content: center; align-items: center;">
            <x-primary-button class="ms-3" class="margin-left: 0; padding-left: 0;">
                <div class="text-center items-center">
                    {{ __('Войти') }}
                </div>
            </x-primary-button>
        </div>

        <div class="mt-4" style="display: flex; justify-content: center; align-items: center;">
            <a class="text-heding" href="{{ route('google.login') }}">Войти через Google</a>
        </div>
    </form>
</x-guest-layout>

@include('partials.footer')

@endsection