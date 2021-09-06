import {terminalLog} from '../../functions/terminalLog';

context('verify a11y of my surfconext page', () => {
    it('contains no a11y problems on load', () => {
        cy.login().then(() => {
            cy.visit('https://profile.vm.openconext.org/').then(() => {
                cy.visit('https://profile.vm.openconext.org/my-surfconext').then(() => {
                    cy.removeSFToolbar();
                    cy.injectAxe();
                    cy.checkA11y(null, null, terminalLog);
                });
            });
        });
    });

    it('contains no html errors', () => {
        cy.login().then(() => {
            cy.visit('https://profile.vm.openconext.org/').then(() => {
                cy.visit('https://profile.vm.openconext.org/my-surfconext').then(() => {
                    cy.wait(300).then(() => {
                        cy.removeSFToolbar();
                        cy.htmlvalidate();
                    });
                });
            });
        });
    });
});
