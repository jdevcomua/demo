@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Редагування закладу</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Редагування закладу</div>
                    </div>
                    <form class="form-horizontal" role="form" enctype="multipart/form-data"
                          action="{{ route('admin.info.directories.update.organization', $organization->id) }}" method="post">
                        @csrf
                        <div class="panel-body">
                            @if($errors->organization)
                                @foreach($errors->organization->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="fa fa-remove pr10"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            @if (\Session::has('success_organization'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_organization') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="nickname" class="col-lg-3 control-label">Назва <span class="required-field">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" id="name" name="name" class="form-control"
                                           value="{{ $organization->name }}" required {{$inputsDisabled}}>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nickname" class="col-lg-3 control-label">Адреса <span class="required-field">*</span></label>
                                <div class="col-lg-8">
                                    <textarea name="address" class="form-control" id="address" cols="20" rows="2" required {{$inputsDisabled}}>{{ $organization->address }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nickname" class="col-lg-3 control-label">ПІБ представника <span class="required-field">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" id="chief_full_name" name="chief_full_name" class="form-control"
                                           value="{{ $organization->chief_full_name }}" required {{$inputsDisabled}}>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nickname" class="col-lg-3 control-label">Контактні дані <span class="required-field">*</span></label>
                                <div class="col-lg-8">
                                    <textarea name="contact_info" class="form-control" id="contact_info" cols="20" rows="5" required {{$inputsDisabled}}>{{ $organization->contact_info }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nickname" class="col-lg-3 control-label">Реквізити <span class="required-field">*</span></label>
                                <div class="col-lg-8">
                                    <textarea name="requisites" class="form-control" id="requisites" cols="20" rows="5" required {{$inputsDisabled}}>{{ $organization->requisites }}</textarea>
                                </div>
                            </div>
                        </div>
                        @permission('edit-organizations')
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-default ph25">Зберегти</button>
                        </div>
                        @endpermission
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Документи закладу</div>
                    </div>
                    <div class="panel-body pn">
                        @if($errors->organization_files)
                            @foreach($errors->organization_files->all() as $error)
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-remove pr10"></i>
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif

                        @if (\Session::has('success_organization_files'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="fa fa-check pr10"></i>
                                {{ \Session::get('success_organization_files') }}
                            </div>
                        @endif
                        <span class="help-block mt10 ph10">
                                Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf та не більше ніж 10Mb
                            </span>
                        <form role="form" enctype="multipart/form-data" id="upload-form"
                              action="{{ route('admin.info.directories.organization.upload-file', $organization->id) }}" method="post">
                            @csrf
                            <div id="files-container">
                                @foreach($organization->files as $document)
                                    <div class="file-item doc">
                                        <a href="/{{ $document->path }}">
                                            <div class="file-preview" style="background-image: url('/{{$document->isImage() ? $document->path : 'img/file.png'}}');"></div>
                                            <div class="file-name">{{ $document->filename }}.{{ $document->fileextension }}</div>
                                        </a>
                                        @permission('edit-organizations')
                                        <div class="file-delete"
                                             data-rem="{{ route('admin.info.directories.organization.remove-file', $document->id) }}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </div>
                                        @endpermission
                                    </div>
                                @endforeach

                                @permission('edit-organizations')
                                <label for="documents" class="upload file-item doc">
                                        <span class="icon">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        </span>
                                    <span class="title">Додати документи</span>
                                    <input type="file" id="documents" name="documents[]" multiple
                                           style="display: none;"/>
                                </label>
                                    @endpermission



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

@section('scripts-end')

    <script type="text/javascript">
        jQuery(document).ready(function() {

            document.getElementById("documents").onchange = function() {
                document.getElementById("upload-form").submit();
            };

            $('.file-delete').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                $.ajax({
                    url: $(this).data('rem'),
                    type: 'post',
                    success: function(data) {
                        elem.parent('.file-item').remove();
                    },
                    error: function(data) {
                        console.error(data);
                    }
                });
            })
        });
    </script>
@endsection



