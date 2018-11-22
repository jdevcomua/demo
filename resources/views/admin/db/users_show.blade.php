@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Перегляд користувача</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Картка користувача</div>
                        </div>
                        @permission('edit-user')
                        <form class="form-horizontal" action="{{route('admin.db.users.update', $user->id)}}" method="post" role="form">
                            @method('PUT')
                            @csrf
                        @else
                        <form class="form-horizontal" role="form">
                        @endpermission
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
                                        <p class="form-control custom-field">
                                            {{ $user->last_name}}
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="first_name" class="col-lg-3 control-label">Ім'я</label>
                                    <div class="col-lg-8">
                                        <p class="form-control custom-field">
                                            {{ $user->first_name}}
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="middle_name" class="col-lg-3 control-label">По батькові</label>
                                    <div class="col-lg-8">
                                        <p class="form-control custom-field">
                                            {{ $user->middle_name}}
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">Email</label>
                                    <div class="col-lg-8">
                                        @forelse($user->emailsSystem as $email)
                                            <p class="form-control custom-field">
                                                {{ $email->email }}
                                            </p>
                                        @empty
                                            <p class="form-control custom-field"></p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">Додатковий email</label>
                                    <div class="col-lg-8">
                                        @permission('edit-user')
                                            <input class="form-control custom-field" name="email"
                                                   value="{{ old('email') ?? $user->additionalEmail }}">
                                        @else
                                            <p class="form-control custom-field">{{ $user->additionalEmail }}</p>
                                        @endpermission
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">Телефон</label>
                                    <div class="col-lg-8">
                                        @forelse($user->phonesSystem as $phone)
                                            <p class="form-control custom-field">
                                                {{ $phone->phone }}
                                            </p>
                                        @empty
                                            <p class="form-control custom-field"></p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">Додатковий телефон</label>
                                    <div class="col-lg-8">
                                        @permission('edit-user')
                                            <input class="form-control custom-field" name="phone"
                                                   value="{{ old('phone') ?? $user->additionalPhone }}">
                                        @else
                                            <p class="form-control custom-field">{{ $user->additionalPhone }}</p>
                                        @endpermission
                                    </div>
                                </div>

                                <div class="form-group datepicker">
                                    <label for="birthday" class="col-lg-3 control-label">Дата народження</label>
                                    <div class="col-lg-8 ">
                                        <p class="form-control custom-field">
                                            {{ $user->birthday ? $user->birthday->format('d/m/Y') : '' }}
                                        </p>
                                    </div>
                                </div>

                                @permission('private-data')
                                <div class="form-group">
                                    <label for="inn" class="col-lg-3 control-label">ІПН</label>
                                    <div class="col-lg-8">
                                        <p class="form-control custom-field">
                                            {{ $user->inn}}
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="passport" class="col-lg-3 control-label">Паспорт</label>
                                    <div class="col-lg-8">
                                        <p class="form-control custom-field">
                                            {{ $user->passport}}
                                        </p>
                                    </div>
                                </div>
                                @endpermission

                                <div class="form-group">
                                    <label for="gender" class="col-lg-3 control-label">Стать</label>
                                    <div class="col-lg-8">
                                        <p class="form-control custom-field">
                                            @if($user->gender === \App\User::GENDER_FEMALE) Жіноча @endif
                                            @if($user->gender === \App\User::GENDER_MALE) Чоловіча @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @permission('edit-user')
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-info ph25">Зберегти</button>
                            </div>
                            @endpermission
                            <div class="panel-body pb5 pt20">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Тварини</label>
                                    <div class="col-lg-8">
                                        <p class="form-control custom-field">
                                            @foreach($user->animals as $animal)
                                                <a href="{{route('admin.db.animals.edit')}}/{{ $animal->id}}">
                                                    {{$animal->nickname}}</a>@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                        <div class="col-sm-12 pn mt15">
                                            <a href="{{ route('admin.db.animals.create', $user->id) }}"
                                               class="btn btn-success btn-block">Додати тварину</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                                <div class="panel-body pb5 pt20">
                                    <form action="{{ $user->organization ?
                                    route('admin.db.users.detach.organization') :
                                    route('admin.db.users.attach.organization')}}"
                                          class="form-horizontal" method="post">
                                        @csrf
                                        @method('put')

                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <div class="form-group select-gen">
                                        <label class="col-lg-3 control-label">Організація</label>
                                        <div class="col-lg-8">
                                            @if($user->organization)
                                                <p class="form-control custom-field">
                                                    <a href="{{route('admin.info.directories.edit.organization', $user->organization->id)}}">{{$user->organization->name}}</a>
                                                </p>
                                            @else
                                            <select name="organization_id" id="organizations_select" >
                                                @foreach($organizations as $organization)
                                                <option value="{{$organization->id}}">{{$organization->name}}</option>
                                                @endforeach
                                            </select>
                                            @endif

                                            <div class="col-sm-12 pn mt15">
                                                @if(!$user->organization)
                                                    <button type="submit" class="btn btn-primary btn-block">Закріпити за користувачем</button>
                                                @else
                                                    <button type="submit" class="btn btn-danger btn-block">Відкріпити</button>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Адреси</div>
                        </div>
                        @permission('edit-user')

                            @include('admin.db.partials.address_form')

                        @else
                            <form class="form-horizontal" role="form">
                            @if($user->registrationAddress)
                                <div class="panel-body pb5 pt20">
                                    <div class="form-group">
                                        <label class="col-xs-12">Адреса реєстрації</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="registration_district" class="col-lg-3 control-label">Область</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->registrationAddress->district }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_city" class="col-lg-3 control-label">Населений пункт</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->registrationAddress->city }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_street" class="col-lg-3 control-label">Вулиця</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->registrationAddress->street }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_building" class="col-lg-3 control-label">Будинок</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->registrationAddress->building }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_apartment" class="col-lg-3 control-label">Помешкання</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->registrationAddress->apartment }}
                                            </p>
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
                                            <p class="form-control custom-field">
                                                {{ $user->livingAddress->district }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_city" class="col-lg-3 control-label">Населений пункт</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->livingAddress->city }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_street" class="col-lg-3 control-label">Вулиця</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->livingAddress->street }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_building" class="col-lg-3 control-label">Будинок</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->livingAddress->building }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="living_apartment" class="col-lg-3 control-label">Помешкання</label>
                                        <div class="col-lg-8">
                                            <p class="form-control custom-field">
                                                {{ $user->livingAddress->apartment }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            @endif
                            </form>
                        @endpermission
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
    </section>
@endsection

@section('scripts-end')

    <script type="text/javascript">
        jQuery(document).ready(function() {

            $('.form-group.select-gen select').selectize();
            $('#organizations_select').selectize();

        });
    </script>
@endsection