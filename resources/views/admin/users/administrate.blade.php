@extends('adminlte::layouts.app')

@section('css')
    <link href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('htmlheader_title')
    Користувачі
@endsection

@section('contentheader_title')
    Користувачі
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered responsive" id="users-table">
                            <thead >
                            <tr>
                                <th>Прізвище</th>
                                <th>Ім'я</th>
                                <th>По батькові</th>
                                <th>Email</th>
                                <th>Номер телефона</th>
                                <th>День Народження</th>
                                <th>Стать</th>
                                <th>Паспорт</th>
                                <th>Адресса</th>
                                <td>Роль</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>#</th>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Змінити роль</h4>
                </div>
                <form action="#" class="form-horizontal" id="change-role" method="post">

                    <div class="modal-body">
                        @csrf
                        <input type="hidden" value="">
                        <div class="form-group">
                            <label id="user-name" class="col-sm-4 control-label"> </label>

                            <div class="col-sm-8">
                                <select name="role" id="role" class="form-control">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning pull-right">Змінити роль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>
    <script>
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{route('admin.users.administrate.data')}}',
            language: {
                "lengthMenu": "Показано _MENU_ записів на сторінці",
                "zeroRecords": "Нічого не знайшлося - вибачте",
                "info": "Показую сторінку _PAGE_ з _PAGES_",
                "infoEmpty": "Немає записів",
                "infoFiltered": "(відфільтровано з _MAX_ загальної кількості записів)",
                "loadingRecords": "Загрузка...",
                "processing":     "Обробка...",
                "search":         "Пошук:",
                "paginate": {
                    "first":      "Перший",
                    "last":       "Остання",
                    "next":       "Далі",
                    "previous":   "Попередня"
                },
            },
            columns: [
                {data: 'first_name', name: 'first_name'},
                {data: 'last_name', name: 'last_name'},
                {data: 'middle_name', name: 'middle_name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'birthday', name: 'birthday'},
                {data: 'gender', name: 'gender',
                    render: function (data, type, row) {
                        if (data === '1') {

                            return 'Ч' ;
                        }  else {
                            return 'Ж'
                        }
                    }},
                {data: 'passport', name: 'passport'},
                {data: 'residence_address', name: 'residence_address'},
                {data: 'roles', name: 'roles.display_name'},
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    </script>
    <script defer>
        $( document ).ready(function() {
            console.log( "ready!" );
            $('.change-role').on('click', function() {
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                var role = $(this).attr('data-role');
                console.log('open');
                $('#change-role').attr('action', '/admin/users/administrate/' + id + '/role');
                $('#user-name').text(name);
                $('#role').val(role)
            })
        });
    </script>
@endsection
