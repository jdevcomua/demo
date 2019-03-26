@extends('layout.app')

@section('title', 'Правила утримання і догляду за домашніми тваринами')

@section('content')
    <div class="cols-block cols-content-only">
        <div class="cols-block-header">
            <div class="block-title">ПРАВИЛА УТРИМАННЯ І ДОГЛЯДУ ЗА ДОМАШНІМИ ТВАРИНАМИ</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content about-page">
            {!! \Block::get('rules-page') !!}
        </div>
    </div>
@endsection
