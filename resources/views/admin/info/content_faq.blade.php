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
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх питань</div>
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
                                <span class="glyphicon glyphicon-tasks"></span>Додавання нового питання</div>
                        </div>
                        <form class="form-horizontal" role="form"
                              action="{{ route('admin.info.content.faq.store') }}" method="post">
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
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="answer" class="col-lg-3 control-label">
                                        Відповідь:
                                    </label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="answer" name="answer"
                                                  rows="3" required>{{ old('answer') }}</textarea>
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

        <form action="{{route('admin.info.content.faq.delete')}}" id="destroy" method="post" class="hidden">
            @csrf
            <input type="hidden" name="_method" value="delete">
        </form>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            dataTableInit($('#datatable-faq'), {
                ajax: '{{ route('admin.info.content.faq.data', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data) {
                                return "<a href='#' class='delete' " +
                                    "data-id=" + data + ">" +
                                    "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "question" },
                    { "data": "answer" },
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
