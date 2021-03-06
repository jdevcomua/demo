<form class="form-horizontal" action="" method="POST" style="margin-bottom: 30px;">
    @csrf
    <input type="hidden" name="reportName" value="{{$reportName}}">
    <div class="row">
        <div class="form-group datepicker">
            <label class="col-lg-1 control-label">Період з</label>
            <div class="col-lg-11">
                <div class="validation-error alert alert-danger hidden"></div>
                <input type="text" name="dateFrom" class="form-control" value="{{$dateFrom ?? \Carbon\Carbon::now()->format('d/m/Y')}}" autocomplete="off" required>
            </div>
        </div>
        <div class="form-group datepicker">
            <label class="col-lg-1 control-label">Період по</label>
            <div class="col-lg-11">
                <div class="validation-error alert alert-danger hidden"></div>
                <input type="text" name="dateTo" class="form-control" value="{{$dateTo ?? \Carbon\Carbon::now()->format('d/m/Y')}}" autocomplete="off" required>
            </div>
        </div>
    </div>
    <br>
    <button type="submit" class="ml-auto mt-6 btn confirm btn-primary">Згенерувати звіт</button>
    @if(isset($dateFrom) && isset($dateTo))
    <a href="{{route('admin.reports.report.download', ['reportName' => $reportName, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'format' => 'pdf'])}}" class="ml-auto mt-6 btn confirm btn-success">Завантажити звіт PDF</a>
    <a href="{{route('admin.reports.report.download', ['reportName' => $reportName, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'format' => 'xlsx'])}}" class="ml-auto mt-6 btn confirm btn-success">Завантажити звіт XLSX</a>
    @endif
</form>
