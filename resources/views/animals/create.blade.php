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
                <div class="block-title">ЗОБРАЖЕННЯ <span class="required-field">*</span></div>
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
                <small class="form-text text-muted">
                    Фото повинно бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .svg
                    <br>
                    та не більше ніж 2Mb
                </small>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ОСНОВНІ ВІДОМОСТІ</div>
                <div class="block-sub-title">Додайте усі дані про вашу тварину.</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="nickname">Кличка <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Вкажіть, як звати вашу тварину українською мовою
                    </small>
                    <input type="text" class="form-control" id="nickname" name="nickname" required>
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="nickname_lat">Кличка на латині</label>
                    <small class="form-text text-muted">
                        Заповніть це поле, якщо ваша тварина має кличку іншою мовою
                    </small>
                    <input type="text" class="form-control" id="nickname_lat" name="nickname_lat" >
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label>Вид  <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Оберіть вид тварини
                    </small>
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
                    <label>Стать  <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Оберіть стать тварини
                    </small>
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
                    <label for="breed">Порода  <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Оберіть породу тварини
                    </small>
                    <select name="breed" class="breed" required></select>
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="half_breed">Метис породи</label>
                    <div style="width: 100%;">
                        <input type="checkbox" class="form-control" id="half_breed" value="1" style="width:4%;
                        display: inline; float: left; margin-right: 10px;" name="half_breed">
                        <label for="half_breed" style="display: inline; font-weight: 100; font-size: 1.1rem;">Відзначте це поле, якщо ваша тварина не є чистокровною</label>
                    </div>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="color">Окрас  <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Оберіть окрас (масть) тварини
                    </small>
                    <select name="color" class="color" required></select>
                </div>
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="fur">Тип шерсті <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Оберіть тип шерсті тварини
                    </small>
                    <select name="fur" class="fur" required></select>
                </div>
                <div class="form-group datepicker">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="birthday">Дата народження  <span class="required-field">*</span></label>
                    <small class="form-text text-muted">
                        Оберіть дату народження тварини
                    </small>
                    <input type="text" class="form-control" id="birthday" name="birthday"
                           required autocomplete="off" readonly="true"
                    />
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="tallness">Зріст</label>
                    <small class="form-text text-muted">
                        Вкажіть зріст тварини в сантиметрах
                    </small>
                    <input type="text" class="form-control" id="tallness" name="tallness">
                </div>
                <div class="form-group btn-group-toggle checkbox-group" data-toggle="buttons">
                    @if(false)
                        <label class="btn checkbox-item">
                            <input type="checkbox" name="homeless" autocomplete="off"> Безпритульна
                        </label>
                    @endif
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ЗАСІБ ІДЕНТИФІКАЦІЇ ТВАРИНИ</div>
                <div class="block-sub-title">Додайте відомості про засіб ідентифікації тварини: жетон, чип або тавро, ящо такі є у тварини.</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group select">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="device_type">Тип засобу</label>
                    <small class="form-text text-muted">
                        Оберіть тип засобу ідентифікації тварини
                    </small>
                    <select name="device_type" id="device_type" class="device_type" ></select>
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="device_number">Номер засобу</label>
                    <small class="form-text text-muted">
                        Вкажіть номер засобу ідентифікації тварини
                    </small>
                    <input type="text" class="form-control" id="device_number" name="device_number">
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="device_issued_by">Ким видано</label>
                    <small class="form-text text-muted">
                        Вкажіть, ким виданий засіб ідентифікації, якщо такий є у тварини.
                    </small>
                    <input type="text" class="form-control" id="device_issued_by" name="device_issued_by">
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ІНШІ ВІДОМОСТІ</div>
                <div class="block-sub-title">Додайте інші дані про вашу тварину.</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group btn-group-toggle checkbox-group" data-toggle="buttons">
                    <label for="sterilized" style="width: 100%">Стерилізація</label>
                    <small class="form-text text-muted" style="margin-bottom: 1rem;">
                        Відзначте, якщо вашу тварину стерилізовано
                    </small>
                    <label class="btn checkbox-item">
                        <input type="checkbox" name="sterilized" id="sterilized" autocomplete="off" value="1"> Стерилізовано
                    </label>
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="veterinary_number">Номер ветеринарного паспорту</label>
                    <small class="form-text text-muted">
                        Вкажіть номер ветеринарного паспорту тварини, якщо такий є у тварини.
                    </small>
                    <input type="text" class="form-control" id="veterinary_number" name="veterinary_number">
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="veterinary_issued_by">Ким видано</label>
                    <small class="form-text text-muted">
                        Вкажіть, ким виданий ветеринарний паспорт, якщо такий є у тварини.
                    </small>
                    <input type="text" class="form-control" id="veterinary_issued_by" name="veterinary_issued_by">
                </div>
                <div class="form-group">
                    <div class="validation-error alert alert-danger hidden"></div>
                    <label for="comment">Додаткові відомості</label>
                    <small class="form-text text-muted">
                        Вкажіть особливі прикмети тварини та іншу інформацію щодо тварини, яку вважаєте за потрібну
                    </small>
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
                    <input type='file' id="manual-upload" multiple />
                    <div class="files-list"></div>
                </div>
                <small class="form-text text-muted">
                    Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf
                    <br>
                    та не більше ніж 10Mb
                </small>
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
                <div class="validation-error alert alert-danger hidden" style="margin-top: 2rem;"></div>
                <div class="agreement-checkbox-block">
                    <div class="checkbox">
                        <input class="check" type="checkbox" name="agree" value="1" />
                    </div>
                    <div class="text">
                        З <a href="{{route('rules')}}">правилами утримання і догляду за домашніми тваринами</a> ознайомлений
                    </div>
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
