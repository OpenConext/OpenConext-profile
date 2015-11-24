$(document).ready(function() {
    $('html').removeClass('no-js');

    $(document).on('click', '[data-service-details-state] .service-details-state-toggle', function (e) {
        var serviceDetailsState = $(e.target).parents('[data-service-details-state]');
        var serviceDetailsToggle = serviceDetailsState.attr('data-service-details-state');

        if (serviceDetailsToggle === 'closed' || serviceDetailsToggle === 'initial') {
            serviceDetailsState.attr('data-service-details-state', 'open');
        } else {
            serviceDetailsState.attr('data-service-details-state', 'closed');
        }
    });
});
