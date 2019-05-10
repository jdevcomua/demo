@extends('layout.app')

@section('title', 'Помилка авторизації')

@section('content')
    <div class="cols-block">
        <div class="cols-block-header">
            <div class="block-title">Помилка авторизації</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content">
            На жаль, нам не вистачає інформації для авторизації вас в системі.<br>
            (Скоріш за все ми не
            отримали ваші паспортні дані, ІНН або ж не підтвердилася ваша реєстрація в Києві).<br>
            Проте, ви завжди можете авторизуватися за допомогою Bank ID, або ж продовжити переглядати сайт без авторизації.
            <div class="row mt-5">
                <div class="form-buttons col-md-6">
                    <a href="{{ route('re-login') }}" class="btn btn-primary">Bank ID</a>
                </div>
                <div class="col-md-6 form-buttons text-md-right">
                    <a href="/" class="btn btn-dgrey">Вільне плавання</a>
                </div>
            </div>
        </div>
    </div>
@endsection
