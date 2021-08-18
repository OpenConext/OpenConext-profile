$(document).on('change', 'input.confirmation', function (e) {
    const checkbox = $(e.target),
       button = $('button.confirm-button');

    if (checkbox.is(':checked') === true) {
        button.attr('disabled', false);
    } else {
        button.attr('disabled', true);
    }
});
