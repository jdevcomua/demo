const ACT_SHOW = 'show';
const ACT_EDIT = 'edit';
const ACT_DESTROY = 'destroy';

const methods = {
    [ACT_SHOW]: 'GET',
    [ACT_EDIT]: 'GET',
    [ACT_DESTROY]: 'DELETE',
};


$(document).on('click','table.datatable .act-show', function(e) {
    e.preventDefault();
    makeAction(ACT_SHOW, $(this));
});

$(document).on('click','table.datatable .act-edit', function(e) {
    e.preventDefault();
    makeAction(ACT_EDIT, $(this));
});

$(document).on('click','table.datatable .act-destroy', function(e) {
    e.preventDefault();
    makeAction(ACT_DESTROY, $(this));
});

function makeAction(act, elem) {
    const table = elem.parents('table.datatable');

    const id = elem.data('id');
    const method = methods[act];
    let url = table.data('act-' + act + '-url');
    const msg = table.data('act-' + act + '-msg');

    if (msg !== undefined && !confirm(msg)) return;
    if (id === undefined) throw new Error('Item doesn\'t have an id');
    if (url === undefined) throw new Error('Missed url for action \'' + act + '\'');

    url = url.replace("XXX", id);

    if (method !== 'GET') {
        submitForm(url, method);
    } else {
        window.location.href = url;
    }
}

function submitForm(action, method) {
    let form = $('<form action="' + action + '" method="POST"></form>');
    form.appendTo('body');

    form.append('<input type="hidden" name="_token" value="' + csrf_token + '"></input>');
    form.append('<input type="hidden" name="_method" value="' + method + '"></input>');

    form.submit()
}