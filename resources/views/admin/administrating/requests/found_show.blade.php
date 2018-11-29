@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Перегляд загубленої тварини</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="fa fa-remove pr10"></i>
                    {{ $error }}
                </div>
            @endforeach
        @endif
            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Картка загубленої тварини</div>
                        </div>
                        <form class="form-horizontal" role="form" action="" method="post">
                            <div class="panel-body">
                                @if($errors->animal)
                                    @foreach($errors->animal->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_animal'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_animal') }}
                                    </div>
                                @endif


                                <div class="form-group select-gen">
                                    <label for="species" class="col-lg-3 control-label">Вид</label>
                                    <div class="col-lg-8">
                                        <input type="text"  name="species" class="form-control"
                                               value="{{ $animal->species->name }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group select">
                                    <label for="breed" class="col-lg-3 control-label">Порода</label>
                                    <div class="col-lg-8">
                                        <input type="text"  name="breed" class="form-control"
                                               value="{{ $animal->breed->name }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group select">
                                    <label for="color" class="col-lg-3 control-label">Масть</label>
                                    <div class="col-lg-8">
                                        <input type="text"  name="color" class="form-control"
                                               value="{{ $animal->color->name }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="badge" class="col-lg-3 control-label">Номер жетону</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="badge" name="badge" class="form-control"
                                               value="{{ $animal->badge }}" readonly>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label for="badge" class="col-lg-3 control-label">Адреса де знайшли</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="found_address" class="form-control"
                                                   value="{{ $animal->found_address }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="badge" class="col-lg-3 control-label">Ім’я</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="contact_name" class="form-control"
                                                   value="{{ $animal->contact_name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="badge" class="col-lg-3 control-label">Телефон</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="contact_phone" class="form-control"
                                                   value="{{ $animal->contact_phone }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="badge" class="col-lg-3 control-label">Email</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="contact_email" class="form-control"
                                                   value="{{ $animal->contact_email }}" readonly>
                                        </div>
                                    </div>

                                <div class="form-group">
                                    <label for="created_at" class="col-lg-3 control-label">Створено</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="created_at" name="created_at" class="form-control"
                                               value="{{ old('created_at') ?? ($animal->created_at ? $animal->created_at->format('d-m-Y H:i') : '')}}" disabled>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label for="updated_at" class="col-lg-3 control-label">Оновлено</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="updated_at" name="updated_at" class="form-control"
                                                   value="{{ old('updated_at') ?? ($animal->updated_at ? $animal->updated_at->format('d-m-Y H:i') : '')}}" disabled>
                                        </div>
                                    </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Затвердження</div>
                        </div>
                        <form class="form-horizontal">
                            <div class="panel-body">
                                @if($errors->animal_approve)
                                    @foreach($errors->animal_approve->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_animal_approve'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_animal_approve') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Статус:</label>
                                    <div class="col-xs-8">
                                        @if($animal->approved)
                                            <label class="control-label text-success">Затвержено</label>
                                        @else
                                            <label class="control-label text-danger">Не затвержено</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                                <div class="panel-footer text-right">
                                    @if($animal->approved)
                                        <a href="{{ route('admin.administrating.requests.found.disapprove', $animal->id) }}"
                                           class="btn btn-danger ph25">Відмінити затвердження</a>
                                    @else
                                        <a href="{{ route('admin.administrating.requests.found.approve', $animal->id) }}"
                                           class="btn btn-success ph25">Затвердити</a>
                                    @endif
                                </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-visible" id="spy6">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Опрацювання</div>
                        </div>
                        <form class="form-horizontal">
                            <div class="panel-body">
                                @if($errors->animal_processed)
                                    @foreach($errors->animal_processed->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-remove pr10"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                @if (\Session::has('success_animal_processed'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-check pr10"></i>
                                        {{ \Session::get('success_animal_processed') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Статус:</label>
                                    <div class="col-xs-8">
                                        @if($animal->processed)
                                            <label class="control-label text-success">Опрацьовано</label>
                                        @else
                                            <label class="control-label text-danger">Не опрацьовано</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                @if(!$animal->processed)
                                    <a href="{{ route('admin.administrating.requests.found.proceed', $animal->id) }}"
                                       class="btn btn-danger ph25">Опрацьовано</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Фото знайденої тварини</div>
                        </div>
                        <div class="panel-body pn">
                            @if($errors->animal_files)
                                @foreach($errors->animal_files->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_animal_files'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_animal_files') }}
                                </div>
                            @endif
                            <form role="form" enctype="multipart/form-data" id="upload-form"
                                  action="{{ route('admin.db.animals.upload-file', $animal->id) }}" method="post">
                                @csrf
                                <div id="files-container">
                                    @foreach($animal->images as $image)
                                        <div class="file-item">
                                            <a href="/{{ $image->path }}">
                                                <div class="file-preview" style="background-image: url('/{{ $image->path }}');"></div>
                                                <div class="file-name">{{ $image->filename }}.{{ $image->fileextension }}</div>
                                            </a>
                                        </div>
                                    @endforeach


                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
    </section>
@endsection
