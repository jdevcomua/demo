animalSearch = {};

animalSearch.Init = function (
    searchUrl,
    requestUrl,
    modalSearch,
    modalFound,
    modalNotFound,
) {
    animalSearch.searchUrl = searchUrl;
    animalSearch.requestUrl = requestUrl;
    animalSearch.modalSearch = modalSearch;
    animalSearch.modalFound = modalFound;
    animalSearch.modalNotFound = modalNotFound;


    $(document).on('click', '#animal-search', function(e) {
        $('#modal-content').html(animalSearch.modalSearch());
    });

    $(document).on('click', '.not-found-search', function() {
        $('#searchModal').modal('hide');
        setTimeout(function () {
            $('#requestSearchModal').modal('show');
        }, 500);
        $('body').addClass('modal-open');
    });
};

animalSearch.searchAnimalRequest = function (elem) {
    var id = $(elem).attr('data-id');

    $.ajax({
        url: animalSearch.requestUrl,
        type: 'post',
        data: {
            animal_id: id
        },
        success: function (data) {
            if (data.message && data.message == 'already') {
                // TODO modal already requested this animal
            }
            $('#searchModal').modal('hide');
            window.location.reload();
        },
        error: function (data) {
            $('#modal-content').html(animalSearch.modalNotFound());
        }
    });
};

animalSearch.searchAnimal = function () {
    var badge = $('#badge').val();

    $.ajax({
        url: animalSearch.searchUrl,
        type: 'post',
        data: {
            badge: badge
        },
        success: function (data) {
            // console.log(data);
            $('#modal-content').html(animalSearch.modalFound(data));
        },
        error: function (data) {
            $('#modal-content').html(animalSearch.modalNotFound());
        }
    });

    return false;
};