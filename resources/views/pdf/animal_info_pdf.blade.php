@extends('pdf.master')
@section('style')
    <style>
        table.sign-block {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table.sign-block th, table.sign-block td {
            border: none;
        }
        table.sign-block .sign-line {
            height:1px;
            border-bottom: 1px solid #000;
        }
    </style>
@endsection
@section('content')
    <h3 style="text-align: center;">Реєстраційна картка тварини</h3>
    @foreach ($tables as $index => $table)
        @if(isset($table['title']))
        <h3 style="text-align: center; font-weight: 500;">{{$table['title']}}</h3>
        @endif
        <table style="width:100%" >
                <tr>
                    @foreach($table['headers'] as $header)
                    <th>{{$header}}</th>
                    @endforeach
                </tr>
            @foreach($table['columns'] as $column)
                <tr>
                    @foreach($column as $col_data)
                        <td>{{$col_data}}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>
        @if(count($tables)-1 > $index)
        @endif
    @endforeach
    <table class="sign-block">
        <tr>
            <td rowspan="2" style="width: 50%;"><span>Начальник служби обліку<br> та реєстрації тварин</span></td>
            <td></td>
        </tr>
        <tr>
            <td><div class="sign-line"></div></td>
        </tr>
    </table>
@endsection