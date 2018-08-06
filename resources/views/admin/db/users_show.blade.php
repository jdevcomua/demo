@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Редагування користувача</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Картка користувача</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.db.users.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="panel-body">
                                @if($errors->user)
                                    @foreach($errors->user->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_user'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_user') }}
                                    </div>
                                @endif

                                <div class="form-group select-gen">
                                    <label for="last_name" class="col-lg-3 control-label">Прізвище</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="last_name" name="last_name" class="form-control"
                                               value="{{ old('last_name') ?? $user->last_name}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="first_name" class="col-lg-3 control-label">Ім'я</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="first_name" name="first_name" class="form-control"
                                               value="{{ old('first_name') ?? $user->first_name}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="middle_name" class="col-lg-3 control-label">По батькові</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="middle_name" name="middle_name" class="form-control"
                                               value="{{ old('middle_name') ?? $user->middle_name}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-3 control-label">email</label>
                                    <div class="col-lg-8">
                                        @forelse($user->emails as $email)
                                            <input type="text" id="email" name="email" class="form-control"
                                                   value="{{ $email->email }}" disabled>
                                        @empty
                                            <input type="text" id="email" name="email" class="form-control"
                                                   disabled>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">Телефон</label>
                                    <div class="col-lg-8">
                                        @forelse($user->phones as $phone)
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                   value="{{ $phone->phone }}" disabled>
                                        @empty
                                            <input type="text" id="email" name="email" class="form-control"
                                                   disabled>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="form-group datepicker">
                                    <label for="birthday" class="col-lg-3 control-label">Дата народження</label>
                                    <div class="col-lg-8 ">
                                        <input type="text" class="form-control" id="birthday" name="birthday"
                                               value="{{ $user->birthday->format('d/m/Y') }}" disabled />
                                    </div>
                                </div>

                                @if(false)
                                    <div class="form-group">
                                        <label for="inn" class="col-lg-3 control-label">ІПН</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="inn" name="inn" class="form-control"
                                                   value="{{ old('inn') ?? $user->inn}}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="passport" class="col-lg-3 control-label">Паспорт</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="passport" name="passport" class="form-control"
                                               value="{{ old('passport') ?? $user->passport}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="gender" class="col-lg-3 control-label">Стать</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" id="gender" name="gender" disabled>
                                            <option @if($user->gender === \App\User::GENDER_FEMALE) selected @endif
                                            value="{{ \App\Models\Animal::GENDER_FEMALE }}">Жіноча</option>
                                            <option @if($user->gender === \App\User::GENDER_MALE) selected @endif
                                            value="{{ \App\Models\Animal::GENDER_FEMALE }}">Чоловіча</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body pb5 pt20">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Тварини</label>
                                    <div class="col-lg-8">
                                        <p class="form-control">
                                            @foreach($user->animals as $animal)
                                                <a href="{{route('admin.db.animals.edit')}}/{{ $animal->id}}">
                                                    {{$animal->nickname}}</a>@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-default ph25">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Адреси</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.db.users.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            @if($user->registrationAddress)
                                <div class="panel-body pb5 pt20">
                                    <div class="form-group">
                                        <label class="col-xs-12">Адреса реєстрації</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="registration_district" class="col-lg-3 control-label">Область</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="registration_district" class="form-control"
                                                   value="{{ $user->registrationAddress->district }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_city" class="col-lg-3 control-label">Населений пункт</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="registration_city" class="form-control"
                                                   value="{{ $user->registrationAddress->city }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_street" class="col-lg-3 control-label">Вулиця</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="registration_street" class="form-control"
                                                   value="{{ $user->registrationAddress->street }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_building" class="col-lg-3 control-label">Будинок</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="registration_building" class="form-control"
                                                   value="{{ $user->registrationAddress->building }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_apartment" class="col-lg-3 control-label">Помешкання</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="registration_apartment" class="form-control"
                                                   value="{{ $user->registrationAddress->apartment }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($user->livingAddress)
                                <div class="panel-body pb5 pt20">
                                    <div class="form-group">
                                        <label class="col-xs-12">Адреса проживання</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="living_district" class="col-lg-3 control-label">Область</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="living_district" class="form-control"
                                                   value="{{ $user->livingAddress->district }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_city" class="col-lg-3 control-label">Населений пункт</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="living_city" class="form-control"
                                                   value="{{ $user->livingAddress->city }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_street" class="col-lg-3 control-label">Вулиця</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="living_street" class="form-control"
                                                   value="{{ $user->livingAddress->street }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_building" class="col-lg-3 control-label">Будинок</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="living_building" class="form-control"
                                                   value="{{ $user->livingAddress->building }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_apartment" class="col-lg-3 control-label">Помешкання</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="living_apartment" class="form-control"
                                                   value="{{ $user->livingAddress->apartment }}" disabled>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                @permission('change-roles')
                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Ролі користувача</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.db.users.update.roles', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="panel-body">
                                @if($errors->user_roles)
                                    @foreach($errors->user_roles->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_user_roles'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_user_roles') }}
                                    </div>
                                @endif
                                @foreach($roles as $role)
                                    <div class="checkbox-custom col-md-3 mb10 mt10">
                                        <input type="checkbox" name="roles[]"
                                               value="{{$role->id}}" id="roles-{{$role->id}}"
                                                @if($user->hasRole($role->name)) checked @endif >
                                        <label for="roles-{{$role->id}}">{{$role->display_name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-default ph25">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endpermission
            </div>

        </div>

    </section>
@endsection

@section('scripts-end')

    <script type="text/javascript">
        jQuery(document).ready(function() {

            $('.form-group.select-gen select').selectize();

        });
    </script>
@endsection