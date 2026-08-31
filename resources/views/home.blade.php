@extends('layouts.app')

@section('content')

@include('partials.header')


<div style="height: 30px;"></div>

<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
	<div>
		<div class="column text-center mb-50">
			<div style="height: 30px;"></div>
			@guest
			<a style="color: #93837da5"> Находи уникальные работы, собирай свою коллекцию и делись собственным творчеством. </a> <br>
			<a style="color: #93837da5"> Здесь каждая работа может стать частью чьей-то истории. </a>
			@endguest
			<div style="height: 30px;"></div>
		</div>
	</div>
</div>


@auth
<div style="height: 30px;"></div>

@if (session('status') === 'success-login')
<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5">
	<div class="column text-center mb-50">
	<h4 class="product-title mt-9 mb-0">
		<p
			x-data="{ show: true }"
			x-show="show"
			x-transition
			x-init="setTimeout(() => show = false, 2000)"
			class="text-sm text-gray-600"
		>{{ __('Добро пожаловать!') }}</p>
	</h4>
</div>
</div>
@endif

@endauth


@include('partials.footer')

@endsection
