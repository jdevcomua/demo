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