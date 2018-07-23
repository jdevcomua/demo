@extends('layout.app')

@section('title', 'Профіль')

@section('content')
    <form action="#">
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">ОСНОВНІ ВІДОМОСТІ</div>
                <div class="block-sub-title">Додайте усі данні про вас</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="last_name">Прізвище</label>
                    <input type="text" class="form-control" id="last_name" placeholder="Прізвище">
                </div>
                <div class="form-group">
                    <label for="first_name">Ім'я</label>
                    <input type="text" class="form-control" id="first_name" placeholder="Ім'я">
                </div>
                <div class="form-group">
                    <label for="middle_name">По батькові</label>
                    <input type="text" class="form-control" id="middle_name" placeholder="По батькові">
                </div>
                <div class="form-group datepicker">
                    <label for="birthday">Дата народження</label>
                    <input type="text" class="form-control" id="birthday"/>
                </div>
                <div class="form-group">
                    <label>Стать</label>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn radio-item gender-radio active">
                            <span class="gender-label"><i class="fa fa-mars" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" id="option1" value="male" autocomplete="off" checked>Чоловік
                        </label>
                        <label class="btn radio-item gender-radio">
                            <span class="gender-label"><i class="fa fa-venus" aria-hidden="true"></i></span>
                            <input type="radio" name="gender" id="option2" value="female" autocomplete="off">Жінка
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" class="form-control" id="phone" placeholder="Телефон">
                </div>
                <div class="form-group">
                    <label for="email">Пошта</label>
                    <input type="text" class="form-control" id="email" placeholder="e-mail">
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">РЕЄСТРАЦІЯ</div>
                <div class="block-sub-title">Вкажіть адресу вашої реєстрації</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="qqqqqq">Область</label>
                    <input type="text" class="form-control" id="qqqqqq" placeholder="Область">
                </div>
                <div class="form-group">
                    <label for="qqqqqq">Населений пункт</label>
                    <input type="text" class="form-control" id="qqqqqq" placeholder="Населений пункт">
                </div>
                <div class="form-group">
                    <label for="qqqqqq">Вулиця</label>
                    <input type="text" class="form-control" id="qqqqqq" placeholder="Вулиця">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="qqqqqq">Будинок</label>
                            <input type="text" class="form-control" id="qqqqqq" placeholder="Будинок">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="qqqqqq">Помешкання</label>
                            <input type="text" class="form-control" id="qqqqqq" placeholder="Помешкання">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cols-block">
            <div class="cols-block-header">
                <div class="block-title">КОНТАКТИ</div>
                <div class="block-sub-title">Вкажіть адресу вашого проживання</div>
            </div>
            <div class="cols-block-content form">
                <div class="form-group">
                    <label for="qqqqqq">Область</label>
                    <input type="text" class="form-control" id="qqqqqq" placeholder="Область">
                </div>
                <div class="form-group">
                    <label for="qqqqqq">Населений пункт</label>
                    <input type="text" class="form-control" id="qqqqqq" placeholder="Населений пункт">
                </div>
                <div class="form-group">
                    <label for="qqqqqq">Вулиця</label>
                    <input type="text" class="form-control" id="qqqqqq" placeholder="Вулиця">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="qqqqqq">Будинок</label>
                            <input type="text" class="form-control" id="qqqqqq" placeholder="Будинок">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="qqqqqq">Помешкання</label>
                            <input type="text" class="form-control" id="qqqqqq" placeholder="Помешкання">
                        </div>
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
                    <input class="btn btn-submit" type="submit" value="Зберегти">
                    <a class="btn btn-cancel" href="#">Скасувати</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        $(function () {
            $('.datepicker input').datepicker({
                format: "dd MM yyyy",
                language: "uk",
                locale: "uk",
                autoclose: true
            });
        });
    </script>
@endsection