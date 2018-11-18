@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>База запитів</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх запитів</div>
                        </div>
                        <div class="panel-body pn">
                            @if (\Session::has('success_user'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_user') }}
                                </div>
                            @endif
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Тип</th>
                                    <th>Користувач</th>
                                    <th># Тварини</th>
                                    <th>Кличка</th>
                                    <th>Вид</th>
                                    <th>Стать</th>
                                    <th>Порода</th>
                                    <th>Масть</th>
                                    <th>Тип шерсті</th>
                                    <th>Дії</th>
                                    <th>День народження</th>
                                    <th>Адреса</th>
                                    <th>Створено</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Запит на пошук</option>
                                            <option value="1">Запит на підтвердження господаря</option>
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
                                    <th class="no-search"></th>
                                    <th></th>
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

        <form action="#" method="post" class="hidden" id="remove">
            @csrf
        </form>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.administrating.requests.own.data') }}',
                order: [[ 13, "desc" ]], // def. sort by created_at DESC
                createdRow: function( row, data, dataIndex) {
                    if(data.processed){
                        $(row).addClass('processed');
                    }
                },
                columns: [
                    { "data": "id" },
                    {
                        data: 'type',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Запит на пошук';
                                case 1: return 'Запит на підтвердження господаря';
                                default: return '?';
                            }
                        }
                    },
                    { "data": "user" ,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.users.show') }}/"
                                    + row.user_id + "\">" + data +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "animal_id",
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                    + row.animal_id + "\">" + data +
                                    "</a>";
                            }
                            return '';
                        }},
                    { "data": "nickname" },
                    { "data": "species_name" },
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
                    { "data": "breed_name" },
                    { "data": "color_name" },
                    { "data": "fur_name" },
                    {
                        "data": "processed",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>";
                            } else if(row.animal_id) {
                                return "<a href=\"{{ route('admin.administrating.requests.own.accept') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Прийняти!\">" +
                                    "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href=\"{{ route('admin.administrating.requests.own.decline') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Відмінити!\">" +
                                    "<i class=\"fa fa-ban pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            } else if(!row.animal_id) {
                                return "<a href=\"{{ route('admin.administrating.requests.own.proceed') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Опрацьовано!\">" +
                                    "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                            }
                        }
                    },
                    { "data": "birthday" },
                    { "data": "address" },
                    { "data": "created_at" },
                ],
            });
        });
    </script>
@endsection
