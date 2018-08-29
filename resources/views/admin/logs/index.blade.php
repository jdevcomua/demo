@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Журнал дій</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх системних подій</div>
                        </div>
                        <div class="panel-body pn">
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Користувач</th>
                                    <th>Дія</th>
                                    <th>Об'єкт *</th>
                                    <th>Статус</th>
                                    <th>Завершено</th>
                                    <th>Дії</th>
                                </tr>
                                <tr>
                                    <th>Дата</th>
                                    <th>Користувач</th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach(\App\Models\Log::ACTIONS as $k => $action)
                                                <option value="{{ $k }}">{{ $action }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>Об'єкт</th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach(\App\Models\Log::STATUSES as $k => $status)
                                                <option value="{{ $k }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Ні</option>
                                            <option value="1">Так</option>
                                        </select>
                                    </th>
                                    <th class="no-search"></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="footnote">
                                <p>
                                    * - Для пошуку по цьому полю введіть лише номер об'єкту  (Наприклад #3)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            actions = {!! json_encode(\App\Models\Log::ACTIONS) !!};
            statuses = {!! json_encode(\App\Models\Log::STATUSES) !!};

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.logs.data', null, false) }}',
                order: [[ 0, "desc" ]],
                columns: [
                    {
                        data: 'updated_at',
                        render: function ( data, type, row ) {
                            if (data) {
                                var d = parseDBDate(data);
                                return d.toLocaleDateString('uk') + ' ' + d.toLocaleTimeString('uk');
                            }
                            return data
                        }
                    },
                    {
                        data: 'user',
                        render: function ( data, type, row ) {
                            if (data) {
                                var arr = data.split('|');
                                return '<a href="{{ route('admin.db.users.show') }}/'+row.user_id+'">'+data
                                    +'</a>';
                            }
                            return '';
                        }
                    },
                    {
                        data: 'action',
                        render: function ( data, type, row ) {
                            return actions[data];
                        }
                    },
                    {
                        data: 'object_id',
                        render: function ( data, type, row ) {
                            if (row.object_id && row.object_type) {
                                return '<a href="{{ route('admin.object') }}/' + row.object_type + '/' + row.object_id + '">' +
                                    row.object_type + ' #' + row.object_id + '</a>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'status',
                        render: function ( data, type, row ) {
                            return statuses[data];
                        }
                    },
                    {
                        data: 'finished',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Ні';
                                case 1: return 'Так';
                                default: return '?';
                            }
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function ( data, type, row ) {
                            return '<a href="{{ route('admin.logs.show') }}/'+data+'" class="btn btn-sm ' +
                                'btn-primary btn-block">Докладніше ...</a>';
                        }
                    },
                ],
            });

        });
    </script>
@endsection