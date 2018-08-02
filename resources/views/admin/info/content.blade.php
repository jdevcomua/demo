@extends('admin.layout.app')

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
                                <table class="table table-striped table-hover display datatable responsive nowrap"
                                       id="datatable-faq" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Питання</th>
                                        <th>Відповідь</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="search">
                                    <tr>
                                        <th></th>
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

                                                @if (\Session::has('success_faq'))
                                                    <div class="alert alert-success alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                        <i class="fa fa-check pr10"></i>
                                                        {{ \Session::get('success_faq') }}
                                                    </div>
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
                                <div class="col-md-8">
                                    <p>
                                        {!! \Block::get('this is test block') !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
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
                    { "data": "question" },
                    { "data": "answer" },
                ],
            });
        });
    </script>
@endsection
