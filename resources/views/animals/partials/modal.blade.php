function () {
    return '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
        '</button>' +
    '</div>' +
    '<div class="modal-body">' +
        '<form onsubmit="return animalSearch.searchAnimal()">' +
            '<div class="row">' +
                '<div class="col-sm-6">' +
                    '<h3>НОМЕР ЖЕТОНУ</h3>' +
                    '<p>За цим номером ми спробуємо знайти тварину.<br><br>' +
                    'Номер повинен бути від 5 до 8 символів та складатися тільки з кириличних літер або цифр' +
                    '</p>' +
                '</div>' +
                '<div class="col-sm-6">' +
                    '<h4>Номер</h4>' +
                    '<input type="text" name="badge" id="badge" class="invisible-input" required>' +
                '</div>' +
            '</div>' +
            '<button type="submit" class="pull-right btn search btn-primary">Пошук</button>' +
        '</form>' +
    '</div>'
}