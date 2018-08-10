@extends('layout.app')

@section('title', 'Верифікуйте вашу тварину')

@section('content')
    <div class="success-page">
        @if(Session::has('new-animal'))
            <div class="success-icon"></div>
            <div class="success-title">Ваші дані збережені!</div>
        @endif
        <div class="success-message">
            <p>
                Для закінчення реєстрації тварини Вам необхідно звернутися до Служби обліку та реєстрації тварин, за адресою:
            </p>
            <p>
                <b>м. Київ</b><br>
                <b>проспект Відрадний, 61</b><br>
                <b>т. 044-497-65-23.</b>
            </p>
            <p>
                <b>При собі мати:</b><br>
                - документ, що посвідчує особу<br>
                - ветеринарний паспорт<br>
                - тварину.
            </p>
            <p>
                <b>Часи роботи:</b><br>
                Пн-Чт 9:00-18:00 год.<br>
                Пт 9:00-16:45 год.<br>
                Обід 13:00-14:00 год.
            </p>
        </div>
    </div>
@endsection