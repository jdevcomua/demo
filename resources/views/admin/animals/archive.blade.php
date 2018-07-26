@extends('adminlte::layouts.app')

@section('css')
    <link href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('htmlheader_title')
    Архів тварин
@endsection

@section('contentheader_title')
    Архів тварин
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-xs-12">

                <!-- Default box -->
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered responsive" id="animals-table">
                            <thead >
                            <tr>
                                <th>Вид</th>
                                <th>Порода</th>
                                <th>Колір</th>
                                <th>Прізвище</th>
                                <th>Стать</th>
                                <th>День Народження</th>
                                <th>Стерилізований</th>
                                <th>Хазяїн</th>
                                <th>Модератор</th>
                                <th></th>
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
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

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
        $('#animals-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{route('admin.animals.archive.data')}}',
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
                {data: 'species.name', name: 'species.name'},
                {data: 'breed.name', name: 'breed.name'},
                {data: 'color.name', name: 'color.name'},
                {data: 'nickname', name: 'animals.nickname'},
                {data: 'gender', name: 'animals.gender',
                    render: function (data, type, row) {
                        if (data === '1') {
                            return 'Ч' ;
                        }  else {
                            return 'Ж'
                        }
                    }},
                {data: 'birthday', name: 'animals.birthday'},
                {data: 'sterilized', name: 'animals.sterilized',
                    render: function (data, type, row) {
                        if (data === '1') {
                            return 'Так' ;
                        }  else {
                            return 'Ні'
                        }
                    }},

                {data: 'user.id', name: 'user.id'},
                {data: 'user_that_confirmed.id', name: 'user.id',
                    render: function (data, type, row) {
                    console.log(data);
                        if (data) {
                            return data ;
                        }  else {
                            return 'null'
                        }
                    }},
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
@endsection
