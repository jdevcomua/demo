<form class="form-horizontal" action="" method="POST" style="margin-bottom: 30px;">
    @csrf
    <input type="hidden" name="reportName" value="{{$reportName}}">
    <div class="form-group select">
        <label for="user" class="col-lg-3 control-label">Власник</label>
        <div class="col-lg-8">
            <select name="owner_id" id="owner" data-value="{{ $ownerId ?? '' }}"></select>
        </div>
    </div>
    <br>
    <button type="submit" class="ml-auto mt-6 btn confirm btn-primary">Згенерувати звіт</button>
    @if(isset($ownerId))
        <a href="{{route('admin.reports.report.download', ['reportName' => $reportName,'owner_id' => $ownerId, 'format' => 'pdf'])}}" class="ml-auto mt-6 btn confirm btn-success">Завантажити звіт PDF</a>
        <a href="{{route('admin.reports.report.download', ['reportName' => $reportName,'owner_id' => $ownerId, 'format' => 'xlsx'])}}" class="ml-auto mt-6 btn confirm btn-success">Завантажити звіт XLSX</a>
    @endif
</form>
