@extends('layout.app')

@section('title', 'Профіль')

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">Профіль</div>
    </div>

    @if($errors->email)
        @foreach($errors->email->all() as $error)
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-remove pr10"></i>
                {{ $error }}
            </div>
        @endforeach
    @endif

    @if (\Session::has('success_email'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-check pr10"></i>
            {{ \Session::get('success_email') }}
        </div>
    @endif

    @if($errors->phone)
        @foreach($errors->phone->all() as $error)
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-remove pr10"></i>
                {{ $error }}
            </div>
        @endforeach
    @endif

    @if (\Session::has('success_phone'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-check pr10"></i>
            {{ \Session::get('success_phone') }}
        </div>
    @endif


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
                    <div class="btn-group-toggle readonly">
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
                <a href="" class="btn btn-block btn-default" data-toggle="modal" data-target="#modalPhone">+ Додати ще номер телефону</a>
                @if(count($auth->emails))
                    <div class="form-group">
                        <label for="email">Пошта</label>
                        @foreach($auth->emails as $email)
                            <input type="email" class="form-control mb-4" id="email"
                                   value="{{ $email->email }}" readonly>
                        @endforeach
                    </div>
                @endif
                <a href="" class="btn btn-block btn-default" data-toggle="modal" data-target="#modalEmail">+ Додати ще Email</a>

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
                               value="{{ $auth->registrationAddress->district }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="registration_city">Населений пункт</label>
                        <input type="text" class="form-control" id="registration_city"
                               value="{{ $auth->registrationAddress->city }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="registration_street">Вулиця</label>
                        <input type="text" class="form-control" id="registration_street"
                               value="{{ $auth->registrationAddress->street }}" readonly>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="registration_building">Будинок</label>
                                <input type="text" class="form-control" id="registration_building"
                                       value="{{ $auth->registrationAddress->building }}" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="registration_apartment9">Помешкання</label>
                                <input type="text" class="form-control" id="registration_apartment"
                                       value="{{ $auth->registrationAddress->apartment }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($auth->livingAddress)
            <div class="cols-block">
                <div class="cols-block-header">
                    <div class="block-title">ПРОЖИВАННЯ</div>
                    <div class="block-sub-title">Адреса вашого проживання</div>
                </div>
                <div class="cols-block-content form">
                    <div class="form-group">
                        <label for="living_district">Область</label>
                        <input type="text" class="form-control" id="living_district"
                               value="{{ $auth->livingAddress->district }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="living_city">Населений пункт</label>
                        <input type="text" class="form-control" id="living_city"
                               value="{{ $auth->livingAddress->city }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="living_street">Вулиця</label>
                        <input type="text" class="form-control" id="living_street"
                               value="{{ $auth->livingAddress->street }}" readonly>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="living_building">Будинок</label>
                                <input type="text" class="form-control" id="living_building"
                                       value="{{ $auth->livingAddress->building }}" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="living_apartment">Помешкання</label>
                                <input type="text" class="form-control" id="living_apartment"
                                       value="{{ $auth->livingAddress->apartment }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(false)
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
        @endif
    </form>

    <div class="modal fade" id="modalPhone" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-3">Додати номер телефону</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('profile.phone.add')}}" method="post">

                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="min" class="control-label">
                                Номер телефону
                            </label>

                            <input type="text" name="phone" class="form-control">
                        </div>
                        <button type="submit" class="ml-auto btn btn-primary">+ Додати</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEmail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-3">Додати Email</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('profile.email.add')}}" method="post">

                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="min" class="control-label">
                                Email
                            </label>

                            <input type="email" name="email" class="form-control">
                        </div>
                        <button type="submit" class="ml-auto btn btn-primary">+ Додати</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection