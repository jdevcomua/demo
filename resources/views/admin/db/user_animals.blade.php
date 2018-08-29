@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>{{ $user->full_name }}</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список тварин користувача</div>
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
                                    <th>Номер жетону</th>
                                    <th>Дії</th>
                                    <th>Кличка</th>
                                    <th>Вид</th>
                                    <th>Порода</th>
                                    <th>Масть</th>
                                    <th>Стать</th>
                                    <th>Дата народження</th>
                                    <th>Стерилізація</th>
                                    <th>Верифіковано</th>
                                    <th>Належність тварини</th>
                                    <th>Зареєстровано</th>
                                    <th>Оновлено</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="no-search"></th>
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
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Самка</option>
                                            <option value="1">Самець</option>
                                        </select>
                                    </th>
                                    <th></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Не стерилізовано</option>
                                            <option value="1">Стерилізовано</option>
                                        </select>
                                    </th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Ні</option>
                                            <option value="1">Так</option>
                                        </select>
                                    </th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Безпритульна</option>
                                            <option value="1">Власницька</option>
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

            </div>

        <form action="#" id="destroy" method="post" class="hidden">
            @csrf
            @method('delete')
        </form>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.db.animals.data', ['id' => $user->id], false) }}',
                columns: [
                    { "data": "id"},
                    { "data": "badge" },
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href='#' class='delete' " +
                                    "data-id=" + data + " >"
                                    @permission('delete-animal')
                                    +
                                    "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    @endpermission
                                    ;

                            }
                        }
                    },
                    { "data": "nickname" },
                    { "data": "species_name"},
                    { "data": "breeds_name" },
                    { "data": "colors_name" },
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