const htmlvalidate = require('cypress-html-validate/dist/plugin');

module.exports = (on, config) => {
    require('cypress-terminal-report/src/installLogsPrinter')(on);

    htmlvalidate.install(on, {
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
