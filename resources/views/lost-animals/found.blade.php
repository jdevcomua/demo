@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp

@extends('layout.app')
@section('title', 'Знайдені тварини')
@section('content')
    @if(count($foundAnimals))

        <nav class="pets-lost-nav">
            <a class="pets-list-header @if($curRoute == 'lost-animals.index') active @endif" href="{{route('lost-animals.index')}}">Загублені тварини</a>
            <a class="pets-list-header @if($curRoute == 'lost-animals.found') active @endif" href="{{route('lost-animals.found')}}">Знайдені тварини</a>
        </nav>
        <div class="pets-list-sort">Сортувати за <span class="pets-list-sort-item active">@sortablelink('created_at', 'датою')</span></div>
        <div class="pets-list">
            @foreach($foundAnimals as $foundAnimal)

                <div class="pets-list-item ">
                    <div class="pet-photo"
                         style="background-image: url('{{ isset($foundAnimal->images) && count($foundAnimal->images) ? '/' . $foundAnimal->images[0]->path :
                        '/img/no_photo.png' }}'); position: relative;">
                        @if($foundAnimal->badge)
                            <div class="animal-badge">
                                <span class="animal-badge-icon"></span>
                                <span class="animal-badge-number">{{$foundAnimal->badge}}</span>
                            </div>
                        @endif
                    </div>
                    <div class="pet-info">
                        <div class="pet-info-block">
                            <span class="title">Вид</span>
                            <span class="content">{{ $foundAnimal->species->name }}</span>
                        </div>
                        <div class="pet-info-block">
                            <span class="title">Адреса де знайшли тварину</span>
                            <span class="content">{{ ($foundAnimal->found_address !== null) ? $foundAnimal->found_address : 'Не заповнено' }}</span>
                        </div>

                        <div class="pet-info-block">
                            <span class="title">Знайдена</span>
                            <span class="content">{{ \App\Helpers\Date::getlocalizedDate($foundAnimal->created_at) }}</span>
                        </div>
                        <div class="pet-info-block">
                            <button class="btn btn-found contact" data-contact='{{$foundAnimal->contact_info}}'>Зв'язатися</button>
                        </div>

                    </div>
                    <div class="dropdown more-button dropleft">
                        <div class="more-icon" data-toggle="dropdown"></div>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               href="{{ route('lost-animals.show', ['id' => $foundAnimal->id]) }}">Переглянути</a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        @include('animals._no_animals')
    @endif
    <div class="modal fade" id="contactModal" tabindex="-2" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Зв'язатися з власником об'яви</h3>
                        </div>
                        <div class="col-md-12">
                            <form class="search-request" id="form-modal" enctype="multipart/form-data" action="{{route('lost-animals.i-found-animal')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_info_name">Ім’я</label>
                                    <input type="text" class="form-control" id="contact_info_name" name="contact_name" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_info_phone">Телефон</label>
                                    <input type="text" class="form-control" id="contact_info_phone" name="contact_phone" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="validation-error alert alert-danger hidden"></div>
                                    <label for="contact_info_email">Email</label>
                                    <input type="text" class="form-control" id="contact_info_email" name="contact_email" readonly>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts-end')
    @parent
    <script>
        $('.contact').on('click', function () {
            var contact_info = $(this).data('contact');
            console.log(contact_info.contact_email);
            $('#contact_info_email').val(contact_info.contact_email);
            $('#contact_info_phone').val(contact_info.contact_phone);
            $('#contact_info_name').val(contact_info.contact_name);

            $('#contactModal').modal('show');
        });
    </script>
@endsection


