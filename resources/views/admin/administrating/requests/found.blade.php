@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Знайдені тварини</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Знайдені тварини</div>
                        </div>
                        <div class="panel-body pn">
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Вид</th>
                                    <th>Порода</th>
                                    <th>Дії</th>
                                    <th>Масть</th>
                                    <th>Жетон</th>
                                    <th>Адресса де знайшли</th>
                                    <th>Ім'я</th>
                                    <th>Телефон</th>
                                    <th>Email</th>
                                    <th>Знайдено</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($species as $s)
                                                <option value="{{$s->name}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($breeds as $breed)
                                                <option value="{{$breed->name}}">{{$breed->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="no-search"></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($colors as $color)
                                                <option value="{{$color->name}}">{{$color->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
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
                    </div>
                </div>

            </div>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.administrating.requests.found.data', null, false) }}',
                createdRow: function( row, data, dataIndex) {
                    if(data.processed){
                        $(row).addClass('processed');
                    }
                },
                columns: [
                    { "data": "id"},
                    {"data": "species"},
                    {"data": "breed"},
                    {
                        "data": "processed",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            var approve_btn = "";
                            var view_btn = "<a href=\"{{ route('admin.administrating.requests.found.show', '') }}/"
                                + row.id + "\" data-toggle='tooltip' title=\"Переглянути\">" +
                                "<i class=\"fa fa-eye pr10\" aria-hidden=\"true\"></i>" +
                                "</a>";
                            if (row.approved) {
                                approve_btn = view_btn + "<a href=\"{{ route('admin.administrating.requests.found.disapprove') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Відмінити затвердження!\">" +
                                    "<i class=\"fa fa-thumbs-down pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            } else {
                                approve_btn = view_btn + "<a href=\"{{ route('admin.administrating.requests.found.approve') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Затвердити!\">" +
                                    "<i class=\"fa fa-thumbs-up pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                            if (data) {
                                return "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>" + approve_btn;
                            } else  {
                                return "<a href=\"{{ route('admin.administrating.requests.found.proceed') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Опрацьовано!\">" +
                                    "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" + approve_btn;
                            }
                        }
                    },
                    { "data": "color" },
                    {
                        "data": "badge",
                        defaultContent: 'Не заповнено',
                        orderable: false,
                    },
                    { "data": "found_address" },
                    { "data": "contact_name" },
                    { "data": "contact_phone" },
                    { "data": "contact_email" },
                    { "data": "created_at" },
                ],
            });

        });
    </script>
@endsection
