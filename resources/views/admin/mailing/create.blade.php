@extends('admin.layout.app')

@section('title', 'Templates')

@section('breadcrumbs_prev')
    <li class="crumb-link">
        <a href="{{route('admin.mailings.index')}}">Mailings</a>
    </li>
@endsection

@section('breadcrumbs_title', 'Create new template')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create new mailing
                </div>
                <form action="{{route('admin.mailings.store')}}" method="post" class="form-horizontal">
                @csrf
                    <div class="panel-body">
                        @include('admin.mailing.partials.form-fields')
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts-end')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <script defer>
        $('#period').hide();
        $('#dates').hide();
        $('#type').on('change', function () {
            if ($(this).val() === "{{\App\Models\MailingConfig::TYPE_DATES}}") {
                $('#dates').show();
                $('#period').hide();
            } else if ($(this).val() === "{{\App\Models\MailingConfig::TYPE_SCHEDULED}}") {
                $('#period').show();
                $('#dates').hide();
            } else {
                $('#period').hide();
                $('#dates').hide();
            }
        });
        @if(old('type'))
        setTimeout(function () {
            $('#type').val("{{old('type')}}").change();
        } ,500);
        @endif
        $('.date').datepicker({
            multidate: true,
            format: 'dd-mm-yyyy'
        });

    </script>
@endsection
