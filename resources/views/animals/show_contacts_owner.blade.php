@extends('layout.app')

@section('title', 'Контактні дані власника тварини')

@section('content')
    <div class="cols-block pb-0">
        @if($animal->user)
            <div class="cols-block-header">
                <div class="block-title">Контактні дані власника тварини</div>
                <div class="block-sub-title"></div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="last_name">Ім'я</label>
                    <input type="text" class="form-control" id="last_name"
                           value="{{$animal->user->first_name}}" readonly>
                </div>
                <div class="form-group">
                    <label for="last_name">Номер телефону</label>
                    <input type="text" class="form-control" id="last_name"
                           value="{{$animal->user->phones[0]->phone}}" readonly>
                </div>
                <div class="form-group">
                    <label for="last_name">Пошта</label>
                    <input type="text" class="form-control" id="last_name"
                           value="{{$animal->user->primary_email->email}}" readonly>
                </div>
            </div>
        @else
            <div class="cols-block-header">
                <div class="block-title">Не знайдено</div>
                <div class="block-sub-title">Власник не зареєстрований в системі</div>
            </div>
        @endif
    </div>
@endsection
