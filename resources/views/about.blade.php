@extends('layout.app')

@section('title', 'Про проект')

@section('content')
    <div class="cols-block">
        <div class="cols-block-header">
            <div class="block-title">ПРО ПРОЕКТ</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content about-page">
            {!! \Block::get('about-page') !!}
        </div>
    </div>
@endsection