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

            <div class="col-md-3 col-sm-5 col-xs-6 mb25">
                <a href="{{ route('admin.info.notifications.create') }}" class="btn btn-success btn-block">Cтворити нотифікацію</a>
            </div>

            <div class="col-xs-12">
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
                                <th>Активна</th>
                                <th>Подій</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="no-search"></th>
                                <th class="select">
                                    <select>
                                        <option selected value>---</option>
                                        @foreach(\App\Models\NotificationTemplate::getTypes() as $k => $v)
                                            <option value="{{ $k }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th></th>
                                <th></th>
                                <th class="select">
                                    <select>
                                        <option selected value>---</option>
                                        <option value="0">Ні</option>
                                        <option value="1">Так</option>
                                    </select>
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form action="#" id="destroy" method="post" class="hidden">
                @csrf
                @method('delete')
            </form>
        </div>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        var canBeDeletedTypes = @json(array_keys(\App\Models\NotificationTemplate::getTypes(false)));

        $(document).ready(function() {
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
                                let actions = "<a href=\"{{ route('admin.info.notifications.edit') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                                if (canBeDeletedTypes.indexOf(row.type) !== -1) {
                                    actions += "<a href='#' class='delete' " +
                                    "data-id=" + data + " >" +
                                    "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                                }
                                return actions;
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
                    {
                        data: 'active',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Ні';
                                case 1: return 'Так';
                                default: return '?';
                            }
                        }
                    },
                    { "data": "events" },
                ],
            });

            $(document).on('click','.delete', function() {
                if (confirm('Ви впевнені що хочете видалити нотифікацію?')) {
                    var form = $('#destroy');
                    var id = $(this).attr('data-id');
                    $(form).attr('action', '{{route('admin.info.notifications.delete')}}/' + id);
                    $(form).submit();
                }
            });
        });
    </script>
@endsection
