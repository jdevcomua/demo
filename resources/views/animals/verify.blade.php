@extends('layout.app')

@section('title', 'Верифікуйте вашу тварину')

@section('content')
    <div class="success-page">
        @if(Session::has('new-animal'))
            <div class="success-icon"></div>
            <div class="success-title">Ваші дані збережені!</div>
        @endif
        <div class="success-message">
            {!! \Block::get('animal-verify') !!}
        </div>
    </div>
@endsection