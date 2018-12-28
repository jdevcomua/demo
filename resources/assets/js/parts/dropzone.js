$().ready(function () {
    $('form .file-uploader').each(function () {

        let dropzone = {
            $form: $(this).parents('form'),
            $fileList: $(this).find('.files-list'),
            $manualInput: $(this).find('input[type=file]'),

            droppedFiles: [],

            showFiles: function(files) {
                dropzone.$fileList.find('.file-item:not(.exists)').remove();
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
                    dropzone.$fileList.append(elem);
                }
                dropzone.updateFileDeletes()
            },

            updateFileDeletes: function () {
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
                        dropzone.droppedFiles.splice($(this).data('id'), 1);
                        dropzone.showFiles(dropzone.droppedFiles);
                    }
                })
            }
        };

        if (isAdvancedUpload) {

            dropzone.$form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function() {
                    dropzone.$form.addClass('is-dragover');
                })
                .on('dragleave dragend drop', function() {
                    dropzone.$form.removeClass('is-dragover');
                })
                .on('drop', function(e) {
                    dropzone.droppedFiles = dropzone.droppedFiles.concat(toArray(e.originalEvent.dataTransfer.files));
                    dropzone.showFiles(dropzone.droppedFiles);
                });

        }

        dropzone.$manualInput.on('change', function(e) {
            dropzone.droppedFiles = dropzone.droppedFiles.concat(toArray($(this).prop("files")));
            dropzone.showFiles(dropzone.droppedFiles);
        });

        dropzone.$form.on('submit', function (e) {
            if (isAdvancedUpload) {
                e.preventDefault();

                hideNames(dropzone.$form);

                var ajaxData = new FormData(dropzone.$form.get(0));
                var progressBar = $('.uploader-overlay .progress-bar');
                var progressValue = $('.uploader-overlay .value');

                if (dropzone.droppedFiles) {
                    $.each( dropzone.droppedFiles, function(i, file) {
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
                        var data;
                        try {
                            data = JSON.parse(ajax.responseText);
                        } catch (e) {}
                        if (ajax.status === 200) {
                            dropzone.$form.removeClass('is-uploading');
                            if (data.url) {
                                window.location.href = data.url;
                            }
                        } else {
                            $('body').removeClass('no-scroll');
                            $('.uploader-overlay').hide();
                            showNames(dropzone.$form);
                            if (ajax.status === 422) showValidationErrors(data.errors);
                        }
                    }

                };

                $('.uploader-overlay').show();
                $('body').addClass('no-scroll');
                ajax.open(dropzone.$form.attr('method'), dropzone.$form.attr('action'), true);
                ajax.send(ajaxData);

            }
        });

    })
});

var isAdvancedUpload = function() {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();


function toArray(fileList) {
    return Array.prototype.slice.call(fileList);
}



// $(function () {
//     updateFileDeletes();
// });

function hideNames(form) {
    form = form.find(':input');
    form.each(function (elem) {
        if ($(this).attr('type') == 'file' && $(this).val() == '') {
            $(this).data('name', $(this).attr('name'));
            $(this).removeAttr('name');
        }
    });
}
function showNames(form) {
    form = form.find(':input');
    form.each(function (elem) {
        if ($(this).attr('type') === 'file')
            $(this).attr('name', $(this).data('name'));
    });
}
