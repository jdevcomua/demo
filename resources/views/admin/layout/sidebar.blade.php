@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp


<aside id="sidebar_left" class="nano nano-primary affix">
    <div class="sidebar-left-content nano-content">
        <ul class="nav sidebar-menu">
            @permission('view-animals')
            <li class="sidebar-label pt20">Реєстри</li>
            <li{!! (strpos($curRoute, '.db.animals') !== false) ? ' class="active" ' : '' !!}>
                <a href="{{ route('admin.db.animals.index') }}">
                    <span class="fa fa-paw"></span>
                    <span class="sidebar-title">Тварини</span>
                </a>
            </li>
            @endpermission

            @permission('view-users')
            <li @if($curRoute == 'admin.db.users.index' || $curRoute == 'admin.db.users.show') class="active" @endif>
                <a href="{{ route('admin.db.users.index') }}">
                    <span class="fa fa-users"></span>
                    <span class="sidebar-title">Користувачі</span>
                </a>
            </li>
            @endpermission

            <li {!! (strpos($curRoute, '.db.archive.animals') !== false) ? ' class="active" ' : '' !!}>
                <a href="{{route('admin.db.archive.animals.index')}}">
                    <span class="fa fa-archive"></span>
                    <span class="sidebar-title">Архів тварин</span>
                </a>
            </li>

            @permission('edit-content')
                <li class="sidebar-label pt20">Інформація</li>
                <li @if($curRoute == 'admin.info.directories.index') class="active" @endif>
                    <a href="{{ route('admin.info.directories.index') }}">
                        <span class="fa fa-book"></span>
                        <span class="sidebar-title">Довідники</span>
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
                            <a href="{{route('admin.content.faq.index')}}">
                                <span class="fa fa-question-circle"></span>Часті питання</a>
                        </li>
                        <li{!! (strpos($curRoute, '.content.block') !== false) ? ' class="active" ' : '' !!}>
                            <a href="{{route('admin.content.block.index')}}">
                                <span class="fa fa-info-circle"></span>Блоки
                            </a>
                        </li>
                    </ul>
                </li>

            <li{!! (strpos($curRoute, '.reports.') !== false) ? ' class="active" ' : '' !!}>
                <a class="accordion-toggle {!! (strpos($curRoute, '.reports.') !== false)
                            ? 'menu-open' : '' !!}" href="#">
                    <span class="fa fa-file-text"></span>
                    <span class="sidebar-title">Звіти</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    <li{!! (strpos($curRoute, '.reports.registered-animals.') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.reports.registered-animals.index')}}">
                            <span class="fa fa-question-circle"></span>Реєстрація тварин</a>
                    </li>
                    <li{!! (strpos($curRoute, '.reports.registered-animals-homeless.') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.reports.registered-animals-homeless.index')}}">
                            <span class="fa fa-info-circle"></span>Реєстрація безпритульних тварин
                        </a>
                    </li>
                    <li{!! (strpos($curRoute, '.reports.animals-amount-species') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.reports.animals-amount-species.index')}}">
                            <span class="fa fa-info-circle"></span>Кількість тварин за видом
                        </a>
                    </li>
                    <li{!! (strpos($curRoute, '.reports.animals-amount-breeds') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.reports.animals-amount-breeds.index')}}">
                            <span class="fa fa-info-circle"></span>Кількість тварин за породою
                        </a>
                    </li>
                    <li{!! (strpos($curRoute, '.reports.registered-animals-owners.') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.reports.registered-animals-owners.index')}}">
                            <span class="fa fa-info-circle"></span>Реєстрація власників тварин
                        </a>
                    </li>
                    <li{!! (strpos($curRoute, '.reports.registered-animals-of-owner.') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.reports.registered-animals-of-owner.index')}}">
                            <span class="fa fa-info-circle"></span>Довідка за тваринами власника
                        </a>
                    </li>
                </ul>
            </li>
                <li class="sidebar-label pt20">Mailing Settings</li>

                <li {!! (strpos($curRoute, '.templates') !== false) ? ' class="active" ' : '' !!}>
                    <a href="{{ route('admin.templates.index') }}">
                        <span class="glyphicon glyphicon-envelope"></span>
                        <span class="sidebar-title">{{ __('Email Templates') }}</span>
                    </a>
                </li>
            @if(false)
                <li >
                    <a href="{{ route('admin.mailings.index') }}">
                        <span class="glyphicon glyphicon-cog"></span>
                        <span class="sidebar-title">{{ __('Mailing configs') }}</span>
                    </a>
                </li>

                <li >
                    <a href="{{ route('admin.groups.index') }}">
                        <span class="glyphicon glyphicon-list"></span>
                        <span class="sidebar-title">{{ __('Mailing groups') }}</span>
                    </a>
                </li>
            @endif
            @endpermission

            <li class="sidebar-label pt20">Адміністрування</li>
            @permission(['change-roles', 'block-user', 'delete-user', 'edit-roles', 'view-syslog'])
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
            <li{!! (strpos($curRoute, 'administrating.requests') !== false) ? ' class="active" ' : '' !!}>
                <a class="accordion-toggle {!! (strpos($curRoute, 'administrating.requests'))
                            ? 'menu-open' : '' !!}" href="#">
                    <span class="fa fa-file-text"></span>
                    <span class="sidebar-title">Запити</span>
                    <span class="caret"></span>
                    @if($hasNewRequestsLost || $hasNewRequestsOwn || $hasNewRequestsChangeOwn || $hasNewRequestsFound)
                        <span class="sidebar-title-tray">
                                    <span class="label label-xs bg-danger">!</span>
                                </span>
                    @endif
                </a>
                <ul class="nav sub-nav">
                    <li{!! (strpos($curRoute, 'requests.own') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{ route('admin.administrating.requests.own.index') }}">
                            <span class="fa fa-question-circle"></span>Запити на власництво
                            @if($hasNewRequestsOwn)
                                <span class="sidebar-title-tray">
                                    <span class="label label-xs bg-danger">!</span>
                                </span>
                            @endif
                        </a>
                    </li>
                    <li{!! (strpos($curRoute, 'requests.lost') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.administrating.requests.lost.index')}}">
                            <span class="fa fa-info-circle"></span>Загублені тварини
                            @if($hasNewRequestsLost)
                                <span class="sidebar-title-tray">
                                    <span class="label label-xs bg-danger">!</span>
                                </span>
                            @endif
                        </a>
                    </li>
                    <li{!! (strpos($curRoute, 'requests.found') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.administrating.requests.found.index')}}">
                            <span class="fa fa-info-circle"></span>Знайдені тварини
                            @if($hasNewRequestsFound)
                                <span class="sidebar-title-tray">
                                    <span class="label label-xs bg-danger">!</span>
                                </span>
                            @endif
                        </a>
                    </li>

                    <li{!! (strpos($curRoute, 'requests.change-own') !== false) ? ' class="active" ' : '' !!}>
                        <a href="{{route('admin.administrating.requests.change-own.index')}}">
                            <span class="fa fa-info-circle"></span>Зміна власника
                            @if($hasNewRequestsChangeOwn)
                                <span class="sidebar-title-tray">
                                    <span class="label label-xs bg-danger">!</span>
                                </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
