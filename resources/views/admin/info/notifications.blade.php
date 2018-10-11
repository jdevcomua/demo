@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Нотифікації</span>
        </div>
    </header>
    <!-- End: Topbar -->
    <section id="content" class="animated fadeIn">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="fa fa-bell"></span>Нотифікації
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
                                <th>Назва</th>
                                <th>Тема</th>
                                <th>Текст</th>
                                <th>Активна</th>
                                <th>Подій</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="no-search"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    {{--<div class="panel-footer">--}}
                        {{--<p>Ви можете використовувати наспупні змінні: <b>{кількість}, {ім'я}</b></p>--}}
                    {{--</div>--}}
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
                        orderable: false,
                        render: function (data, type, row) {
                            if (data) {
                                return "<a href=\"{{ route('admin.info.notifications.edit') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href='#' class='delete' " +
                                    "data-id=" + data + " >" +
                                    "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    {
                        data: 'type',
                        render: function ( data, type, row ) {
                            var types = @json((object) \App\Models\NotificationTemplate::getTypes());
                            return types[data];
                        }
                    },
                    { "data": "name" },
                    { "data": "subject" },
                    { "data": "body" },
                    { "data": "active" },
                    {
                        data: 'events',
                        defaultContent: '0',
                        render: function ( data, type, row ) {
                            if (data) {
                                var arr = data.split('@');
                                return arr.length;
                            }
                        }
                    },
                ],
            });
        });
    </script>
@endsection
