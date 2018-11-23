@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Довідники</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">
        <div class="row">
            <div class="col-xs-12 pn">
                <div class="col-xs-12 col-md-6 pn">
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Список всіх порід</div>
                            </div>
                            <div class="panel-body pn">
                                @if($errors->breed_rem)
                                    @foreach($errors->breed_rem->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_breed_rem'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_breed_rem') }}
                                    </div>
                                @endif
                                <table class="table table-striped table-hover display datatable responsive nowrap"
                                       id="datatable-breed" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дії</th>
                                        <th>Вид тварини</th>
                                        <th>Назва</th>
                                        <th>FCI</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="no-search"></th>
                                        <th class="select">
                                            <select>
                                                <option selected value>---</option>
                                                @foreach($species as $s)
                                                    <option value="{{$s->name}}">{{$s->name}}</option>
                                                @endforeach
                                            </select>
                                        </th>
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
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Додавання нової породи</div>
                            </div>
                            <form class="form-horizontal" role="form"
                                  action="{{ route('admin.info.directories.store.breed') }}" method="post">
                                @csrf
                                <div class="panel-body">
                                    @if($errors->breed)
                                        @foreach($errors->breed->all() as $error)
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <i class="fa fa-remove pr10"></i>
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif

                                    @if (\Session::has('success_breed'))
                                        <div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-check pr10"></i>
                                            {{ \Session::get('success_breed') }}
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="breed-species" class="col-lg-3 control-label">Вид тварини:</label>
                                        <div class="col-lg-8">
                                            <div class="bs-component">
                                                <select id="breed-species" name="b_species" class="form-control" required>
                                                    @foreach($species as $s)
                                                        <option value="{{$s->id}}" @if(old('b_species') == $s->id) selected @endif>
                                                            {{$s->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="breed-name" class="col-lg-3 control-label">Назва породи:</label>
                                        <div class="col-lg-8">
                                            <div class="bs-component">
                                                <input type="text" id="breed-name" name="b_name"
                                                       class="form-control" value="{{ old('b_name') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="breed-fci" class="col-lg-3 control-label">FCI:</label>
                                        <div class="col-lg-8">
                                            <div class="bs-component">
                                                <input type="text" id="breed-fci" name="b_fci"
                                                       class="form-control" value="{{ old('b_fci') }}">
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
                <div class="col-xs-12 col-md-6 pn">
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Список усіх мастей</div>
                            </div>
                            <div class="panel-body pn">
                                @if($errors->color_rem)
                                    @foreach($errors->color_rem->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_color_rem'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_color_rem') }}
                                    </div>
                                @endif
                                <table class="table table-striped table-hover display datatable responsive nowrap"
                                       id="datatable-color" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дії</th>
                                        <th>Вид тварини</th>
                                        <th>Назва</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="no-search"></th>
                                        <th class="select">
                                            <select>
                                                <option selected value>---</option>
                                                @foreach($species as $s)
                                                    <option value="{{$s->name}}">{{$s->name}}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Додавання нової масті</div>
                            </div>
                            <form class="form-horizontal" role="form"
                                  action="{{ route('admin.info.directories.store.color') }}" method="post">
                                @csrf
                                <div class="panel-body">
                                    @if($errors->color)
                                        @foreach($errors->color->all() as $error)
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <i class="fa fa-remove pr10"></i>
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif

                                    @if (\Session::has('success_color'))
                                        <div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-check pr10"></i>
                                            {{ \Session::get('success_color') }}
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="color-species" class="col-lg-3 control-label">Вид тварини:</label>
                                        <div class="col-lg-8">
                                            <div class="bs-component">
                                                <select id="color-species" name="c_species" class="form-control" required>
                                                    @foreach($species as $s)
                                                        <option value="{{$s->id}}" @if(old('c_species') == $s->id) selected @endif>
                                                            {{$s->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="color-name" class="col-lg-3 control-label">Назва масті:</label>
                                        <div class="col-lg-8">
                                            <div class="bs-component">
                                                <input type="text" id="color-name" name="c_name"
                                                       class="form-control" value="{{ old('c_name') }}" required>
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
            <div class="col-xs-12 pn">
                <div class="col-xs-12 col-md-6 pn">
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Список усіх типів шерсті</div>
                            </div>
                            <div class="panel-body pn">
                                @if($errors->fur_rem)
                                    @foreach($errors->fur_rem->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_fur_rem'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_fur_rem') }}
                                    </div>
                                @endif
                                <table class="table table-striped table-hover display datatable responsive nowrap"
                                       id="datatable-fur" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дії</th>
                                        <th>Вид тварини</th>
                                        <th>Назва</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="no-search"></th>
                                        <th class="select">
                                            <select>
                                                <option selected value>---</option>
                                                @foreach($species as $s)
                                                    <option value="{{$s->name}}">{{$s->name}}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Додавання нового типу шерсті</div>
                            </div>
                            <form class="form-horizontal" role="form"
                                  action="{{ route('admin.info.directories.store.fur') }}" method="post">
                                @csrf
                                <div class="panel-body">
                                    @if($errors->fur)
                                        @foreach($errors->fur->all() as $error)
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <i class="fa fa-remove pr10"></i>
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif

                                    @if (\Session::has('success_fur'))
                                        <div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-check pr10"></i>
                                            {{ \Session::get('success_fur') }}
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="fur-species" class="col-lg-3 control-label">Вид тварини:</label>
                                        <div class="col-lg-8">
                                            <select id="fur-species" name="f_species" class="form-control" required>
                                                @foreach($species as $s)
                                                    <option value="{{$s->id}}" @if(old('f_species') == $s->id) selected @endif>
                                                        {{$s->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fur-name" class="col-lg-3 control-label">Назва шерсті:</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="fur-name" name="f_name"
                                                   class="form-control" value="{{ old('f_name') }}" required>
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
                <div class="col-xs-12 col-md-6 pn">
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Список усіх причин смерті</div>
                            </div>
                            <div class="panel-body pn">
                                @if($errors->cause_of_deaths_rem)
                                    @foreach($errors->cause_of_deaths_rem->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_cause_of_deaths_rem'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_cause_of_deaths_rem') }}
                                    </div>
                                @endif
                                <table class="table table-striped table-hover display datatable responsive nowrap"
                                       id="datatable-cause-of-deaths" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дії</th>
                                        <th>Причина смерті</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="no-search"></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Додавання нової причини смерті</div>
                            </div>
                            <form class="form-horizontal" role="form"
                                  action="{{ route('admin.info.directories.store.cause-of-death') }}" method="post">
                                @csrf
                                <div class="panel-body">
                                    @if($errors->cause_of_deaths)
                                        @foreach($errors->cause_of_deaths->all() as $error)
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <i class="fa fa-remove pr10"></i>
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif

                                    @if (\Session::has('success_cause_of_deaths'))
                                        <div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-check pr10"></i>
                                            {{ \Session::get('success_cause_of_deaths') }}
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="fur-name" class="col-lg-3 control-label">Причина смерті:</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="fur-name" name="d_name"
                                                   class="form-control" value="{{ old('d_name') }}" required>
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

                <div class="col-xs-12 col-md-12 pn">
                    <div class="col-xs-12">
                        <div class="panel panel-visible" id="spy5">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <span class="glyphicon glyphicon-tasks"></span>Список усіх закладів та установ</div>
                            </div>
                            <div class="panel-body pn">
                                @if($errors->organization_rem)
                                    @foreach($errors->organization_rem->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_organization_rem'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_organization_rem') }}
                                    </div>
                                @endif
                                <table class="table table-striped table-hover display datatable responsive nowrap"
                                       id="datatable-organizations" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дії</th>
                                        <th>Назва</th>
                                        <th>ПІБ представника</th>
                                        <th>Адреса</th>
                                        <th>Контактні дані</th>
                                        <th>Реквізити</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th class="no-search"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            @permission('edit-organizations')
                            <div class="panel-footer text-right">
                                <a href="{{route('admin.info.directories.create.organization')}}" class="btn btn-default ph25">Додати</a>
                            </div>
                            @endpermission
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal fade" id="modalFur" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Змінити тип шерсті</h4>
                    </div>
                    <form action="{{ route('admin.info.directories.update.fur') }}" class="form-horizontal" id="change-fur" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="furId">
                            <div class="form-group">
                                <label for="fur-species" class="col-lg-3 control-label">Вид тварини:</label>
                                <div class="col-lg-8">
                                    <select id="fur-species-modal" name="species_id" class="form-control" required>
                                        @foreach($species as $s)
                                            <option value="{{$s->id}}" @if(old('f_species') == $s->id) selected @endif>
                                                {{$s->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fur-name" class="col-lg-3 control-label">Назва шерсті:</label>
                                <div class="col-lg-8">
                                    <input type="text" id="fur-name-modal" name="name"
                                           class="form-control" value="{{ old('f_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning pull-right">Змінити</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalColor" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Змінити тип шерсті</h4>
                    </div>
                    <form action="{{ route('admin.info.directories.update.color') }}" class="form-horizontal" id="change-fur" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="colorId">
                            <div class="form-group">
                                <label for="color-species" class="col-lg-3 control-label">Вид тварини:</label>
                                <div class="col-lg-8">
                                    <select id="color-species-modal" name="species_id" class="form-control" required>
                                        @foreach($species as $s)
                                            <option value="{{$s->id}}" @if(old('species_id') == $s->id) selected @endif>
                                                {{$s->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="color-name" class="col-lg-3 control-label">Назва шерсті:</label>
                                <div class="col-lg-8">
                                    <input type="text" id="color-name-modal" name="name"
                                           class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning pull-right">Змінити</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalBreed" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Змінити тип шерсті</h4>
                    </div>
                    <form action="{{ route('admin.info.directories.update.breed') }}" class="form-horizontal" id="change-breed" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="breedId">
                            <div class="form-group">
                                <label for="breed-species" class="col-lg-3 control-label">Вид тварини:</label>
                                <div class="col-lg-8">
                                    <select id="breed-species-modal" name="species_id" class="form-control" required>
                                        @foreach($species as $s)
                                            <option value="{{$s->id}}" @if(old('species_id') == $s->id) selected @endif>
                                                {{$s->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="breed-name" class="col-lg-3 control-label">Назва шерсті:</label>
                                <div class="col-lg-8">
                                    <input type="text" id="breed-name-modal" name="name"
                                           class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="breed-fci" class="col-lg-3 control-label">FCI</label>
                                <div class="col-lg-8">
                                    <input type="text" id="breed-fci-modal" name="fci"
                                           class="form-control" value="{{ old('fci') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning pull-right">Змінити</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalCauseOfDeath" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Змінити причину смерті</h4>
                    </div>
                    <form action="{{ route('admin.info.directories.update.cause-of-death') }}" class="form-horizontal" id="change-causeOfDeath" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="causeOfDeathId">
                            <div class="form-group">
                                <label for="causeOfDeath-name" class="col-lg-3 control-label">Причина смерті:</label>
                                <div class="col-lg-8">
                                    <input type="text" id="causeOfDeath-name-modal" name="name"
                                           class="form-control" value="{{ old('d_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning pull-right">Змінити</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable-breed'), {
                ajax: '{{ route('admin.info.directories.data.breed', null, false) }}',
                columns: [
                    { "data": "id", 'width': '7%' },
                    {
                        "data": "id", 'width': '7%',
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"\" data-id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil update-breed pr10\" aria-hidden=\"true\" data-toggle='modal'" +
                                    " data-target=\"#modalBreed\" data-fci='"+ row.fci+ "'></i>" +
                                    "<a href=\"{{ route('admin.info.directories.remove.breed') }}?id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "species_name", 'width': '20%' },
                    { "data": "name" },
                    { "data": "fci", 'width': '10%' },
                ],
            });

            dataTableInit($('#datatable-color'), {
                ajax: '{{ route('admin.info.directories.data.color', null, false) }}',
                columns: [
                    { "data": "id", 'width': '10%' },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"\" data-id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil update-color pr10\" aria-hidden=\"true\" data-toggle='modal'" +
                                    " data-target=\"#modalColor\"></i>" +
                                    "<a href=\"{{ route('admin.info.directories.remove.color') }}?id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "species_name" },
                    { "data": "name" },
                ],
            });

            dataTableInit($('#datatable-fur'), {
                ajax: '{{ route('admin.info.directories.data.fur', null, false) }}',
                columns: [
                    { "data": "id", 'width': '10%' },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"\" data-id="
                                + data + "\">" +
                                "<i class=\"fa fa-pencil update-fur pr10\" aria-hidden=\"true\" data-toggle='modal'" +
                                " data-target=\"#modalFur\"></i>" +
                                "</a>" +
                                "<a href=\"{{ route('admin.info.directories.remove.fur') }}?id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "species_name" },
                    { "data": "name" },
                ],
            });

            dataTableInit($('#datatable-cause-of-deaths'), {
                ajax: '{{ route('admin.info.directories.data.cause-of-death', null, false) }}',
                columns: [
                    { "data": "id", 'width': '10%' },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"\" data-id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil update-causeOfDeath pr10\" aria-hidden=\"true\" data-toggle='modal'" +
                                    " data-target=\"#modalCauseOfDeath\"></i>" +
                                    "</a>" +
                                    "<a href=\"{{ route('admin.info.directories.remove.cause-of-death') }}?id="
                                    + data + "\">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>";
                            }
                        }
                    },
                    { "data": "name" },
                ],
            });
            dataTableInit($('#datatable-organizations'), {
                ajax: '{{ route('admin.info.directories.data.organization', null, false) }}',
                columns: [
                    { "data": "id", 'width': '10%' },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                var editShowIcon = 'fa-eye';
                                @permission('edit-organizations')
                                editShowIcon = 'fa-pencil';
                                @endpermission
                                var content = "<a href=\"{{ route('admin.info.directories.edit.organization', '') }}" + "/" + data + "\"><i class=\"fa " + editShowIcon +  " pr10\" aria-hidden=\"true\"> </i></a>";
                                @permission('edit-organizations')
                                content += "<a href=\"{{ route('admin.info.directories.remove.organization') }}?id=" + data + "\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>";
                                @endpermission
                                console.log(content);
                                return content;

                            }
                        }
                    },
                    { "data": "name" },
                    { "data": "chief_full_name" },
                    { "data": "address" },
                    { "data": "contact_info" },
                    { "data": "requisites" },
                ],
            });
        });
        $(document).on('click', '.fa-trash', function(e) {
            if (!confirm('Ви впевнені що хочете видалити запис?')) {
                e.preventDefault();
            }
        });
        $(document).on('click', '.fa-pencil.update-fur', function(e) {
                e.preventDefault();
                var text = $(this).parents('td').next().next().text();
                var id = $(this).parent().attr('data-id');
                $('#furId').val(id);
                if ($(this).parents('td').next().text() === 'Кiт')  {
                    $('#fur-species-modal').val(2);
                } else {
                    $('#fur-species-modal').val(1);
                }
                $('#fur-name-modal').val(text);
        });

        $(document).on('click', '.fa-pencil.update-causeOfDeath', function(e) {
            e.preventDefault();
            var text = $(this).parents('td').next().text();
            var id = $(this).parent().attr('data-id');
            $('#causeOfDeathId').val(id);
            $('#causeOfDeath-name-modal').val(text);
        });

        $(document).on('click', '.fa-pencil.update-color', function(e) {
                e.preventDefault();
                var text = $(this).parents('td').next().next().text();
                var id = $(this).parent().attr('data-id');
                $('#colorId').val(id);
                if ($(this).parents('td').next().text() === 'Кiт')  {
                    $('#color-species-modal').val(2);
                } else {
                    $('#color-species-modal').val(1);
                }
                $('#color-name-modal').val(text);
        });
        $(document).on('click', '.fa-pencil.update-breed', function(e) {
            e.preventDefault();
            var text = $(this).parents('td').next().next().text();
            var id = $(this).parent().attr('data-id');
            var fci = $(this).attr('data-fci');
            console.log(fci);
            $('#breedId').val(id);
            if ($(this).parents('td').next().text() === 'Кiт')  {
                $('#breed-species-modal').val(2);
            } else {
                $('#breed-species-modal').val(1);
            }
            $('#breed-name-modal').val(text);
            $('#breed-fci-modal').val(fci);
        });
    </script>
@endsection