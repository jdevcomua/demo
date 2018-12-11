<div class="form-group {{ $errors->has('group') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="subject">Group:</label>
    <div class="col-sm-10">
        <select name="group" class="form-control">
            <option value="all">All users</option>
            @foreach($groups as $group)
                <option value="{{$group->id}}"
                        @if(isset($mailing) && ($mailing->group_id === $group->id)) selected @endif
                >{{$group->name}}</option>
            @endforeach
        </select>
        @if ($errors->has('group'))
            <span class="help-block" role="alert">
                <strong>{{ $errors->first('group') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('email_template_id') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="email_template_id">Template:</label>
    <div class="col-sm-10">
        <select class="form-control" name="email_template_id">
            @foreach($templates as $template)
                <option value="{{$template->id}}"
                        @if(isset($mailing) && ($mailing->email_template_id === $template->id)) selected @endif
                >{{$template->name}}</option>
            @endforeach
        </select>
        @if ($errors->has('email_template_id'))
            <span class="help-block" role="alert">
                <strong>{{ $errors->first('email_template_id') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="type">Type:</label>
    <div class="col-sm-10">
        <select class="form-control" name="type" id="type">
            <option>Choose a type</option>
            @foreach(\App\Models\MailingConfig::getMailingTypes() as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
            @endforeach
        </select>
        @if ($errors->has('type'))
            <span class="help-block" role="alert">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>

<div id="period" class="period">

    <div class="form-group {{ $errors->has('period_type') ? ' has-error' : '' }}">
        <label class="col-sm-2 control-label" for="period_type">Period Type:</label>
        <div class="col-sm-10">
            <select class="form-control" name="period_type">
                <option value="">Choose mailing type</option>
                @foreach(\App\Models\MailingConfig::getPeriodTypes() as $k => $v)
                    <option value="{{$k}}"
                        @if(isset($mailing) && ($mailing->period_type === $k)) selected @endif
                    >{{$v}}</option>
                @endforeach
            </select>
            @if ($errors->has('period_type'))
                <span class="help-block" role="alert">
                    <strong>{{ $errors->first('period_type') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('period') ? ' has-error' : '' }}">
        <label for="period" class="col-sm-2 control-label">Period:</label>
        <div class="col-sm-10">
            <input type="number" name="period" class="form-control " id="period" value="{{old('period') ? old('period') : (isset($mailing) ? $mailing->period : '')}}">
            @if ($errors->has('period'))
                <span class="help-block" role="alert">
                    <strong>{{ $errors->first('period') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div id="dates" class="dates">
    <div class="form-group {{ $errors->has('dates') ? ' has-error' : '' }}">
        <label class="col-sm-2 control-label" for="dates">Dates:</label>
        <div class="col-sm-10">
            <input type="text" name="dates" class="form-control date" value="{{old('dates') ? old('dates') : (isset($mailing) ? $mailing->dates : '')}}">
            @if ($errors->has('dates'))
                <span class="help-block" role="alert">
                    <strong>{{ $errors->first('dates') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="custom-control custom-checkbox">
    <label class="control-label col-sm-2" for="customCheck1">Is Active</label>
    <input type="checkbox" name="is_active" class="custom-control-input mt-1" id="customCheck1" @if(isset($mailing) && $mailing->is_active) checked @endif>
</div>

<br>

<div class="form-group {{ $errors->has('priority') ? ' has-error' : '' }}">
    <label class="col-sm-2 control-label" for="priority">Period Type:</label>
    <div class="col-sm-10">
        <select class="form-control" name="priority">
            <option value="">Choose priority</option>
            @foreach(\App\Models\MailingConfig::getPriorities() as $k => $v)
                <option value="{{$k}}"
                        @if(isset($mailing) && ($mailing->priority === $k)) selected @endif
                >{{$v}}</option>
            @endforeach
        </select>
        @if ($errors->has('priority'))
            <span class="help-block" role="alert">
                    <strong>{{ $errors->first('priority') }}</strong>
                </span>
        @endif
    </div>
</div>