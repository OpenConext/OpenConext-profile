$('html').removeClass('no-js');

var localeSwitches  = $('.locale-switch');

localeSwitches.each(function(index, localeSwitch) {
    var localeOptions = $('option', localeSwitch);

    for (var i = 0; i < localeOptions.length; i++) {
        var localeOptionButton = $('<button>');

        if ($(localeOptions[i]).prop('selected')) {
            localeOptionButton.addClass('active');
        }

        localeOptionButton.text(localeOptions[i].text)
            .attr('data-locale', localeOptions[i].value);

        $(localeSwitch).append(localeOptionButton);
    }
});

$(document).on('click', '.locale-switch button[data-locale]' , function(e) {
    var localeChoice = $(e.target).attr('data-locale');

    $('.locale-switch:first option').filter(function() {
        return this.value === localeChoice;
    }).prop('selected', true);

    $('.locale-switch:first form').submit();
});

$(document).on('click', '[data-service-details-state] .service-details-state-toggle', function (e) {
    var serviceDetailsState = $(e.target).parents('[data-service-details-state]'),
        serviceDetailsToggle = serviceDetailsState.attr('data-service-details-state');

    if (serviceDetailsToggle === 'closed' || serviceDetailsToggle === 'initial') {
        serviceDetailsState.attr('data-service-details-state', 'open');
    } else {
        serviceDetailsState.attr('data-service-details-state', 'closed');
    }
});
