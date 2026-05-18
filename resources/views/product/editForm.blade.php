@extends('layouts.app')

@section('content')

@include('partials.header')

<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            EDIT ART
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
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $product->description)" required autocomplete="description" />
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="price" :value="__('Price')" />
                    <x-text-input id="price" name="price" type="number" class="mt-1 block" :value="old('price', $product->price)" min="0" step="0.01"/>
                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                </div>

                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('status', $product->status)">
                        <option value="for_sale"> {{ $status::FORSALE->label() }}  </option>
                        <option value="draft"> {{ $status::DRAFT->label() }} </option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>

                <div>
                    <x-input-label for="image" :value="__('Image')" />
                    <input id="image" type="file" name="image" :value="old('image')">
                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                </div>
                
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>


@include('partials.footer')

@endsection