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
                                <span class="glyphicon glyphicon-tasks"></span>Редагування повідомлення
                                <span class="danger pull-right">Ви можете використовувати наспупну змінну: {кількість}</span>
                            </div>
                        </div>
                        <form class="form" role="form"
                              action="{{ route('admin.info.emails.store', $email->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="put">
                            <div class="panel-body">
                                @if($errors->email)
                                    @foreach($errors->email->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_email'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_email') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="subject" class="control-label">
                                        Тема:
                                    </label>
                                        <input type="text" id="subject" name="subject"
                                               class="form-control" value="{{ old('subject') ?? $email->subject }}"
                                               required>
                                </div>
                                <div class="form-group">
                                    <label for="body" class="control-label">
                                        Текст:
                                    </label>
                                    <textarea name="body" class="summernote" style="display: none"
                                              title="About page edit">{!! $email->body !!}</textarea>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button type="submit" class="btn btn-success btn-block">Оновити</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('scripts-end')
    <script src="/js/admin/summernote.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('.summernote').summernote({
                height: 255, //set editable area's height
                focus: false, //set focus editable area after Initialize summernote
                oninit: function() {},
                onChange: function(contents, $editable) {},
            });
        });
    </script>
@endsection

