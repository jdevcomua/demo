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

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title hidden-xs">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх порід</div>
                        </div>
                        <div class="panel-body pn">
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable-breed" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Вид тварини</th>
                                    <th>Назва</th>
                                    <th>FCI</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
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
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title hidden-xs">
                                <span class="glyphicon glyphicon-tasks"></span>Список усіх мастей</div>
                        </div>
                        <div class="panel-body pn">
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable-color" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Вид тварини</th>
                                    <th>Назва</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
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
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title hidden-xs">
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

                                @if (\Session::has('success-breed'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success-breed') }}
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
                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title hidden-xs">
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

                                @if (\Session::has('success-color'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success-color') }}
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
                                    <label for="color-name" class="col-lg-3 control-label">Назва породи:</label>
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

            $('#datatable-breed tfoot th').each(function() {
                if ($(this).find('select').length !== 0) return;
                var title = $('#datatable-breed thead th').eq($(this).index()).text();
                $(this).html('<input type="text" class="form-control" />');
            });
            $('#datatable-color tfoot th').each(function() {
                if ($(this).find('select').length !== 0) return;
                var title = $('#datatable-color thead th').eq($(this).index()).text();
                $(this).html('<input type="text" class="form-control" />');
            });


            var breed_table = $('#datatable-breed').DataTable({
                sDom: 't<"dt-panelfooter clearfix"ip>',
                ajax: {
                    url: '{{ route('admin.info.directories.data.breed', null, false) }}'
                },
                serverSide: true,
                responsive: true,
                columns: [
                    { "data": "id" },
                    { "data": "species_name" },
                    { "data": "name" },
                    { "data": "fci" },
                ],
                language: dataTableLang
            });

            var color_table = $('#datatable-color').DataTable({
                sDom: 't<"dt-panelfooter clearfix"ip>',
                ajax: {
                    url: '{{ route('admin.info.directories.data.color', null, false) }}'
                },
                serverSide: true,
                responsive: true,
                columns: [
                    { "data": "id" },
                    { "data": "species_name" },
                    { "data": "name" },
                ],
                language: dataTableLang
            });

            applySearch(breed_table);
            applySearch(color_table);

            function applySearch(table) {
                table.columns().eq(0).each(function (colIdx) {
                    var $input = $('input', table.column(colIdx).footer());

                    $input.on('keyup', function (e) {
                        if (e.keyCode === 13) searchInTable(table, colIdx, this.value);
                    });
                    $input.on('blur', function (e) {
                        searchInTable(table, colIdx, this.value);
                    });

                    $('select', table.column(colIdx).footer()).on('change', function (e) {
                        searchInTable(table, colIdx, this.value);
                    });
                });
                table.on('draw', function () {
                    table.responsive.recalc();
                });
            }

            function searchInTable(table, column, search) {
                table.column(column).search(search).draw();
            }

            $(window).resize(function () {
                breed_table.responsive.recalc()
                color_table.responsive.recalc()
            });

        });
    </script>
@endsection