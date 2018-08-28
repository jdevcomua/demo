function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(input).parent().css('background-image', 'url(' + e.target.result + ')');
            $(input).parent().addClass('filled');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).on('change',".imageInput", function() {
    readURL(this);
});
$(document).on('click', '.filled', function (e) {
    $(this).find('input').val('');
    $(this).css('background-image', '');
    $(this).removeClass('filled');
    return false;
});