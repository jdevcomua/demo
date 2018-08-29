window.$ = window.jQuery = require('jquery');

require('./bootstrap');
require('./smoothscroll');
require('bootstrap-datepicker');
require('selectize');


require('./parts/datepicker');
require('./parts/image-input');
require('./parts/selectize');
require('./parts/dropzone');
require('./parts/validation');
require('./parts/animal-search');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$().ready(function () {
    $(document).on('click', '#menu-toggle', function(){
        $(this).toggleClass('is-active');
        $(this).parents('header').toggleClass('menu-open');
    });
});

$('input[required]').each(function () {
    $(this).attr('title', 'Заповніть це поле.');
});