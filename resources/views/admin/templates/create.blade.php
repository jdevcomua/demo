@extends('admin.layout.app')

@section('title', 'Templates')

@section('breadcrumbs_title', 'Create new template')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Ствоити новий шаблон
                </div>
                <form action="{{route('admin.templates.store')}}" method="post">
                    @csrf
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="subject">Назва:</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}">
                            @if ($errors->has('name'))
                                <span class="help-block" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
                            <label for="subject">Тема:</label>
                            <input type="text" name="subject" class="form-control " id="subject" value="{{old('subject')}}">
                            @if ($errors->has('subject'))
                                <span class="help-block" role="alert">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('body') ? ' has-error' : '' }}">
                            <label for="subject">Текст:</label>
                            <textarea name="body" id="body" class="form-control summernote" cols="30" rows="10">{{old('body')}}</textarea>
                            @if ($errors->has('body'))
                                <span class="help-block" role="alert">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-success">Створити</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
