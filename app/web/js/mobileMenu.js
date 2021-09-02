window.addEventListener('load', function () {
    document.querySelector('.navigation__mobileButton').addEventListener('click', function (e) {
        const self = e.target;
        const currentValue = self.getAttribute('aria-pressed');

        if (currentValue === 'false') {
            self.setAttribute('aria-pressed', 'true');
            self.setAttribute('aria-expanded', 'true');
            document.querySelector('.navigation').classList.add('openMenu');
            document.querySelector('.navigation__item:first-of-type .navigation__link').focus();
        } else {
            self.setAttribute('aria-pressed', 'false');
            document.querySelector('.navigation').classList.remove('openMenu');
        }
    });

    function fireClickEvent(element)
    {
        const clickEvent = new MouseEvent('click');
        element.dispatchEvent(clickEvent);
    }

    document.querySelector('.navigation').addEventListener('keydown', function (e) {
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
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case '':
                            e.preventDefault();
                            break;
                    }
                }
                break;

            case SPACE:
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case 'navigation__mobileButton':
                        case 'navigation__link':
                            e.preventDefault();
                            fireClickEvent(e.target);
                            break;
                    }
                }
                break;

            case ARROWRIGHT:
            case ARROWDOWN:
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-last')) {
                                document.querySelector('.navigation__link-first').focus();
                                return;
                            }
                            e.target.parentElement.nextElementSibling.firstElementChild.focus();
                            break;
                    }
                }
                break;

            case ARROWLEFT:
            case ARROWUP:
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-first')) {
                                document.querySelector('.navigation__link-last').focus();
                                return;
                            }
                            e.target.parentElement.previousElementSibling.firstElementChild.focus();
                            break;
                    }
                }
                break;

            case HOME:
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-first')) {
                                return;
                            }
                            document.querySelector('.navigation__link-first').focus();
                            break;
                    }
                }
                break;

            case END:
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case 'navigation__link':
                            e.preventDefault();
                            if (classList.contains('navigation__link-last')) {
                                return;
                            }
                            document.querySelector('.navigation__link-last').focus();
                            break;
                    }
                }
                break;

            case ESCAPE:
                for (let i = 0; i < classList.length; i++) {
                    const className = classList[i];
                    switch (className) {
                        case 'navigation__link':
                        case 'navigation__mobileButton':
                        case 'navigation__mobileIcon':
                        case 'navigation':
                        case 'navigation__list':
                        case 'navigation__item':
                            e.preventDefault();
                            const mobileButton = document.querySelector('.navigation__mobileButton');

                            if (mobileButton.attr('aria-pressed') === 'true') {
                                fireClickEvent(mobileButton);
                                mobileButton.focus();
                            }
                            break;
                    }
                }
                break;
        }
    });
});
