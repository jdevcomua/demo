@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Архів тварин</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх архівованих тварин</div>
                        </div>
                        <div class="panel-body pn">
                            @if (\Session::has('success_animal'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_animal') }}
                                </div>
                            @endif
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Дії</th>
                                    <th>Дата архівації</th>
                                    <th>Причина архівації</th>
                                    <th>Причина смерті</th>
                                    <th>Дата смерті</th>
                                    <th>Дата виїзду</th>
                                    <th>Номер жетону</th>
                                    <th>Кличка</th>
                                    <th>Вид</th>
                                    <th>Стать</th>
                                    <th>Власник</th>
                                    <th>Коментар</th>
                                    <th>Порода</th>
                                    <th>Масть</th>
                                    <th>Дата народження</th>
                                    <th>Стерилізація</th>
                                    <th>Верифіковано</th>
                                    <th>Належність тварини</th>
                                    <th>Зареєстровано</th>
                                    <th>Оновлено</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="Смерть">Смерть</option>
                                            <option value="Виїзд">Виїзд</option>
                                        </select>
                                    </th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($causes_of_deaths_array as $k => $v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($species as $s)
                                                <option value="{{$s->name}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Самка</option>
                                            <option value="1">Самець</option>
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Не стерилізовано</option>
                                            <option value="1">Стерилізовано</option>
                                        </select>
                                    </th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Ні</option>
                                            <option value="1">Так</option>
                                        </select>
                                    </th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Безпритульна</option>
                                            <option value="1">Власницька</option>
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        <form action="#" id="destroy" method="post" class="hidden">
            @csrf
            @method('delete')
        </form>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        causes_of_death = {!! json_encode($causes_of_deaths_array) !!};

        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.db.archive.animals.data', null, false) }}',
                order: [[ 14, "desc" ]], // def. sort by created_at DESC
                columns: [
                    { "data": "id"},
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                            @permission('delete-animal')
                                +
                                    "<a href='#' class='delete' " +
                                "data-id=" + data + " >" +
                                "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                "</a>"
                            @endpermission
                            ;

                            }
                        }
                    },
                    { "data": "archived_at"},
                    { "data": "archived_type"},
                    {
                        "data": "death",
                        defaultContent: '-',
                        render: function ( data, type, row ) {
                            if(row['archived_type'] === 'Смерть') {
                                return causes_of_death[data];
                            }
                        }
                    },
                    {
                        "data": "death_date",
                        defaultContent: '-',
                        render: function ( data, type, row ) {
                            if(row['archived_type'] === 'Смерть') {
                                return data;
                            }
                        }
                    },
                    {
                        "data": "moved_out_date",
                        defaultContent: '-',
                        render: function ( data, type, row ) {
                            if(row['archived_type'] === 'Виїзд') {
                                return data;
                            }
                        }
                    },
                    { "data": "badge" },
                    { "data": "nickname" },
                    { "data": "species_name"},
                    {
                        data: 'gender',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Самка';
                                case 1: return 'Самець';
                                default: return '?';
                            }
                        }
                    },
                    {
                        data: 'owner_name',
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                var arr = data.split('||');
                                return '<a href="{{ route('admin.db.users.show') }}/' + arr[1] + '">' + arr[0] + '</a>';
                            }
                        }
                    },
                    { "data": "comment" },
                    { "data": "breeds_name" },
                    { "data": "colors_name" },
                    {
                        data: 'birthday',
                        render: function ( data, type, row ) {
                            var d = parseDBDate(data);
                            return d.toLocaleDateString('uk')
                        }
                    },
                    {
                        data: 'sterilized',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Не стерилізовано';
                                case 1: return 'Стерилізовано';
                                default: return '?';
                            }
                        }
                    },
                    {
                        data: 'verified',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Ні';
                                case 1: return 'Так';
                                default: return '?';
                            }
                        }
                    },
                    {
                        data: 'owner_type',
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return '<i class="fa fa-home"></i>';
                            } else {
                                return '<i class="fa fa-ban"></i>';
                            }
                        }
                    },
                    { data: "created_at",
                        render: function ( data, type, row ) {
                            if (data) {
                                var d = parseDBDate(data);
                                return d.toLocaleDateString('uk') + ' ' + d.toLocaleTimeString('uk');
                            }
                            return data
                        }
                    },
                    { data: "updated_at",
                        render: function ( data, type, row ) {
                            if (data) {
                                var d = parseDBDate(data);
                                return d.toLocaleDateString('uk') + ' ' + d.toLocaleTimeString('uk');
                            }
                            return data
                        }
                    },
                ],
            });

            jQuery(document).on('click','.delete', function(e) {
                e.preventDefault();
                if (confirm('Ви впевнені що хочете видалити тварину?')) {
                    var id = jQuery(this).attr('data-id');
                    var form = jQuery('#destroy');
                    $(form).attr('action', "{{route('admin.db.animals.remove')}}/"+id);
                    $(form).submit();
                }
            });
        });
    </script>
@endsection
