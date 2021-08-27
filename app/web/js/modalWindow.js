window.addEventListener('load', function () {
    const modalWindowButtons = document.querySelectorAll('.modalWindowButton');
    if (!!modalWindowButtons) {
        for (let i = 0; i < modalWindowButtons.length; i++) {
            const button = modalWindowButtons[i];
            button.addEventListener('click', function (e) {
                const id = e.target.getAttribute('data-id');
                const modal = document.getElementById(id);
                addModalTrap(modal);

                setTimeout(function () {
                    const cancel = modal.querySelector('.modalWindow__cancel');
                    cancel.focus({preventScroll: true});
                }, 200);
            });
        }
    }

    const cancelButtons = document.querySelectorAll('.modalWindow__cancel');
    if (!!cancelButtons) {
        for (let i = 0; i < cancelButtons.length; i++) {
            const cancelButton = cancelButtons[i];
            cancelButton.addEventListener('click', function (e) {
                const id = e.target.getAttribute('data-to');
                const modal = document.getElementById(id);

                setTimeout(function () {
                    const button = document.querySelector(`a[data-id="${id}"]`);
                    button.focus({preventScroll: true});
                    modal.removeEventListener('keydown', modalTrap);
                }, 200);
            });
        }
    }
});

function addModalTrap(modal)
{
    const focusableElements =
      'button:not([type="hidden"]), [href]:not([type="hidden"]), input:not([type="hidden"]), select:not([type="hidden"]), textarea:not([type="hidden"]), [tabindex]:not([tabindex="-1"]):not([type="hidden"])';
    const focusableContent = modal.querySelectorAll(focusableElements);
    const firstFocusableElement = focusableContent[0]; // get first element to be focused inside modal

    modal.addEventListener('keydown', modalTrap);

    firstFocusableElement.focus();
}

function modalTrap(e)
{
    let isTabPressed = e.key === 'Tab' || e.keyCode === 9;
    let isEscPressed = e.key === 'Escape' || e.keyCode === 27;
    const focusableElements =
      'button:not([type="hidden"]), [href]:not([type="hidden"]), input:not([type="hidden"]), select:not([type="hidden"]), textarea:not([type="hidden"]), [tabindex]:not([tabindex="-1"]):not([type="hidden"])';
    const focusableContent = this.querySelectorAll(focusableElements);
    const firstFocusableElement = focusableContent[0]; // get first element to be focused inside modal
    const lastFocusableElement = focusableContent[focusableContent.length - 1]; // get last element to be focused inside modal

    if (isEscPressed) {
        const cancelButton = this.querySelector('.modalWindow__cancel');
        const clickEvent = new MouseEvent('click');
        cancelButton.dispatchEvent(clickEvent);
        return;
    }

    if (!isTabPressed) {
        return;
    }

    if (e.shiftKey) { // if shift key pressed for shift + tab combination
        if (document.activeElement === firstFocusableElement) {
            lastFocusableElement.focus(); // add focus for the last focusable element
            e.preventDefault();
        }
    } else { // if tab key is pressed
        if (document.activeElement === lastFocusableElement) { // if focused has reached to last focusable element then focus first focusable element after pressing tab
            firstFocusableElement.focus(); // add focus for the first focusable element
            e.preventDefault();
        }
    }
}
