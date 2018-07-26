@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Тварина {{ $animal->nickname }}
@endsection
@section('contentheader_title')
    Тварина {{ $animal->nickname }}
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
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
                        <form action="{{route('admin.animals.update', $animal->id)}}" class="" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-group {{$errors->has('nickname') ? ' has-error' : '' }}">
                                <label for="nickname" class="col-sm-4 control-label">Прізвище</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Прізвище" value="{{$animal->nickname}}">
                                    @if ($errors->has('nickname'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>


                            <div class="form-group {{$errors->has('species_id') ? ' has-error' : '' }}">
                                <label for="species_id" class="col-sm-4 control-label">Вид</label>

                                <div class="col-sm-8">
                                    <select name="species_id" id="" class="form-control">
                                        @foreach($species as $type)
                                        <option value="{{$type->id}}" @if($animal->species_id === $type->id) selected @endif>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('species_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('species_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('breed_id') ? ' has-error' : '' }}">
                                <label for="breed_id" class="col-sm-4 control-label">Порода</label>

                                <div class="col-sm-8">
                                    <select name="breed_id" id="breed"></select>
                                    @if ($errors->has('breed_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('breed_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('color_id') ? ' has-error' : '' }}">
                                <label for="color_id" class="col-sm-4 control-label">Колір</label>

                                <div class="col-sm-8">
                                    <select name="color_id" id="color"></select>
                                    @if ($errors->has('color_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('color_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('gender') ? ' has-error' : '' }}">
                                <label for="gender" class="col-sm-4 control-label">Стать</label>

                                <div class="col-sm-8">
                                    <select name="gender" id="" class="form-control">
                                        <option value="0" @if($animal->gender === 0) selected @endif>Жіноча</option>
                                        <option value="1" @if($animal->gender === 1) selected @endif>Чоловіча</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('birthday') ? ' has-error' : '' }}">
                                <label for="birthday" class="col-sm-4 control-label">День Народження</label>

                                <div class="col-sm-8">
                                    <input type="text" name="birthday" class="form-control datepicker"  id="birthday" value="{{$animal->birthday->format('d-m-Y')}}">
                                    @if ($errors->has('birthday'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('sterilized') ? ' has-error' : '' }}">
                                <label for="sterilized" class="col-sm-4 control-label">Стерилізований</label>

                                <div class="col-sm-8">
                                    <select name="sterilized" id="" class="form-control">
                                        <option value="0" @if($animal->sterilized === 0) selected @endif>Ні</option>
                                        <option value="1" @if($animal->sterilized === 1) selected @endif>Так</option>
                                    </select>
                                    @if ($errors->has('sterilized'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('sterilized') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('user_id') ? ' has-error' : '' }}">
                                <label for="user_id" class="col-sm-4 control-label">Хазяїн</label>

                                <div class="col-sm-8">
                                    <select name="user_id" id="" class="form-control">
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" @if($animal->user_id === $user->id) selected @endif>{{$user->full_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('user_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{$errors->has('comment') ? ' has-error' : '' }}">
                                <label for="comment" class="col-sm-4 control-label">Комментар</label>

                                <div class="col-sm-8">
                                    <textarea name="comment" class="form-control" id="" cols="30" rows="10">{{ $animal->comment ? $animal->comment : old('comment') }}</textarea>
                                    {{--<input type="text" name="comment" class="form-control" id="comment" value="{{$animal->comment}}">--}}
                                    @if ($errors->has('comment'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="col-md-3 pull-left">
                                <button type="submit" class="btn btn-block btn-info">Змінити</button>
                            </div>
                            <div class="col-md-4 pull-right">
                                <a class="btn btn-block btn-success confirm">Підтвердити</a>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="{{route('admin.animals.confirm', $animal->id)}}" method="post" class="hidden" id="confirm">
                    @csrf
                </form>
            </div>

            <div class="col-md-8 ">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Файли тварини</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @foreach($animal->files as $file)
                                <div class="col-sm-4">
                                @if($file->type === \App\Models\AnimalsFile::FILE_TYPE_PHOTO)
                                    <img src="/{{$file->path}}" alt="">
                                @else
                                    <a href="/{{$file->path}}">{{$file->fileName}}</a>
                                @endif
                                </div>
                            @endforeach
                        </div>
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

        // Selectize

        var options = {
            valueField: 'value',
            labelField: 'name',
        };
        var breeds = $('#breed').selectize(options);
        var colors = $('#color').selectize(options);

        $('select[name="species_id"]').change(function(event) {
            breeds[0].selectize.clear();
            breeds[0].selectize.clearOptions();
            colors[0].selectize.clear();
            colors[0].selectize.clearOptions();
            updateSelects(event.target.value);
        });

        var xhrBreeds;
        var xhrColors;
        function updateSelects(species, set = false) {
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
            if (set) {

            }
        }

        updateSelects($('select[name="species_id"]')[0].value, true);

        /////////////////////////////////////////
        setTimeout(function () {
            breeds[0].selectize.addItem({{$animal->breed_id}});
            colors[0].selectize.addItem({{$animal->color_id}});
        }, 1000)

        $('.confirm').on('click', function() {
            $('#confirm').submit();
        })
    </script>
@endsection
