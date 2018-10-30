@extends('layout.app')

@section('title', 'Верифікуйте вашу тварину')

@section('content')
    <div class="success-page">
        @if(Session::has('error'))
            <div class="alert alert-danger" role="alert">
                {{Session::get('error')}}
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-12">
            <video muted="" autoplay="" playsinline="" class="cam-video" id="qr-video"></video>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <div class="buttons">
                <div class="line">
                    <hr>
                    <span>або</span>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row d-flex flex-column justify-content-center">
                <form action="{{route('animals.search')}}" id="badge-search" method="post" class="col-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3">
                    @csrf
                    <input type="text" name="badge" id="cam-qr-result" class="form-control">
                    <div class="col-12 mt-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-big btn-primary mt-3 m-auto">Пошук</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12">

        </div>
    </div>
@endsection

@section('scripts-end')
    <script type="module">
        import QrScanner from "../vendor/qr-scanner/qr-scanner.min.js";
        const video = document.getElementById('qr-video');
        const camQrResult = document.getElementById('cam-qr-result');

        function setResult(label, result) {
            $(label).val(result);
            $('#badge-search').submit();
        }

        const scanner = new QrScanner(video, result => setResult(camQrResult, result));
        scanner.start();

    </script>
@endsection