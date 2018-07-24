@extends('layout.app')

@section('title', 'Додати нову тварину')

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <span>Додати домашню тварину</span>
    </div>
    <form action="{{ route('pets.store') }}" enctype='multipart/form-data' method="POST">
        @csrf
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ЗОБРАЖЕННЯ</div>
                <div class="block-sub-title">Що більше фото тим простіше знайти тварину у випадку втрати.<br>
                    Фотографуйте тваринку з різних сторін.</div>
                <div class="pet-photo-hint"></div>
            </div>
            <div class="cols-block-content">
                <div class="new-pet-photos">
                    <label class="photo-item photo-item-main" for="image1">
                        <input type='file' name="image1" id="image1" class="imageInput" />
                        <span class="add-btn">+</span>
                    </label>
                    <div class="small-photos">
                        <label class="photo-item" for="image2">
                            <input type='file' name="image2" id="image2" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image3">
                            <input type='file' name="image3" id="image3" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image4">
                            <input type='file' name="image4" id="image4" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image5">
                            <input type='file' name="image5" id="image5" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image6">
                            <input type='file' name="image6" id="image6" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image7">
                            <input type='file' name="image7" id="image7" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image8">
                            <input type='file' name="image8" id="image8" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
                        <label class="photo-item" for="image9">
                            <input type='file' name="image9" id="image9" class="imageInput" />
                            <span class="add-btn">+</span>
                        </label>
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
                    <label for="middle_name">Кличка</label>
                    <input type="text" class="form-control" id="nickname" placeholder="Кличка">
                </div>
                <div class="form-group">
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
                    <label>Стать</label>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn radio-item big-radio active">
                            <span class="label"><i class="fa fa-mars" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="male" autocomplete="off" checked>Самець
                        </label>
                        <label class="btn radio-item big-radio">
                            <span class="label"><i class="fa fa-venus" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" value="female" autocomplete="off">Самка
                        </label>
                    </div>
                </div>
                <div class="form-group select">
                    <label for="breed">Порода</label>
                    <select name="breed" id="breed"></select>
                </div>
                <div class="form-group select">
                    <label for="color">Масть</label>
                    <select name="color" id="color"></select>
                </div>
                <div class="form-group datepicker">
                    <label for="birthday">Дата народження</label>
                    <input type="text" class="form-control" id="birthday"/>
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ДОКУМЕНТИ</div>
                <div class="block-sub-title">Додайте документи тварини. Наприклад паспорт.</div>
            </div>
            <div class="cols-block-content form">

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
                    <a class="btn btn-cancel" href="#">Скасувати</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts-end')
    <script>
        $(function () {
            $('.datepicker input').datepicker({
                format: "dd MM yyyy",
                language: "uk",
                locale: "uk",
                autoclose: true
            });
        });

        /////////////////////////////////////////
        // Image preview

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(input).parent().css('background-image', 'url(' + e.target.result + ')');
                    $(input).parent().addClass('filled');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".imageInput").change(function() {
            readURL(this);
        });
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Selectize

        var options = {
            valueField: 'value',
            labelField: 'name',
        };
        var breeds = $('.form-group.select select#breed').selectize(options);
        var colors = $('.form-group.select select#color').selectize(options);
        console.log(breeds, colors);
        $('input[name="species"]').change(function(event) {
            breeds[0].selectize.clear();
            breeds[0].selectize.clearOptions();
            colors[0].selectize.clear();
            colors[0].selectize.clearOptions();
            updateSelects(event.target.value);
        });

        var xhrBreeds;
        var xhrColors;
        function updateSelects(species) {
            breeds[0].selectize.load(function (callback) {
                xhrBreeds && xhrBreeds.abort();
                xhrBreeds = $.ajax({
                    url: '/ajax/species/'+species+'/breeds',
                    success: function (results) {
                        callback(JSON.parse(results));
                    },
                    error: function () {
                        callback();
                    }
                })
            });
            colors[0].selectize.load(function (callback) {
                xhrColors && xhrColors.abort();
                xhrColors = $.ajax({
                    url: '/ajax/species/'+species+'/colors',
                    success: function (results) {
                        callback(JSON.parse(results));
                    },
                    error: function () {
                        callback();
                    }
                })
            });
        }

        updateSelects($('input[name="species"]')[0].value);
        /////////////////////////////////////////
    </script>
@endsection