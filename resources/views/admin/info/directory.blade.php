@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Довідники</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="row flex-grid">
                <div class="col-md-6 col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх порід</div>
                        </div>
                        <div class="panel-body pn">
                            @if($errors->breed_rem)
                                @foreach($errors->breed_rem->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_breed_rem'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_breed_rem') }}
                                </div>
                            @endif
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable-breed" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Дії</th>
                                    <th>Вид тварини</th>
                                    <th>Назва</th>
                                    <th>FCI</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($species as $s)
                                                <option value="{{$s->name}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список усіх мастей</div>
                        </div>
                        <div class="panel-body pn">
                            @if($errors->color_rem)
                                @foreach($errors->color_rem->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_color_rem'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_color_rem') }}
                                </div>
                            @endif
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable-color" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Дії</th>
                                    <th>Вид тварини</th>
                                    <th>Назва</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($species as $s)
                                                <option value="{{$s->name}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Додавання нової породи</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.info.directories.store.breed') }}" method="post">
                            @csrf
                            <div class="panel-body">
                                @if($errors->breed)
                                    @foreach($errors->breed->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_breed'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_breed') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="breed-species" class="col-lg-3 control-label">Вид тварини:</label>
                                    <div class="col-lg-8">
                                        <div class="bs-component">
                                            <select id="breed-species" name="b_species" class="form-control" required>
                                                @foreach($species as $s)
                                                    <option value="{{$s->id}}" @if(old('b_species') == $s->id) selected @endif>
                                                        {{$s->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="breed-name" class="col-lg-3 control-label">Назва породи:</label>
                                    <div class="col-lg-8">
                                        <div class="bs-component">
                                            <input type="text" id="breed-name" name="b_name"
                                                   class="form-control" value="{{ old('b_name') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="breed-fci" class="col-lg-3 control-label">FCI:</label>
                                    <div class="col-lg-8">
                                        <div class="bs-component">
                                            <input type="text" id="breed-fci" name="b_fci"
                                                   class="form-control" value="{{ old('b_fci') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-default ph25">Додати</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Додавання нової масті</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.info.directories.store.color') }}" method="post">
                            @csrf
                            <div class="panel-body">
                                @if($errors->color)
                                    @foreach($errors->color->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_color'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_color') }}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="color-species" class="col-lg-3 control-label">Вид тварини:</label>
                                    <div class="col-lg-8">
                                        <div class="bs-component">
                                            <select id="color-species" name="c_species" class="form-control" required>
                                                @foreach($species as $s)
                                                    <option value="{{$s->id}}" @if(old('c_species') == $s->id) selected @endif>
                                                        {{$s->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="color-name" class="col-lg-3 control-label">Назва масті:</label>
                                    <div class="col-lg-8">
                                        <div class="bs-component">
                                            <input type="text" id="color-name" name="c_name"
                                                   class="form-control" value="{{ old('c_name') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-default ph25">Додати</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
    <script src="/js/admin/jquery.dataTables.js"></script>
    <script src="/js/admin/dataTables.tableTools.min.js"></script>
    <script src="/js/admin/dataTables.colReorder.min.js"></script>
    <script src="/js/admin/dataTables.bootstrap.js"></script>
    <script src="/js/admin/dataTables.responsive.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {


            dataTableInit($('#datatable-breed'), {
                ajax: '{{ route('admin.info.directories.data.breed', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.info.directories.remove.breed') }}?id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "species_name" },
                    { "data": "name" },
                    { "data": "fci" },
                ],
            });

            dataTableInit($('#datatable-color'), {
                ajax: '{{ route('admin.info.directories.data.color', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.info.directories.remove.color') }}?id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "species_name" },
                    { "data": "name" },
                ],
            });

        });
    </script>
@endsection