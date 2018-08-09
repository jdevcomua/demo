@extends('layout.app')

@section('title', 'Додати домашню тварину')

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">Додати домашню тварину</div>
    </div>
    <form action="{{ route('animals.store') }}" enctype='multipart/form-data' method="POST" id="form">
        @csrf
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ЗОБРАЖЕННЯ</div>
                <div class="block-sub-title">Що більше фото тим простіше знайти тварину у випадку втрати.<br>
                    Фотографуйте тваринку з різних сторін.</div>
                <div class="pet-photo-hint"><div>Це фото буде головним</div></div>
            </div>
            <div class="cols-block-content">
                <div class="validation-error alert alert-danger hidden"></div>
                <div class="new-pet-photos">
                    <label class="photo-item photo-item-main" for="image1">
                        <input type='file' name="images[1]" id="image1" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image2">
                        <input type='file' name="images[2]" id="image2" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image3">
                        <input type='file' name="images[3]" id="image3" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image4">
                        <input type='file' name="images[4]" id="image4" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image5">
                        <input type='file' name="images[5]" id="image5" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image6">
                        <input type='file' name="images[6]" id="image6" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image7">
                        <input type='file' name="images[7]" id="image7" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image8">
                        <input type='file' name="images[8]" id="image8" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
                    <label class="photo-item" for="image9">
                        <input type='file' name="images[9]" id="image9" class="imageInput" />
                        <span class="add-btn"></span>
                    </label>
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
                    <input type="text" class="form-control" id="nickname" name="nickname" required>
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
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label>Стать</label>
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
                    <label for="breed">Порода</label>
                    <select name="breed" id="breed" required></select>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="color">Масть</label>
                    <select name="color" id="color" required></select>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="fur">Тип шерсті</label>
                    <select name="fur" id="fur" required></select>
                </div>
                <div class="form-group datepicker">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="birthday">Дата народження</label>
                    <input type="text" class="form-control" id="birthday" name="birthday"
                           required autocomplete="off" />
                </div>
                <div class="form-group btn-group-toggle checkbox-group" data-toggle="buttons">
                    <label class="btn checkbox-item">
                        <input type="checkbox" name="sterilized" autocomplete="off" value="1"> Стерилізовано
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
                    <textarea name="comment" id="comment" rows="5"></textarea>
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
