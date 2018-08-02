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
                @if(count($auth->phones))
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        @foreach($auth->phones as $phone)
                            <input type="text" class="form-control mb-4" id="phone"
                                   value="{{ $phone->phone }}" readonly>
                        @endforeach
                    </div>
                @endif
                @if(count($auth->emails))
                    <div class="form-group">
                        <label for="email">Пошта</label>
                        @foreach($auth->emails as $email)
                            <input type="email" class="form-control mb-4" id="email"
                                   value="{{ $email->email }}" readonly>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @if($auth->registrationAddress)
            <div class="cols-block">
                <div class="cols-block-header">
                    <div class="block-title">РЕЄСТРАЦІЯ</div>
                    <div class="block-sub-title">Адреса вашої реєстрації</div>
                </div>
                <div class="cols-block-content form">
                    <div class="form-group">
                        <label for="registration_district">Область</label>
                        <input type="text" class="form-control" id="registration_district"
                               value="{{ $auth->registrationAddress->district }}">
                    </div>
                    <div class="form-group">
                        <label for="registration_city">Населений пункт</label>
                        <input type="text" class="form-control" id="registration_city"
                               value="{{ $auth->registrationAddress->city }}">
                    </div>
                    <div class="form-group">
                        <label for="registration_street">Вулиця</label>
                        <input type="text" class="form-control" id="registration_street"
                               value="{{ $auth->registrationAddress->street }}">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="registration_building">Будинок</label>
                                <input type="text" class="form-control" id="registration_building"
                                       value="{{ $auth->registrationAddress->building }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="registration_apartment9">Помешкання</label>
                                <input type="text" class="form-control" id="registration_apartment"
                                       value="{{ $auth->registrationAddress->apartment }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($auth->livingAddress)
            <div class="cols-block">
                <div class="cols-block-header">
                    <div class="block-title">КОНТАКТИ</div>
                    <div class="block-sub-title">Адреса вашого проживання</div>
                </div>
                <div class="cols-block-content form">
                    <div class="form-group">
                        <label for="living_district">Область</label>
                        <input type="text" class="form-control" id="living_district"
                               value="{{ $auth->livingAddress->district }}">
                    </div>
                    <div class="form-group">
                        <label for="living_city">Населений пункт</label>
                        <input type="text" class="form-control" id="living_city"
                               value="{{ $auth->livingAddress->city }}">
                    </div>
                    <div class="form-group">
                        <label for="living_street">Вулиця</label>
                        <input type="text" class="form-control" id="living_street"
                               value="{{ $auth->livingAddress->street }}">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="living_building">Будинок</label>
                                <input type="text" class="form-control" id="living_building"
                                       value="{{ $auth->livingAddress->building }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="living_apartment">Помешкання</label>
                                <input type="text" class="form-control" id="living_apartment"
                                       value="{{ $auth->livingAddress->apartment }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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