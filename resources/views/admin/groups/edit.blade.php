@extends('admin.layout.app')

@section('title', 'Templates')

@section('breadcrumbs_prev')
    <li class="crumb-link">
        <a href="{{route('admin.groups.index')}}">Templates</a>
    </li>
@endsection

@section('breadcrumbs_title', 'Edit group')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit group
                </div>
                <form action="{{route('admin.groups.update', $group->id)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="subject">Name:</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{old('name') ? old('name') : $group->name}}">
                            @if ($errors->has('name'))
                                <span class="help-block" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('users') ? ' has-error' : '' }}">
                            <label for="users">Users:</label>
                            <input type="text" name="users" id="users" value="{{old('users')}}">
                            @if ($errors->has('users'))
                                <span class="help-block" role="alert">
                                    <strong>{{ $errors->first('users') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts-end')
    <script >
        $( document ).ready(function() {
            var $test = $('#users').selectize({
                persist: false,
                valueField: 'id',
                labelField: 'name',
                searchField: ['name'],
                preload: true,
                maxItems: 10,
                sortField: [
                    {field: 'name', direction: 'asc'}
                ],
                create: false,
                load: function (query, callback) {
                    if (!query.length) return callback();
                    $.ajax({
                        url: '{{route('admin.users.search')}}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            query: query
                        },
                        error: function () {
                            callback();
                        },
                        success: function (res) {
                            callback(res.data);
                        }
                    });
                },
                onInitialize: function(){
                    var selectize = this;
                    $.get('{{route('admin.users.search')}}', function( data ) {
                        selectize.addOption(data.data); // This is will add to option
                        var selected_items = [];
                        $.each({!! $group->users()->pluck('users.name', 'users.id') !!}, function( i, obj) {
                            selected_items.push(i);
                        });
                        selectize.setValue(selected_items); //this will set option values as default
                    });
                }
            });
            $(document).on('click', 'div.selectize-input div.item', function(e) {
                var select = $('#users').selectize();
                var selectSizeControl = select[0].selectize;
                var selectedValue = $(this).attr("data-value");
                selectSizeControl.removeItem(selectedValue);
                selectSizeControl.refreshItems();
                selectSizeControl.refreshOptions();
            })
        });
    </script>
@endsection