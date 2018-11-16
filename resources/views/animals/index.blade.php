@extends('layout.app')

@section('title', 'Мої тварини')

@section('content')
    @if(count($pets))
        <div class="pets-list-header">СПИСОК МОЇХ ТВАРИН</div>
        <div class="pets-list">
            @foreach($pets as $pet)
                <div class="pets-list-item">
                    @if(count($pet->images))
                        <div class="pet-photo" style="background-image: url('{{ $pet->images[0]->path }}')"></div>
                    @else
                        <div class="pet-photo" style="background-image: url('/img/no_photo.png')"></div>
                    @endif
                    <div class="pet-info">
                        <div class="pet-info-block">
                            <span class="title">{{ $pet->species->name }}</span>
                            <span class="content">{{ $pet->nickname }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Порода</span>
                            <span class="content">{{ $pet->breed->name }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Масть</span>
                            <span class="content">{{ $pet->color->name }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Дата народження</span>
                            <span class="content">{{ \App\Helpers\Date::getlocalizedDate($pet->birthday) }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Статус</span>
                            @if($pet->isLost())
                                <span class="content red">Загублено</span>
                            @elseif($pet->verified)
                                <span class="content green">Верифіковано</span>
                            @else
                                <span class="content red">Не верифіковано</span>
                            @endif
                        </div>
                    </div>
                    <div class="dropdown more-button dropleft">
                        <div class="more-icon" data-toggle="dropdown"></div>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               href="{{ route('animals.show', ['id' => $pet->id]) }}">Переглянути картку</a>
                            @if(!$pet->verified)
                                <a class="dropdown-item"
                                   href="{{ route('animals.edit', ['id' => $pet->id]) }}">Редагувати інформацію</a>
                                <a class="dropdown-item"
                                   href="{{ route('animals.destroy', ['id' => $pet->id]) }}" onclick="event.preventDefault();
                                   document.getElementById('delete-form-{{ $pet->id }}').submit();">Видалити</a>
                                <form id="delete-form-{{ $pet->id }}" action="{{ route('animals.destroy', ['id' => $pet->id]) }}"
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        @include('animals._no_animals')
    @endif
    <div class="add-new-pet-wrap">
        @if(count($pets))
            <div class="above-text">Маєте ще тварину? Додавайте!</div>
        @endif
        <div class="buttons">
            <a href="{{ route('animals.create') }}" class="add-new-pet btn btn-big btn-block btn-primary">+ Додати</a>
            <div class="line">
                <hr>
                <span>або</span>
            </div>
            <a href="#" class="btn btn-block btn-grey" id="animal-search" data-toggle="modal" data-target="#searchModal">Мою тварину вже <br> зареєстровано</a>
            @if(false)
            <div class="line">
                <hr>
            </div>
            <a href="{{ route('animals.scan') }}" class="add-new-pet btn btn-block btn-warning">Сканувати код</a>
            @endif
        </div>
    </div>

    <div class="modal fade" id="requestSearchModal" tabindex="-2" role="dialog" aria-labelledby="requestSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>ЗАПИТ НА ПОШУК</h3>
                            <p>Після ретельного пошуку ми надішлемо вам лист на пошту з результатами</p>
                        </div>
                        <div class="col-md-12">
                            <form class="search-request" action="{{route('animals.search-request')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="nickname">Кличка <span class="required-field">*</span></label>
                                    <input type="text" class="form-control" id="nickname" name="nickname" required="">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label>Вид <span class="required-field">*</span></label>
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn radio-item big-radio active">
                                            <span class="label label-dog"></span>
                                            <input type="radio" name="species" value="1" autocomplete="off" checked>Собака
                                        </label>
                                        <label class="btn radio-item big-radio">
                                            <span class="label label-cat"></span>
                                            <input type="radio" name="species" value="2" autocomplete="off">Кіт
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label>Стать <span class="required-field">*</span></label>
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn radio-item big-radio active">
                                            <span class="label"><i class="fa fa-mars" aria-hidden="true"></i></span>
                                            <input type="radio" name="gender"
                                                   value="{{ \App\Models\Animal::GENDER_MALE }}" autocomplete="off" checked>Самець
                                        </label>
                                        <label class="btn radio-item big-radio">
                                            <span class="label"><i class="fa fa-venus" aria-hidden="true"></i></span>
                                            <input type="radio" name="gender"
                                                   value="{{ \App\Models\Animal::GENDER_FEMALE }}" autocomplete="off">Самка
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="breed">Порода <span class="required-field">*</span></label>
                                    <select name="breed" id="breed" required></select>
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="color">Масть <span class="required-field">*</span></label>
                                    <select name="color" id="color" required></select>
                                </div>
                                <div class="form-group select hidden">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="fur">Тип шерсті <span class="required-field">*</span></label>
                                    <select name="fur" id="fur" required></select>
                                </div>
                                <div class="form-group datepicker">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="birthday">Дата народження <span class="required-field">*</span></label>
                                    <input type="text" class="form-control" id="birthday" name="birthday"
                                           required autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="street">Вулиця</label>
                                    <input type="text" class="form-control" id="street" name="street">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="building">Будинок</label>
                                            <input type="text" class="form-control" id="building" name="building">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="apartment">Приміщення</label>
                                            <input type="text" class="form-control" id="apartment" name="apartment">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary" style="width: 350px;">Відправити</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="modal-content"></div>
        </div>
    </div>
@endsection

@section('scripts-end')
    @parent
    <script>
        animalSearch.Init(
            '{{route('ajax.animals.search')}}',
            '{{route('ajax.animals.request')}}',
            @include('animals.partials.modal'),
            @include('animals.partials.found'),
            @include('animals.partials.not-found'),
        );
    </script>
@endsection


