@extends('admin.layout.app')

@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="#">База тварин</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="animated fadeIn">

            <div class="row">

                @if(false)
                    <div class="col-md-2 col-sm-3 col-xs-6 mb25">
                        <a href="{{ route('admin.db.animals.create') }}" class="btn btn-success btn-block">Додати тварину</a>
                    </div>
                @endif

                <div class="col-xs-12">
                    <div class="panel panel-visible" id="spy5">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <span class="glyphicon glyphicon-tasks"></span>Список всіх тварин</div>
                        </div>
                        <div class="panel-body pn">
                            @if (\Session::has('success_animal'))
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check pr10"></i>
                                    {{ \Session::get('success_animal') }}
                                </div>
                            @endif
                            <table class="table table-striped table-hover display datatable responsive nowrap"
                                   id="datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Дії</th>
                                    <th>Кличка</th>
                                    <th>Вид</th>
                                    <th>Порода</th>
                                    <th>Масть</th>
                                    <th>Стать</th>
                                    <th>Дата народження</th>
                                    <th>Стерилізовано</th>
                                    <th>Власник</th>
                                    <th>Верифіковано</th>
                                    {{--<th>Ким верифіковано</th>--}}
                                    <th>Зареєстровано</th>
                                    <th>Оновлено</th>
                                </tr>
                                </thead>
                                <tfoot class="search">
                                <tr>
                                    <th></th>
                                    <th class="no-search"></th>
                                    <th></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            @foreach($species as $s)
                                                <option value="{{$s->name}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Самка</option>
                                            <option value="1">Самець</option>
                                        </select>
                                    </th>
                                    <th></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Ні</option>
                                            <option value="1">Так</option>
                                        </select>
                                    </th>
                                    <th></th>
                                    <th>
                                        <select>
                                            <option selected value>---</option>
                                            <option value="0">Ні</option>
                                            <option value="1">Так</option>
                                        </select>
                                    </th>
                                    {{--<th></th>--}}
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        <form action="#" id="destroy" method="post" class="hidden">
            @csrf
            @method('delete')
        </form>

    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.db.animals.data', null, false) }}',
                columns: [
                    { "data": "id"},
                    {
                        "data": "id",
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"{{ route('admin.db.animals.edit') }}/"
                                    + data + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>" +
                                    "<a href='#' class='delete' " +
                                    "data-id=" + data + " >"
                                    @permission('delete-animal')
                                    +
                                    "<i class=\"fa fa-trash pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    @endpermission
                                    ;

                            }
                        }
                    },
                    { "data": "nickname" },
                    { "data": "species_name"},
                    { "data": "breeds_name" },
                    { "data": "colors_name" },
                    {
                        data: 'gender',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Самка';
                                case 1: return 'Самець';
                                default: return '?';
                            }
                        }
                    },
                    {
                        data: 'birthday',
                        render: function ( data, type, row ) {
                            var d = parseDBDate(data);
                            return d.toLocaleDateString('uk')
                        }
                    },
                    {
                        data: 'sterilized',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Ні';
                                case 1: return 'Так';
                                default: return '?';
                            }
                        }
                    },
                    {
                        data: 'owner_name',
                        defaultContent: '',
                        render: function ( data, type, row ) {
                            if (data) {
                                var arr = data.split('||');
                                return '<a href="{{ route('admin.db.users.show') }}/' + arr[1] + '">' + arr[0] + '</a>';
                            }
                        }
                    },
                    {
                        data: 'verified',
                        render: function ( data, type, row ) {
                            switch (data) {
                                case 0: return 'Ні';
                                case 1: return 'Так';
                                default: return '?';
                            }
                        }
                    },
                    {{--{--}}
                        {{--data: 'verified_name',--}}
                        {{--defaultContent: '',--}}
                        {{--render: function ( data, type, row ) {--}}
                            {{--if (data) {--}}
                                {{--var arr = data.split('||');--}}
                                {{--return '<a href="{{ route('admin.db.users.show') }}/' + arr[1] + '">' + arr[0] + '</a>';--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                    { "data": "created_at" },
                    { "data": "updated_at" },
                ],
            });

            jQuery(document).on('click','.delete', function(e) {
                e.preventDefault();
                if (confirm('Ви впевнені що хочете видалити тварину?')) {
                    var id = jQuery(this).attr('data-id');
                    var form = jQuery('#destroy');
                    $(form).attr('action', "{{route('admin.db.animals.remove')}}/"+id);
                    $(form).submit();
                }
            });
        });
    </script>
@endsection