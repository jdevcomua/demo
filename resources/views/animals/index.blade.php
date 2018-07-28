@extends('layout.app')

@section('title', 'Мої тварини')

@section('content')
    @if(count($pets))
        <div class="pets-list-header">СПИСОК МОЇХ ТВАРИН</div>
        <div class="pets-list">
            @foreach($pets as $pet)
                <div class="pets-list-item">
                    @if(count($pet->images))
                        <div class="pet-photo" style="background-image: url('{{ $pet->images[0]->path }}')"></div>
                    @else
                        <div class="pet-photo" style="background-image: url('/img/no_photo.png')"></div>
                    @endif
                    <div class="pet-info">
                        <div class="pet-info-block">
                            <span class="title">{{ $pet->species->name }}</span>
                            <span class="content">{{ $pet->nickname }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Масть</span>
                            <span class="content">{{ $pet->color->name }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Дата народження</span>
                            <span class="content">{{ \App\Helpers\Date::getlocalizedDate($pet->birthday) }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Статус</span>
                            @if($pet->verified)
                                <span class="content green">Верифіковано</span>
                            @else
                                <span class="content red">Не верифіковано</span>
                            @endif
                        </div>
                    </div>
                    <div class="dropdown more-button dropleft">
                        <i class="fa fa-ellipsis-v " data-toggle="dropdown" aria-hidden="true" aria-expanded="false"></i>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               href="{{ route('animals.show', ['id' => $pet->id]) }}">Переглянути картку</a>
                            <a class="dropdown-item"
                               href="{{ route('animals.edit', ['id' => $pet->id]) }}">Редагувати інформацію</a>
                            <a class="dropdown-item"
                               href="{{ route('animals.destroy', ['id' => $pet->id]) }}" onclick="event.preventDefault();
                               document.getElementById('delete-form-{{ $pet->id }}').submit();">Видалити</a>
                            <form id="delete-form-{{ $pet->id }}" action="{{ route('animals.destroy', ['id' => $pet->id]) }}"
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        @include('animals._no_animals')
    @endif
    <div class="add-new-pet-wrap">
        @if(count($pets))
            <div class="above-text">Маєш ще тваринку? Додавай!</div>
        @endif
        <a href="{{ route('animals.create') }}" class="add-new-pet btn btn-big btn-primary">+ Додати</a>
    </div>
@endsection