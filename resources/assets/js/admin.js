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


require('./parts/admin/selectize');
require('./parts/admin/datepicker');
require('./parts/admin/datatable');
require('./parts/admin/popup');


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('input[required]').each(function () {
    $(this).attr('title', 'Заповніть це поле.');
});
$('input.custom-file-input').change(function () {
    var count = this.files.length;
    var txt;
    switch (count) {
        case 1: txt = ' файл'; break;
        case 2:
        case 3:
        case 4: txt = ' файли'; break;
        default: txt = ' файлів';
    }
    $(this).prev().text('Обрано: ' + count + txt);
});


parseDBDate = function (dateString) {
    if (dateString) {
        dateString = dateString.replace(/-/g, '/');
        return new Date(dateString)
    }
    return null;
};

$(document).on('keypress', '#badge', function (e) {
    var char = String.fromCharCode(e.which);
    return (char.replace(/[^\dа-яіїє]/gi, '').length === 1) &&
        !($(this).val().length >= 8);
});