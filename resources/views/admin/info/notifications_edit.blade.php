@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Нотифікації</span>
        </div>
    </header>
    <!-- End: Topbar -->
    <section id="content" class="animated fadeIn">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Редагування нотифікації
                        </div>
                    </div>
                    <form class="form" role="form"
                          action="{{ route('admin.info.notifications.update', $notification->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="panel-body">
                            @if($errors->notification)
                                @foreach($errors->notification->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_notification'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_notification') }}
                                </div>
                            @endif

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="name" class="control-label">
                                        Назва:
                                    </label>
                                    <input type="text" id="name" name="name"
                                           class="form-control" value="{{ old('name') ?? $notification->name }}"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="subject" class="control-label">
                                        Тип нотифікації:
                                    </label>

                                    @if($notification->isSystem())
                                        <div class="radio-custom mb5">
                                            <input type="radio" id="radio" checked disabled>
                                            <label for="radio">
                                                {{ \App\Models\NotificationTemplate::getTypes()[$notification->type] .
                                                ' (' . \App\Models\NotificationTemplate::getDescription()[$notification->type] . ')' }}
                                            </label>
                                        </div>
                                    @else
                                        @foreach(\App\Models\NotificationTemplate::getTypes(false) as $id => $type)
                                            <div class="radio-custom mb5">
                                                <input type="radio" id="radio{{ $id }}" name="type" value="{{ $id }}"
                                                       @if($notification->type == $id) checked @endif>
                                                <label for="radio{{ $id }}">
                                                    {{ $type . ' (' . \App\Models\NotificationTemplate::getDescription()[$id] . ')' }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="subject" class="control-label">
                                        Тема:
                                    </label>
                                    <input type="text" id="subject" name="subject"
                                           class="form-control" value="{{ old('subject') ?? $notification->subject }}">
                                </div>
                                <div class="form-group">
                                    <label for="body" class="control-label">
                                        Текст:
                                    </label>
                                    <textarea name="body" class="summernote" style="display: none"
                                              title="About page edit">{!! old('body') ?? $notification->body !!}</textarea>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label mb15">
                                        Події що викликають нотифікацію:
                                    </label>
                                    @foreach(app('rha_events') as $name => $class)
                                        <div class="checkbox-custom mb5">
                                            <input type="checkbox" @if($notification->hasEvent($class)) checked @endif
                                                id="event{{ $loop->iteration }}" name="events[]" value="{{ $class }}">
                                            <label for="event{{ $loop->iteration }}">{{ $name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <label class="control-label mb15">
                                        Стан нотифікації:
                                    </label>
                                    <div class="checkbox-custom checkbox-primarya mb5">
                                        <input type="checkbox" @if($notification->active) checked @endif id="active" name="active">
                                        <label for="active">Активовано</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-success">Зберегти</button>
                            @if(!$notification->isSystem())
                                <button class="btn btn-primary">Надіслати всім користувачам!</button>
                            @endif
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

