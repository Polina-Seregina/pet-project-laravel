@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 style="font-size: 50px; letter-spacing: 15px; color: #93837d33" class="main-title z-2">
            РЕДАКТИРОВАТЬ АРТ
        </h3>
    </div>
</section>

<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
    <div class="p-2 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="column product-wrapper mb-50">

            <form method="post" action="{{ route('products.update', ['product' => $product]) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                @method('patch')
                <div>
                    <x-input-label for="name" :value="__('Название')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Описание')" />
                    <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $product->description)" required autocomplete="description" />
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="price" :value="__('Стоимость')" />
                    <x-text-input id="price" name="price" type="number" class="mt-1 block" :value="old('price', $product->price)" min="0" step="0.01"/>
                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                </div>

                <div>
                    <x-input-label for="status" :value="__('Статус')" />
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('status', $product->status)">
                        <option value="{{ $status::FORSALE->value }}"
                        @if ($product->status->value === $status::FORSALE->value)
                            selected
                        @endif
                        > {{ $status::FORSALE->label() }}  </option>
                        <option value="{{ $status::DRAFT->value }}" 
                        @if ($product->status->value === $status::DRAFT->value)
                            selected
                        @endif> {{ $status::DRAFT->label() }} </option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>

                
                @if ($product->author === $user)
            
                <div>
                    <x-input-label for="image" :value="__('Изображение')" />
                    <img class="mt-2 mb-3" style="border-radius:10%" width="100" src="{{ Storage::url($product->image) }}">
                    <p class="text-sm text-gray-600 mb-4"> Установленное изображение. Загрузи новое, чтобы заменить. </p>
                    <input id="image" type="file" name="image">
                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                </div>
                @endif
                
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>


@include('partials.footer')

@endsection