import {terminalLog} from '../../functions/terminalLog';

context('verify a11y of homepage', () => {
    beforeEach(() => {
        cy.login();
    });

    it('contains no a11y problems on load', () => {
        cy.removeSFToolbar();
        cy.injectAxe();
        cy.checkA11y(null, null, terminalLog);
    });

    it('contains no html errors', () => {
        cy.wait(300).then(() => {
            cy.removeSFToolbar();
            cy.htmlvalidate();
        });
    });
});
