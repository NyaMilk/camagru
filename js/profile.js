"use strict";

(function () {
    let page = window.location.href.split('&');
    console.log(page[2]);

    if (page[2] == 'posts') {
        document.querySelector('.profile-nav a').classList.add('profile-nav-active');
    } else {
        document.querySelector('.profile-nav a:last-child').classList.add('profile-nav-active');
    };
})();
