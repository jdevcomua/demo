@extends('layout.app')

@section('title', 'Часті питання')

@section('content')
    <div class="cols-block">
        <div class="cols-block-header">
            <div class="block-title">ЧАСТІ ПИТАННЯ</div>
            <div class="block-sub-title"></div>
        </div>
        <div class="cols-block-content faq">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Які дані для входу в систему мені необхідні?
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                    <div class="card-body">

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Я не впевнений у правильності введених щодо тварини даних. Хто буде перевіряти їх коректність?
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
                    <div class="card-body">
                        В межах наступних розробок, для підтвердження та ведення інформації щодо тварини, планується реєстрація
                        у системі ветеринарних установ, які, у свою чергу, будуть проходити внутрішній процес верифікації.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection