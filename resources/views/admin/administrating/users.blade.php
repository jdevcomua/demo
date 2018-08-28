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
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх користувачів</div>
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
                                    <th>Дії</th>
                                    <th>#K</th>
                                    <th>Прізвище</th>
                                    <th>Ім'я</th>
                                    <th>По батькові</th>
                                    <th>e-mail</th>
                                    <th>Телефон</th>
                                    <th>Оновлено</th>
                                    <th>Ролі</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th></th>
                                    <th></th>
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
                ajax: '{{ route('admin.administrating.users.data', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.users.show') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-eye pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    @permission('block-user')
                                    + "<a href='#' class='ban' data-id=" + data + ">" +
                                    "<i class=\"fa fa-ban\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    @endpermission
                                    ;
                            }
                        }
                    },
                    { "data": "ext_id" },
                    { "data": "last_name" },
                    { "data": "first_name" },
                    { "data": "middle_name" },
                    {
                        data: 'emails',
                        render: function ( data, type, row ) {
                            return (data) ? data.split('|').join('<br>') : '';
                        }
                    },
                    {
                        data: 'phones',
                        render: function ( data, type, row ) {
                            return (data) ? data.split('|').join('<br>') : '';
                        }
                    },
                    { "data": "updated_at" },
                    {
                        data: 'role_names',
                        render: function ( data, type, row ) {
                            return (data) ? data.split('|').join('<br>') : '';
                        }
                    },
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