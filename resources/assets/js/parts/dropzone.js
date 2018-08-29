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