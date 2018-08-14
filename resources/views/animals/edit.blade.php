@extends('layout.app')

@section('title', 'Редагування інформації тварини')

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">Редагування інформації тварини</div>
    </div>
    <form action="{{ route('animals.update', $pet->id) }}" enctype='multipart/form-data' method="POST" id="form">
        @method('PUT')
        @csrf

        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ЗОБРАЖЕННЯ  <span class="required-field">*</span></div>
                <div class="block-sub-title">Що більше фото тим простіше знайти тварину у випадку втрати.<br>
                    Фотографуйте тваринку з різних сторін.</div>
                <div class="pet-photo-hint"><div>Це фото буде головним</div></div>
            </div>
            <div class="cols-block-content">
                <div class="validation-error alert alert-danger hidden"></div>
                <div class="new-pet-photos">
                    <label class="photo-item photo-item-main @if(array_key_exists(1, $pet->imagesArray)) filled @endif " for="image1"
                           @if(array_key_exists(1, $pet->imagesArray)) style="background-image: url('/{{ $pet->imagesArray[1] }}')" @endif>
                        <input type='file' name="images[1]" id="image1" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    @for($i = 2; $i < 10; $i++)
                        <label class="photo-item @if(array_key_exists($i, $pet->imagesArray)) filled @endif " for="image{{ $i }}"
                               @if(array_key_exists($i, $pet->imagesArray)) style="background-image: url('/{{ $pet->imagesArray[$i] }}')" @endif >
                            <input type='file' name="images[{{ $i }}]" id="image{{ $i }}" class="imageInput" />
                            <span class="add-btn"></span>
                        </label>
                    @endfor
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ОСНОВНІ ВІДОМОСТІ</div>
                <div class="block-sub-title">Додайте усі данні про вашу тварину.</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="nickname">Кличка <span class="required-field">*</span></label>
                    <input type="text" class="form-control" id="nickname" name="nickname" required
                        value="{{ $pet->nickname }}">
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label>Вид</label>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn radio-item big-radio @if($pet->species_id === 1) active @endif">
                            <span class="label label-dog"></span>
                            <input type="radio" name="species" value="1" autocomplete="off"
                                   @if($pet->species_id === 1) checked @endif >Собака
                        </label>
                        <label class="btn radio-item big-radio @if($pet->species_id === 2) active @endif">
                            <span class="label label-cat"></span>
                            <input type="radio" name="species" value="2" autocomplete="off"
                                   @if($pet->species_id === 2) checked @endif >Кіт
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label>Стать <span class="required-field">*</span></label>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn radio-item big-radio @if($pet->gender === \App\Models\Animal::GENDER_MALE) active @endif">
                            <span class="label"><i class="fa fa-mars" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="{{ \App\Models\Animal::GENDER_MALE }}" autocomplete="off"
                                   @if($pet->gender === \App\Models\Animal::GENDER_MALE) checked @endif >Самець
                        </label>
                        <label class="btn radio-item big-radio @if($pet->gender === \App\Models\Animal::GENDER_FEMALE) active @endif">
                            <span class="label"><i class="fa fa-venus" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="{{ \App\Models\Animal::GENDER_FEMALE }}" autocomplete="off"
                                   @if($pet->gender === \App\Models\Animal::GENDER_FEMALE) checked @endif >Самка
                        </label>
                    </div>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="breed">Порода <span class="required-field">*</span></label>
                    <select name="breed" id="breed" required data-value="{{ $pet->breed_id }}"></select>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="color">Масть <span class="required-field">*</span></label>
                    <select name="color" id="color" required data-value="{{ $pet->color_id }}"></select>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="fur">Тип шерсті <span class="required-field">*</span></label>
                    <select name="fur" id="fur" required data-value="{{ $pet->fur_id }}"></select>
                </div>
                <div class="form-group datepicker">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="birthday">Дата народження <span class="required-field">*</span></label>
                    <input type="text" class="form-control" id="birthday" name="birthday" required
                           value="{{ $pet->birthday->format('d/m/Y') }}"/>
                </div>
                <div class="form-group btn-group-toggle checkbox-group" data-toggle="buttons">
                    <label class="btn checkbox-item @if($pet->sterilized) active @endif">
                        <input type="checkbox" name="sterilized" autocomplete="off" value="1"
                               @if($pet->sterilized) checked @endif> Стерилізовано
                    </label>
                    @if(false)
                        <label class="btn checkbox-item">
                            <input type="checkbox" name="homeless" autocomplete="off"> Безпритульна
                        </label>
                    @endif
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="comment">Коментарі (Особливі прикмети)</label>
                    <textarea name="comment" id="comment" rows="5">{{ $pet->comment }}</textarea>
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ДОКУМЕНТИ</div>
                <div class="block-sub-title">Додайте документи тварини. Наприклад паспорт.</div>
            </div>
            <div class="cols-block-content form">
                <div class="file-uploader">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label class="file-dropzone" for="manual-upload">
                        <span class="desktop">Виберіть файл або просто перетягніть</span>
                        <span class="mobile">Виберіть файл</span>
                    </label>
                    <input type='file' id="manual-upload" multiple />
                    <div class="files-list">
                        @foreach($pet->documents as $doc)
                            <div class="file-item exists">
                                <span class="file-name">{{ $doc->filename }}</span>
                                <span class="file-ext">.{{ $doc->fileextension }}</span>
                                <span class="file-delete exists" data-id="{{ $doc->id }}"
                                      data-rem="{{ route('animals.remove-file', $doc->id) }}"></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="cols-block footer">
            <div class="cols-block-header">
                <div class="block-title"></div>
                <div class="block-sub-title"></div>
            </div>
            <div class="cols-block-content form">
                <div class="form-buttons">
                    <input class="btn btn-primary" type="submit" value="Зберегти">
                    <a class="btn btn-cancel" href="{{ route('animals.index') }}">Скасувати</a>
                </div>
            </div>
        </div>
    </form>
    <div class="uploader-overlay" style="display: none">
        <div class="uploader-progress">
            <span class="value"></span>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                     aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
@endsection
