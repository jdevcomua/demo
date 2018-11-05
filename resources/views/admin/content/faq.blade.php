@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Часті питання</span>
        </div>
    </header>
    <!-- End: Topbar -->
    <section id="content" class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Список всіх питань
                        </div>
                    </div>
                    <div class="panel-body pn">
                        @if (\Session::has('success_faq'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="fa fa-check pr10"></i>
                                {{ \Session::get('success_faq') }}
                            </div>
                        @endif

                        <table class="table table-striped table-hover display datatable responsive nowrap"
                               id="datatable-faq" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Дії</th>
                                <th>Питання</th>
                                <th>Відповідь</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="no-search"></th>
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

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Додавання нового питання
                        </div>
                    </div>
                    <form class="form-horizontal" role="form"
                          action="{{ route('admin.content.faq.store') }}" method="post">
                        @csrf
                        <div class="panel-body">
                            @if($errors->new_faq)
                                @foreach($errors->new_faq->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_new_faq'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_new_faq') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="question" class="col-lg-3 control-label">
                                    Питання:
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" id="question" name="question"
                                           class="form-control" value="{{ old('question') }}"
                                           autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="answer" class="col-lg-3 control-label">
                                    Відповідь:
                                </label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" id="answer" name="answer"
                                              rows="6" required>{{ old('answer') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-default ph25">Додати</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="modal-edit" class="popup-basic admin-form mfp-with-anim mfp-hide">
            <div class="panel panel-visible" id="spy5">
                <div class="panel-heading">
                    <div class="panel-title">
                        <span class="glyphicon glyphicon-tasks"></span>Редагування питання
                    </div>
                </div>
                <form class="form-horizontal" role="form" id="modal-form-edit" method="post">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="edit-question" class="col-lg-3 control-label">
                                Питання:
                            </label>
                            <div class="col-lg-8">
                                <input type="text" id="edit-question" name="question"
                                       class="form-control" autocomplete="off"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit-answer" class="col-lg-3 control-label">
                                Відповідь:
                            </label>
                            <div class="col-lg-8">
                                    <textarea class="form-control" id="edit-answer" name="answer"
                                              rows="6" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-default ph25">Зберегти</button>
                    </div>
                </form>
            </div>
        </div>

        <form action="#" id="destroy" method="post" class="hidden">
            @csrf
            <input type="hidden" name="_method" value="delete">
        </form>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        $(document).ready(function() {
            const dTable = dataTableInit($('#datatable-faq'), {
                ajax: '{{ route('admin.content.faq.data', null, false) }}',
                columns: [
                    { "data": "order" },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data) {
                                return "<a href='#' class='edit' " +
                                    "data-id=" + data + ">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href='#' class='delete' " +
                                    "data-id=" + data + ">" +
                                    "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"+
                                    "<a href=\"{{ route('admin.content.faq.move-up') }}/"
                                    + data + "\" title=\"Move Up\">" +
                                    "<i class=\"fa fa-arrow-up pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href=\"{{ route('admin.content.faq.move-down') }}/"
                                    + data + "\" title=\"Move Down\">" +
                                    "<i class=\"fa fa-arrow-down pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "question" },
                    { "data": "answer" },
                ],
            });

            popupEdit.init('{{ route('admin.content.faq.show', 'XXX') }}',
                '{{ route('admin.content.faq.update', 'XXX') }}',
                function (response) {
                    $('#edit-question').val(response.data.question);
                    $('#edit-answer').val(response.data.answer);
                },
                function () {
                    dTable.ajax.reload();
                }
            );
        });

        $(document).on('click','.delete', function() {
            if (confirm('Ви впевнені що хочете видалити питання?')) {
                var form = $('#destroy');
                var id = $(this).attr('data-id');
                $(form).attr('action', '{{route('admin.content.faq.delete')}}/' + id);
                $(form).submit();
            }
        });
    </script>
@endsection
