@php
    $curRoute = \Route::current()->getName();
@endphp


<aside id="sidebar_left" class="nano nano-primary affix">
    <div class="sidebar-left-content nano-content">
        <ul class="nav sidebar-menu">
            <li class="sidebar-label pt20">Бази</li>
            <li>
                <a href="#">
                    <span class="fa fa-paw"></span>
                    <span class="sidebar-title">Тварини</span>
                </a>
            </li>
            <li @if($curRoute == 'admin.db.users') class="active" @endif>
                <a href="#">
                    <span class="fa fa-users"></span>
                    <span class="sidebar-title">Користувачі</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="fa fa-archive"></span>
                    <span class="sidebar-title">Архів тварин</span>
                </a>
            </li>


            <li class="sidebar-label pt20">Інформація</li>
            <li>
                <a href="#">
                    <span class="fa fa-book"></span>
                    <span class="sidebar-title">Довідники</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="fa fa-envelope"></span>
                    <span class="sidebar-title">Повідомлення</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="fa fa-bell"></span>
                    <span class="sidebar-title">Нотифікації</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="fa fa-file-text"></span>
                    <span class="sidebar-title">Контент</span>
                </a>
            </li>


            <li class="sidebar-label pt20">Адміністрування</li>
            <li>
                <a href="#">
                    <span class="fa fa-users"></span>
                    <span class="sidebar-title">Користувачі</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="fa fa-history"></span>
                    <span class="sidebar-title">Журнал дій</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="fa fa-ban"></span>
                    <span class="sidebar-title">Блокування</span>
                </a>
            </li>
        </ul>
    </div>
</aside>