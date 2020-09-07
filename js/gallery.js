(function() {
    let item = document.forms['gallery_sort'].elements['sort'];
    for (let i = 0; i < item.length; i++) {
        item[i].onclick = function() {
            window.location.href = "gallery.php?sort=" + this.value + "&page=1";
        };
    }
})();