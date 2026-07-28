@extends('layouts.admin')

@section('content')
<section class="theme-banner-one">
    <div class="title-one text-center mb-70">
        <h3 class="main-title z-2">
            Административная панель
        </h3>
    </div>
    <div class="copyright-wrap pb-xl-0 pb-5"> </div>
</section>

<div style="height: 30px;"></div>

<div class="max-w-7xl mb-10 mx-auto sm:px-8 lg px-5 space-y-6">
    <div class="product-wrapper text-center p-4 shadow">
        <div class="mx-auto">
            <h4 class="widget-title">Меню</h4>
            <div style="height: 30px;"></div>
                <ul class="list-unstyled service-category-widget widget-box m-0" >
                    <li><a href="{{route('admin.usersList')}}">Список пользователей <span class="float-end"></span></a>
                    </li>
                    <li><a href="{{route('admin.list')}}">Cписок администраторов<span class="float-end"></span></a>
                    </li>
                </ul>
            </div>
        </div>
    
        <div class="widget-contact">
            <h3 class="contact-title">
                <form action="logout" method="post">
                    @csrf                            
                    <button type="submit">Выйти из аккаунта</button>
                </form>
            </h3>
        </div>

        
    </div>
</div>
</div>
@endsection