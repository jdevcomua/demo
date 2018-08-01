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
                            <input type="hidden" name="_method" value="PUT">
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
                                        <input type="text" id="email" name="email" class="form-control"
                                               value="{{ old('email') ?? $user->email}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">Телефон</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="phone" name="phone" class="form-control"
                                               value="{{ old('phone') ?? $user->phone}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group datepicker">
                                    <label for="birthday" class="col-lg-3 control-label">Дата народження</label>
                                    <div class="col-lg-8 ">
                                        <input type="text" class="form-control" id="birthday" name="birthday"
                                               value="{{ $user->birthday->format('d/m/Y') }}" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inn" class="col-lg-3 control-label">ІПН</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="inn" name="inn" class="form-control"
                                               value="{{ old('inn') ?? $user->inn}}" disabled>
                                    </div>
                                </div>

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

                                <div class="form-group">
                                    <label for="residence_address" class="col-lg-3 control-label">Адресса</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="residence_address" name="residence_address" class="form-control"
                                               value="{{ old('residence_address') ?? $user->residence_address}}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Тварини</label>
                                    <div class="col-lg-8">
                                        <p class="form-control">
                                            @foreach($user->animals as $animal)
                                                <a href="{{route('admin.db.animals.edit')}}/{{ $animal->id}}">{{$animal->nickname}}</a>,
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

                @permission('change-roles')
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tags"></span>Ролі користувача</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.db.users.update.roles', $user->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="panel-body">
                                <div class="row">

                                @foreach($roles as $role)
                                    <div class="checkbox-custom mb5 col-md-6">
                                        <input
                                                type="checkbox"
                                                name="roles[]"
                                                value="{{$role->id}}"
                                                id="roles-{{$role->id}}"
                                                @if($user->hasRole($role->name))
                                                    checked
                                                @endif
                                        >
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