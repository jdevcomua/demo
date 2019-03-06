@extends('layout.app')

@section('title', 'Реєстр домашніх тварин')

@section('content')

    <div class="main-page-wrapper" style="padding-left: 15px;">
        <div class="three-cols-block">
            <div class="column">
                <div class="first-block">
                    <h2 class="header uppercase">Що таке Реєстр домашніх тварин?</h2>
                    <div class="subtext" style="">
                        <p>Реєстр домашніх тварин – це єдина міська мережа для
                            реєстрації вашого улюбленця та зберігання всієї важливої інформації щодо нього,
                            а також зручний інструмент, щоб повернути загублену тварину власнику та знайти свою.
                        </p>
                    </div>
                </div>
            </div>

                <div class="column">
                    <div class="first-block">
                        <img src="{{asset('img/guy_with_animals.svg')}}" style="width: 36rem;"
                             alt="guy with animals and the laptop">
                    </div>
                </div>
            </div>

        <h2 class="block-title uppercase">
            Для чого потрібна система?
        </h2>

        <div class="three-cols-block" style="margin-top: 40px; margin-bottom: 70px;">
            <div class="column">
                <div class="image-with-caption">
                    <img src="{{asset('img/reg.svg')}}" alt="Registration">
                    <p>Реєстрації вашого улюбленця</p>
                </div>
            </div>
            <div class="column">
                <div class="image-with-caption">
                    <img src="{{asset('img/sea.svg')}}" alt="Search tools">
                    <p>Швидкого пошуку загубленої тварини</p>
                </div>
            </div>

            <div class="column">
                <div class="image-with-caption">
                    <img src="{{asset('img/vac.svg')}}" alt="Veterinary control">
                    <p>Контролю вакцінації ваших тварин та інших ветеринарних заходів</p>
                </div>
            </div>
        </div>

        <h2 class="block-title uppercase">
            як зареєструвати тварину?
        </h2>

        <div class="three-cols-block steps" style="margin-top: 40px;">
            <div class="column">
                <div class="step-container">
                    <span class="step-number">1</span>
                    <div class="step-main-block">
                        <h3 class="step-main-block-header">Увійдіть в систему</h3>

                        <p class="step-main-block-text">
                            Натисніть кнопку "Увійти" у шапці сайту
                        </p>
                        <p class="step-main-block-text">
                            Авторизуйтесь через КиївID зручним для вас способом
                        </p>
                    </div>
                </div>
            </div>
            <div class="column ">
                <div class="step-container">
                    <span class="step-number">2</span>
                    <div class="step-main-block">
                        <h3 class="step-main-block-header">Створіть картку тварини</h3>

                        <p class="step-main-block-text">
                            Додайте тварину у розділі "Мої тварини"
                        </p>
                        <p class="step-main-block-text">
                            Отримайте на пошту лист-запрошення на верифікацію
                        </p>
                    </div>
                </div>
            </div>
            <div class="column ">
                <div class="step-container">
                    <span class="step-number">3</span>
                    <div class="step-main-block">
                        <h3 class="step-main-block-header">Верифікуйте тварину у реєстратора</h3>

                        <p class="step-main-block-text">
                            Разом з твариною вирушайте на верифікацію за адресою
                            <span style="color: #2504c4; text-decoration: underline">Електротехнічна 5А</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="how-to-identify-block-wrapper">

            <h2 class="block-title uppercase" style="margin-bottom:50px;">
                Як ідентифікувати тварину за QR-кодом?
            </h2>

            <div class="how-to-identify-block">
                <div class="vertical-steps-block">
                    <div class="step-block">
                        <div class="number-with-bottom-line-wrapper">
                            <div class="number-with-bottom-line">
                                <div class="number-container">
                                    <span class="number">1</span>
                                </div>
                            </div>
                            <span class="bottom-line"></span>
                        </div>
                        <div class="text-container">
                            <span class="main-step-text">Зчитайте QR-код мобільним телефоном</span>
                        </div>
                    </div>
                    <div class="step-block">
                        <div class="number-with-bottom-line-wrapper">
                            <div class="number-with-bottom-line">
                                <div class="number-container">
                                    <span class="number">2</span>
                                </div>
                            </div>
                            <span class="bottom-line"></span>
                        </div>
                        <div class="text-container">
                            <span class="main-step-text">Відбувається перехід на сайт</span>
                        </div>
                    </div>

                    <div class="step-block">
                        <div class="number-with-bottom-line-wrapper">
                            <div class="number-with-bottom-line">
                                <div class="number-container">
                                    <span class="number">3</span>
                                </div>
                            </div>
                            <span class="bottom-line"></span>
                        </div>
                        <div class="text-container">
                            <span class="main-step-text">Відбувається пошук тварини в системі</span>
                        </div>
                    </div>
                    <div class="step-block">
                        <div class="number-with-bottom-line-wrapper">
                            <div class="number-with-bottom-line">
                                <div class="number-container">
                                    <span class="number">4</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-container">
                            <span class="main-step-text">Тварину знайдено?</span>
                        </div>
                    </div>

                </div>
                <div class="sub-steps-block">
                    <div class="vertical-steps-block">
                        <div class="step-block">
                            <div class="number-with-bottom-line-wrapper">
                                <div class="number-with-bottom-line">
                                    <div class="number-container" style="padding: 12px">
                                        <span class="number" style="padding: 5px;font-size: 1.2rem;">НІ</span>
                                    </div>
                                </div>
                                <span class="bottom-line"></span>
                            </div>
                            <div class="text-container">
                                <div class="text-with-square-block">
                                    <div class="square"></div>
                                    <div class="text-with-square-wrapper">
                                        <span class="text-with-square">Заповніть форму "Занйдено тварину" та відправте інформацію</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="step-block">
                            <div class="number-with-bottom-line-wrapper">
                                <div class="number-with-bottom-line">
                                    <div class="number-container" style="padding: 10px">
                                        <span class="number" style="padding: 0;font-size: 1.2rem;">ТАК</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-container">
                                <div class="text-with-square-block">
                                    <div class="square"></div>
                                    <div class="text-with-square-wrapper">
                                        <span class="text-with-square">Відображаються дані власника(ім'я, телефон та e-mail)</span>
                                    </div>
                                </div>
                                <br>
                                <div class="text-with-square-block">
                                    <div class="square"></div>
                                    <div class="text-with-square-wrapper">
                                        <span class="text-with-square">Формується повідомлення власнику про зчитування QR-коду</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection
