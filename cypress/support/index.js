// ***********************************************************
// This is a great place to put global configuration and
// behavior that modifies Cypress.
// ***********************************************************
import './commands';
import './commands/login';
import 'cypress-axe';
import 'cypress-html-validate/dist/commands';

require('cypress-terminal-report/src/installLogsCollector')();

Cypress.on('uncaught:exception', (err, runnable) => {
    // we still want to ensure there are no other unexpected
    // errors, so we let them fail the test
})
