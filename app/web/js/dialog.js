$(function () {
    $('.list__details').on('keydown', function (e) {
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
                            $(e.target).trigger('click');
                            break;
                    }
                });
                break;
        }
    });
});
