@extends('layouts.app')

@section('content')

@include('partials.header')


<div style="height: 30px;"></div>

<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
	<div>
		<div class="column text-center mb-50">
			<div style="height: 30px;"></div>
			@guest
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="chose-wrap-1">
						<h3 class="chose-title"><a href="login">Login</a></h3>
						<p>Зайди в свой акк </p>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="chose-wrap-1">
						<h3 class="chose-title"><a href="register">Register</a> </h3>
						<p>Если ты впервые попал сюда</p>
					</div>
				</div>
			</div>
			@endguest
			<div style="height: 30px;"></div>
		</div>
	</div>
</div>


@auth
<div style="height: 30px;"></div>
<div style="height: 30px;"></div>
<div style="height: 30px;"></div>

@if (session('status') === 'success-login')
<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
	<div class="p-2 sm:p-8 bg-white shadow sm:rounded-lg">
		<div class="column product-wrapper text-center mb-50">
			<div style="height: 30px;"></div>
			<h4 class="product-title mt-9 mb-0">
				<p
					x-data="{ show: true }"
					x-show="show"
					x-transition
					x-init="setTimeout(() => show = false, 2000)"
					class="text-red-500"
				>{{ __('Success login!') }}</p>
			</h4>
			<div style="height: 30px;"></div>
		</div>
	</div>
</div>
@endif

@endauth


@include('partials.footer')

@endsection
