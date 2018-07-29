<header class="navbar navbar-fixed-top bg-primary">
    <div class="navbar-branding">
        <a class="navbar-brand" href="{{ route('admin.index') }}">
            <b>RHA</b> Admin
        </a>
        <span id="toggle_sidemenu_l" class="ad ad-lines"></span>
    </div>

    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown">{{ $auth->name }}
                <span class="caret caret-tp hidden-xs"></span>
            </a>
            <ul class="dropdown-menu list-group dropdown-persist" role="menu">
                <li class="list-group-item">
                    <a href="{{ route('profile') }}"><span class="fa fa-user"></span>Профіль</a>
                </li>
                <li class="list-group-item">
                    <a href="#" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                        <span class="fa fa-power-off"></span>Вийти</a>
                    <form id="logout-form" action="{{ route('logout') }}"
                          method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>

</header>