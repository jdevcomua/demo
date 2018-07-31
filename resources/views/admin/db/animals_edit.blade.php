@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Редагування тварини</a>
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
                              action="{{ route('admin.db.animals.update', $animal->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="panel-body">
                                @if($errors->user)
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
                                <div class="form-group datepicker">
                                    <label for="birthday" class="col-lg-3 control-label">Дата народження</label>
                                    <div class="col-lg-8 ">
                                        <input type="text" class="form-control" id="birthday" name="birthday"
                                               value="{{ $animal->birthday->format('d/m/Y') }}" required />
                                    </div>
                                </div>
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
                                    <label class="col-lg-3 control-label">Статус:</label>
                                    <div class="col-lg-8">
                                        @if($animal->verified)
                                            <label class="control-label text-success">Верифіковано</label>
                                        @else
                                            <label class="control-label text-danger">Не верифіковано</label>
                                        @endif
                                    </div>
                                </div>
                                @if($animal->verified)
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Ким:</label>
                                        <div class="col-lg-8">
                                            <a href="#"><label class="cursor control-label">{{ $animal->userThatConfirmed->name }}</label></a>
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
                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Файли тварини</div>
                        </div>
                        <div class="panel-body pn">
                            <div id="files-container">
                                @foreach($animal->images as $image)
                                    <a href="/{{ $image->path }}" class="file-item">
                                        <div class="file-preview" style="background-image: url('/{{ $image->path }}');"></div>
                                        <div class="file-name">{{ $image->filename }}.{{ $image->fileextension }}</div>
                                    </a>
                                @endforeach

                                @foreach($animal->documents as $document)
                                    <a href="/{{ $document->path }}" class="file-item doc">
                                        <div class="file-preview" style="background-image: url('/img/file.png');"></div>
                                        <div class="file-name">{{ $document->filename }}.{{ $document->fileextension }}</div>
                                    </a>
                                @endforeach

                                <div class="file-item gap"></div>
                                <div class="file-item gap"></div>
                                <div class="file-item gap"></div>
                                <div class="file-item gap"></div>
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

            $('.form-group.select-gen select').selectize();

        });
    </script>
@endsection