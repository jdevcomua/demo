@extends('layout.app')

@section('title', $animal->nickname)
{{--$animal->chronicles[1]->type->fields[0]->value--}}

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">{{ $animal->nickname }} {{$animal->nickname_lat ? '(' . $animal->nickname_lat . ')' : ''}}</div>
        @if(!$animal->verified) <a href="{{ route('animals.edit', $animal->id) }}"
                                   class="action-link">Редагувати</a> @endif
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
        @if($animal->badge !== null)
            <div class="animal-badge"
                 style="position: inherit; margin: 2rem 0 0 1.1rem; background-image: none; background-color: rgba(1, 68, 121, 0.8);">
                <span class="animal-badge-icon"></span>
                <span class="animal-badge-number">{{$animal->badge->number}}</span>
            </div>
        @endif
        <div class="cols-block" style="padding-top: 5.5rem;">
            <div class="cols-block-header">
                <div class="block-title">ОСНОВНІ ВІДОМОСТІ</div>
                <div class="block-sub-title">Основні дані про тварину</div>
            </div>
            <div class="cols-block-content">
                <div class="pet-info">
                    <div class="pet-info-block">
                        <span class="title">Кличка</span>
                        <span class="content">{{ $animal->nickname }}</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Кличка на латині</span>
                        <span class="content">{{ $animal->nickname_lat ?? 'Відсутня' }}</span>
                    </div>
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
                        <span class="title">Порода</span>
                        <span class="content">{{ $animal->breed->name }}</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Метис породи</span>
                        <span class="content">{{ $animal->half_breed ? 'Так' : 'Ні' }}</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Окрас</span>
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
                    <div class="pet-info-block">
                        <span class="title">Зріст</span>
                        <span class="content">{{ $animal->tallness !== null ?  $animal->tallness . ' см' : 'Не вказано'}}</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Стерилізація</span>
                        <span class="content">{{ $animal->sterilized ?  'Так' : 'Ні'}}</span>
                    </div>
                    <div class="pet-info-block">
                        <span class="title">Тестування тварини</span>
                        <span class="content">{{ $animal->testing ?? 'Не проведено'}}</span>
                    </div>
                    @if($animal->veterinaryPassport)
                        <div class="pet-info-block">
                            <span class="title">Номер паспорту</span>
                            <span class="content">{{ $animal->veterinaryPassport->number }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Ким видано</span>
                            <span class="content">{{ $animal->veterinaryPassport->issued_by }}</span>
                        </div>
                    @endif
                    @if($animal->comment !== null)
                        <div class="pet-info-block comment">
                            <span class="title">Коментарі (Особливі прикмети)</span>
                            <span class="content">{{ $animal->comment }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">РЕЄСТРАЦІЯ, ВЕРИФІКАЦІЯ ТА ІДЕНТИФІКАЦІЇ</div>
                <div class="block-sub-title">Відомості про верифікацію та засіб ідентифікації тварини: жетон, чип або
                    тавро
                </div>
            </div>
            <div class="cols-block-content">
                <div class="pet-info">
                    <div class="pet-info-block">
                        <span class="title">Дата реєстрації</span>
                        <span class="content">{{ $animal->created_at->format('d/m/Y') }}</span>
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
                    @if($animal->verified)
                        <div class="pet-info-block">
                            <span class="title">Дата верифікації</span>
                            <span class="content">{{ $animal->verification->updated_at->format('d/m/Y') }}</span>
                        </div>
                        @if($animal->verification->user)
                            <div class="pet-info-block">
                                <span class="title">Ким верифіковано</span>
                                <span class="content">{{ $animal->verification->user->name }}</span>
                            </div>
                        @endif
                    @endif

                </div>
                @if(count($animal->identifyingDevices))
                    @foreach ($animal->identifyingDevices as $index => $identifyingDevice)
                        <div class="pet-info divider">
                            <div class="pet-info-block">
                                <span class="title">Тип пристрою</span>
                                <span class="content">{{$identifyingDevice->type->name}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Номер</span>
                                <span class="content">{{$identifyingDevice->number}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Дата видачі</span>
                                <span class="content">{{$identifyingDevice->created_at->format('m/d/Y')}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Ким видано</span>
                                <span class="content">{{$identifyingDevice->issued_by}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Додаткова інформація</span>
                                <span class="content">{{$identifyingDevice->info ?? 'Відсутня'}}</span>
                            </div>
                        </div>

                    @endforeach
                @endif
            </div>
        </div>
        @if(count($veterinaryMeasures))
            <hr class="divider">
            <div class="cols-block">
                <div class="cols-block-header">
                    <div class="block-title">ВЕТЕРИНАРНІ ЗАХОДИ</div>
                    <div class="block-sub-title">Відомості про ветеринарні заходи щодо тварини</div>
                </div>
                <div class="cols-block-content">
                    @foreach ($veterinaryMeasures as $index => $measure)
                        <div class="pet-info @if($index) divider @endif">
                            <div class="pet-info-block">
                                <span class="title">Дата</span>
                                <span class="content">{{$measure->date->format('d/m/Y')}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Захід</span>
                                <span class="content">{{$measure->name ?? $measure->veterinaryMeasure->name}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Відомості</span>
                                <span class="content">{{$measure->description ?? 'Відсутні'}}</span>
                            </div>
                            <div class="pet-info-block">
                                <span class="title">Ким проведено</span>
                                <span class="content">{{$measure->made_by}}</span>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        @endif

        @if(count($animal->chronicles))
            <hr class="divider">

            <div class="cols-block">
                <div class="cols-block-header">
                    <div class="block-title">ІСТОРІЯ</div>
                    <div class="block-sub-title">Журнал дій щодо тварини</div>
                </div>
                <div class="cols-block-content">
                    <div class="pet-chronicles-block">
                        @foreach($animal->chronicles->sortByDesc('created_at') as $chronicle)
                            <div class="pet-chronicles-block-item">
                                <span class="date">{{$chronicle->date}}</span>
                                <div class="content">{{$chronicle->text}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <hr class="divider">

        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ФАЙЛИ</div>
                <div class="block-sub-title">Завантажені файли</div>
            </div>
            <div class="cols-block-content">
                <div class="files-container">
                    <div class="files-list">
                        @if($animal->hasDocuments() || $animal->hasVetFiles())
                            @foreach($animal->documents as $doc)
                                <a href="/{{ $doc->path }}" class="file-item">
                                    <span class="file-name">{{ $doc->filename }}</span>
                                    <span class="file-ext">.{{ $doc->fileextension }}</span>
                                </a>
                            @endforeach
                            @foreach($animal->animalVeterinaryMeasure as $veterinaryMeasure)
                                @if(count($veterinaryMeasure->files))
                                    @foreach($veterinaryMeasure->files as $file)
                                        <a href="/{{ $file->path }}" class="file-item">
                                            <span class="file-name">{{ $file->filename }}</span>
                                            <span class="file-ext">.{{ $file->fileextension }}</span>
                                        </a>
                                    @endforeach
                                @endif
                            @endforeach
                        @else
                            <div class="no-files">Файли відсутні...</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <hr class="divider">
        <div class="animal-actions">
            @if($animal->lost && !$animal->lost->found)
                <div class="animal-action">
                    <div class="action-title">Тварину знайдено</div>
                    <div class="action-description">Якщо ви знайшли вашого улюбленця тисніть кнопку <i>Тварину
                            знайдено</i>!
                    </div>
                    <button class="btn btn-primary btn-i i-ok btn-tbig lost_animal-btn">Тварину знайдено</button>
                </div>
            @else
                <div class="animal-action">
                    <div class="action-title">Розшук</div>
                    <div class="action-description">Якщо ви втратили вашого улюбленця тисніть кнопку <i>Розшук</i> для
                        того щоб швидше знайти його!
                    </div>
                    <button class="btn btn-red btn-i i-warn btn-tbig lost_animal-btn">Розшук</button>
                </div>
            @endif
            <div class="animal-action">
                <div class="action-title">Зміна власника</div>
                <div class="action-description">Якщо власник тварини змінився тисніть кнопку <i>Змінити власника</i> для
                    того щоб повідомити про це нас
                </div>
                <button class="btn btn-dgrey btn-i i-change btn-tbig" id="changeOwnerButton">Змінити власника</button>
            </div>
            <div class="animal-action">
                <div class="action-title">Тварина померла</div>
                <div class="action-description">Якщо ваш улюбленець помер тисніть кнопку <i>Тварина померла</i> для того
                    щоб повідомити про це!
                </div>
                <button class="btn btn-dgrey btn-i i-dead btn-tbig" id="animal_death-btn">Тварина померла</button>
            </div>
            <div class="animal-action">
                <div class="action-title">Зміна країни</div>
                <div class="action-description">Якщо тварина змінила країну проживання тисніть кнопку <i>Тварина
                        виїхала</i> для того щоб повідомити про це!
                </div>
                <button class="btn btn-light-blue btn-i i-plane btn-tbig" id="moved_animal-btn">Тварина виїхала</button>
            </div>
        </div>
    </div>


    <form id="lostAnimalSearch" action="{{route('animals.lost', $animal->id)}}" method="post">
        @csrf
    </form>

    <div class="modal fade" id="requestChangeOwner" tabindex="-2" role="dialog"
         aria-labelledby="requestChangeOwnerLabel" aria-hidden="true">
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
                                    <label for="full_name">ПІБ нового власника <span
                                                class="required-field">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="passport">Номер паспорту нового власника <span
                                                        class="required-field">*</span></label>
                                            <input type="text" class="form-control" id="passport" name="passport"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="validation-error alert alert-danger hidden"></div>
                                            <label for="contact_phone">Контактний номер телефону <span
                                                        class="required-field">*</span></label>
                                            <input type="text" class="form-control" id="contact_phone"
                                                   name="contact_phone" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary"
                                        style="width: 350px;">Відправити
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="informAnimalDeath" tabindex="-2" role="dialog" aria-labelledby="informAnimalDeathLabel"
         aria-hidden="true">
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
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="created_at" class="control-label">Дата смерті</label>
                                    <input type="text" id="date_death" name="date"
                                           class="form-control no-cursor readonly" autocomplete="off" required>
                                </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary submit-ajax"
                                        style="width: 350px;">Повідомити
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="informAnimalMoved" tabindex="-2" role="dialog" aria-labelledby="informAnimalMovedLabel"
         aria-hidden="true">
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
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="created_at" class="control-label">Дата вивезення</label>
                                    <input type="text" id="date_move" name="date"
                                           class="form-control no-cursor readonly" required autocomplete="off">
                                </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary submit-ajax"
                                        style="width: 350px;">Повідомити
                                </button>
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
        $(".readonly").on('keydown paste', function (e) {
            e.preventDefault();
        });

        $('.lost_animal-btn').on('click', function () {
            var form = $('#lostAnimalSearch');
            form.submit();
        });

        $('#changeOwnerButton').on('click', function () {
            $('#requestChangeOwner').modal('show');
        });

        $('#animal_death-btn').on('click', function () {
            $('#informAnimalDeath').modal('show');
        });

        $('#moved_animal-btn').on('click', function () {
            $('#informAnimalMoved').modal('show');
        });

        $('#cause_of_death').selectize();

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
                success: function (data) {
                    window.location = data;
                },
                error: function (errors) {
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
