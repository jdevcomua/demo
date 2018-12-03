@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Редагування тварини</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="fa fa-remove pr10"></i>
                    {{ $error }}
                </div>
            @endforeach
        @endif
            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Картка тварини</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.db.animals.update', $animal->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="panel-body">
                                @if($errors->animal)
                                    @foreach($errors->animal->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_animal'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_animal') }}
                                    </div>
                                @endif

                                {{--<div class="form-group">--}}
                                    {{--<label for="nickname" class="col-lg-3 control-label">Власник</label>--}}
                                    {{--<div class="col-lg-8">--}}
                                        {{--<p class="form-control custom-field">--}}
                                            {{--@if($animal->user)--}}
                                            {{--<a href="{{ route('admin.db.users.show', $animal->user->id) }}">--}}
                                                {{--{{ $animal->user->fullName}}</a>--}}
                                            {{--@endif--}}
                                        {{--</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group select">
                                    <label for="user" class="col-lg-3 control-label">Власник</label>
                                    <div class="col-lg-8">
                                        <select name="user" id="user" data-value="{{ $animal->user_id }}"></select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-3 col-md-hidden"></div>
                                    <div class="col-lg-8">
                                        <a href="" class="btn drop-owner btn-default pull-right">Видалити власника</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname" class="col-lg-3 control-label">Кличка</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="nickname" name="nickname" class="form-control"
                                            value="{{ old('nickname') ?? $animal->nickname}}" required>
                                    </div>
                                </div>
                                <div class="form-group select-gen">
                                    <label for="species" class="col-lg-3 control-label">Вид</label>
                                    <div class="col-lg-8">
                                        <select id="species" name="species" required>
                                            @foreach($species as $s)
                                                <option value="{{$s->id}}"
                                                        @if($animal->species_id === $s->id) selected @endif>{{$s->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group select-gen">
                                    <label for="gender" class="col-lg-3 control-label">Стать</label>
                                    <div class="col-lg-8">
                                        <select id="gender" name="gender" required>
                                            <option @if($animal->gender === \App\Models\Animal::GENDER_FEMALE) selected @endif
                                                    value="{{ \App\Models\Animal::GENDER_FEMALE }}">Самка</option>
                                            <option @if($animal->gender === \App\Models\Animal::GENDER_MALE) selected @endif
                                                    value="{{ \App\Models\Animal::GENDER_MALE }}">Самець</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group select">
                                    <label for="breed" class="col-lg-3 control-label">Порода</label>
                                    <div class="col-lg-8">
                                        <select name="breed" id="breed" required data-value="{{ $animal->breed_id }}"></select>
                                    </div>
                                </div>
                                <div class="form-group select">
                                    <label for="color" class="col-lg-3 control-label">Масть</label>
                                    <div class="col-lg-8">
                                        <select name="color" id="color" required data-value="{{ $animal->color_id }}"></select>
                                    </div>
                                </div>
                                <div class="form-group select">
                                    <label for="fur" class="col-lg-3 control-label">Тип шерсті</label>
                                    <div class="col-lg-8">
                                        <select name="fur" id="fur" required data-value="{{ $animal->fur_id }}"></select>
                                    </div>
                                </div>
                                <div class="form-group datepicker">
                                    <label for="birthday" class="col-lg-3 control-label">Дата народження</label>
                                    <div class="col-lg-8 ">
                                        <input type="text" class="form-control" id="birthday" name="birthday"
                                               value="{{ $animal->birthday->format('d/m/Y') }}" required readonly="true"
                                        />
                                    </div>
                                </div>
                                @permission('verify-animal')
                                <div class="form-group">
                                    <label for="badge" class="col-lg-3 control-label">Номер жетону</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="badge" name="badge" class="form-control"
                                               value="{{ $animal->badge }}" required>
                                        <span class="help-block mt5">Номер повинен бути від 5 до 8 символів та складатися тільки з кириличних літер або цифр</span>
                                    </div>
                                </div>
                                @endpermission
                                <div class="form-group">
                                    <label for="sterilized" class="col-lg-3 control-label"></label>
                                    <div class="col-lg-8">
                                        <div class="checkbox-custom pt10">
                                            <input type="checkbox" id="sterilized" value="1" name="sterilized"
                                                   @if($animal->sterilized) checked @endif>
                                            <label for="sterilized">Стерилізовано</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="comment">Коментарі (Особливі прикмети)</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="comment" name="comment"
                                                  rows="3" style="resize: none">{{ $animal->comment }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="created_at" class="col-lg-3 control-label">Зареєстровано</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="created_at" name="created_at" class="form-control"
                                               value="{{ old('created_at') ?? ($animal->created_at ? $animal->created_at->format('d-m-Y H:i') : '')}}" disabled>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label for="updated_at" class="col-lg-3 control-label">Оновлено</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="updated_at" name="updated_at" class="form-control"
                                                   value="{{ old('updated_at') ?? ($animal->updated_at ? $animal->updated_at->format('d-m-Y H:i') : '')}}" disabled>
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
                                <span class="glyphicon glyphicon-tasks"></span>Верифікація</div>
                        </div>
                        <form class="form-horizontal">
                            <div class="panel-body">
                                @if($errors->animal_verify)
                                    @foreach($errors->animal_verify->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_animal_verify'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_animal_verify') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Статус:</label>
                                    <div class="col-xs-8">
                                        @if($animal->verified)
                                            <label class="control-label text-success">Верифіковано</label>
                                        @else
                                            <label class="control-label text-danger">Не верифіковано</label>
                                        @endif
                                    </div>
                                </div>
                                @if($animal->verified)
                                        <div class="form-group">
                                        <label class="col-xs-3 control-label">Ким:</label>
                                        <div class="col-xs-8">
                                            @if($animal->verification->user)
                                                <a href="{{ route('admin.db.users.show', $animal->verification->user->id) }}">
                                                    <label class="cursor control-label">{{ $animal->verification->user->name }}</label>
                                                </a>
                                            @else
                                                <label class="cursor control-label text-danger">Помилка - Користувач не знайдений</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Дата Верифікації:</label>
                                        <div class="col-xs-8">
                                            <label class="control-label">{{ $animal->verification->updated_at->format('d-m-Y H:i') }}</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @permission('verify-animal')
                                <div class="panel-footer text-right">
                                    @if($animal->verified)
                                        <a href="{{ route('admin.db.animals.verify', $animal->id) }}?state=0"
                                           class="btn btn-danger ph25">Відмінити верифікацію</a>
                                    @else
                                        <a href="{{ route('admin.db.animals.verify', $animal->id) }}?state=1"
                                           class="btn btn-success ph25">Верифікувати</a>
                                    @endif
                                </div>
                            @endpermission
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Тварину загублено</div>
                        </div>
                        <form class="form-horizontal">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Статус:</label>
                                    <div class="col-xs-8">
                                        @if(isset($animal->lost) && !$animal->lost->found)
                                            <label class="control-label text-danger">Так</label>
                                        @else
                                            <label class="control-label text-success">Ні</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Тварину архівовано</div>
                        </div>
                        <form class="form-horizontal">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Статус:</label>
                                    <div class="col-xs-8">
                                        @if($animal->archived_type)
                                            <label class="control-label">Так</label>
                                        @else
                                            <label class="control-label">Ні</label>
                                        @endif
                                    </div>
                                </div>
                                @if($animal->archived_type)
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Причина архівації:</label>
                                        <div class="col-xs-8">
                                            <label class="control-label">{{$animal->archived_type}}</label>
                                        </div>
                                    </div>
                                    @if($animal->archived_type === 'Смерть')
                                        <div class="form-group">
                                            <label class="col-xs-3 control-label">Причина смерті:</label>
                                            <div class="col-xs-8">
                                                <label class="control-label">{{$animal->archivable->cause_of_death}}</label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">Дата {{$animal->archived_type === 'Смерть' ? 'смерті': 'виїзду'}}:</label>
                                        <div class="col-xs-8">
                                            <label class="control-label">{{$animal->archived_type === 'Смерть' ? $animal->archivable->died_at : $animal->archivable->moved_out_at}}</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(!$animal->archived_type)
                                <div class="panel-footer text-right">
                                    <a id="archiveButton" href="#"
                                       class="btn btn-danger ph25">Архівувати</a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Ідентифікуючі пристрої</div>
                        </div>
                        @if($errors->identifying_device)
                            @foreach($errors->identifying_device->all() as $error)
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-remove pr10"></i>
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif

                        @if (\Session::has('success_identifying_device'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="fa fa-check pr10"></i>
                                {{ \Session::get('success_identifying_device') }}
                            </div>
                        @endif

                        @if($animal->identifying_devices_count > 0)
                            @foreach($animal->identifyingDevicesArray() as $k => $v)
                                @if($animal->$k !== null)
                                    <form class="form-horizontal" action="{{route('admin.db.animals.remove-identifying-device', $animal->id)}}" method="post">
                                        @csrf
                                        <input type="hidden" name="device_type" value="{{$k}}">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">{{$v}}:</label>
                                                <div class="col-xs-6">
                                                    <label class="control-label">{{$animal->$k}}</label>
                                                </div>
                                                <div class="text-right col-xs-3">
                                                    <button type="submit" class="btn btn-default deleteDeviceBtn ph25 float-right">Видалити</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            @endforeach
                        @else
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-xs-3 control-label"></label>
                                    <div class="col-xs-8">
                                        <label class="control-label">Ідентифікуючі пристрої відсутні</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($animal->identifying_devices_count < count($animal->identifyingDevicesArray()))
                            <div class="panel-footer text-right">
                                <a id="deviceAddButton" href="#"
                                   class="btn btn-success ph25 float-right">Додати</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Файли тварини</div>
                        </div>
                        <div class="panel-body pn">
                            @if($errors->animal_files)
                                @foreach($errors->animal_files->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_animal_files'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_animal_files') }}
                                </div>
                            @endif
                            <span class="help-block mt10 ph10">
                                Фото повинно бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg та не більше ніж 2Mb
                            </span>
                            <span class="help-block mt10 ph10">
                                Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf та не більше ніж 10Mb
                            </span>
                            <form role="form" enctype="multipart/form-data" id="upload-form"
                                  action="{{ route('admin.db.animals.upload-file', $animal->id) }}" method="post">
                                @csrf
                                <div id="files-container">
                                    @foreach($animal->images as $image)
                                        <div class="file-item">
                                            <a href="/{{ $image->path }}">
                                                <div class="file-preview" style="background-image: url('/{{ $image->path }}');"></div>
                                                <div class="file-name">{{ $image->filename }}.{{ $image->fileextension }}</div>
                                            </a>
                                            <div class="file-delete"
                                                 data-rem="{{ route('admin.db.animals.remove-file', $image->id) }}">
                                                <i class="fa fa-times" aria-hidden="true"></i></div>
                                        </div>
                                    @endforeach

                                    @foreach($animal->documents as $document)
                                        <div class="file-item doc">
                                            <a href="/{{ $document->path }}">
                                                <div class="file-preview" style="background-image: url('/img/file.png');"></div>
                                                <div class="file-name">{{ $document->filename }}.{{ $document->fileextension }}</div>
                                            </a>
                                            <div class="file-delete"
                                                 data-rem="{{ route('admin.db.animals.remove-file', $document->id) }}">
                                                <i class="fa fa-times" aria-hidden="true"></i></div>
                                        </div>
                                    @endforeach

                                    <label for="images" class="upload file-item">
                                        <span class="icon">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                        </span>
                                        <span class="title">
                                            Додати зображення<br>
                                            Максимум - {{ \App\Models\AnimalsFile::MAX_PHOTO_COUNT }}
                                        </span>
                                        <input type="file" id="images" name="images[]" multiple
                                               style="display: none;"/>
                                    </label>
                                    <label for="documents" class="upload file-item doc">
                                        <span class="icon">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        </span>
                                        <span class="title">Додати документи</span>
                                        <input type="file" id="documents" name="documents[]" multiple
                                                style="display: none;"/>
                                    </label>



                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        <div class="modal fade" id="archiveAnimal" tabindex="-2" role="dialog" aria-labelledby="requestChangeOwnerLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="margin-bottom: 25px;">
                                <h3>Архівування тварини</h3>
                            </div>
                            <div class="col-md-12">
                                <form id="archive_form" action="{{route('admin.db.animals.archive', $animal->id)}}"
                                      method="POST">
                                    @csrf
                                    <div class="row">

                                        <div class="form-group select">
                                            <label for="archive_type" class="col-lg-3 control-label">Причина
                                                архівації</label>
                                            <div class="col-lg-9">
                                                <select name="archive_type" id="archive_type" required>
                                                    <option value="death">Смерть</option>
                                                    <option value="moved_out">Виїзд</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group select" id="causeOfDeathBlock">
                                            <label for="archive_type" class="col-lg-3 control-label">Причина
                                                смерті</label>
                                            <div class="col-lg-9">
                                                <select name="cause_of_death" id="cause_of_death" required>
                                                    @foreach($causesOfDeath as $causeOfDeath)
                                                        <option value="{{$causeOfDeath->id}}">{{$causeOfDeath->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group datepicker">
                                            <label for="created_at" class="col-lg-3 control-label">Дата</label>
                                            <div class="col-lg-9">
                                                <input type="text" id="date" name="date" class="form-control" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="ml-auto mt-6 btn confirm btn-primary">Архівувати</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="modal fade" id="addIdentifyingDeviceModal" tabindex="-2" role="dialog" aria-labelledby="addIdentifyingDeviceModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 25px;">
                                    <h3>Додати новий ідентифікуючий пристрій</h3>
                                </div>
                                <div class="col-md-12">
                                    <form id="identifyDeviceForm" action="{{route('admin.db.animals.add-identifying-device', $animal->id)}}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group select">
                                                <label for="archive_type" class="col-lg-3 control-label">Тип пристрою:</label>
                                                <div class="col-lg-9">
                                                    <div class="validation-error alert alert-danger hidden"></div>
                                                    <select name="device_type" id="device_type" required>
                                                        @foreach($animal->identifyingDevicesArray() as $k => $v)
                                                            @if($animal->$k === null)
                                                                <option value="{{$k}}">{{$v}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="created_at" class="col-lg-3 control-label">Номер пристрою:</label>
                                                    <div class="col-lg-9">
                                                        <div class="validation-error alert alert-danger hidden"></div>
                                                        <input type="text" name="device_number" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <button type="submit" class="ml-auto mt-6 btn confirm btn-primary submit-ajax">Додати</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('scripts-end')

    <script type="text/javascript">
        jQuery(document).ready(function() {
            {{--$('.form-group.select select#user').selectize({--}}
                {{--options: JSON.parse({!! $users !!}),--}}
                {{--labelField: 'name',--}}
                {{--searchField: ['name']--}}
            {{--});--}}
            $.ajax({
                url: '/ajax/users',
                type: 'get',
                success: function(data) {
                    var users = $('.form-group.select select#user').selectize({
                        options: JSON.parse(data),
                        labelField: 'name',
                        valueField: 'value',
                        searchField: ['name']
                    });
                    users[0].selectize.setValue($('.form-group.select select#user').data('value'));
                    $('.drop-owner').on('click', function (e) {
                        e.preventDefault();
                        users[0].selectize.setValue('');
                    })
                },
                error: function(data) {
                    console.error(data);
                }
            });

            $('.form-group.select-gen select').selectize();

            $('.file-delete').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                $.ajax({
                    url: $(this).data('rem'),
                    type: 'post',
                    success: function(data) {
                        elem.parent('.file-item').remove();
                    },
                    error: function(data) {
                        console.error(data);
                    }
                });
            })
        });

        document.getElementById("images").onchange = function() {
            document.getElementById("upload-form").submit();
        };
        document.getElementById("documents").onchange = function() {
            document.getElementById("upload-form").submit();
        };

        $('#archiveButton').on('click', function (e) {
            e.preventDefault();
            $('#archiveAnimal').modal('show');
        });

        $('#archive_type').selectize();
        $('#cause_of_death').selectize();

        $('#archive_type').on('change', function (e) {
            var valueSelected = this.value;

            if (valueSelected !== 'death') {
                $('#causeOfDeathBlock').hide();
            } else {
                $('#causeOfDeathBlock').show();
            }
        });


        $('#archive_form').submit(function (e) {
            e.preventDefault();
            var causeOfDeathSelect = $('#cause_of_death');

            if ($('#causeOfDeathBlock').css('display') === 'none') {
                causeOfDeathSelect.remove();
            }
            $(this).unbind('submit').submit();
        });

        $('#deviceAddButton').on('click', function () {
            $('#addIdentifyingDeviceModal').modal('show');
        });

        $('#device_type').selectize({
            maxItems: 1,
            persist: false,
            create: false,
        });

        $('.deleteDeviceBtn').on('click', function () {
            return confirm("Ви впевнені що хочете видалити даний пристрій?");
        });

        $('.submit-ajax').click(function (e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var inputs = form.find('[name]');
            var ajaxData = {};

            inputs.each(function () {
                var name = $(this).attr('name');
                ajaxData[name] = $(this).val();
            });


            jQuery.ajax({
                url: form.attr('action'),
                method: 'post',
                data: ajaxData,
                success: function(data){
                    window.location = data;
                },
                error: function (errors) {
                    console.log(errors['responseJSON']['errors']);
                    fillErrors(form, errors);
                }
            });
        });

        function fillErrors(form, errors) {
            errors = errors['responseJSON']['errors'];

            for (var key in errors) {
                if (errors.hasOwnProperty(key)) {
                    form.find('[name="' + key + '"]').siblings('.validation-error').empty().append("<p>" + errors[key][0] + "</p>").removeClass('hidden');
                }
            }
        }
    </script>
@endsection