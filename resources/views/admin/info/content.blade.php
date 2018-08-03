@extends('admin.layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/plugins/summernote/summernote.css">
@endsection

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Контент</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="panel">
                <div class="panel-heading">
                    <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
                        <li class="active">
                            <a href="#faq" data-toggle="tab">Питання</a>
                        </li>
                        <li>
                            <a href="#blocks" data-toggle="tab">Контент</a>
                        </li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content pn br-n">
                        <div id="faq" class="tab-pane active">
                            <div class="row">
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
                                    </thead>
                                    <tfoot class="search">
                                    <tr>
                                        <th></th>
                                        <th class="no-search"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-visible" id="spy5">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <span class="glyphicon glyphicon-tasks"></span>Додавання нового питання</div>
                                        </div>
                                        <form class="form-horizontal" role="form"
                                              action="{{ route('admin.info.content.faq.store') }}" method="post">
                                            @csrf
                                            <div class="panel-body">
                                                @if($errors->faq)
                                                    @foreach($errors->faq->all() as $error)
                                                        <div class="alert alert-danger alert-dismissable">
                                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                            <i class="fa fa-remove pr10"></i>
                                                            {{ $error }}
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <div class="form-group">
                                                    <label for="faq-question" class="col-lg-3 control-label">Питання:</label>
                                                    <div class="col-lg-8">
                                                        <div class="bs-component">
                                                            <input type="text" id="faq-question" name="question"
                                                                   class="form-control" value="{{ old('question') }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="faq-answer" class="col-lg-3 control-label">Відповідь:</label>
                                                    <div class="col-lg-8">
                                                        <div class="bs-component">
                                                            <textarea id="faq-answer" class="form-control" name="answer" id="textArea2" rows="3">{{old('answer')}}</textarea>
                                                        </div>
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
                        </div>
                        <div id="blocks" class="tab-pane">
                            <div class="row">
                                @foreach($blocks as $block)
                                    <div class="col-md-6">
                                        <form action="{{route('admin.info.content.block.update', $block->id)}}" method="post">
                                            @csrf
                                            <input type="hidden" name="_method" value="put">
                                            <div class="form-group">
                                                <label for="nickname" class="control-label">Назва блоку</label>
                                                <input type="text" id="nickname" name="nickname" class="form-control"
                                                       value="{{ $block->title}}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Текст блоку
                                                </label>
                                                <textarea name="body" class="summernote">
                                                    {!! $block->body !!}
                                                </textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block">Змінити</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
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
    <script src="/plugins/summernote/summernote.min.js"></script>
    <script src="/js/admin/jquery.dataTables.js"></script>
    <script src="/js/admin/dataTables.tableTools.min.js"></script>
    <script src="/js/admin/dataTables.colReorder.min.js"></script>
    <script src="/js/admin/dataTables.bootstrap.js"></script>
    <script src="/js/admin/dataTables.responsive.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            dataTableInit($('#datatable-faq'), {
                ajax: '{{ route('admin.info.content.faq.data', null, false) }}',
                columns: [
                    { "data": "id" },
                    {
                        "data": "id",
                        defaultContent: '',
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
            var form = jQuery('#destroy');
            var id =  jQuery(this).attr('data-id');
            jQuery(form).attr('action', '{{route('admin.info.content.faq.delete')}}/' +id);
            jQuery(form).submit();
        });
        $('.summernote').summernote({
            height: 255, //set editable area's height
            focus: false, //set focus editable area after Initialize summernote
            oninit: function() {},
            onChange: function(contents, $editable) {},
        });
    </script>
@endsection
