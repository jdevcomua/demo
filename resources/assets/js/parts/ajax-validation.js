window.setValidationWithCallBack = function (formSelector, callBackOnSuccess) {
    var $form = $(formSelector);
    $form.find('.submit-ajax').click(function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var ajaxData = new FormData(form.get(0));

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