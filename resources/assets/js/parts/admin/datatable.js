dataTableLang = {
    "sProcessing":   "Зачекайте...",
    "sLengthMenu":   "Показати _MENU_ записів",
    "sZeroRecords":  "Записи відсутні.",
    "sInfo":         "Записи з _START_ по _END_ із _TOTAL_ записів",
    "sInfoEmpty":    "Записи з 0 по 0 із 0 записів",
    "sInfoFiltered": "(відфільтровано з _MAX_ записів)",
    "sInfoPostFix":  "",
    "sSearch":       "Пошук:",
    "sUrl":          "",
    "oPaginate": {
        "sFirst": "Перша",
        "sPrevious": "Попередня",
        "sNext": "Наступна",
        "sLast": "Остання"
    },
    "oAria": {
        "sSortAscending":  ": активувати для сортування стовпців за зростанням",
        "sSortDescending": ": активувати для сортування стовпців за спаданням"
    }
};

var dataTablesDefs = {
    sDom: 't<"dt-panelfooter clearfix"ip>',
    serverSide: true,
    language: dataTableLang,
    bSortCellsTop: true
};

dataTableInit = function (table, options) {
    table.find('thead tr+tr th').each(function() {
        if ($(this).find('select').length !== 0 || $(this).hasClass('no-search')) return;

        var title = table.find('thead tr:first-child() th').eq($(this).index()).text();
        $(this).html('<input type="text" class="form-control" />');
    });

    var dt = table.DataTable({...dataTablesDefs, ...options});

    table.wrap('<div class="scroll-table"></div>');

    dt.columns().eq(0).each(function(colIdx) {
        var header = $(dt.column(colIdx).header());
        var $input = header.parents('thead').children().eq(1).children().eq(header.index());

        $input.find('input').on('keyup', function(e) {
            if(e.keyCode === 13) searchInTable(dt, colIdx, this.value);
        });
        $input.find('input').on('blur', function(e) {
            searchInTable(dt, colIdx, this.value);
        });
        $('select', $input).on('change', function(e) {
            searchInTable(dt, colIdx, this.value);
        });
    });

    return dt;
};

function searchInTable(table, column, search) {
    table.column(column).search(search).draw();
}