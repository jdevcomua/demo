@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Перегляд ветеринарного заходу</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Перегляд заходу</div>
                    </div>
                    <form class="form-horizontal" role="form" enctype="multipart/form-data"
                          action="" method="post">
                        @csrf
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Ветеринарний захід</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animal_vet_measure->veterinaryMeasure->name}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Тварина</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        <a href="{{route('admin.db.animals.edit', $animal_vet_measure->animal->id)}}">
                                            {{ $animal_vet_measure->animal->nickname}}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Дата проведення</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{\App\Helpers\Date::getlocalizedDate($animal_vet_measure->date)}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Відомості</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animal_vet_measure->description ?? 'Відсутні'}}
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col-lg-3 control-label">Ким проведено</label>
                                <div class="col-lg-8">
                                    <p class="form-control custom-field">
                                        {{ $animal_vet_measure->made_by}}
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
                        @if(count($animal_vet_measure->files))
                            <form role="form" enctype="multipart/form-data" id="upload-form"
                                  action="" method="post">
                                @csrf
                                <div id="files-container">
                                    @foreach($animal_vet_measure->files as $document)
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



