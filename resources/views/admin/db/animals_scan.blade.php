@extends('admin.layout.app')

@section('title', 'Сканування QR-коду')

@section('content')
    <section id="content" class="animated fadeIn">

    <div class="col-xs-12">
        <div class="panel panel-visible" id="spy5">
            <div class="panel-heading">
                <div class="panel-title">
                    <span class="glyphicon glyphicon-tasks"></span>Сканування QR-коду</div>
            </div>
            <div class="panel-body pn">
    <div class="success-page">
        @if(Session::has('error'))
            <div class="alert alert-danger" role="alert">
                {{Session::get('error')}}
            </div>
        @endif
    </div>
        <div class="col-12">
            <video muted="" autoplay="" playsinline="" class="cam-video" id="qr-video"></video>
        </div>
        <div class="col-12">
            <div class="buttons">
                <div class="line center-block" style="width:300px">
                    <hr>
                    <span style="display: inherit; text-align: center;">або</span>
                </div>
            </div>
        </div>
        <div class="col-12" style="margin-bottom:15px">
            <div class="row">
                <form action="{{route('animals.search')}}" id="badge-search" method="post" class="col-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3 center-block">
                    @csrf
                    <input type="text" name="badge" id="cam-qr-result" class="form-control">
                    <br>
                    <div class="col-12 mt-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-big btn-primary mt-3 m-auto center-block">Пошук</button>
                    </div>
                </form>
            </div>
        </div>

            </div>
        </div>
    </div>
    </section>
@endsection

@section('scripts-end')
    <script type="module">
        import QrScanner from "../../vendor/qr-scanner/qr-scanner.min.js";
        const video = document.getElementById('qr-video');
        const camQrResult = document.getElementById('cam-qr-result');

        function setResult(label, result) {
            $(label).val(result);
            $('#badge-search').submit();
        }

        const scanner = new QrScanner(video, result => setResult(camQrResult, result.match(/\d+/)[0]));
        scanner.start();

    </script>
@endsection