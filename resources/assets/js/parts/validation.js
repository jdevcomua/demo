showValidationErrors = function (err) {
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
};

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