@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Системні ролі</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх ролей</div>
                        </div>
                        <div class="panel-body pn">
                            @if($errors->role_rem)
                                @foreach($errors->role_rem->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_role_rem'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_role_rem') }}
                                </div>
                            @endif
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Дії</th>
                                    <th>Роль</th>
                                    <th>Назва</th>
                                    <th>Дозволів</th>
                                    <th>Користувачів</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th></th>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th class="no-search"></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Створити нову роль</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.roles.store') }}" method="post">
                            @csrf
                            <div class="panel-body">
                                @if($errors->role)
                                    @foreach($errors->role->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_role'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_role') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="role-name" class="col-lg-3 control-label ptn">
                                        Назва ролі (англ):
                                    </label>
                                    <div class="col-lg-8">
                                        <input type="text" id="role-name" name="name"
                                               class="form-control" value="{{ old('name') }}"
                                               placeholder="role" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="role-display_name" class="col-lg-3 control-label ptn">
                                        Назва для відображення:
                                    </label>
                                    <div class="col-lg-8">
                                        <input type="text" id="role-display_name" name="display_name"
                                               class="form-control" value="{{ old('display_name') }}"
                                               placeholder="Роль" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Дозволи:</label>
                                    <div class="col-lg-8 pt10">
                                        @foreach($permissions as $permission)
                                            <div class="col-sm-12 pl15">
                                                <div class="checkbox-custom mb5">
                                                    <input type="checkbox" name="permission[]"
                                                           value="{{ $permission->id }}"
                                                           id="perms{{ $permission->id }}">
                                                    <label for="perms{{ $permission->id }}">
                                                        {{ $permission->display_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-default ph25">Створити</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.roles.data', null, false) }}',
                columns: [
                    { "data": "id"},
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.roles.edit') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href=\"{{ route('admin.roles.remove') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "name"},
                    { "data": "display_name"},
                    { "data": "permissions_count" },
                    { "data": "users_count"},
                ],
            });

        });
    </script>
@endsection