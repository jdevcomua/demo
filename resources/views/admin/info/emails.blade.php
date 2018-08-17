@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Повідомлення</a>
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
                            <span class="glyphicon glyphicon-envelope"></span>Повідомлення
                        </div>
                    </div>
                    <div class="panel-body pn">
                        @if (\Session::has('success_emails'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="fa fa-check pr10"></i>
                                {{ \Session::get('success_emails') }}
                            </div>
                        @endif

                        <table class="table table-striped table-hover display datatable responsive nowrap"
                               id="datatable-emails" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Дії</th>
                                <th>Назва</th>
                                <th>Тема</th>
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
                            </tr>
                            </tfoot>
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
            dataTableInit($('#datatable-emails'), {
                ajax: '{{ route('admin.info.emails.data', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function (data, type, row) {
                            if (data) {
                                return "<a href='{{route('admin.info.emails.edit')}}/" + data +"' class='edit'>" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "title" },
                    { "data": "subject" },
                    { "data": "body",
                        defaultContent: '',
                        render: function (data, type, row) {
                            if (data) {
                                return data.substring(0, 60) + '...';
                            }
                        }},
                ],
            });
        });

        jQuery(document).on('click','.delete', function() {
            if (confirm('Ви впевнені що хочете видалити питання?')) {
                var form = jQuery('#destroy');
                var id = jQuery(this).attr('data-id');
                jQuery(form).attr('action', '{{route('admin.info.content.faq.delete')}}/' + id);
                jQuery(form).submit();
            }
        });
    </script>
@endsection
