@extends('layout.app')

@section('title', 'Профіль')

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">Профіль</div>
    </div>
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ОСНОВНІ ВІДОМОСТІ</div>
                <div class="block-sub-title">Додайте усі данні про вас</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="last_name">Прізвище</label>
                    <input type="text" class="form-control" id="last_name"
                           value="{{ $auth->last_name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="first_name">Ім'я</label>
                    <input type="text" class="form-control" id="first_name"
                           value="{{ $auth->first_name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="middle_name">По батькові</label>
                    <input type="text" class="form-control" id="middle_name"
                           value="{{ $auth->middle_name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="birthday">Дата народження</label>
                    <input type="text" class="form-control" id="birthday"
                           value="{{ $auth->birthday->format('d/m/Y') }}" readonly/>
                </div>
                <div class="form-group">
                    <label>Стать</label>
                    <div class="btn-group-toggle">
                        <label class="btn radio-item big-radio
                                @if($auth->gender === \App\User::GENDER_MALE) active @endif">
                            <span class="label"><i class="fa fa-mars" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="male" autocomplete="off">Чоловік
                        </label>
                        <label class="btn radio-item big-radio
                                @if($auth->gender === \App\User::GENDER_FEMALE) active @endif">
                            <span class="label"><i class="fa fa-venus" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="female" autocomplete="off">Жінка
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" class="form-control" id="phone"
                           value="{{ $auth->phone }}" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Пошта</label>
                    <input type="email" class="form-control" id="email"
                           value="{{ $auth->email }}" readonly>
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">РЕЄСТРАЦІЯ</div>
                <div class="block-sub-title">Вкажіть адресу вашої реєстрації</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="registration_district">Область</label>
                    <input type="text" class="form-control" id="registration_district"
                           value="{{ $auth->address_registration->district }}">
                </div>
                <div class="form-group">
                    <label for="registration_city">Населений пункт</label>
                    <input type="text" class="form-control" id="registration_city"
                           value="{{ $auth->address_registration->city }}">
                </div>
                <div class="form-group">
                    <label for="registration_street">Вулиця</label>
                    <input type="text" class="form-control" id="registration_street"
                           value="{{ $auth->address_registration->street }}">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="registration_building">Будинок</label>
                            <input type="text" class="form-control" id="registration_building"
                                   value="{{ $auth->address_registration->building }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="registration_apartment9">Помешкання</label>
                            <input type="text" class="form-control" id="registration_apartment"
                                   value="{{ $auth->address_registration->apartment }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">КОНТАКТИ</div>
                <div class="block-sub-title">Вкажіть адресу вашого проживання</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="living_district">Область</label>
                    <input type="text" class="form-control" id="living_district"
                           value="{{ $auth->address_living->district }}">
                </div>
                <div class="form-group">
                    <label for="living_city">Населений пункт</label>
                    <input type="text" class="form-control" id="living_city"
                           value="{{ $auth->address_living->city }}">
                </div>
                <div class="form-group">
                    <label for="living_street">Вулиця</label>
                    <input type="text" class="form-control" id="living_street"
                           value="{{ $auth->address_living->street }}">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="living_building">Будинок</label>
                            <input type="text" class="form-control" id="living_building"
                                   value="{{ $auth->address_living->building }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="living_apartment">Помешкання</label>
                            <input type="text" class="form-control" id="living_apartment"
                                   value="{{ $auth->address_living->apartment }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cols-block footer">
            <div class="cols-block-header">
                <div class="block-title"></div>
                <div class="block-sub-title"></div>
            </div>
            <div class="cols-block-content form">
                <div class="form-buttons">
                    <input class="btn btn-primary" type="submit" value="Зберегти">
                    <a class="btn btn-cancel" href="#">Скасувати</a>
                </div>
            </div>
        </div>
    </form>
@endsection