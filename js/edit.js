(function() {
    let new_avatar = document.getElementById('new-avatar');

    new_avatar.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            if (!this.files[0].type.match("image*")) {
                alert("Wrong type file");
                return;
            }
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('current-avatar').setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        }
    }, false);
})();