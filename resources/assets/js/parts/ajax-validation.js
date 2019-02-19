window.setValidationWithCallBack = function (formSelector, callBackOnSuccess) {
    var $form = $(formSelector);
    $form.find('.submit-ajax').click(function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var ajaxData = new FormData(form.get(0));

        //todo: needs refactoring, replacement of hardcoded input name
        let ajaxDataFiles = ajaxData.getAll('documents[]');
        let ajaxDataFileToAdd = [];

        let fileNamesToDelete = fileNamesToDeleteFromFormData(form, 'documents[]');

        ajaxData.delete('documents[]');

        if (fileNamesToDelete.length) {
            for (let i = 0; i < ajaxDataFiles.length; i++) {
                let name = ajaxDataFiles[i].name;
                if (fileNamesToDelete.indexOf(name) === -1) {
                    ajaxDataFileToAdd.push(ajaxDataFiles[i]);
                }
            }
            if (ajaxDataFileToAdd.length) {
                for (let i = 0; i < ajaxDataFileToAdd.length; i++) {
                    ajaxData.append('documents[]', ajaxDataFileToAdd[i]);
                }
            }
        } else {
            if (fileNamesToDelete === -1) {
                for (let i = 0; i < ajaxDataFiles.length; i++) {
                    ajaxData.append('documents[]', ajaxDataFiles[i]);
                }
            }
        }

        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: ajaxData,
            contentType: false,
            processData: false,
            success: callBackOnSuccess,
            error: function (errors) {
                $('.submit-ajax').removeAttr('disabled');
                fillErrors(form, errors);
            }
        });
        $(this).attr('disabled', '');
    });
};

function fillErrors(form, errors) {
    errors = errors['responseJSON']['errors'];

    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            var inputName = key;
            if (key.indexOf('.') !== -1) {
                inputName = key.substr(0, key.indexOf('.')) + '[]';
            }
            form.find('[name="' + inputName + '"]').siblings('.validation-error').empty().append("<p>" + errors[key][0] + "</p>").removeClass('hidden');
        }
    }
}

$('.validation-error').on('click', function () {
    $(this).addClass('hidden');
});

function fileNamesToDeleteFromFormData($form, inputName) {
    let $filesListItems = $form.find('[name="' + inputName + '"]').siblings('.files-list').children();
    let fileListItems = [];

    let filesFromInput = $form.find('input[name="' + inputName + '"]').prop('files');
    let filesFromInputNames = [];
    let fileNamesToDelete = [];



    if ($filesListItems.length) {
        $filesListItems.each(function () {
            fileListItems.push($(this).find('.file-name').text() + $(this).find('.file-ext').text())
        });


        for(let i = 0; i < filesFromInput.length; i++) {
            filesFromInputNames.push(filesFromInput[i].name);
        }

        for (let i = 0; i < filesFromInputNames.length; i++) {
            if (fileListItems.indexOf(filesFromInputNames[i]) === -1) {
                fileNamesToDelete.push(filesFromInputNames[i]);
            }
        }

        if (!fileNamesToDelete.length) {
            return -1;
        }

        return fileNamesToDelete;
    }
    return [];
}