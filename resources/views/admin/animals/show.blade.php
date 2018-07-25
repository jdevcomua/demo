@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Тварина {{ $animal->nickname }}
@endsection
@section('contentheader_title')
    Тварина {{ $animal->nickname }}
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-4 ">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Тварина</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{route('admin.animals.update', $animal->id)}}" class="form-horizontal" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-group {{$errors->has('nickname') ? ' has-error' : '' }}">
                                <label for="nickname" class="col-sm-2 control-label">Прізвище</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Прізвище" value="{{$animal->nickname}}">
                                    @if ($errors->has('nickname'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>


                            <div class="form-group {{$errors->has('species_id') ? ' has-error' : '' }}">
                                <label for="birthday" class="col-sm-2 control-label">Стать</label>

                                <div class="col-sm-10">
                                    <select name="species_id" id="" class="form-control">
                                        <option value="0" @if($animal->species_id === 0) selected @endif>Жіноча</option>
                                        <option value="1" @if($animal->species_id === 1) selected @endif>Чоловіча</option>
                                    </select>
                                    @if ($errors->has('species_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('species_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('passport') ? ' has-error' : '' }}">
                                <label for="passport" class="col-sm-2 control-label">Паспорт</label>

                                <div class="col-sm-10">
                                    <input type="text" name="passport" class="form-control"  id="passport" value="{{$animal->passport}}">
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
                                    <input type="text" name="residence_address" class="form-control" id="residence_address" value="{{$animal->residence_address}}">
                                    @if ($errors->has('residence_address'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('residence_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group select">
                                <label for="breed">Порода</label>
                                <select name="breed" id="breed"></select>
                            </div>
                            <div class="form-group select">
                                <label for="color">Масть</label>
                                <select name="color" id="color"></select>
                            </div>

                            <div class="col-md-3 pull-right">
                                <button type="submit" class="btn btn-block btn-info">Змінити</button>
                            </div>
                        </form>
                        <table class="table table-striped">
                            <tr>
                                <td>Вид</td>
                                <td>{{$animal->species->name}}</td>
                            </tr>
                            <tr>
                                <td>Порода</td>
                                <td>{{$animal->breed->name}}</td>
                            </tr>
                            <tr>
                                <td>Колір</td>
                                <td>{{$animal->color->name}}</td>
                            </tr>
                            <tr>
                                <td>Прізвище</td>
                                <td>{{$animal->nickname}}</td>
                            </tr>
                            <tr>
                                <td>Стать</td>
                                <td>{{$animal->gender ? 'Ч' : 'Ж'}}</td>
                            </tr>
                            <tr>
                                <td>День Народження</td>
                                <td>{{$animal->birthday->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <td>Стерилізований</td>
                                <td>{{$animal->sterilized ? 'Так' : 'Ні'}}</td>
                            </tr>
                            <tr>
                                <td>Хазяїн</td>
                                <td>
                                    <a href="{{route('admin.users.show', $animal->user->id)}}">{{$animal->user->full_name}}</a></td>
                            </tr>
                            @if($animal->userThatConfirmed)
                            <tr>
                                <td>Модератор</td>
                                <td>{{$animal->userThatConfirmed->full_name}}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8 ">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Файли тварини</h3>
                    </div>
                    <div class="box-body">
                        @foreach($animal->files as $file)
                            @if($file->type === \App\Models\AnimalsFile::$FILE_TYPE_PHOTO)
                                <img src="{{$file->path}}" alt="">
                            @else
                                <a href="{{$file->path}}">{{$file->fileName}}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        // Selectize

        var options = {
            valueField: 'value',
            labelField: 'name',
        };
        var breeds = $('.form-group.select select#breed').selectize(options);
        var colors = $('.form-group.select select#color').selectize(options);
        console.log(breeds, colors);
        $('input[name="species"]').change(function(event) {
            breeds[0].selectize.clear();
            breeds[0].selectize.clearOptions();
            colors[0].selectize.clear();
            colors[0].selectize.clearOptions();
            updateSelects(event.target.value);
        });

        var xhrBreeds;
        var xhrColors;
        function updateSelects(species) {
            breeds[0].selectize.load(function (callback) {
                xhrBreeds && xhrBreeds.abort();
                xhrBreeds = $.ajax({
                    url: '/ajax/species/'+species+'/breeds',
                    success: function (results) {
                        callback(JSON.parse(results));
                    },
                    error: function () {
                        callback();
                    }
                })
            });
            colors[0].selectize.load(function (callback) {
                xhrColors && xhrColors.abort();
                xhrColors = $.ajax({
                    url: '/ajax/species/'+species+'/colors',
                    success: function (results) {
                        callback(JSON.parse(results));
                    },
                    error: function () {
                        callback();
                    }
                })
            });
        }

        updateSelects($('input[name="species"]')[0].value);
        /////////////////////////////////////////
    </script>
@endsection
