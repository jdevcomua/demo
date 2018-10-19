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

                                <div class="form-group" id="subjectText" style="display: none">
                                    <label for="subject" class="control-label">
                                        Тема:
                                    </label>
                                    <input type="text" id="subject" name="subject"
                                           class="form-control" value="{{ old('subject') ?? $notification->subject }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Текст:
                                    </label>
                                    <textarea name="body" id="bodyText" rows="3"
                                              style="display: none">{!! old('body') ?? $notification->body !!}</textarea>
                                    <textarea name="bodyHtml" class="summernote" style="display: none" id="bodyTextHtml"
                                              title="About page edit">{!! old('bodyHtml') ?? $notification->body !!}</textarea>
                                </div>
                            </div>

                            <div class="col-md-3">
                                @if(!$notification->isSystem())
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
                                @endif

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
                                <button class="btn btn-primary ml20">Надіслати всім користувачам!</button>
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
        var notifyWithHtmlBody = @json(\App\Models\NotificationTemplate::getTypesWithHtmlBody());
        var notifyWithTitle = @json(\App\Models\NotificationTemplate::getTypesWithTitle());

        jQuery(document).ready(function() {

            $('.summernote').summernote({
                height: 255, //set editable area's height
                focus: false, //set focus editable area after Initialize summernote
                oninit: function() {},
                onChange: function(contents, $editable) {},
            });

            $('input[type=radio][name=type]').change(updateTextBoxes);
            updateTextBoxes();

            function updateTextBoxes() {
                var elem = $('input[type=radio][name=type]:checked');

                if (notifyWithHtmlBody.indexOf(parseInt(elem.val())) !== -1) {
                    $('textarea#bodyText').hide();
                    $('.note-editor').show();
                } else {
                    $('.note-editor').hide();
                    $('textarea#bodyText').show();
                }

                if (notifyWithTitle.indexOf(parseInt(elem.val())) !== -1) {
                    $('#subjectText').show();
                } else {
                    $('#subjectText').hide();
                }
            }
        });
    </script>
@endsection

