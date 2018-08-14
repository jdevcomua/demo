@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">База заблокованих користувачів</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список заблокованих користувачів</div>
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
                                    <th>Ім'я</th>
                                    <th>Прізвище</th>
                                    <th>По батькові</th>
                                    <th>e-mail</th>
                                    <th>Телефон</th>
                                    <th>Зареєстровано</th>
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
                ajax: '{{ route('admin.administrating.banned.data', null, false) }}',
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
                                    "<i class=\"fa fa-user-plus\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    @endpermission
                                    ;

                            }
                        }
                    },
                    { "data": "ext_id" },
                    { "data": "first_name" },
                    { "data": "last_name" },
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
                    { data: "created_at",
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

            jQuery(document).on('click','.ban', function(e) {
                e.preventDefault();
                if (confirm('Ви впевнені що хочете розблокувати користувача?')) {
                    var id = jQuery(this).attr('data-id');
                    var form = jQuery('#remove');
                    $(form).attr('action', "{{route('admin.administrating.users.unban')}}/"+id);
                    $(form).submit();
                }
            });

        });
    </script>
@endsection