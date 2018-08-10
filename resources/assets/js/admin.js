window._ = require('lodash');
window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

require('./smoothscroll');

require('selectize');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    $('.datepicker input').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        language: "uk",
        locale: "uk",
        autoclose: true
    });
});

/////////////////////////////////////////
// Selectize

var options = {
    valueField: 'value',
    labelField: 'name',
};
var breeds = $('.form-group.select select#breed').selectize(options);
var colors = $('.form-group.select select#color').selectize(options);
var furs = $('.form-group.select select#fur').selectize(options);

$('select[name="species"]').change(function(event) {
    breeds[0].selectize.clear();
    breeds[0].selectize.clearOptions();
    colors[0].selectize.clear();
    colors[0].selectize.clearOptions();
    furs[0].selectize.clear();
    furs[0].selectize.clearOptions();
    updateSelects(event.target.value);
});

var xhrBreeds;
var xhrColors;
var xhrFurs;
function updateSelects(species) {
    breeds[0].selectize.load(function (callback) {
        xhrBreeds && xhrBreeds.abort();
        xhrBreeds = $.ajax({
            url: '/ajax/species/'+species+'/breeds',
            success: function (results) {
                callback(JSON.parse(results));
                checkDefaultValues();
            },
            error: function () {
                callback();
            }
        })
    });
    colors[0].selectize.load(function (callback) {
        xhrColors && xhrColors.abort();
        xhrColors = $.ajax({
            url: '/ajax/species/'+species+'/colors',
            success: function (results) {
                callback(JSON.parse(results));
                checkDefaultValues();
            },
            error: function () {
                callback();
            }
        })
    });
    furs[0].selectize.load(function (callback) {
        xhrFurs && xhrFurs.abort();
        xhrFurs = $.ajax({
            url: '/ajax/species/'+species+'/furs',
            success: function (results) {
                callback(JSON.parse(results));
                checkDefaultValues();
            },
            error: function () {
                callback();
            }
        })
    });
}

function checkDefaultValues() {
    breeds[0].selectize.setValue($('.form-group.select select#breed').data('value'));
    colors[0].selectize.setValue($('.form-group.select select#color').data('value'));
    furs[0].selectize.setValue($('.form-group.select select#fur').data('value'));
}

if ($('select[name="species"]').length) {
    updateSelects($('select[name="species"]').val());
}
/////////////////////////////////////////

/////////////////////////////////////////
// DataTable

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
    responsive: true,
    language: dataTableLang
};

dataTableInit = function (table, options) {
    table.find('tfoot th').each(function() {
        if ($(this).find('select').length !== 0 || $(this).hasClass('no-search')) return;

        var title = table.find('thead th').eq($(this).index()).text();
        $(this).html('<input type="text" class="form-control" />');
    });

    var dt = table.DataTable({...dataTablesDefs, ...options});

    dt.columns().eq(0).each(function(colIdx) {
        var $input = $('input', dt.column(colIdx).footer());

        $input.on('keyup', function(e) {
            if(e.keyCode === 13) searchInTable(dt, colIdx, this.value);
        });
        $input.on('blur', function(e) {
            searchInTable(dt, colIdx, this.value);
        });
        $('select', dt.column(colIdx).footer()).on('change', function(e) {
            searchInTable(dt, colIdx, this.value);
        });
    });

    dt.on('draw', function () {
        dt.responsive.recalc();
    });
    $(window).resize(function () {
        dt.responsive.recalc()
    });
};

function searchInTable(table, column, search) {
    table.column(column).search(search).draw();
}

/////////////////////////////////////////

parseDBDate = function (dateString) {
    dateString = dateString.replace(/-/g, '/');
    return new Date(dateString)
};
