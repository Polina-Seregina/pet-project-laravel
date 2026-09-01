@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            СОЗДАЕМ ИСКУССТВО
        </h3>
    </div>
</section>

<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
    <div class="p-2 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="column product-wrapper mb-50">

            <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf

                <div>
                    <x-input-label f or="name" :value="__('Название')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Описание')" />
                    <textarea class ='border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full' id="description" name="description" type="text" required autocomplete="description"> {{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="price" :value="__('Стоимость')" />
                    <x-text-input id="price" name="price" type="number" class="mt-1 block" :value="old('price')" min="0" step="0.01"/>
                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                </div>

                <div>
                    <x-input-label for="status" :value="__('Статус')" />
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="{{ $status::FORSALE->value }}" selected> {{ $status::FORSALE->label() }} </option>
                        <option value="{{ $status::DRAFT->value }}"> {{ $status::DRAFT->label() }} </option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>

                <div>
                    <x-input-label for="image" :value="__('Изображение')" />
                    <input id="image" type="file" name="image">
                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                </div>

                
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>


@include('partials.footer')

@endsection