@if($document->title() !== null)
    <h3 style="text-align: center;">{{$document->title()}}</h3>
@endif
@include('print.base_tables', ['document' => $document])

@if($document->signBlock())
    <table class="sign-block">
        <tr>
            <td rowspan="2" style="width: 50%;"><span>{!! $document->signText() !!}</span></td>
            <td></td>
        </tr>
        <tr>
            <td><div class="sign-line"></div></td>
        </tr>
    </table>
@else
    <div style="margin-bottom: 40px"></div>
@endif