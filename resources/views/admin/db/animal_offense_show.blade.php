@extends('admin.layout.app')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Перегляд правопорушення</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Перегляд правопорушення</div>
                    </div>
                    <form class="form-horizontal" role="form" enctype="multipart/form-data"
                          action="" method="post">
                        @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Тварина</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        <a href="{{route('admin.db.animals.edit', $animalOffense->animal->id)}}">
                                            {{ $animalOffense->animal->nickname}}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Дата правопорушення</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{\App\Helpers\Date::getlocalizedDate($animalOffense->date)}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Вид правопорушення</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animalOffense->offense->name}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Відомості</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animalOffense->description ?? 'Відсутні'}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Наявність укусу</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animalOffense->description ? 'Так' : 'Ні'}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Належність правопорушення</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animalOffense->offenseAffiliation->name}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Дата протоколу</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{\App\Helpers\Date::getlocalizedDate($animalOffense->protocol_date)}}

                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Номер протоколу</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{$animalOffense->protocol_number}}

                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Ким зафіксовано</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animalOffense->made_by}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Файли</div>
                    </div>
                    <div class="panel-body pn">
                        @if(count($animalOffense->files))
                            <form role="form" enctype="multipart/form-data" id="upload-form"
                                  action="" method="post">
                                @csrf
                                <div id="files-container">
                                    @foreach($animalOffense->files as $document)
                                        <div class="file-item doc">
                                            <a href="/{{ $document->path }}">
                                                <div class="file-preview" style="background-image: url('/{{$document->isImage() ? $document->path : 'img/file.png'}}');"></div>
                                                <div class="file-name">{{ $document->filename }}.{{ $document->fileextension }}</div>
                                            </a>
                                        </div>
                                    @endforeach

                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                    <div class="file-item gap"></div>
                                </div>
                            </form>
                        @else
                        <h3 class="text-center" style="margin-bottom: 30px;">Відсутні</h3>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
@endsection



