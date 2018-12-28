@extends('admin.layout.app')

@section('title', 'Templates')
@section('breadcrumbs_title', 'Groups')


@section('content')

    <section id="content" class="animated fadeIn">

        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-visible" id="spy5">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <span class="glyphicon glyphicon-tasks"></span>Mailing groups list</div>
                    </div>
                    <div class="panel-body pn">
                        @if (\Session::has('success'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="fa fa-check pr10"></i>
                                {{ \Session::get('success') }}
                            </div>
                        @endif
                        <table class="table table-striped table-hover display datatable responsive nowrap"
                               id="datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Actions</th>
                                <th>Name</th>
                                <th>Users</th>
                                {{--<th>Body</th>--}}
                            </tr>
                            <tr>
                                <th></th>
                                <th class="no-search"></th>
                                <th></th>
                                <th></th>
                                {{--<th></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <form action="#" method="post" class="hidden" id="remove">
            @csrf
            @method('delete')
        </form>

        <a class="btn btn-info ph25" href="{{route('admin.groups.create')}}">Add new group</a>
    </section>
@endsection

@section('scripts-end')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            dataTableInit($('#datatable'), {
                ajax: '{{ route('admin.groups.data', null, false) }}',
                columns: [
                    { "data": "id", 'width': '4%' },
                    {
                        "data": "id",
                        defaultContent: '',
                        orderable: false,
                        render: function ( data, type, row ) {
                            if (data) {
                                return "<a href=\"/admin/groups/"
                                    + data + "/edit" + "\">" +
                                    "<i class=\"fa fa-pencil pr10\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    + "<a href='/admin/groups/' class='delete' data-id=" + data + ">" +
                                    "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>" +
                                    "</a>"
                                    ;
                            }
                        }
                    },
                    { "data": "name" },
                    { "data": "users"},
                ],
            });

            jQuery(document).on('click','.delete', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you wanna delete this role?')) {
                    var id = jQuery(this).attr('data-id');
                    var form = jQuery('#remove');
                    $(form).attr('action', "{{route('admin.groups.destroy', '')}}/"+id);
                    $(form).submit();
                }
            });

        });
    </script>
@endsection