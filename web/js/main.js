$('html').removeClass('no-js');

var localeSwitches  = $('.locale-switch');

localeSwitches.each(function (index, localeSwitch) {
    var localeOptions = $('option', localeSwitch);

    for (var i = 0; i < localeOptions.length; i++) {
        var localeOptionButton = $('<button>'),
            localeOption = $(localeOptions[i]);

        localeOptionButton.toggleClass('active', localeOption.prop('selected'));

        localeOptionButton.text(localeOption.text())
            .attr('data-locale', localeOption.val());

        $(localeSwitch).append(localeOptionButton);
    }
});

$(document).on('click', '.locale-switch button[data-locale]' , function (e) {
    var button = $(e.target),
        localeChoice = button.attr('data-locale'),
        localeForm = button.parents('.locale-switch').find('form');

    localeForm.find('option').filter(function() {
        return this.value === localeChoice;
    }).prop('selected', true);

    localeForm.submit();
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
