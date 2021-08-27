const htmlvalidate = require('cypress-html-validate/dist/plugin');

module.exports = (on, config) => {
    htmlvalidate.install(on, {
        "rules": {
            "require-sri": [ "error", {
                "target": "crossorigin",
            }],
            "heading-level": 'warn',
            /**
             * Heading-level:
             * I opened a ticket to allow some options to be set on this, as the way it works now is not according to spec.
             * This should be updated when that issue is resolved: https://gitlab.com/html-validate/html-validate/-/issues/132
             */
            "no-missing-references": 'warn',
            /**
             * No-missing-references:
             * I opened a ticket to fix a bug in this rule.  It turns out that multiple id's in an aria-describedby always yield an error, even though a space separated list is ok.
             * This rule should be removed when that issue is resolved:
             * https://gitlab.com/html-validate/html-validate/-/issues/133
             */
        },
    });

    // debug a11y in ci
    on('task', {
        log(message) {
            console.log(message);

            return null;
        },
        table(message) {
            console.table(message);

            return null;
        }
      });

    return config;
};
