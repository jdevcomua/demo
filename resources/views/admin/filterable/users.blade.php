@extends('admin.layout.app')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <span>Фільтр - Користувачі</span>
        </div>
    </header>
    <!-- End: Topbar -->


    <div style="margin-top: 20px;"></div>
    <div class="col-md-6 center-block">
        <div class="panel panel-visible" id="spy5">
            <div class="panel-heading">
                <div class="panel-title">
                    <span class="glyphicon glyphicon-tasks"></span>Фільтр</div>
            </div>
            <form id="users-filter-form" class="form-horizontal" action="" method="post">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group select">
                            <label for="breed" class="col-lg-3 control-label">Організація</label>
                            <div class="col-lg-8">
                                <select name="organization" id="organization"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="animalsAmountMin" class="col-lg-3 control-label">Мін. кількість тварин</label>
                            <div class="col-lg-8">
                                <input type="text" id="animalsAmountMin" name="animalsAmountMin" class="form-control" value="{{old('animalsAmountMin') ?? '-'}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="animalsAmountMax" class="col-lg-3 control-label">Макс. кількість тварин</label>
                            <div class="col-lg-8">
                                <input type="text" id="animalsAmountMax" name="animalsAmountMax" class="form-control" value="{{old('animalsAmountMax') ?? '-'}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Дата народження</label>
                            <div class="col-lg-8 input-group input-daterange">
                                <input type="text" autocomplete="off" name="birthdayMin" class="form-control" value="{{old('birthdayMin') ?? '-'}}" readonly>
                                <div class="input-group-addon">до</div>
                                <input type="text" autocomplete="off" name="birthdayMax" class="form-control" value="{{old('birthdayMax') ?? '-'}}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Дата реєстрації</label>
                            <div class="col-lg-8 input-group input-daterange">
                                <input type="text" autocomplete="off" name="createdAtMin" class="form-control" value="{{old('createdAtMin') ?? '-'}}" readonly>
                                <div class="input-group-addon">до</div>
                                <input type="text" autocomplete="off" name="createdAtMax" class="form-control" value="{{old('createdAtMax') ?? '-'}}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Дата оновлення</label>
                            <div class="col-lg-8 input-group input-daterange">
                                <input type="text" autocomplete="off" name="updatedAtMin" class="form-control" value="{{old('updatedAtMin') ?? '-'}}" readonly>
                                <div class="input-group-addon">до</div>
                                <input type="text" autocomplete="off" name="updatedAtMax" class="form-control" value="{{old('updatedAtMax') ?? '-'}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button type="submit" class="btn btn-primary ph25">Результати</button>
                    @if(count($users))
                        <button type="submit" id="download" class="btn btn-success ph25">Завантажити в XLSX</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <section id="content" class="animated fadeIn">

        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy56">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Користувачі</div>
                    </div>
                    <div class="panel-body pn" style="overflow-x:scroll;">
                        <table class="table table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Прізвище</th>
                                <th>Ім'я</th>
                                <th>По батькові</th>
                                <th>e-mail</th>
                                <th>Телефон</th>
                                <th>Дата народження</th>
                                <th>Паспорт</th>
                                <th>Адреси</th>
                                <th>Тварини</th>
                                <th>Організація</th>
                                <th>Зареєстровано</th>
                                <th>Оновлено</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->last_name}}</td>
                                    <td>{{$user->first_name}}</td>
                                    <td>{{$user->middle_name}}</td>
                                    <td>{{$user->primary_email->email}}</td>
                                    <td>{{$user->phones[0]->phone}}</td>
                                    <td>{{$user->birthday}}</td>
                                    <td>{{$user->passport}}</td>
                                    <td>Address</td>
                                    <td>{{count($user->animals)}}</td>
                                    <td>{{$user->organization ? $user->organization->name : '-'}}</td>
                                    <td>{{$user->created_at}}</td>
                                    <td>{{$user->updated_at}}</td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>

                        @if (!count($users))
                            <div class="footnote">
                                <p class="text-center">
                                Нічого не знайдено!
                                </p>
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>

    </section>
@endsection

@section('scripts-end')
    <script>
        jQuery(document).ready(function() {
            $filterUsersForm = $('form#users-filter-form');
            $.ajax({
                url: '/ajax/organizations',
                type: 'get',
                success: function (data) {
                    parsedData = JSON.parse(data);
                    parsedData.unshift({name: "-", value: "-"});
                    var organizations = $('.form-group.select select#organization').selectize({
                        options: parsedData,
                        labelField: 'name',
                        valueField: 'value',
                        searchField: ['name']
                    });
                    defaultValue = '{{old('organization') ?? '-'}}';
                    if (defaultValue !== '-') {
                        parsedData.find(function(el) {
                            if (el.value === defaultValue) return el.name;
                        });
                    }
                    organizations[0].selectize.setValue(defaultValue);
                },
                error: function (data) {
                    console.error(data);
                }
            });

            $('.input-daterange input').each(function() {
                $(this).datepicker({
                    changeYear: true,
                    changeMonth: true,
                    maxDate: '-1D',
                    yearRange: "1900:",
                });
            });

            $('#download').on('click', function (e) {
                e.preventDefault();
                $filterUsersForm.attr('action', '{{route('admin.administrating.users.filter.download')}}');
                $filterUsersForm.submit();
                $filterUsersForm.attr('action', '');
            });
        });
    </script>
@endsection