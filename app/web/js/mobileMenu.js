$(function () {
    $('.navigation__mobileButton').on('click', function (e) {
        const currentValue = $(this).attr('aria-pressed');

        if (currentValue === 'false') {
            $(this).attr('aria-pressed', 'true')
                   .attr('aria-expanded', 'true');
            $('.navigation').addClass('openMenu');
            $('.navigation__item:first-of-type .navigation__link').focus();
        } else {
            $(this).attr('aria-pressed', 'false');
            $('.navigation').removeClass('openMenu');
        }
    });

    $('.navigation').on('keydown', function (e) {
        const ENTER      = 13;
        const SPACE      = 32;
        const ARROWDOWN  = 40;
        const ARROWLEFT  = 37;
        const ARROWRIGHT = 39;
        const ARROWUP    = 38;
        const HOME       = 36;
        const END        = 35;
        const ESCAPE     = 27;
        const classList = e.target.classList;
        switch (e.keyCode) {
            case ENTER:
                classList.forEach(className => {
                    switch (className) {
                        case '':
                            e.preventDefault();
                            break;
                    }
                });
                break;

            case SPACE:
                classList.forEach(className => {
                    switch (className) {
                        case 'navigation__mobileButton':
                        case 'navigation__link':
                            e.preventDefault();
                            $(e.target).trigger('click');
                            break;
                    }
                });
                break;

            case ARROWRIGHT:
            case ARROWDOWN:
                classList.forEach(className => {
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-last')) {
                                $('.navigation__link-first').focus();
                                return;
                            }
                            $(e.target).parent().next().children().first().focus();
                            break;
                    }
                });
                break;

            case ARROWLEFT:
            case ARROWUP:
                classList.forEach(className => {
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-first')) {
                                $('.navigation__link-last').focus();
                                return;
                            }
                            $(e.target).parent().prev().children().first().focus();
                            break;
                    }
                });
                break;

            case HOME:
                classList.forEach(className => {
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-first')) {
                                return;
                            }
                            $('.navigation__link-first').focus();
                            break;
                    }
                });
                break;

            case END:
                classList.forEach(className => {
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-last')) {
                                return;
                            }
                            $('.navigation__link-last').focus();
                            break;
                    }
                });
                break;

            case ESCAPE:
                classList.forEach(className => {
                    switch (className) {
                        case 'navigation__link':
                        case 'navigation__mobileButton':
                        case 'navigation__mobileIcon':
                        case 'navigation':
                        case 'navigation__list':
                        case 'navigation__item':
                            e.preventDefault();
                            const mobileButton = $('.navigation__mobileButton');

                            if (mobileButton.attr('aria-pressed') === 'true') {
                                mobileButton.trigger('click').focus();
                            }
                            break;
                    }
                });
                break;
        }
    })
});
