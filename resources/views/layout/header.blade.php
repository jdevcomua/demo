@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp

<header>
    <div class="menu-btn">
        <div class="hamburger hamburger--slider" id="menu-toggle">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div>
    </div>
    <div class="name">
        <a href="{{route('index')}}"><img src="{{ asset('img/icon/gerb.png') }}" alt="Герб" class="logo"></a>
        <div class="title">
            <span><a class="text-white" href="{{route('index')}}">Реєстр домашніх тварин</a></span>
            <span class="sub-title">Київська міська державна адміністрація</span>
        </div>
    </div>
    <div class="auth">
        @auth
            <span class="auth-item profile">{{ $auth->name }}</span>
            <a href="#" class="auth-item logout" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">ВИЙТИ</a>
            <form id="logout-form" action="{{ route('logout') }}"
                  method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="auth-item profile">УВІЙТИ</a>
        @endauth
    </div>
    <div class="nav">
        <nav>
            @auth
                <a href="{{ route('animals.index') }}" class="nav-item @if($curRoute == 'animals.index') active @endif">Мої тварини</a>
                <a href="{{ route('profile') }}" class="nav-item @if($curRoute == 'profile') active @endif">Мій профіль</a>
                <div class="nav-divider"></div>
            @endauth
            <a href="{{ route('about') }}" class="nav-item @if($curRoute == 'about') active @endif">Про проект</a>
            <a href="{{ route('faq') }}" class="nav-item @if($curRoute == 'faq') active @endif">Часті запитання</a>
            <a href="{{ route('lost-animals.index') }}" class="nav-item @if(strpos($curRoute, 'lost-animals.') !== false) active @endif">Загублені/знайдені</a>
            <a href="#" class="nav-item btn btn-found btn-found-white" id="i-found-animal">Знайшов тварину</a>

        </nav>
        <div class="socials">
            <a href="https://www.facebook.com/ReestrKyiv/" rel="noopener noreferrer" target="_blank"
               class="socials-item socials-item-facebook"></a>
            <a href="https://www.youtube.com/channel/UCOZgvQf3ZTn3sKos84geBzA" rel="noopener noreferrer" target="_blank"
               class="socials-item socials-item-youtube"></a>
            {{--<a href="#" class="socials-item socials-item-twitter"></a>--}}
        </div>
    </div>
    <div class="modal fade" id="foundAnimalModal" tabindex="-2" role="dialog" aria-labelledby="foundAnimalModalLabel" aria-hidden="true">
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
                            <h3>Знайшов тварину</h3>
                            <p>Будь ласка, заповніть інформацію про тварину щоб ми швидше могли знайти власника.<br><br> Поля з зірочкою * обов'язкові для заповення.<br>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <form class="search-request" id="form-modal-found-animal" enctype="multipart/form-data" action="{{route('lost-animals.i-found-animal')}}" method="POST">
                                @csrf
                                <input type="hidden" name="only_badge" id="only_badge_hidden" value="0">
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="badge">Номер жетону</label>
                                    <p>Додайте номер жетону, якщо він є у тварини</p>
                                    <input type="text" class="form-control" id="found-badge" name="badge">
                                </div>
                                <p><a href="#" style="text-decoration: underline; color: inherit" id="found-badge-btn">Знайдено тільки жетон тварини</a></p>

                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label>Вид тварини *</label>
                                    <p>Оберіть вид тварини</p>
                                    <div class="btn-group-toggle" data-toggle="buttons" style="justify-content: space-evenly;">
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
                                    <label for="breed">Порода тварини</label>
                                    <p>Оберіть породу тварини, якщо ви її визначили</p>
                                    <select name="breed" class="breed"></select>
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="color">Окрас тварини</label>
                                    <p>Оберіть окрас або масть тварини, якщо ви його визначили</p>

                                    <select name="color" class="color"></select>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="found_address">Адреса та місце, де знайдено тварину *</label>
                                    <p>Вкажіть адресу та інші покажчики, де знайдено тварину</p>
                                    <input type="text" class="form-control" id="found_address" name="found_address">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="additional_info">Додаткова інформація</label>
                                    <p>Додайте іншу інформацію: особливі прикмети, стан тварини тощо.</p>
                                    <input type="text" class="form-control" id="additional_info" name="additional_info">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="street">Фото тварини</label>
                                    <p>Додайте фото тварини</p>
                                    <div class="file-uploader">
                                        <div class="validation-error alert alert-danger hidden"></div>
                                        <label class="file-dropzone" for="manual-upload-modal1">
                                            <span class="desktop">Виберіть файл або просто перетягніть</span>
                                            <span class="mobile">Виберіть файл</span>
                                        </label>
                                        <input type='file' id="manual-upload-modal1" name="documents[]" multiple />
                                        <div class="files-list"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_name">Ім’я *</label>
                                    <p>Додайте ваше ім'я або ім'я контактної особи</p>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_phone">Телефон *</label>
                                    <p>Додайте контактний номер телефону</p>
                                    <input type="text" class="form-control" id="contact_phone" name="contact_phone">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_email">E-mail</label>
                                    <p>Додайте контактний e-mail</p>
                                    <input type="text" class="form-control" id="contact_email" name="contact_email" >
                                </div>
                                <a href="" id="i-found-animal-submit" class="ml-auto mt-6 btn confirm btn-primary submit-ajax" style="width: 350px;">Відправити</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="foundAnimalSuccess" tabindex="-2" role="dialog" aria-labelledby="foundAnimalSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" style="display: flex; justify-content: center; margin-bottom: 30px;">
                            <img style="height: 159px;" src="{{asset('img/icon/tick-inside-circle.svg')}}" alt="success">
                        </div>
                        <div class="col-sm-12"><h3 style="text-align: center; font-size: 2rem; font-weight: bold; margin-bottom: 30px;">
                                Дякуємо! <br>Повідомлення про знайдену тварину створено!</h3>
                            <div class="col-sm-12">
                                <p style="text-align: center; font-size: 1.1rem; color: #000; margin-bottom: 30px;">Ваше повідомлення з'явиться в розділі "Знайдені тварини"
                                    у вигляді оголошення про знайдену тварину після затвердження його Модератором.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: center">
                    <a href="{{route('lost-animals.found')}}" class="btn search btn-primary" style="display: inline-block;">Знайдені тварини</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($_GET['badgeNotFound']))
        @include('animals.partials.badgeNotFound')
    @endif
</header>
