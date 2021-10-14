Cypress.Commands.add('fillUsername', (username = 'Granny') => {
    cy.get('#username').type(username);
});

Cypress.Commands.add('fillPassword', (pass = 'Weatherwax') => {
    cy.get('#password').type(pass);
});

Cypress.Commands.add('submitLoginForms', () => {
    cy.get('input[type="submit"].button').then((loginButton) => {
        loginButton.trigger('click');
        setTimeout(() => {
            console.log('waiting');
        }, 600);
    });
    cy.checkForConsent();
});

Cypress.Commands.add('checkForProfile', (tries = 1) => {
    cy.get('body').then((body) => {
        const isProfileHeader = document.querySelector('.header__profile');

        if (!!isProfileHeader) {
            cy.get('.header__profile');
        } else {
            if (tries < 20) {
                cy.checkForProfile(tries++);
            }
        }
    });
});

Cypress.Commands.add('checkForUrl', (url) => {
    cy.url().should('eq', url);
});

Cypress.Commands.add('checkForConsent', () => {
    cy.get('body').then((body) => {
        const isConsentPage = document.querySelector('.consent');

        if (!!isConsentPage) {
            cy.wait(100);
            cy.get('#accept').submit();
        }
    });
});

Cypress.Commands.add('login', (username = 'Tiffany', pass = 'Aching', submit = true, url = 'https://profile.vm.openconext.org') => {
    cy.visit(url);
    cy.fillUsername(username);
    cy.fillPassword(pass);
    if (submit) {
        cy.submitLoginForms();
        cy.wait(300);
    }
    cy.checkForUrl(url + '/');
});
