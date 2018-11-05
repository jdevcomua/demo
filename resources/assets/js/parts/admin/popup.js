popupEdit = {};

popupEdit.id = 0;

popupEdit.init = function (urlGet, urlPut, beforeOpen, afterClose) {
    $(document).on('click','.edit', function(e) {
        e.preventDefault();
        popupEdit.id = $(this).data('id');
        axios.get(fillPlaceholder(urlGet, popupEdit.id))
            .then(function (response) {
                beforeOpen(response);
                openPopup('modal-edit');
            })
    });

    $('#modal-form-edit').submit(function (e) {
        e.preventDefault();
        const data = _.object($("#modal-form-edit").serializeArray()
                .map(function(v) {
                    return [v.name, v.value];
                })
        );
        axios.put(fillPlaceholder(urlPut, popupEdit.id), data)
            .then(function (response) {
                closePopup();
                afterClose();
            })
            .catch(function (error) {
                console.log(error);
                //TODO validation errors
            });
    });
};

function fillPlaceholder(url, id) {
    return url.replace('XXX', id);
}

function openPopup(popup) {
    $.magnificPopup.open({
        removalDelay: 500,
        items: {
            src: '#' + popup
        },
        callbacks: {
            beforeOpen: function (e) {
                this.st.mainClass = 'mfp-slideDown';
            }
        },
        midClick: true
    });
}
function closePopup() {
    $.magnificPopup.close();
}
