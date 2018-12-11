@extends('admin.layout.app')

@section('content')

    <section id="content" class="animated fadeIn fire">

        <div class="row">

            <div class="col-xs-12">
                <form method="POST" action="{{ route('admin.templates.fire') }}">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Нова розсилка
                        </div>
                        <div class="panel-body">
                            @if (\Session::has('success'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success') }}
                                </div>
                            @endif
                                @if (\Session::has('error'))
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('error') }}
                                    </div>
                                @endif
                            <div class="form-group">
                                <label for="dashboard_search">Список користувачів розсилки</label>
                                <select class="selectize" id="dashboard_search" name="users[]" multiple="multiple">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                                <label for="template">Оберіть шаблон</label>
                                <select class="form-control" required required name="template" id="template">
                                    <option value="">Оберіть шаблон</option>
                                    @foreach($templates as $template)
                                        <option value="{{$template->id}}">{{$template->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-success">Розіслати листи</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
    <script defer>
        $('.selectize').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false,
            placeholder     : "Усі корисувачі",
        });
    </script>
@endsection
