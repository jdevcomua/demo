@extends('layout.app')

@section('title', 'Знайдена тварина')
@php

@endphp
@section('content')
    <div class="page-title">
        <a href="{{ route('lost-animals.found') }}" class="page-back-link"></a>
        <div class="title">{{ $animal->nickname }}</div>
    </div>
    <div class="animal-show">
        <div class="animal-images">
            <div class="animal-image main"
                 style="background-image: url('{{ isset($animal->imagesArray[0]) ? '/' . $animal->imagesArray[0] : '/img/no_photo.png' }}')">
            </div>
            @for($i = 1; $i < 9; $i++)
                @if(array_key_exists($i, $animal->imagesArray))
                    <div class="animal-image" style="background-image: url('/{{ $animal->imagesArray[$i] }}')"></div>
                @endif
            @endfor
        </div>
        <div class="pet-info">
            <div class="pet-info-block">
                <span class="title">Вид</span>
                <span class="content">{{ $animal->species->name ?? 'Не заповнено' }}</span>
            </div>

            @if($animal->color)
            <div class="pet-info-block">
                <span class="title">Масть</span>
                <span class="content">{{ $animal->color->name ?? 'Не заповнено'}}</span>
            </div>
            @endif

            <div class="pet-info-block">
                <span class="title">Знайдено</span>
                <span class="content">{{ \App\Helpers\Date::getlocalizedDate($animal->created_at) }}</span>
            </div>

            @if($animal->breed)
            <div class="pet-info-block">
                <span class="title">Порода</span>
                <span class="content">{{ $animal->breed->name ?? 'Не заповнено'}}</span>
            </div>
            @endif

            <div class="pet-info-block w-100">
                <span class="title">Адреса де знайшли тваринку</span>
                <span class="content">{{ $animal->found_address ?? 'Не заповнено'}}</span>
            </div>

            <div class="pet-info-block comment mt-lg-5">
                <span class="title">Контактні дані</span>
            </div>
            <hr class="divider" style="width:100%">

            <div class="pet-info-block">
                <span class="title">Ім'я</span>
                <span class="content">{{ $animal->contact_name ?? 'Не заповнено'}}</span>
            </div>

            <div class="pet-info-block">
                <span class="title">Телефон</span>
                <span class="content">{{ $animal->contact_phone ?? 'Не заповнено'}}</span>
            </div>

            <div class="pet-info-block w-100">
                <span class="title">Email</span>
                <span class="content">{{ $animal->contact_email ?? 'Не заповнено'}}</span>
            </div>

        </div>
    </div>



@endsection

