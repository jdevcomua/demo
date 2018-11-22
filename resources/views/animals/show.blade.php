@extends('layout.app')

@section('title', $animal->nickname)

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">{{ $animal->nickname }}</div>
        @if(!$animal->verified) <a href="{{ route('animals.edit', $animal->id) }}" class="action-link">Редагувати</a> @endif
    </div>
    <div class="animal-show">
        <div class="animal-images">
            <div class="animal-image main"
                 @if(array_key_exists(1, $animal->imagesArray)) style="background-image: url('/{{ $animal->imagesArray[1] }}')" @endif>
            </div>
            @for($i = 2; $i < 10; $i++)
                @if(array_key_exists($i, $animal->imagesArray))
                    <div class="animal-image" style="background-image: url('/{{ $animal->imagesArray[$i] }}')"></div>
                @endif
            @endfor
        </div>
        <div class="pet-info">
            <div class="pet-info-block">
                <span class="title">Вид</span>
                <span class="content">{{ $animal->species->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Стать</span>
                <span class="content">
                    @if($animal->gender === \App\Models\Animal::GENDER_FEMALE) Самка @endif
                    @if($animal->gender === \App\Models\Animal::GENDER_MALE) Самець @endif
                </span>
            </div>
            <div class="pet-info-block">
                <span class="title">Статус</span>
                @if($animal->lost && !$animal->lost->found)
                    <span class="content red">Загублено</span>
                @elseif($animal->verified)
                    <span class="content green">Верифіковано</span>
                @else
                    <span class="content red">Не верифіковано</span>
                @endif
            </div>
            <div class="pet-info-block">
                <span class="title">Масть</span>
                <span class="content">{{ $animal->color->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Тип шерсті</span>
                <span class="content">{{ $animal->fur->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Дата народження</span>
                <span class="content">{{ \App\Helpers\Date::getlocalizedDate($animal->birthday) }}</span>
            </div>
            <div class="pet-info-block w-100">
                <span class="title">Порода</span>
                <span class="content">{{ $animal->breed->name }}</span>
            </div>
        </div>
        <div class="animal-options">
            @if($animal->sterilized)
                <div class="animal-option-item">
                    Стерилізовано
                </div>
            @endif
        </div>
        <div class="pet-info-block comment">
            <span class="title">Коментарі (Особливі прикмети)</span>
            <span class="content">{{ $animal->comment }}</span>
        </div>
        <hr class="divider">
        <div class="files-container">
            <div class="files-container-title">Файли</div>
            <div class="files-list">
                @forelse($animal->documents as $doc)
                    <a href="/{{ $doc->path }}" class="file-item">
                        <span class="file-name">{{ $doc->filename }}</span>
                        <span class="file-ext">.{{ $doc->fileextension }}</span>
                    </a>
                @empty
                    <div class="no-files">Файли відсутні...</div>
                @endforelse
            </div>
        </div>
        <hr class="divider">
        <div class="animal-actions">
            @if($animal->lost && !$animal->lost->found)
                <div class="animal-action">
                    <div class="action-title">Тварину знайдено</div>
                    <div class="action-description">Якщо ви знайшли вашого улюбленця тисніть кнопку <i>Тварину знайдено</i>!</div>
                    <button class="btn btn-primary btn-i i-ok btn-tbig lost_animal-btn" >Тварину знайдено</button>
                </div>
            @else
                <div class="animal-action">
                    <div class="action-title">Розшук</div>
                    <div class="action-description">Якщо ви втратили вашого улюбленця тисніть кнопку <i>Розшук</i> для того щоб швидше знайти його!</div>
                    <button class="btn btn-red btn-i i-warn btn-tbig lost_animal-btn" >Розшук</button>
                </div>
            @endif
            <div class="animal-action">
                <div class="action-title">Зміна власника</div>
                <div class="action-description">Якщо власник тварини змінився тисніть кнопку <i>Змінити власника</i> для того щоб повідомити про це нас</div>
                <button class="btn btn-dgrey btn-i i-change btn-tbig" id="changeOwnerButton">Змінити власника</button>
            </div>
                <div class="animal-action">
                    <button class="btn btn-primary" id="animal_death-btn" >Повідомити про смерть</button>
                </div>
                <div class="animal-action">
                    <button class="btn btn-primary" id="moved_animal-btn">Тварина
                        вивезена з м. Київ</button>
                </div>
        </div>
    </div>

    <form id="lostAnimalSearch" action="{{route('animals.lost', $animal->id)}}" method="post">
        @csrf
    </form>

    <div class="modal fade" id="requestChangeOwner" tabindex="-2" role="dialog" aria-labelledby="requestChangeOwnerLabel" aria-hidden="true">
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
                            <h3>ЗАПИТ НА ЗМІНУ ВЛАСНИКА</h3>
                            <p>Після обробки вашого запиту ми надішлемо вам лист на пошту з результатами</p>
                        </div>
                        <div class="col-md-12">
                            <form class="search-request" action="{{route('animals.change-owner')}}" method="POST">
                                @csrf
                                <input type="hidden" name="animal_id" value="{{$animal->id}}">
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="full_name">ПІБ нового власника <span class="required-field">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="passport">Номер паспорту нового власника <span class="required-field">*</span></label>
                                            <input type="text" class="form-control" id="passport" name="passport" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="contact_phone">Контактний номер телефону <span class="required-field">*</span></label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
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

    <div class="modal fade" id="informAnimalDeath" tabindex="-2" role="dialog" aria-labelledby="informAnimalDeathLabel" aria-hidden="true">
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
                            <h3>Нам дуже шкода…</h3>
                        </div>
                        <div class="col-md-12 mt-3">
                            <form class="search-request" action="{{route('animals.inform-death')}}" method="POST">
                                @csrf
                                <input type="hidden" name="animal_id" value="{{$animal->id}}">

                                <div class="form-group select" id="causeOfDeathBlock">
                                    <label for="archive_type" class="control-label">Оберіть причину смерті</label>
                                        <select name="cause_of_death" id="cause_of_death" required>
                                            @foreach($causesOfDeath as $causeOfDeath)
                                                <option value="{{$causeOfDeath->id}}">{{$causeOfDeath->name}}</option>
                                            @endforeach
                                        </select>
                                </div>

                                        <div class="form-group datepicker">
                                            <label for="created_at" class="control-label">Дата смерті</label>
                                                <input type="text" id="date_death" name="date" class="form-control no-cursor readonly" autocomplete="off" required>
                                        </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary" style="width: 350px;">Повідомити</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="informAnimalMoved" tabindex="-2" role="dialog" aria-labelledby="informAnimalMovedLabel" aria-hidden="true">
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
                            <h3>Тварина вивезена з м.Київ</h3>
                        </div>
                        <div class="col-md-12 mt-3">
                            <form class="search-request" action="{{route('animals.inform-moved')}}" method="POST">
                                @csrf
                                <input type="hidden" name="animal_id" value="{{$animal->id}}">

                                <div class="form-group datepicker">
                                    <label for="created_at" class="control-label">Дата вивезення</label>
                                    <input type="text" id="date_move" name="date" class="form-control no-cursor readonly" required autocomplete="off" >
                                </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary" style="width: 350px;">Повідомити</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-end')
    <script>
            $(".readonly").on('keydown paste', function(e){
                e.preventDefault();
            });

        $('.lost_animal-btn').on('click', function () {
            var form = $('#lostAnimalSearch');
            form.submit();
        });

        $('#animal_death-btn').on('click', function () {
            $('#informAnimalDeath').modal('show');
        });

        $('#moved_animal-btn').on('click', function () {
            $('#informAnimalMoved').modal('show');
        });

        $('#cause_of_death').selectize();

    </script>
@endsection
