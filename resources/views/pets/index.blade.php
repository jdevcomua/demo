@extends('layout.app')

@section('title', 'Мої тварини')

@section('content')
    @if(count($pets))
        <div class="pets-list-header">СПИСОК МОЇХ ТВАРИН</div>
        <div class="pets-list">
            <div class="pets-list-item">
                <div class="pet-photo">
                    <img src="/img/pet1.jpg" alt="petName">
                </div>
                <div class="pet-info">
                    <div class="pet-info-block">
                        <span class="title">Собака</span>
                        <span class="content">Шарік Матусєвіч</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Масть</span>
                        <span class="content">Чорний</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Дата народження</span>
                        <span class="content">26 квітня 2016</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Статус</span>
                        <span class="content red">Не верифіковано</span>
                    </div>
                </div>
                <i class="fa fa-ellipsis-v more-button" aria-hidden="true"></i>
            </div>
            <div class="pets-list-item">
                <div class="pet-photo">
                    <img src="/img/pet2.jpg" alt="petName">
                </div>
                <div class="pet-info">
                    <div class="pet-info-block">
                        <span class="title">Кіт</span>
                        <span class="content">Шарік Матусєвіч</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Масть</span>
                        <span class="content">Білий</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Дата народження</span>
                        <span class="content">26 квітня 2016</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Статус</span>
                        <span class="content green">Верифіковано</span>
                    </div>
                </div>
                <i class="fa fa-ellipsis-v more-button" aria-hidden="true"></i>
            </div>
        </div>
    @else
        @include('pets._no_pets')
    @endif
    <div class="add-new-pet-wrap">
        @if(count($pets))
            <div class="above-text">Маєш ще тваринку? Додавай!</div>
        @endif
        <a href="#" class="add-new-pet btn btn-primary">+ Додати</a>
    </div>
@endsection