@extends('layout.app')

@section('title', 'Часті питання')

@section('content')
    <div class="cols-block">
        <div class="cols-block-header">
            <div class="block-title">ЧАСТІ ПИТАННЯ</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content faq">
            @foreach($questions as $q)
                <div class="card">
                    <div class="card-header" id="heading{{ $q->id }}">
                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#collapse{{ $q->id }}" aria-expanded="true"
                                aria-controls="collapse{{ $q->id }}">
                            {{ $q->question }}
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div id="collapse{{ $q->id }}" class="collapse" aria-labelledby="heading{{ $q->id }}">
                        <div class="card-body">
                            {{ $q->answer }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection