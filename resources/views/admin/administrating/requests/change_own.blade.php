@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Зміна власника</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список запитів на зміну власника</div>
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
                                    <th>Тварина</th>
                                    <th>ПІБ нового власника</th>
                                    <th># Паспорта</th>
                                    <th>Контактний номер тел.</th>
                                    <th>Дії</th>
                                    <th>Створено</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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

        <form action="#" method="post" class="hidden" id="remove">
            @csrf
        </form>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.administrating.requests.change-own.data') }}',
                order: [[ 6, "desc" ]], // def. sort by created_at DESC
                createdRow: function( row, data, dataIndex) {
                    if(data.processed){
                        $(row).addClass('processed');
                    }
                },
                columns: [
                    { "data": "id" },
                    { "data": "nickname",
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                    + row.animal_id + "\">" + data +
                                    "</a>";
                            }
                            return '';
                        }},
                    { "data": "full_name" },
                    { "data": "passport" },
                    { "data": "contact_phone" },
                    {
                        "data": "processed",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<i class=\"fa fa-check pr10\" aria-hidden=\"true\"></i>";
                            } else  {
                                return "<a href=\"{{ route('admin.administrating.requests.change-own.proceed') }}/"
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
