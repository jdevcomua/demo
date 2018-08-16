'<div class="modal-header">' +
    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
    '</div>' +
'<div class="modal-body">'+
    '<div class="d-flex flex-column justify-content-center text-center">'+
        '<img src="/img/found.svg" alt="">'+
        '<h2>Ми знайшли тварину</h2>'+
        '<div class="row">'+
            '<div class="col-md-6">'+
                '<p class="grey">' + data.animal.species_text + '</p>'+
                '<p class="black"> ' + data.animal.nickname + '</p>'+
                '<p class="grey">Дата народження</p>'+
                '<p class="black"> ' + data.animal.birthday_text + '</p>'+
            '</div>'+
            '<div class="col-md-6">'+
                '<p class="grey">Масть</p>'+
                '<p class="black"> ' + data.animal.color_text + '</p>'+
                '<p class="grey">Порода</p>'+
                '<p class="black"> ' + data.animal.breed_text + '</p>'+
            '</div>'+
        '</div>'+
        '<button type="button" data-id="' + data.animal.id + '" class="ml-auto mt-6 btn confirm btn-primary" style="width: 350px;">Це моя тварина</button>'+
    '</div>'+
'</div>'