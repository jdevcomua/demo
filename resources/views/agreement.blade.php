@extends('layout.app')

@section('title', 'Згода на обробку персональних даних')

@section('content')
    <div class="cols-block cols-content-only">
        <div class="cols-block-header">
            <div class="block-title">ЗГОДА НА ОБРОБКУ ПЕРСОНАЛЬНИХ ДАНИХ</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content about-page">
            {!! \Block::get('agreement-page') !!}
        </div>
    </div>
@endsection
