window.$ = window.jQuery = require('jquery');

require('./bootstrap');

require('./smoothscroll');

require('bootstrap-datepicker');

require('selectize');


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

/////////////////////////////////////////
// Date picker init

$(function () {
    $.fn.datepicker.dates['uk'] = {
        days: ["Неділя", "Понеділок", "Вівторок", "Середа", "Четвер", "П'ятниця", "Субота"],
        daysShort: ["Нед", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
        daysMin: ["Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        months: ["Cічень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"],
        monthsShort: ["Січ", "Лют", "Бер", "Кві", "Тра", "Чер", "Лип", "Сер", "Вер", "Жов", "Лис", "Гру"],
        today: "Сьогодні",
        clear: "Очистити",
        weekStart: 1
    };
    $('.datepicker input').datepicker({
        format: "dd/mm/yyyy",
        language: "uk",
        locale: "uk",
        autoclose: true
    });

    updateFileDeletes();
});
/////////////////////////////////////////

/////////////////////////////////////////
// Image preview

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(input).parent().css('background-image', 'url(' + e.target.result + ')');
            $(input).parent().addClass('filled');
            $(input).parent().addClass('canBeDeleted');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).on('change',".imageInput", function() {
    readURL(this);
});
$(document).on('click', '.canBeDeleted', function () {
    var id = $(this).find('input').attr('id');
    var parent = $(this).parent();
    if($(this).hasClass('photo-item-main')) {
        var main = true;
    }
    var where = $(this).next();
    $(this).remove();
    if (main) {
        $('<label class="photo-item photo-item-main" for="image' + id + '">\n' +
            '                        <input type=\'file\' name="images[' + id + ']" id="image' + id + '" class="imageInput" />\n' +
            '                        <span class="add-btn"></span>\n' +
            '                    </label>').insertBefore(where);
    } else {
        $('<label class="photo-item" for="image' + id + '">\n' +
            '                        <input type=\'file\' name="images[' + id + ']" id="image' + id + '" class="imageInput" />\n' +
            '                        <span class="add-btn"></span>\n' +
            '                    </label>').insertBefore(where);
    }

});
/////////////////////////////////////////

/////////////////////////////////////////
// Selectize

var options = {
    valueField: 'value',
    labelField: 'name',
    searchField: ['name']
};
var breeds = $('.form-group.select select#breed').selectize(options);
var colors = $('.form-group.select select#color').selectize(options);
var furs = $('.form-group.select select#fur').selectize(options);

$('input[name="species"]').change(function(event) {
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

if ($('input[name="species"]').length) {
    updateSelects($("input:radio[name ='species']:checked,input:radio[name ='species'].checked").val());
}
/////////////////////////////////////////

/////////////////////////////////////////
// Dropzone

var isAdvancedUpload = function() {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

var $form = $('form#form');
var $fileList = $('.file-uploader .files-list');
var $manualInput = $('#manual-upload');

if (isAdvancedUpload) {

    var droppedFiles = [];

    $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
    })
        .on('dragover dragenter', function() {
            $form.addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            $form.removeClass('is-dragover');
        })
        .on('drop', function(e) {
            droppedFiles = droppedFiles.concat(toArray(e.originalEvent.dataTransfer.files));
            showFiles(droppedFiles);
        });

}

$manualInput.on('change', function(e) {
    droppedFiles = droppedFiles.concat(toArray($(this).prop("files")));
    showFiles(droppedFiles);
});

function toArray(fileList) {
    return Array.prototype.slice.call(fileList);
}

function showFiles(files) {
    $fileList.find('.file-item:not(.exists)').remove();
    for (var i = 0; i < files.length; i++) {
        var arr = files[i].name.split('.');
        var name = '';
        var ext = '';
        switch (arr.length) {
            case 1:
                name = arr[0];
                break;
            case 2:
                name = arr[0];
                ext = '.' + arr[1];
                break;
            default:
                ext = '.' + arr.pop();
                name = arr.join('.');
                break;
        }
        var elem = $('<div class="file-item"></div>');
        elem.append($('<span class="file-name">' + name + '</span>'));
        elem.append($('<span class="file-ext">' + ext + '</span>'));
        elem.append($('<span class="file-delete"></span>').data('id', i));
        $fileList.append(elem);
    }
    updateFileDeletes()
}

function updateFileDeletes() {
    var $files = $('.file-delete');
    $files.off();
    $files.on('click', function () {
        if ($(this).hasClass('exists')) {
            $(this).parent('.file-item').remove();
            $.ajax({
                url: $(this).data('rem'),
                type: 'post',
                error: function(data) {
                    console.error(data);
                }
            });
        } else {
            droppedFiles.splice($(this).data('id'), 1);
            showFiles(droppedFiles);
        }
    })
}

$form.on('submit', function (e) {
    if (isAdvancedUpload) {
        e.preventDefault();

        hideNames();

        var ajaxData = new FormData($form.get(0));
        var progressBar = $('.uploader-overlay .progress-bar');
        var progressValue = $('.uploader-overlay .value');

        if (droppedFiles) {
            $.each( droppedFiles, function(i, file) {
                ajaxData.append('documents[]', file );
            });
        }

        var ajax = new XMLHttpRequest();

        ajax.upload.onprogress = function (event) {
            var pc = parseInt(event.loaded / event.total * 100);

            progressBar.css('width', pc + "%");
            progressValue.text(pc + " %");
        };

        ajax.onload = function () {
            if (ajax.readyState === 4) {
                var data = JSON.parse(ajax.responseText);
                if (ajax.status === 200) {
                    $form.removeClass('is-uploading');
                    if (data.url) {
                        window.location.href = data.url;
                    }
                } else {
                    $('body').removeClass('no-scroll');
                    $('.uploader-overlay').hide();
                    showNames();
                    showValidationErrors(data.errors);
                }
            }

        };

        $('.uploader-overlay').show();
        $('body').addClass('no-scroll');
        ajax.open($form.attr('method'), $form.attr('action'), true);
        ajax.send(ajaxData);

    }
});

function hideNames() {
    var form = $('form#form :input');
    form.each(function (elem) {
        if ($(this).attr('type') == 'file' && $(this).val() == '') {
            $(this).data('name', $(this).attr('name'));
            $(this).removeAttr('name');
        }
    });
}
function showNames() {
    var form = $('form#form :input');
    form.each(function (elem) {
        if ($(this).attr('type') === 'file')
            $(this).attr('name', $(this).data('name'));
    });
}
/////////////////////////////////////////

/////////////////////////////////////////
// Validation errors

function showValidationErrors(err) {
    var $highestElem = false;
    for (var key in err) {
        if (err.hasOwnProperty(key)) {
            var search_key = key.split('.')[0];
            if (search_key == 'documents') {
                $elem = $('.file-uploader .validation-error');
            } else {
                var $elem = findNearest($('[name^=' + search_key + ']').first(), '.validation-error');
            }
            $elem.text(err[key]);
            $elem.removeClass('hidden');

            if (!$highestElem || $highestElem.offset().top > $elem.offset().top) {
                $highestElem = $elem;
            }
        }
    }

    $([document.documentElement, document.body]).animate({
        scrollTop: $highestElem.offset().top
    }, 1000);
}

function findNearest(elem, selector) {
    var $res;
    var level = 0;
    do {
        $res = elem.siblings(selector);
        elem = elem.parent();
        level++;
    } while ($res.length === 0 && level < 3);
    return $res;
}

$('input, .photo-item, .form-group.select div, textarea').on('click change', function () {
    findNearest($(this), '.validation-error').addClass('hidden');
});
/////////////////////////////////////////