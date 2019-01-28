@php
    $curRoute = \Route::current() ? \Route::current()->getName() : '';
@endphp

@extends('layout.app')
@section('title', 'Загублені тварини')
@section('content')
    <nav class="pets-lost-nav">
        <a class="pets-list-header @if($curRoute == 'lost-animals.index') active @endif" href="{{route('lost-animals.index')}}">Загублені тварини</a>
        <a class="pets-list-header @if($curRoute == 'lost-animals.found') active @endif" href="{{route('lost-animals.found')}}">Знайдені тварини</a>
    </nav>
    @if(count($lostAnimals))
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
                        @if($lostAnimal->animal->user !== null)
                            <div class="pet-info-block">
                                <span class="title">Адреса проживання</span>
                                <span class="content">{{ ($lostAnimal->animal->user->living_address !== null) ? $lostAnimal->animal->user->living_address->full_address : 'Не заповнено' }}</span>
                            </div>
                        @endif
                        <div class="pet-info-block">
                            <span class="title">Загубилася</span>
                            <span class="content">{{ \App\Helpers\Date::getlocalizedDate($lostAnimal->animal->lost->lost_at) }}</span>
                        </div>
                        @if($lostAnimal->animal->user !== null)
                            <div class="pet-info-block">
                                <button class="btn btn-found contact" data-contact='{{$lostAnimal->animal->user->contact_info}}'>Знайшов</button>
                            </div>
                        @endif
                    </div>
                    <div class="dropdown more-button dropleft">
                        <div class="more-icon" data-toggle="dropdown"></div>
                        <div class="dropdown-menu">
                            @if($lostAnimal->animal->user_id === \Auth::id())
                            <a class="dropdown-item"
                               href="{{ route('animals.show', ['id' => $lostAnimal->animal->id]) }}">Переглянути
                                картку</a>
                            @else
                                <a class="dropdown-item"
                                   href="{{ route('lost-animals.lost.show', ['id' => $lostAnimal->id]) }}">Переглянути
                                    картку</a>
                            @endif
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
        @include('lost-animals._no_animals')
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
                            <h3>Зв'язатися з власником тваринки</h3>
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


