@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Нотифікації</a>
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
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-envelope"></span>Нотифікації
                            </div>
                        </div>
                        <div class="panel-body pn">
                            @if (\Session::has('success_notifications'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_notifications') }}
                                </div>
                            @endif

                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable-notifications" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Дії</th>
                                    <th>Тип</th>
                                    <th>Мін</th>
                                    <th>Макс</th>
                                    <th>Текст</th>
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
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <p>Ви можете використовувати наспупні змінні: <b>{кількість}, {ім'я}</b></p>

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
                        <h4 class="modal-title">Змінити нотифікацію</h4>
                    </div>
                    <form action="#" class="form-horizontal" id="change-notification" method="post">

                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" id="notificationId">
                            <div class="form-group">
                                <label for="min" class="col-sm-3 control-label">
                                    Мін
                                </label>

                                <div class="col-sm-9">
                                    <input type="number" min="1" id="min" name="min" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="max" class="col-sm-3 control-label">
                                    Макс
                                </label>

                                <div class="col-sm-9">
                                    <input type="number" min="1" id="max" name="max" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="user-name" class="col-sm-3 control-label">
                                    Текст
                                </label>

                                <div class="col-sm-9">
                                    <input type="text" id="text" name="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning pull-right">Змінити</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            dataTableInit($('#datatable-notifications'), {
                ajax: '{{ route('admin.info.notifications.data', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function (data, type, row) {
                            if (data) {
                                return "<a href='#' data-id='" + data +"' class='edit'" +
                                        "data-min='" + row.min +"' " +
                                        "data-max='" + row.max +"' " +
                                        "data-text='" + row.text +"' " +
                                    "data-toggle=\"modal\" data-target=\"#modal\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "type" },
                    { "data": "min" },
                    { "data": "max"},
                    { "data": "text"},
                ],
            });
        });
        jQuery(document).on('click','.edit', function() {
            var form = jQuery('#change-notification');
            var id =  jQuery(this).attr('data-id');
            var min =  jQuery(this).attr('data-min');
            var max =  jQuery(this).attr('data-max');
            var text =  jQuery(this).attr('data-text');

            jQuery(form).attr('action', '{{route('admin.info.notifications.store')}}/' +id);
            jQuery('#min').val(min);
            jQuery('#max').val(max);
            jQuery('#text').val(text);
        });
    </script>
@endsection
