import {terminalLog} from '../../functions/terminalLog';

context('verify a11y of my services page', () => {
    it('contains no a11y problems on load', () => {
        cy.login().then(() => {
            cy.visit('https://profile.vm.openconext.org/').then(() => {
                cy.visit('https://profile.vm.openconext.org/my-services').then(() => {
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
                cy.visit('https://profile.vm.openconext.org/my-services').then(() => {
                    cy.wait(300).then(() => {
                        cy.removeSFToolbar();
                        cy.htmlvalidate({
                            "rules": {
                                "require-sri": [ "error", {
                                    "target": "crossorigin",
                                }],
                                /**
                                 * Heading-level:
                                 * This allows the page to have a h2 before a h1 (necessary due to navigation).
                                 */
                                "heading-level": [ "error", {
                                    "minInitialRank": "h2"
                                }],
                                "prefer-native-element": [
                                  "error", {
                                        "exclude": [ "button" ],
                                }],
                            },
                        });
                    });
                });
            });
        });
    });
});
