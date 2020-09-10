"use strict";

(function () {
    let item = document.forms['gallery_sort'].elements['sort'];
    let page = window.location.href.split(/(?:&|=)+/);

    for (let i = 0; i < item.length; i++) {
        item[i].onclick = function () {
            window.location.href = "gallery.php?sort=" + this.value + "&page=1";
        };
    }

    if (page[1] == 'all') {
        document.querySelector('.gallery__form label').classList.add('gallery__form-active');
    }
    else if (page[1] == 'popular') {
        document.querySelector('.gallery__form label:nth-child(2)').classList.add('gallery__form-active');
    }
    else {
        document.querySelector('.gallery__form label:last-child').classList.add('gallery__form-active');
    }
})();