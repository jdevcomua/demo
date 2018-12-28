@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Загублені тварини</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Загублені тварини</div>
                        </div>
                        <div class="panel-body pn">
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Тварина</th>
                                    <th>Тварину знайдено</th>
                                    <th>Дії</th>
                                    <th>Створено</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="select">
                                        <select>
                                            <option selected value>---</option>
                                            <option value="1">Так</option>
                                            <option value="0">Ні</option>
                                        </select>
                                    </th>
                                    <th class="no-search"></th>
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
                ajax: '{{ route('admin.administrating.requests.lost.data', null, false) }}',
                createdRow: function( row, data, dataIndex) {
                    if(data.processed){
                        $(row).addClass('processed');
                    }
                },
                columns: [
                    { "data": "id"},
                    {
                        "data": "nickname",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                + row.animal_id + "\">" + data +
                                "</a>";
                        }
                    },
                    {
                        "data": "found",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            return data ? 'Так' : 'Ні';
                        }
                    },
                    {
                        "data": "processed",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>";
                            } else  {
                                return "<a href=\"{{ route('admin.administrating.requests.lost.proceed') }}/"
                                    + row.id + "\" data-toggle='tooltip' title=\"Опрацьовано!\">" +
                                    "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                            }
                        }
                    },
                    { "data": "created_at" },
                ],
            });

        });
    </script>
@endsection
