@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Додавання тварини</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Картка тварини</div>
                        </div>
                        <form class="form-horizontal" role="form" enctype="multipart/form-data"
                              action="{{ route('admin.db.animals.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ ($user) ? $user->id : \Auth::id() }}">
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


                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Зображення</label>
                                    <div class="col-lg-8 control-label text-left">
                                        <button type="button">
                                            <label for="images" class="mn">Оберіть файли</label>
                                        </button>
                                        <span class="file-count pl5"></span>
                                        <input type="file" id="images" name="images[]"
                                               multiple class="custom-file-input">
                                        <span class="help-block mt5">Фото повинно бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg та не більше ніж 2Mb</span>
                                    </div>
                                </div>


                                <div class="form-group select">
                                    <label for="user" class="col-lg-3 control-label">Власник</label>
                                    <div class="col-lg-8">
                                        <select name="user" id="user" data-value="{{ $user ? $user->id : '' }}"></select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname" class="col-lg-3 control-label">Кличка</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="nickname" name="nickname" class="form-control"
                                            value="{{ old('nickname') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nickname_lat" class="col-lg-3 control-label">Кличка на латині</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="nickname_lat" name="nickname_lat" class="form-control"
                                               value="{{ old('nickname_lat') }}" >
                                    </div>
                                </div>
                                <div class="form-group select-gen">
                                    <label for="species" class="col-lg-3 control-label">Вид</label>
                                    <div class="col-lg-8">
                                        <select id="species" name="species" required>
                                            @foreach($species as $s)
                                                <option value="{{$s->id}}"
                                                        @if(old('species_id') === $s->id) selected @endif>{{$s->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group select-gen">
                                    <label for="gender" class="col-lg-3 control-label">Стать</label>
                                    <div class="col-lg-8">
                                        <select id="gender" name="gender" required>
                                            <option @if(old('gender') === \App\Models\Animal::GENDER_FEMALE) selected @endif
                                                    value="{{ \App\Models\Animal::GENDER_FEMALE }}">Самка</option>
                                            <option @if(old('gender') === \App\Models\Animal::GENDER_MALE) selected @endif
                                                    value="{{ \App\Models\Animal::GENDER_MALE }}">Самець</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group select">
                                    <label for="breed" class="col-lg-3 control-label">Порода</label>
                                    <div class="col-lg-8">
                                        <select name="breed" id="breed" required data-value="{{ old('breed') }}"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sterilized" class="col-lg-3 control-label"></label>
                                    <div class="col-lg-8">
                                        <div class="checkbox-custom pt10">
                                            <input type="checkbox" id="half_breed" value="1" name="half_breed">
                                            <label for="half_breed">Метис породи</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group select">
                                    <label for="color" class="col-lg-3 control-label">Масть</label>
                                    <div class="col-lg-8">
                                        <select name="color" id="color" required data-value="{{ old('color') }}"></select>
                                    </div>
                                </div>
                                <div class="form-group select">
                                    <label for="fur" class="col-lg-3 control-label">Тип шерсті</label>
                                    <div class="col-lg-8">
                                        <select name="fur" id="fur" required data-value="{{ old('fur') }}"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tallness" class="col-lg-3 control-label">Зріст</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="tallness" name="tallness" class="form-control" value="{{ old('tallness') }}">
                                        <span class="help-block mt5">Зріст вказується в сантиметрах</span>
                                    </div>
                                </div>
                                <div class="form-group datepicker">
                                    <label for="birthday" class="col-lg-3 control-label">Дата народження</label>
                                    <div class="col-lg-8 ">
                                        <input type="text" class="form-control" id="birthday" name="birthday"
                                               value="{{ old('birthday') }}" required autocomplete="off" readonly="true" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sterilized" class="col-lg-3 control-label"></label>
                                    <div class="col-lg-8">
                                        <div class="checkbox-custom pt10">
                                            <input type="checkbox" id="sterilized" value="1" name="sterilized"
                                                   @if(old('sterilized')) checked @endif>
                                            <label for="sterilized">Стерилізовано</label>
                                        </div>
                                    </div>
                                </div>
                                @permission('verify-animal')
                                    <div class="form-group select">
                                        <label for="device_type" class="col-lg-3 control-label">Вид засобу ідентифікації:</label>
                                        <div class="col-lg-8">
                                            <select name="device_type" id="device_type">
                                                <option value="">-</option>
                                            @foreach($identifyingDeviceTypes as $identifyingDeviceType)
                                                        <option value="{{$identifyingDeviceType->id}}">{{$identifyingDeviceType->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="device_number" class="col-lg-3 control-label">Номер засобу ідентифікації:</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="device_number" name="device_number" class="form-control" value="{{old('device_number') ?? ''}}">
                                        </div>
                                    </div>
                                @endpermission
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="testing">Тестування тварини</label>
                                    <div class="col-lg-8">
                                    <textarea class="form-control" id="testing" name="testing"
                                              rows="3" style="resize: none">{{ old('testing') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="comment">Коментарі (Особливі прикмети)</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="comment" name="comment"
                                                  rows="3" style="resize: none">{{ old('comment') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Документи</label>
                                    <div class="col-lg-8 control-label text-left">
                                        <button type="button">
                                            <label for="documents" class="mn">Оберіть файли</label>
                                        </button>
                                        <span class="file-count pl5"></span>
                                        <input type="file" id="documents" name="documents[]"
                                               multiple class="custom-file-input">
                                        <span class="help-block mt5">Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf та не більше ніж 10Mb</span>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-default ph25">Зберегти</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

    </section>
@endsection

@section('scripts-end')

    <script type="text/javascript">
        jQuery(document).ready(function() {

            $('.form-group.select-gen select').selectize();

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
                },
                error: function(data) {
                    console.error(data);
                }
            });

            $('.form-group.select-gen select').selectize();

            $('#device_type').selectize({
                maxItems: 1,
                persist: false,
                create: false,
            });
        });
    </script>
@endsection
