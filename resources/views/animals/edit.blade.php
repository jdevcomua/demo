@extends('layout.app')

@section('title', 'Редагування інформації тварини')

@section('content')
    {{--TODO styles--}}
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <span>Редагування інформації тварини</span>
    </div>
    <form action="{{ route('animals.update', $pet->id) }}" enctype='multipart/form-data' method="POST" id="form">
        @method('PUT')
        @csrf

        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ЗОБРАЖЕННЯ</div>
                <div class="block-sub-title">Що більше фото тим простіше знайти тварину у випадку втрати.<br>
                    Фотографуйте тваринку з різних сторін.</div>
                <div class="pet-photo-hint"></div>
            </div>
            <div class="cols-block-content">
                <div class="validation-error alert alert-danger hidden"></div>
                <div class="new-pet-photos">
                    <label class="photo-item photo-item-main @if(array_key_exists(1, $pet->images)) filled @endif " for="image1"
                           @if(array_key_exists(1, $pet->images)) style="background-image: url('/{{ $pet->images[1] }}')" @endif>
                        <input type='file' name="images[1]" id="image1" class="imageInput" />
                        <span class="add-btn">+</span>
                    </label>
                    <div class="small-photos">
                        @for($i = 2; $i < 10; $i++)
                            <label class="photo-item @if(array_key_exists($i, $pet->images)) filled @endif " for="image{{ $i }}"
                                   @if(array_key_exists($i, $pet->images)) style="background-image: url('/{{ $pet->images[$i] }}')" @endif >
                                <input type='file' name="images[{{ $i }}]" id="image{{ $i }}" class="imageInput" />
                                <span class="add-btn">+</span>
                            </label>
                        @endfor
                    </div>
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
                    <label for="nickname">Кличка</label>
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
                    <label>Стать</label>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn radio-item big-radio @if($pet->gender === \App\Models\Animal::GENDER_MALE) active @endif">
                            <span class="label"><i class="fa fa-mars" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="0" autocomplete="off"
                                   @if($pet->gender === \App\Models\Animal::GENDER_MALE) checked @endif >Самець
                        </label>
                        <label class="btn radio-item big-radio @if($pet->gender === \App\Models\Animal::GENDER_FEMALE) active @endif">
                            <span class="label"><i class="fa fa-venus" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="1" autocomplete="off"
                                   @if($pet->gender === \App\Models\Animal::GENDER_FEMALE) checked @endif >Самка
                        </label>
                    </div>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="breed">Порода</label>
                    <select name="breed" id="breed" required data-value="{{ $pet->breed_id }}"></select>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="color">Масть</label>
                    <select name="color" id="color" required data-value="{{ $pet->color_id }}"></select>
                </div>
                <div class="form-group datepicker">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="birthday">Дата народження</label>
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
                    <label class="file-dropzone" for="manual-upload">Виберіть файл або просто перетягніть</label>
                    <input type='file' id="manual-upload" name="manual-upload" multiple />
                    <div class="files-list"></div>
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
@endsection
