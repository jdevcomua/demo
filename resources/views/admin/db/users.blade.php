@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">База користувачів</a>
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
                            <div class="panel-title hidden-xs">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх користувачів</div>
                        </div>
                        <div class="panel-body pn">
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>#K</th>
                                    <th>Ім'я</th>
                                    <th>Прізвище</th>
                                    <th>По батькові</th>
                                    <th>e-mail</th>
                                    <th>Телефон</th>
                                    <th>Дата народження</th>
                                    <th>ІПН</th>
                                    <th>Паспорт</th>
                                    <th>Стать</th>
                                    <th>Зареєстровано</th>
                                    <th>Оновлено</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
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
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Жін.</option>
                                            <option value="1">Чол.</option>
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

            $('#datatable tfoot th').each(function() {
                if ($(this).find('select').length !== 0) return;
                var title = $('#datatable thead th').eq($(this).index()).text();
                $(this).html('<input type="text" class="form-control" />');
            });

            // DataTable
            var my_table = $('#datatable').DataTable({
                sDom: 't<"dt-panelfooter clearfix"ip>',
                ajax: {
                    url: '{{ route('admin.db.users.data', null, false) }}'
                },
                serverSide: true,
                responsive: true,
                columns: [
                    { "data": "id", "responsivePriority": 1 },
                    { "data": "ext_id", "responsivePriority": 11 },
                    { "data": "first_name", "responsivePriority": 2 },
                    { "data": "last_name", "responsivePriority": 3 },
                    { "data": "middle_name", "responsivePriority": 6 },
                    { "data": "email", "responsivePriority": 5 },
                    { "data": "phone", "responsivePriority": 7 },
                    {
                        data: 'birthday',
                        responsivePriority: 10,
                        render: function ( data, type, row ) {
                            var d = new Date(data);
                            return d.toLocaleDateString('uk')
                        }
                    },
                    { "data": "inn", "responsivePriority": 9 },
                    { "data": "passport", "responsivePriority": 8 },
                    {
                        data: 'gender',
                        responsivePriority: 4,
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Жін.';
                                case 1: return 'Чол.';
                                default: return '?';
                            }
                        }
                    },
                    { "data": "created_at", "responsivePriority": 12 },
                    { "data": "updated_at", "responsivePriority": 13 },
                ],
                language: {
                    "sProcessing":   "Зачекайте...",
                    "sLengthMenu":   "Показати _MENU_ записів",
                    "sZeroRecords":  "Записи відсутні.",
                    "sInfo":         "Записи з _START_ по _END_ із _TOTAL_ записів",
                    "sInfoEmpty":    "Записи з 0 по 0 із 0 записів",
                    "sInfoFiltered": "(відфільтровано з _MAX_ записів)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Пошук:",
                    "sUrl":          "",
                    "oPaginate": {
                        "sFirst": "Перша",
                        "sPrevious": "Попередня",
                        "sNext": "Наступна",
                        "sLast": "Остання"
                    },
                    "oAria": {
                        "sSortAscending":  ": активувати для сортування стовпців за зростанням",
                        "sSortDescending": ": активувати для сортування стовпців за спаданням"
                    }
                }
            });

            my_table.columns().eq(0).each(function(colIdx) {
                var $input = $('input', my_table.column(colIdx).footer());

                $input.on('keyup', function(e) {
                    if(e.keyCode === 13) searchInTable(my_table, colIdx, this.value);
                });
                $input.on('blur', function(e) {
                    searchInTable(my_table, colIdx, this.value);
                });
                $('select', my_table.column(colIdx).footer()).on('change', function(e) {
                    searchInTable(my_table, colIdx, this.value);
                });
            });

            function searchInTable(table, column, search) {
                table.column(column).search(search).draw();
            }

            my_table.on('draw', function () {
                my_table.responsive.recalc();
            });
            $(window).resize(function () {
                my_table.responsive.recalc()
            });

        });
    </script>
@endsection