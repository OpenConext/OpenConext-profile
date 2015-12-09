$(document).ready(function () {
    $('html').removeClass('no-js');

    // To prevent duplicates, use the first locale options as a reference for building buttons
    var localeOptions = $('.locale-switch:first option');

    for (var i = 0; i < localeOptions.length; i++) {
        var localeDataAttribute = 'data-locale="'+ localeOptions[i].value +'"';

        var localeOptionClassAttribute = '';
        if (localeOptions[i].selected) {
            localeOptionClassAttribute = 'class="active"';
        }

        var localeOptionButton =
            '<button ' + localeOptionClassAttribute + ' ' + localeDataAttribute + '>'
            + localeOptions[i].text
            + '</button>';
        $('.locale-switch').append(localeOptionButton);
    }

    $(document).on('click', '.locale-switch button[data-locale]' , function(e) {
        var localeChoice = $(e.target).attr('data-locale');
        $('.locale-switch:first option[value='+ localeChoice +']').attr('selected', true);

        $('.locale-switch:first form').submit();
    })
});
