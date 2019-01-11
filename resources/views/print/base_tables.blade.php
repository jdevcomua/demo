@foreach ($document->tables() as $index => $table)
    @if(isset($table->title))
        <h3 style="text-align: center; font-weight: 500;">{{$table->title}}</h3>
    @endif
    <table style="width:100%" >
        <tr>
            @foreach($table->headers as $header)
                <th>{{$header}}</th>
            @endforeach
        </tr>
        @foreach($table->columns as $column)
            <tr>
                @foreach($column as $col_data)
                    <td>{{$col_data}}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
@endforeach