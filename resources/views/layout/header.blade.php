@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp

<header>
    <div class="menu-btn">
        <div class="hamburger hamburger--slider" id="menu-toggle">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div>
    </div>
    <div class="name">
        <a href="{{route('index')}}"><img src="{{ asset('img/icon/gerb.png') }}" alt="Герб" class="logo"></a>
        <div class="title">
            <span><a class="text-white" href="{{route('index')}}">Реєстр домашніх тварин<a href="{{route('index')}}"></a></span>
            <span class="sub-title mt-3">Київська міська державна адміністрація</span>
        </div>
    </div>
    <div class="auth">
        @auth
            <span class="auth-item profile">{{ $auth->name }}</span>
            <a href="#" class="auth-item logout" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">ВИЙТИ</a>
            <form id="logout-form" action="{{ route('logout') }}"
                  method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="auth-item profile">УВІЙТИ</a>
        @endauth
    </div>
    <div class="nav">
        <nav>
            @auth
                <a href="{{ route('animals.index') }}" class="nav-item @if($curRoute == 'animals.index') active @endif">Мої тварини</a>
                <a href="{{ route('profile') }}" class="nav-item @if($curRoute == 'profile') active @endif">Мій профіль</a>
                <div class="nav-divider"></div>
            @endauth
            <a href="{{ route('about') }}" class="nav-item @if($curRoute == 'about') active @endif">Про проект</a>
            <a href="{{ route('faq') }}" class="nav-item @if($curRoute == 'faq') active @endif">Часті запитання</a>
        </nav>
        <div class="socials">
            <a href="#" class="socials-item socials-item-facebook"></a>
            <a href="#" class="socials-item socials-item-youtube"></a>
            <a href="#" class="socials-item socials-item-twitter"></a>
        </div>
    </div>
</header>