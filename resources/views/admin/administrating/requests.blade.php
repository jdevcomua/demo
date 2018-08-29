@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>База користувачів</span>
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
                                    <th>Користувач</th>
                                    <th># Тварини</th>
                                    <th>Кличка</th>
                                    <th>Вид</th>
                                    <th>Порода</th>
                                    <th>Масть</th>
                                    <th>Тип шерсті</th>
                                    <th>Дії</th>
                                    <th>День народження</th>
                                    <th>Адреса</th>
                                    <th>Створено</th>
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
                                    <th class="no-search"></th>
                                    <th></th>
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

        <form action="#" method="post" class="hidden" id="remove">
            @csrf
        </form>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.administrating.requests.data') }}',
                columns: [
                    { "data": "id" },
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
                    { "data": "breed_name" },
                    { "data": "color_name" },
                    { "data": "fur_name" },
                    {
                        "data": "processed",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>";
                            } else if(row.animal_id) {
                                return "<a href=\"{{ route('admin.administrating.requests.accept') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Прийняти!\">" +
                                    "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href=\"{{ route('admin.administrating.requests.decline') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Відмінити!\">" +
                                    "<i class=\"fa fa-ban pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            } else if(!row.animal_id) {
                                return "<a href=\"{{ route('admin.administrating.requests.proceed') }}/"
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

            jQuery(document).on('click','.ban', function(e) {
                e.preventDefault();
                if (confirm('Ви впевнені що хочете заблокувати користувача?')) {
                    var id = jQuery(this).attr('data-id');
                    var form = jQuery('#remove');
                    $(form).attr('action', "{{route('admin.administrating.users.ban')}}/"+id);
                    $(form).submit();
                }
            });
        });
    </script>
@endsection