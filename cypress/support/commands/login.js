Cypress.Commands.add('fillUsername', (username = 'Granny') => {
    cy.get('#username').type(username);
});

Cypress.Commands.add('fillPassword', (pass = 'Weatherwax') => {
    cy.get('#password').type(pass);
});

Cypress.Commands.add('submitLoginForms', () => {
    cy.get('.login-form').submit();
    cy.checkForConsent();
});

Cypress.Commands.add('checkForConsent', () => {
    cy.get('body').then((body) => {
        const isConsentPage = document.querySelector('.consent');

        if (!!isConsentPage) {
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
});
