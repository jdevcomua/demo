@extends('layout.app')

@section('title', 'Верифікуйте вашу тварину')

@section('content')
    <div class="cols-block pb-0">
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
    </div>
@endsection