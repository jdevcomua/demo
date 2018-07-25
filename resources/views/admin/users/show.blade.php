@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Користувач {{ $user->full_name }}
@endsection
@section('contentheader_title')
    Користувач {{ $user->full_name }}
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 ">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Користувач</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{route('admin.users.update', $user->id)}}" class="form-horizontal" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-group {{$errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-sm-2 control-label">Прізвище</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Прізвище" value="{{$user->first_name}}">
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group {{$errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-sm-2 control-label">Ім'я</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Ім'я" value="{{$user->last_name}}">
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('middle_name') ? ' has-error' : '' }}">
                                <label for="middle_name" class="col-sm-2 control-label">По батькові</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="По батькові" value="{{$user->middle_name}}">
                                    @if ($errors->has('middle_name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('middle_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{$user->email}}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('phone') ? ' has-error' : '' }}">
                                <label for="phone" class="col-sm-2 control-label">Номер телефона</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Номер телефона" value="{{$user->phone}}">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('birthday') ? ' has-error' : '' }}">
                                <label for="birthday" class="col-sm-2 control-label">День Народження</label>

                                <div class="col-sm-10">
                                    <input type="text" name="birthday" class="form-control datepicker" id="birthday" value="{{$user->birthday->format('d-m-Y')}}">
                                    @if ($errors->has('birthday'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('gender') ? ' has-error' : '' }}">
                                <label for="birthday" class="col-sm-2 control-label">Стать</label>

                                <div class="col-sm-10">
                                    <select name="gender" id="" class="form-control">
                                        <option value="0" @if($user->gender === 0) selected @endif>Жіноча</option>
                                        <option value="1" @if($user->gender === 1) selected @endif>Чоловіча</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('passport') ? ' has-error' : '' }}">
                                <label for="passport" class="col-sm-2 control-label">Паспорт</label>

                                <div class="col-sm-10">
                                    <input type="text" name="passport" class="form-control"  id="passport" value="{{$user->passport}}">
                                    @if ($errors->has('passport'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('passport') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('residence_address') ? ' has-error' : '' }}">
                                <label for="residence_address" class="col-sm-2 control-label">Адреса</label>

                                <div class="col-sm-10">
                                    <input type="text" name="residence_address" class="form-control" id="residence_address" value="{{$user->residence_address}}">
                                    @if ($errors->has('residence_address'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('residence_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('residence_address') ? ' has-error' : '' }}">
                                <label for="residence_address" class="col-sm-2 control-label">Тварини</label>

                                <div class="col-sm-10">
                                    <p class="form-control">
                                        @foreach($user->animals as $animal)
                                            <a href="{{route('admin.animals.show', $animal->id)}}"> {{$animal->nickname}},</a>
                                        @endforeach
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 pull-right">
                                <button type="submit" class="btn btn-block btn-info">Змінити</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.ru.min.js"></script>    <script>
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'ru',
            startView: 3
        });
    </script>
@endsection
