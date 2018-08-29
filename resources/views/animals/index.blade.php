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
            <div class="above-text">Маєте ще тварину? Додавайте!</div>
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
                            <form action="{{route('animals.search-request')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="nickname">Кличка</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname" required="">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label>Вид</label>
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
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="breed">Порода</label>
                                    <select name="breed" id="breed" required required></select>
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="color">Масть</label>
                                    <select name="color" id="color" required required></select>
                                </div>
                                <div class="form-group select hidden">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="fur">Тип шерсті</label>
                                    <select name="fur" id="fur" required required></select>
                                </div>
                                <div class="form-group datepicker">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="birthday">Дата народження</label>
                                    <input type="text" class="form-control" id="birthday" name="birthday"
                                           required required autocomplete="off" readonly="true" />
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
                                            <label for="building">Будинок</label>
                                            <input type="text" class="form-control" id="building" name="building" required="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="apartment">Приміщення</label>
                                            <input type="text" class="form-control" id="apartment" name="apartment" required="" >
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

    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" id="modal-content">

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

    function searchAnimal() {
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

        return false;
    }

    $(document).on('click', '.search', function(e) {
        e.preventDefault();
        searchAnimal();
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
                window.location.reload();
            },
            error: function(data) {
                $('#modal-content').html(@include('animals.partials.not-found'));
            }
        });
    });
    $(document).on('click', '.not-found-search', function() {
        $('#searchModal').modal('hide');
        setTimeout(function () {
            $('#requestSearchModal').modal('show');
        }, 500);
        $('body').addClass('modal-open');
    });
    // $('#form').on('submit', function(){
    //     $(window).scrollTop(0);
    //     window.location.reload();
    // })
</script>
@endsection


