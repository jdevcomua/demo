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
                                    <th>Дата створення</th>
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
                columns: [
                    { "data": "id"},
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                + row.animal_id + "\">" + row.nickname +
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
                    { "data": "created_at" },
                ],
            });

        });
    </script>
@endsection
