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
                            <p>Будь ласка, заповніть інформацію про тварину щоб ми швидше могли знайти власника<br>
                            <a href="#" style="text-decoration: underline; color: inherit" id="found-badge-btn">Знайшов жетон</a>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <form class="search-request" id="form-modal" enctype="multipart/form-data" action="{{route('lost-animals.i-found-animal')}}" method="POST">
                                @csrf
                                <div class="form-group" style="display: none">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="badge">Номер жетону</label>
                                    <input type="text" class="form-control" id="badge" name="badge">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label>Вид </label>
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
                                    <label for="breed">Порода </label>
                                    <select name="breed" class="breed"></select>
                                </div>
                                <div class="form-group select">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="color">Масть </label>
                                    <select name="color" class="color"></select>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="found_address">Адреса де знайшли тварину</label>
                                    <input type="text" class="form-control" id="found_address" name="found_address">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="street">Фото тварини</label>
                                    <div class="file-uploader">
                                        <div class="validation-error alert alert-danger hidden"></div>
                                        <label class="file-dropzone" for="manual-upload-modal1">
                                            <span class="desktop">Виберіть файл або просто перетягніть</span>
                                            <span class="mobile">Виберіть файл</span>
                                        </label>
                                        <input type='file' id="manual-upload-modal1" multiple />
                                        <div class="files-list"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_name">Ваше ім’я</label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_phone">Ваш телефон</label>
                                    <input type="text" class="form-control" id="contact_phone" name="contact_phone">
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_email">Ваш email</label>
                                    <input type="text" class="form-control" id="contact_email" name="contact_email" >
                                </div>
                                <button type="submit" class="ml-auto mt-6 btn confirm btn-primary" style="width: 350px;">Відправити</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
