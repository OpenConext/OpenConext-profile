window.addEventListener('load', function () {
    /**
     * handle all things locale-switch related
     */
    const localeSwitchElements = document.querySelectorAll('.locale-switch');
    if (!!localeSwitchElements) {
        for (let i = 0; i < localeSwitchElements.length; i++) {
            const localeSwitch = localeSwitchElements[i];
            /**
             * Dynamically add all buttons for the locale switch
             */
            const optionElements = localeSwitch.querySelectorAll('option');
            if (!!optionElements) {
                for (let j = 0; j < optionElements.length; j++) {
                    const optionElement = optionElements[j];
                    const value = optionElement.value;
                    const text = optionElement.textContent;
                    const button = document.createElement('button');
                    button.setAttribute('data-locale', value);
                    button.setAttribute('type', 'button');
                    button.textContent = text;

                    if (optionElement.matches("[selected='selected']")) {
                        button.classList.add('active');
                    }

                    localeSwitch.appendChild(button);
                }
            }

            /**
             * Add a label for accessibility & because the choicetype rendering sucks
             */
            const select = localeSwitch.querySelector('select');
            if (!!select) {
                const id = select.getAttribute('id');
                const text = localeSwitch.getAttribute('data-label');
                const label = document.createElement('label');
                label.setAttribute('for', id);
                label.textContent = text;
                label.classList.add('locale-switch-label');

                localeSwitch.firstElementChild.prepend(label);
            }

        /**
         * if a locale button is clicked, submit the form with the value of the clicked button.
         */
            localeSwitch.addEventListener('click', function (e) {
                const self = e.target;
                if (self.matches('button[data-locale]:not(.active)')) {
                    const choice = self.getAttribute('data-locale');
                    const optionElements = localeSwitch.querySelectorAll('option');
                    if (!!optionElements) {
                        for (let k = 0; k < optionElements.length; k++) {
                            const optionElement = optionElements[k];
                            if (optionElement.value === choice) {
                                optionElement.setAttribute('selected', 'selected');
                                continue;
                            }

                            optionElement.removeAttribute('selected');
                        }
                    }

                    const form = localeSwitch.querySelector('form');
                    if (!!form) {
                        form.submit();
                    }
                }
            });
        }
    }
});
