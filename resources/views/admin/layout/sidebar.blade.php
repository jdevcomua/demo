@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp


<aside id="sidebar_left" class="nano nano-primary affix">
    <div class="sidebar-left-content nano-content">
        <ul class="nav sidebar-menu">
            <li class="sidebar-label pt20">Реєстри</li>
            <li{!! (strpos($curRoute, '.db.animals') !== false) ? ' class="active" ' : '' !!}>
                <a href="{{ route('admin.db.animals.index') }}">
                    <span class="fa fa-paw"></span>
                    <span class="sidebar-title">Тварини</span>
                </a>
            </li>
            <li @if($curRoute == 'admin.db.users.index' || $curRoute == 'admin.db.users.show') class="active" @endif>
                <a href="{{ route('admin.db.users.index') }}">
                    <span class="fa fa-users"></span>
                    <span class="sidebar-title">Користувачі</span>
                </a>
            </li>
            @if(false)
            <li>
                <a href="#">
                    <span class="fa fa-archive"></span>
                    <span class="sidebar-title">Архів тварин</span>
                </a>
            </li>
            @endif

            @permission('edit-content')
                <li class="sidebar-label pt20">Інформація</li>
                <li @if($curRoute == 'admin.info.directories.index') class="active" @endif>
                    <a href="{{ route('admin.info.directories.index') }}">
                        <span class="fa fa-book"></span>
                        <span class="sidebar-title">Довідники</span>
                    </a>
                </li>
                <li {!! (strpos($curRoute, '.info.emails') !== false) ? ' class="active" ' : '' !!}>
                    <a href="{{route('admin.info.emails.index')}}#">
                        <span class="fa fa-envelope"></span>
                        <span class="sidebar-title">Повідомлення</span>
                    </a>
                </li>
                <li {!! (strpos($curRoute, '.info.notifications') !== false) ? ' class="active" ' : '' !!}>
                    <a href="{{route('admin.info.notifications.index')}}">
                        <span class="fa fa-bell"></span>
                        <span class="sidebar-title">Нотифікації</span>
                    </a>
                </li>
                <li{!! (strpos($curRoute, '.content.') !== false) ? ' class="active" ' : '' !!}>
                <a class="accordion-toggle {!! (strpos($curRoute, '.content.') !== false)
                            ? 'menu-open' : '' !!}" href="#">
                        <span class="fa fa-file-text"></span>
                        <span class="sidebar-title">Контент</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="nav sub-nav">
                        <li{!! (strpos($curRoute, '.content.faq') !== false) ? ' class="active" ' : '' !!}>
                            <a href="{{route('admin.info.content.faq.index')}}">
                                <span class="fa fa-question-circle"></span>Часті питання</a>
                        </li>
                        <li{!! (strpos($curRoute, '.content.block') !== false) ? ' class="active" ' : '' !!}>
                            <a href="{{route('admin.info.content.block.index')}}">
                                <span class="fa fa-info-circle"></span>Блоки
                            </a>
                        </li>
                    </ul>
                </li>
            @endpermission

            @permission(['change-roles', 'block-user', 'delete-user', 'edit-roles', 'view-syslog'])
                <li class="sidebar-label pt20">Адміністрування</li>
                <li  @if($curRoute == 'admin.administrating.users.index') class="active" @endif>
                    <a href="{{route('admin.administrating.users.index')}}">
                        <span class="fa fa-users"></span>
                        <span class="sidebar-title">Користувачі</span>
                    </a>
                </li>
                @permission('block-user')
                    <li @if($curRoute == 'admin.administrating.banned') class="active" @endif>
                        <a href="{{route('admin.administrating.banned')}}">
                            <span class="fa fa-ban"></span>
                            <span class="sidebar-title">Блокування</span>
                        </a>
                    </li>
                @endpermission
                @permission('edit-roles')
                    <li{!! (strpos($curRoute, 'admin.roles') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{ route('admin.roles.index') }}">
                            <span class="fa fa-lock"></span>
                            <span class="sidebar-title">Ролі</span>
                        </a>
                    </li>
                @endpermission
                @permission('view-syslog')
                    <li{!! (strpos($curRoute, 'admin.logs') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{ route('admin.logs.index') }}">
                            <span class="fa fa-history"></span>
                            <span class="sidebar-title">Журнал дій</span>
                        </a>
                    </li>
                @endpermission
            @endpermission
        </ul>
    </div>
</aside>