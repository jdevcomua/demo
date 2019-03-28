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
require('./parts/ajax-validation');

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

$('#i-found-animal').on('click', function (e) {
    e.preventDefault();
    $('#foundAnimalModal').modal('show');
});

$('#found-badge-btn').on('click', function () {
    let inputIdsToOmit = ['found-badge', 'contact_name', 'contact_phone', 'contact_email'];

    let $form = $(this).parents().eq(1);
    let $inputContainers = $form.find('.form-group');
    let $onlyBadgeHidden = $('#only_badge_hidden');

    if ($onlyBadgeHidden.attr('value') === "1") {
        $onlyBadgeHidden.attr('value', "0");
    } else {
        $onlyBadgeHidden.attr('value', "1");
    }
    $inputContainers.each(function () {
        $currentInput = $(this).find('input');
        if (!$currentInput.length || ($currentInput.length && inputIdsToOmit.indexOf($currentInput.attr('id')) === -1)) {
            $(this).toggle();
        }
    });
});

$('.one-click').on('click', function(e) {
    $(this).attr('disabled', '');
});

window.setValidationWithCallBack('#form-modal-found-animal', function () {
    $('#foundAnimalModal').modal('hide');
    $('#foundAnimalSuccess').modal('show');
});