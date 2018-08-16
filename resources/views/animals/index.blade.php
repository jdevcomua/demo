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
                            <span class="title">Масть</span>
                            <span class="content">{{ $pet->color->name }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Дата народження</span>
                            <span class="content">{{ \App\Helpers\Date::getlocalizedDate($pet->birthday) }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Статус</span>
                            @if($pet->verified)
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
            <div class="above-text">Маєш ще тваринку? Додавай!</div>
        @endif
        <div class="buttons">
            <a href="{{ route('animals.create') }}" class="add-new-pet btn btn-big btn-block btn-primary">+ Додати</a>
            <div class="line">
                <hr>
                <span>або</span>
            </div>
            <a href="" class="btn btn-block btn-grey" data-toggle="modal" data-target="#searchModal">Мою тварину вже <br> зареєстровано</a>
        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="requestSearchModal" tabindex="-1" role="dialog" aria-labelledby="requestSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>ЗАПИТ НА ПОШУК</h3>
                            <p>Після ретельного пошуку ми надішлемо вам лист на пошту з результатами</p>
                        </div>
                        <div class="col-md-12">
                            <form action="{{route('animals.search-request')}}" method="POST" id="form">
                                @csrf
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="nickname">Кличка</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname" required="">
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="breed">Порода</label>
                                    <select name="breed" id="breed" required-field></select>
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="color">Масть</label>
                                    <select name="color" id="color" required-field></select>
                                </div>
                                <div class="form-group datepicker">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="birthday">Дата народження</label>
                                    <input type="text" class="form-control" id="birthday" name="birthday"
                                           required-field autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="street">Вулиця</label>
                                    <input type="text" class="form-control" id="street" name="street" required="" >
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="house">Будинок</label>
                                            <input type="text" class="form-control" id="house" name="house" required="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="appartment">Приміщення</label>
                                            <input type="text" class="form-control" id="appartment" name="appartment" required="" >
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"  class="ml-auto mt-6 btn confirm btn-primary" style="width: 350px;">Відправити</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-end')
    @parent
<script>
    $(document).on('click', '.btn-grey', function(e) {
        $('#modal-content').html(@include('animals.partials.modal'));
    });
    $(document).on('click', '.search', function(e){
        e.preventDefault();
        var loader = '<div class="showbox">\n' +
            '  <div class="loader">\n' +
            '    <svg class="circular" viewBox="25 25 50 50">\n' +
            '      <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>\n' +
            '    </svg>\n' +
            '  </div>\n' +
            '</div>';
        var badge = $('#badge').val();

        $.ajax({
            url: '{{route('ajax.animals.search')}}',
            type: 'post',
            data: {
                badge: badge
            },
            success: function(data) {
                console.log(data);
                $('#modal-content').html(@include('animals.partials.found'));
            },
            error: function(data) {
                $('#modal-content').html(@include('animals.partials.not-found'));
            }
        });
    });
    $(document).on('click', '.confirm', function() {
        var id = $(this).attr('data-id');

        $.ajax({
            url: '{{route('ajax.animals.request')}}',
            type: 'post',
            data: {
                animal_id: id
            },
            success: function(data) {
                $('#searchModal').modal('hide');
            },
            error: function(data) {
                $('#modal-content').html(@include('animals.partials.not-found'));
            }
        });
    });
    $(document).on('click', '.not-found-search', function() {
        $('#searchModal').modal('hide');
    });
</script>
@endsection


