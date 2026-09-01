<header class="theme-main-menu theme-menu-one">
	<div class="heding-bg header-top d-none">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="topbar-content d-flex align-items-center justify-content-center justify-content-md-between">
						<div class="ht-topbar-left"> </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="main-header-area">
		<div class="container-fluid">
			<div class="row align-items-center justify-content-between">
                <div class="col-md-auto col-6"> </div>
				<div class="col-md-auto d-flex align-items-center justify-content-end d-lg-inline-block d-none">
					<div class="main-menu d-none d-lg-block">
						<nav id="mobile-menu">
							<ul class="menu-list">
								@auth
									<li> <a href="{{ route('products.index') }}">КАТАЛОГ</a> </li>
									<li> <a href="{{ route('profile.show') }}">ЛИЧНЫЙ КАБИНЕТ </a> </li>
									@role('admin')
										<li> <a href="{{ route('admin.index') }}">АДМИНИСТРАТИВНАЯ ПАНЕЛЬ</a> </li>
									@endrole
								@endauth
								@guest
								<li> <a href="/login">ВХОД</a> </li>
								<li> <a href="/register">РЕГИСТРАЦИЯ</a> </li>
								@endguest
							</ul>
						</nav>
					</div>
				</div>
				<div class="col-md-auto col-6">
					<div class="right-nav d-flex align-items-center justify-content-end"> </div>
				</div>
			</div>
		</div>
	</div>
</header>
		<!-- header-area end -->