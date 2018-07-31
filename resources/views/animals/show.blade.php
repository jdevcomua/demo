@extends('layout.app')

@section('title', $animal->nickname)



@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">{{ $animal->nickname }}</div>
        <a href="{{ route('animals.edit', $animal->id) }}" class="action-link">Редагувати</a>
    </div>
    <div class="animal-show">
        <div class="animal-images">
            <div class="animal-image main"
                 @if(array_key_exists(1, $animal->images)) style="background-image: url('/{{ $animal->images[1] }}')" @endif>
            </div>
            <div class="images-small">
                @for($i = 2; $i < 10; $i++)
                    @if(array_key_exists($i, $animal->images))
                        <div class="animal-image" style="background-image: url('/{{ $animal->images[$i] }}')"></div>
                    @endif
                @endfor
                <div class="animal-image gap"></div>
                <div class="animal-image gap"></div>
                <div class="animal-image gap"></div>
            </div>
        </div>
        <div class="pet-info">
            <div class="pet-info-block">
                <span class="title">Вид</span>
                <span class="content">{{ $animal->species->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Стать</span>
                <span class="content">
                    @if($animal->gender === \App\Models\Animal::GENDER_FEMALE) Самка @endif
                    @if($animal->gender === \App\Models\Animal::GENDER_MALE) Самець @endif
                </span>
            </div>
            <div class="pet-info-block">
                <span class="title">Статус</span>
                @if($animal->verified)
                    <span class="content green">Верифіковано</span>
                @else
                    <span class="content red">Не верифіковано</span>
                @endif
            </div>
            <div class="pet-info-block">
                <span class="title">Порода</span>
                <span class="content">{{ $animal->breed->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Масть</span>
                <span class="content">{{ $animal->color->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Дата народження</span>
                <span class="content">{{ \App\Helpers\Date::getlocalizedDate($animal->birthday) }}</span>
            </div>
        </div>
        <div class="animal-options">
            @if($animal->sterilized)
                <div class="animal-option-item">
                    Стерилізовано
                </div>
            @endif
        </div>
        <div class="pet-info-block comment">
            <span class="title">Коментарі (Особливі прикмети)</span>
            <span class="content">{{ $animal->comment }}</span>
        </div>
        <hr class="divider">
        <div class="files-container">
            <div class="files-container-title">Файли</div>
            <div class="files-list">
                @forelse($animal->documents as $doc)
                    <a href="/{{ $doc->path }}" class="file-item">
                        <span class="file-name">{{ $doc->name }}</span>
                        <span class="file-ext">.{{ $doc->extension }}</span>
                    </a>
                @empty
                    <div class="no-files">Файли відсутні...</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
