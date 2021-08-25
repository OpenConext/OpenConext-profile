window.addEventListener('load', () => {
    document.querySelector('.list__details').addEventListener('keydown', function (e) {
        const SPACE      = 32;
        const classList = e.target.classList;

        switch (e.keyCode) {
            case SPACE:
                classList.forEach(className => {
                    switch (className) {
                        case 'listDetails__name':
                        case 'listDetails__title':
                        case 'listDetails__statusArrow':
                            e.preventDefault();
                            const clickEvent = new MouseEvent('click');
                            e.target.dispatchEvent(clickEvent);
                            break;
                    }
                });
                break;
        }
    });
});
