@php
    $curRoute = \Route::current()->getName();
@endphp

<header>
    <div class="name">
        <img src="{{ asset('img/icon/gerb.png') }}" alt="Герб" class="logo">
        <div class="title">
            <span>Реєстр домашніх тварин</span>
            <span class="sub-title">Київська міська державна адміністрація</span>
        </div>
    </div>
    <div class="auth">
        {{--@auth--}}
            <a href="#" class="auth-item profile">Веніамін Матусєвіч</a>
            <a href="#" class="auth-item logout">ВИЙТИ</a>
        {{--@else--}}
            {{--<a href="#" class="auth-item profile">ВВІЙТИ</a>--}}
        {{--@endauth--}}
    </div>
    <div class="nav">
        <nav>
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