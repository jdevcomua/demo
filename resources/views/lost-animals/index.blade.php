@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp

@extends('layout.app')
@section('title', 'Загублені тварини')
@section('content')
    @if(count($lostAnimals))

        <nav class="pets-lost-nav">
            <a class="pets-list-header @if($curRoute == 'lost-animals.index') active @endif" href="{{route('lost-animals.index')}}">Загублені тварини</a>
            <a class="pets-list-header @if($curRoute == 'lost-animals.found') active @endif" href="{{route('lost-animals.found')}}">Знайдені тварини</a>
        </nav>
        <div class="pets-list-sort">Сортувати за <span class="pets-list-sort-item active">@sortablelink('created_at', 'датою')</span></div>
        <div class="pets-list">
            @foreach($lostAnimals as $lostAnimal)

                <div class="pets-list-item ">
                    <div class="pet-photo"
                         style="background-image: url('{{ count($lostAnimal->animal->images) ? $lostAnimal->animal->images[0]->path :
                        '/img/no_photo.png' }}'); position: relative;">
                        @if($lostAnimal->animal->badge)
                            <div class="animal-badge">
                                <span class="animal-badge-icon"></span>
                                <span class="animal-badge-number">{{$lostAnimal->animal->badge}}</span>
                            </div>
                        @endif
                    </div>
                    <div class="pet-info">
                        <div class="pet-info-block">
                            <span class="title">{{ $lostAnimal->animal->species->name }}</span>
                            <span class="content">{{ $lostAnimal->animal->nickname }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Адреса проживання</span>
                            <span class="content">{{ ($lostAnimal->animal->user->living_address !== null) ? $lostAnimal->animal->user->living_address->full_address : 'Не заповнено' }}</span>
                        </div>

                        <div class="pet-info-block">
                            <span class="title">Загубилася</span>
                            <span class="content">{{ \App\Helpers\Date::getlocalizedDate($lostAnimal->animal->lost->lost_at) }}</span>
                        </div>
                        <div class="pet-info-block">
                            <button class="btn btn-found">Знайшов</button>
                        </div>

                    </div>
                    <div class="dropdown more-button dropleft">
                        <div class="more-icon" data-toggle="dropdown"></div>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               href="{{ route('animals.show', ['id' => $lostAnimal->animal->id]) }}">Переглянути
                                картку</a>
                            @if(!$lostAnimal->animal->verified)
                                @if($lostAnimal->animal->user_id === \Auth::id())
                                    <a class="dropdown-item"
                                       href="{{ route('animals.edit', ['id' => $lostAnimal->animal->id]) }}">Редагувати
                                        інформацію</a>
                                    <a class="dropdown-item"
                                       href="{{ route('animals.destroy', ['id' => $lostAnimal->animal->id]) }}"
                                       onclick="event.preventDefault();
                                               document.getElementById('delete-form-{{ $lostAnimal->animal->id }}').submit();">Видалити</a>
                                    <form id="delete-form-{{ $lostAnimal->animal->id }}"
                                          action="{{ route('animals.destroy', ['id' => $lostAnimal->animal->id]) }}"
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        @include('animals._no_animals')
    @endif
@endsection

@section('scripts-end')
    @parent
    <script>

    </script>
@endsection


