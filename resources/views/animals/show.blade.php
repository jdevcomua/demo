@extends('layout.app')

@section('title', $animal->nickname)

@section('content')
    <div class="page-title">
        <a href="{{ route('index') }}" class="page-back-link"></a>
        <div class="title">{{ $animal->nickname }}</div>
        @if(!$animal->verified) <a href="{{ route('animals.edit', $animal->id) }}" class="action-link">Редагувати</a> @endif
    </div>
    <div class="animal-show">
        <div class="animal-images">
            <div class="animal-image main"
                 @if(array_key_exists(1, $animal->imagesArray)) style="background-image: url('/{{ $animal->imagesArray[1] }}')" @endif>
            </div>
            @for($i = 2; $i < 10; $i++)
                @if(array_key_exists($i, $animal->imagesArray))
                    <div class="animal-image" style="background-image: url('/{{ $animal->imagesArray[$i] }}')"></div>
                @endif
            @endfor
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
                <span class="title">Масть</span>
                <span class="content">{{ $animal->color->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Тип шерсті</span>
                <span class="content">{{ $animal->fur->name }}</span>
            </div>
            <div class="pet-info-block">
                <span class="title">Дата народження</span>
                <span class="content">{{ \App\Helpers\Date::getlocalizedDate($animal->birthday) }}</span>
            </div>
            <div class="pet-info-block w-100">
                <span class="title">Порода</span>
                <span class="content">{{ $animal->breed->name }}</span>
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
                        <span class="file-name">{{ $doc->filename }}</span>
                        <span class="file-ext">.{{ $doc->fileextension }}</span>
                    </a>
                @empty
                    <div class="no-files">Файли відсутні...</div>
                @endforelse
            </div>
        </div>
        @if($animal->verified)
        <hr class="divider">
        <div class="animal-actions">
            @if($animal->lostRecord() === null || $animal->lostRecord()->found)
            <div class="animal-action">
                <div class="action-title">Розшук</div>
                <div class="action-description">Якщо ви втратили вашого улюбленця тисніть кнопку <i>Розшук</i> для того щоб швидше знайти його!</div>
                <button class="btn btn-red btn-i i-warn btn-tbig lost_animal-btn" >Розшук</button>
            </div>
            @else
                <div class="animal-action">
                    <div class="action-title">Тварину знайдено</div>
                    <div class="action-description">Якщо ви знайшли вашого улюбленця тисніть кнопку <i>Тварину знайдено</i>!</div>
                    <button class="btn btn-primary btn-i i-ok btn-tbig lost_animal-btn" >Тварину знайдено</button>
                </div>
            @endif
            <div class="animal-action">
                <div class="action-title">Зміна власника</div>
                <div class="action-description">Якщо власник тварини змінився тисніть кнопку <i>Змінити власника</i> для того щоб повідомити про це нас</div>
                <button class="btn btn-dgrey btn-i i-change btn-tbig">Змінити власника</button>
            </div>
        </div>
        @endif
    </div>

    <form id="lostAnimalSearch" action="{{route('animals.lost')}}" method="post">
        @csrf
        <input type="hidden" name="animal_id" value="{{$animal->id}}">
    </form>
@endsection

@section('scripts-end')
    <script>
        $('.lost_animal-btn').on('click', function () {
            var form = $('#lostAnimalSearch');
            form.submit();
        });
    </script>
@endsection