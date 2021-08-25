window.addEventListener('load', () => {
    /**
     * handle all things locale-switch related
     */
    document.querySelectorAll('.locale-switch').forEach((function (localeSwitch) {
      /**
       * Dynamically add all buttons for the locale switch
       */
        localeSwitch.querySelectorAll('option').forEach(function (optionElement) {
            const value = optionElement.value;
            const text = optionElement.textContent;
            const button = document.createElement('button');
            button.setAttribute('data-locale', value);
            button.textContent = text;

            if (optionElement.matches("[selected='selected']")) {
                button.classList.add('active');
            }

            localeSwitch.appendChild(button);
        });

      /**
       * if a locale button is clicked, submit the form with the value of the clicked button.
       */
        localeSwitch.addEventListener('click', function (e) {
            const self = e.target;
            if (self.matches('button[data-locale]:not(.active)')) {
                const choice = self.getAttribute('data-locale');
                localeSwitch.querySelectorAll('option').forEach(function (optionElement) {
                    if (optionElement.value === choice) {
                        optionElement.setAttribute('selected', 'selected');
                        return true;
                    }

                    optionElement.removeAttribute('selected');
                });

                localeSwitch.querySelector('form').submit();
            }
        });
    }));
});
