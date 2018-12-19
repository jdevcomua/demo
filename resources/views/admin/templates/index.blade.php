@extends('admin.layout.app')

@section('title', 'Templates')
@section('breadcrumbs_title', 'Templates')


@section('content')
    <section id="content" class="animated fadeIn">
        <div class="row">

            <div class="col-md-2 col-sm-3 col-xs-6 mb25">
                <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-block">Створити новий шаблон</a>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6 mb25" style="padding-top: 0;">
                <a href="{{ route('admin.templates.show.fire') }}" class="btn btn-success btn-block">Нова розсилка</a>
            </div>

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Список шаблонів листів</div>
                    </div>
                    <div class="panel-body pn">
                        @if (\Session::has('success'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="fa fa-check pr10"></i>
                                {{ \Session::get('success') }}
                            </div>
                        @endif
                        <table class="table table-striped table-hover display datatable responsive nowrap"
                               id="datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Дії</th>
                                <th>Назва</th>
                                <th>Тема</th>
                                <th>Текст</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="no-search"></th>
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

        <form action="#" method="post" class="hidden" id="remove">
            @csrf
            @method('delete')
        </form>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.templates.data', null, false) }}',
                columns: [
                    { "data": "id", 'width': '4%' },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"/admin/templates/"
                                    + data + "/edit" + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                + "<a href='/admin/templates/' class='delete' data-id=" + data + ">" +
                                "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                "</a>"
                            ;
                            }
                        }
                    },
                    { "data": "name" },
                    { "data": "subject" },
                    {
                        "data": "body",
                        render: function (data, type, row) {
                            if (data) {
                                data = data.length > 200 ? data.slice(0,199) : data;
                                return escapeHtml(data);
                            }
                        },
                    },
                ],
            });

            jQuery(document).on('click','.delete', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you wanna delete this role?')) {
                    var id = jQuery(this).attr('data-id');
                    var form = jQuery('#remove');
                    $(form).attr('action', "{{route('admin.templates.destroy', '')}}"+id);
                    $(form).submit();
                }
            });
            function escapeHtml(text) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };

                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }
        });
    </script>
@endsection