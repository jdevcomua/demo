@extends('layout.app')

@section('title', $animal->nickname)

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">{{ $animal->nickname }}</div>
    </div>
    <div class="animal-show">
        <div class="animal-images">
            <div class="animal-image main"
                 style="background-image: url('{{ isset($animal->imagesArray[1]) ? '/' . $animal->imagesArray[1] : '/img/no_photo.png' }}')">
            </div>
            @for($i = 2; $i < 10; $i++)
                @if(array_key_exists($i, $animal->imagesArray))
                    <div class="animal-image" style="background-image: url('/{{ $animal->imagesArray[$i] }}')"></div>
                @endif
            @endfor
        </div>
        <div class="pet-info">
            <div class="pet-info-block">
                <span class="title">Вид</span>
                <span class="content">{{ $animal->species->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Стать</span>
                <span class="content">
                    @if($animal->gender === \App\Models\Animal::GENDER_FEMALE) Самка @endif
                    @if($animal->gender === \App\Models\Animal::GENDER_MALE) Самець @endif
                </span>
            </div>
            <div class="pet-info-block">
                <span class="title">Статус</span>
                @if($animal->lost && !$animal->lost->found)
                    <span class="content red">Загублено</span>
                @endif
            </div>
            <div class="pet-info-block">
                <span class="title">Масть</span>
                <span class="content">{{ $animal->color->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Тип шерсті</span>
                <span class="content">{{ $animal->fur->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Дата народження</span>
                <span class="content">{{ \App\Helpers\Date::getlocalizedDate($animal->birthday) }}</span>
            </div>
            <div class="pet-info-block w-100">
                <span class="title">Порода</span>
                <span class="content">{{ $animal->breed->name }}</span>
            </div>
        </div>
        <div class="animal-options">
            @if($animal->sterilized)
                <div class="animal-option-item">
                    Стерилізовано
                </div>
            @endif
        </div>
        <div class="pet-info-block comment">
            <span class="title">Коментарі (Особливі прикмети)</span>
            <span class="content">{{ $animal->comment }}</span>
        </div>
        <hr class="divider">
    </div>

@endsection
