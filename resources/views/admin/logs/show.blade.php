@extends('admin.layout.app')

@section('styles')
    <style>
        pre.sf-dump, pre.sf-dump .sf-dump-default {
            z-index: 1 !important;
            min-height: 39px;
        }
        .changes-block {
            display: flex;
            flex-direction: column;
            height: auto;
            min-height: 39px;
        }
        c-g {
            background: #adfdad;
            padding: 0 4px;
        }
        c-r {
            background: #f2b3b5;
            text-decoration: line-through;
            padding: 0 4px;
        }
    </style>
@endsection

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">Подія #{{ $log->id }}</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

        <div class="tray tray-center">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Детальна інформація про подію</div>
                        </div>
                        <form class="form-horizontal" role="form">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Дата:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn">{{ $log->updated_at }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Користувач:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn">
                                            @if($log->user)
                                                <a href="{{ route('admin.db.users.show', $log->user->id) }}">
                                                    {{ $log->user->fullName }}
                                                </a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Дія:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn">{{ $log->actionName }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Об'єкт:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn">
                                            @if($log->object)
                                                @php
                                                    $obj = explode('|', $log->object);
                                                @endphp
                                                <a href="{{ route('admin.object') }}/{{ $obj[1] }}/{{ $obj[2] }}">
                                                    {{ $obj[0] }} #{{ $obj[2] }}
                                                </a>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Статус:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn">{{ $log->statusName  }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Завершено:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn">@if($log->finished)Так @elseНі @endif</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Зміни:</label>
                                    <div class="col-lg-9">
                                        <p class="form-control mn changes-block">{!! $log->changes !!}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="col-lg-2 control-label">Payload:</label>
                                    <div class="col-lg-9">
                                        {!! Symfony\Component\VarDumper\VarDumper::dump(
                                        json_decode($log->payload)) !!}
                                    </div>
                                </div>
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
        var compacted = document.querySelectorAll('.sf-dump-compact');
        for (var i = 0; i < compacted.length; i++) {
            compacted[i].className = 'sf-dump-expanded';
        }
    </script>
@endsection