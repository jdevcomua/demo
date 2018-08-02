@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Контент</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                @if(isset($new))
                                    Нове запитання
                                @else
                                    Запитання {{$faq->question}}
                                @endif
                            </div>
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
                                <form class="form-horizontal" role="form"
                                      action="{{ route('admin.info.content.faq.store', isset($new) ? : $faq->id) }}" method="post">
                                    @csrf

                                    

                                </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
