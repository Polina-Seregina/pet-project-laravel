@extends('layouts.app')

@section('content')

@include('partials.header')

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


@include('partials.footer')

@endsection
