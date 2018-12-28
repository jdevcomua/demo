@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Створення закладу чи установи</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Створення закладу чи установи</div>
                    </div>
                    <form class="form-horizontal" role="form" enctype="multipart/form-data"
                          action="{{ route('admin.info.directories.store.organization') }}" method="post">
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
                                               value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname" class="col-lg-3 control-label">Адреса <span class="required-field">*</span></label>
                                    <div class="col-lg-8">
                                        <textarea name="address" class="form-control" id="address" cols="20" rows="2">{{ old('address') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname" class="col-lg-3 control-label">ПІБ представника <span class="required-field">*</span></label>
                                    <div class="col-lg-8">
                                        <input type="text" id="chief_full_name" name="chief_full_name" class="form-control"
                                               value="{{ old('chief_full_name') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname" class="col-lg-3 control-label">Контактні дані <span class="required-field">*</span></label>
                                    <div class="col-lg-8">
                                        <textarea name="contact_info" class="form-control" id="contact_info" cols="20" rows="5">{{ old('contact_info') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname" class="col-lg-3 control-label">Реквізити <span class="required-field">*</span></label>
                                    <div class="col-lg-8">
                                        <textarea name="requisites" class="form-control" id="requisites" cols="20" rows="5">{{ old('requisites') }}</textarea>
                                    </div>
                                </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Документи</label>
                                <div class="col-lg-8 control-label text-left">
                                    <button type="button">
                                        <label for="documents" class="mn">Оберіть файли</label>
                                    </button>
                                    <span class="file-count pl5"></span>
                                    <input type="file" id="documents" name="documents[]"
                                           multiple class="custom-file-input">
                                    <span class="help-block mt5">Документи повинні бути одного з цих форматів: .jpg, .jpeg, .bmp, .png, .txt, .doc, .docx, .xls, .xlsx, .pdf та не більше ніж 2Mb</span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-default ph25">Зберегти</button>
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

        });
    </script>
@endsection
