<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Gravatar::get($user->email) }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p style="overflow: hidden;text-overflow: ellipsis;max-width: 160px;" data-toggle="tooltip" title="{{ Auth::user()->full_name }}">{{ Auth::user()->full_name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('adminlte_lang::message.online') }}</a>
                </div>
            </div>
        @endif


        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Адміністрування</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Бази</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('admin.animals.index')}}"> <i class='fa fa-paw'></i>Тварини</a></li>
                    <li><a href="{{route('admin.users.index')}}"> <i class='fa fa-users'></i>Користувачі</a></li>
                    <li><a href="{{route('admin.animals.archive')}}"> <i class='fa fa-archive'></i>Архів тварин</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class='fa fa-info-circle'></i> <span>Інформація</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#"> <i class='fa fa-info'></i>Довідники</a></li>
                    <li><a href="#"> <i class='fa fa-commenting-o'></i>Повідомлення</a></li>
                    <li><a href="#"> <i class='fa fa-exclamation-triangle'></i>Нотифікації</a></li>
                    <li><a href="#"> <i class='fa fa-file-text-o'></i>Контент</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class='fa fa-cogs'></i> <span>Адміністрування</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{route('admin.users.administrate')}}"> <i class='fa fa-users'></i>Користувачі</a></li>
                    <li><a href="#"> <i class='fa fa-book'></i>Журнал дій</a></li>
                    <li><a href="{{route('admin.users.bans')}}"> <i class='fa fa-ban'></i>Блокування</a></li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
