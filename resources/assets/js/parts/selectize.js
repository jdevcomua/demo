$(document).ready(function () {

var options = {
    valueField: 'value',
    labelField: 'name',
    searchField: ['name']
};
var breeds = $('.form-group.select select.breed').selectize(options);
var colors = $('.form-group.select select.color').selectize(options);
var furs = $('.form-group.select select.fur').selectize(options);
var device_types = $('select#device_type').selectize(options);

var xhrDeviceTypes;
if (device_types.length) {
    device_types[0].selectize.load(function (callback) {
        xhrDeviceTypes && xhrDeviceTypes.abort();
        xhrDeviceTypes = $.ajax({
            url: '/ajax/device-types',
            success: function success(results) {
                callback(JSON.parse(results));
            },
            error: function error() {
                callback();
            }
        });
    });
}

var selects = [breeds, colors, furs];
var selects_names = ['breeds', 'colors', 'furs'];
var select_names_singular = ['breed', 'color', 'fur'];

$('input[name="species"]').change(function(event) {
    for (var i = 0; i < selects.length; i++) {
        selects[i].each(function (index, value) {
            value.selectize.clear();
            value.selectize.clearOptions();
        });
    }

    updateSelects(event.target.value);
});

var xhrs = [];

function updateSelects(species) {
    for (var i = 0, j = 0; i < selects.length; i++) {
        selects[i].each(function (index, value) {
            value.selectize.load(function (callback) {
                xhrs[j] && xhrs[j].abort();
                xhrs[j] = $.ajax({
                    url: '/ajax/species/'+species+'/' + selects_names[i],
                    success: function (results) {
                        callback(JSON.parse(results));
                        checkDefaultValues();
                    },
                    error: function () {
                        callback();
                    }
                })
            });
            j++;
        });
    }
}

function checkDefaultValues() {
    var select_name = '';
    for (var i = 0; i < selects.length; i++) {
        select_name = select_names_singular[i];
        selects[i].each(function (index, value) {
            value.selectize.setValue($(this).data('value'));
        });
    }
}
$(document).ready(function () {
    if ($('input[name="species"]').length) {
        updateSelects($("input:radio[name ='species']:checked,input:radio[name ='species'].checked").val());
    }
});
});