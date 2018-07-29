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


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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
