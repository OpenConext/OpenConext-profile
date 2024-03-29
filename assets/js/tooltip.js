window.addEventListener('load', function () {
    const changeAriaExpanded = function (element) {
        const ariaExpanded = element.getAttribute('aria-expanded');
        const newValue = {
            false: true,
            true: false,
        };

        // We use the newValue object because ariaExpanded is a string containing either "true" or "false".
        // Casting that to a boolean will, in both instances, give us true.
        // By using an object we can use array notation to access the right value based on what we get back from the attribute.
        element.setAttribute('aria-expanded', newValue[ariaExpanded]);
    };

    const changeAriaPressed = function (element) {
        const ariaPressed = element.getAttribute('aria-pressed');
        const newValue = {
            false: true,
            true: false,
        };

        // We use the newValue object because ariaPressed is a string containing either "true" or "false".
        // Casting that to a boolean will, in both instances, give us true.
        // By using an object we can use array notation to access the right value based on what we get back from the attribute.
        element.setAttribute('aria-pressed', newValue[ariaPressed]);
    };

    const tooltipsAndModals = document.querySelectorAll('label.tooltip, label.modal');
    for (let i = 0; i < tooltipsAndModals.length; i++) {
        const tooltipOrModal = tooltipsAndModals[i];
        tooltipOrModal.addEventListener('click', function (e) {
            const id = e.target.getAttribute('for');
            const checkbox = document.getElementById(id);

            if (!!checkbox) {
                changeAriaExpanded(checkbox);
                changeAriaPressed(checkbox);

                setTimeout(function () {
                    const expanded = checkbox.getAttribute('aria-expanded');
                    const value = document.querySelector(`[data-for ="${id}"]`);
                    if (expanded === 'false' && !!value) {
                        value.focus();
                    } else {
                        e.target.focus();
                    }
                }, 200);
            }
        });

        tooltipOrModal.addEventListener('keydown', function (e) {
            const ENTER      = 13;
            const SPACE      = 32;

            switch (e.keyCode) {
                case ENTER:
                case SPACE:
                    const clickEvent = new MouseEvent('click');
                    e.target.dispatchEvent(clickEvent);
                    break;
            }
        });
    }
});

