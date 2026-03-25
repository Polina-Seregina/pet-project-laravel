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

<section class="ht-project-section pt-140 pb-140 pt-lg-60 pb-lg-60"
	data-background="assets/img/bg/bg-1.png">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10">
					<div class="title-one text-center mb-50">
                            <p>You are logged in! </p>
                    </div>
                </div>
            </div>
        </div>
</section>

<div class="container-fluid">
	<div class="row align-items-center">
	    <div class="col-xl-7">
            <div class="d-md-flex align-items-center ">
                <form action="logout" method="post">
                    @csrf
                    <button class="ht-btn bs-style" type="submit">Logout</button>
                </form>
		    </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection