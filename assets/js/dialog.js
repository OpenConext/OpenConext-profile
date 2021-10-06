window.addEventListener('load', function () {
    const listDetailsElements = document.querySelectorAll('.listDetails__details');

    if (!!listDetailsElements) {
        for (let i = 0; i < listDetailsElements.length; i++) {
            const listDetailsElement = listDetailsElements[i];
            listDetailsElement.addEventListener('keydown', function (e) {
                const SPACE      = 32;
                const classList = e.target.classList;
                switch (e.keyCode) {
                    case SPACE:
                        for (let i = 0; i < classList.length; i++) {
                            const className = classList[i];
                            switch (className) {
                                case 'listDetails__name':
                                case 'listDetails__title':
                                case 'listDetails__statusArrow':
                                    e.preventDefault();
                                    const clickEvent = new MouseEvent('click');
                                    e.target.dispatchEvent(clickEvent);
                                    break;
                            }
                        }
                        break;
                }
            });
        }
    }
});
