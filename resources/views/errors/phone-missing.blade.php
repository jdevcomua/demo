@extends('layout.app')

@section('title', 'Помилка авторизації')

@section('content')
    <div class="cols-block">
        <div class="cols-block-header">
            <div class="block-title">Помилка авторизації</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content form">
            <div class="form-group">
                В вашому профілі Київ ID відсутній номер телефону. Будь ласка, вкажіть ваш номер телефону для продовження роботи з сайтом:
            </div>

            @if($errors->phone)
                @foreach($errors->phone->all() as $error)
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-remove pr10"></i>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            <form action="{{ route('profile.phone-missing.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control mb-4" id="phone"
                           value="{{ old('phone') }}" name="phone">
                </div>
                <div class="form-buttons">
                    <input class="btn btn-primary" type="submit" value="Зберегти">
                </div>
            </form>
        </div>
    </div>
@endsection