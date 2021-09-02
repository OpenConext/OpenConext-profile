'use strict';

/**
 * Element.matches polyfill taken from MDN https://developer.mozilla.org/en-US/docs/Web/API/Element/matches
 */
(function () {
    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector ||
          Element.prototype.webkitMatchesSelector;
    }
})();

/**
 * Rather then using classList.forEach, we use a regular for-loop to support IE11.  The same goes for document.querySelectorAll(selector).forEach
 */
// for (let i = 0; i < classList.length; i++) {
//     const className = classList[i];
// }
