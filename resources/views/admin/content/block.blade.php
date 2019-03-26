@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Редагування блоків</span>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">
        <div class="row">
            @if($errors->block)
                @foreach($errors->block->all() as $error)
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-remove pr10"></i>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            @if (\Session::has('success_block'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="fa fa-check pr10"></i>
                    {{ \Session::get('success_block') }}
                </div>
            @endif

            @foreach($blocks as $block)
            
            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>
                            @switch($block->title)
                                @case('about-page')
                                Сторінка "Про проект"
                                @break
                                @case('animal-verify')
                                Верифікація тварини
                                @break
                                @case('agreement-page')
                                Сторінка "Згода на обробку персональних даних"
                                @break
                                @case('rules-page')
                                Сторінка "Правила утримання і догляду за домашніми тваринами"
                                @break
                            @endswitch
                        </div>
                    </div>
                    <div class="panel-body pn">
                        <form action="{{route('admin.content.block.update', $block->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="put">
                            <textarea name="body" class="summernote" style="display: none"
                                      title="About page edit">{!! $block->body !!}</textarea>
                            <button type="submit" class="btn btn-success btn-block">Оновити</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
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
