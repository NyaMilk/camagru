(function () {
    let text = document.getElementById("text");
    let btn = document.querySelector(".btn-save");
    let like = document.getElementsByName("likes")[0];
    let imgLike = document.querySelector(".photo-like");
    let counter_likes = document.getElementById("likes");
    btn.disabled = true;
    let modalID;

    function getComments() {
        let xhttp;
        let url = "ajax/comments.php?";

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector(".page-img__comments-list").innerHTML = this.responseText;
                document.querySelectorAll(".btn-confirm-del").forEach(item => item.addEventListener("click", function () {
                    let param = "com_id=" + this.id;
                    let xhttp;

                    xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "ajax/comments.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log("ok");
                            getComments();
                        }
                    };

                    xhttp.send(param);
                }, false));

                document.querySelectorAll(".modal-link").forEach(item => item.addEventListener("click", function () {
                    modalID = this.href.slice(this.href.search("openModal") + 9);
                    document.getElementById("openModal" + modalID).style.display = "block";
                }, false));
                document.querySelectorAll(".btn-close").forEach(item => item.addEventListener("click", function () {
                    document.getElementById("openModal" + modalID).style.display = "none";
                }, false));
            };
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    document.querySelector(".btn-save").addEventListener("click", function () {
        let new_text = text.value.trim();
        if (new_text.length == 0) {
            text.value = "";
            return;
        }
        let param = "comment=" + new_text;
        let xhttp;

        xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax/comments.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                getComments();
                text.value = "";
                btn.disabled = true;
                btn.style.background = "#E6E7ED";
                btn.style.borderColor = "#E6E7ED";
                btn.style.cursor = "auto";
            }
        };

        xhttp.send(param);
    }, false);

    text.addEventListener("keyup", function (event) {
        if (this.value.length > 80 || this.value.length == 0) {
            btn.disabled = true;
            btn.style.background = "#E6E7ED";
            btn.style.borderColor = "#E6E7ED";
            btn.style.cursor = "auto";
        } else {
            btn.disabled = false;
            btn.style.background = "#49D1CA";
            btn.style.borderColor = "#49D1CA";
            btn.style.cursor = "pointer";
        }
    });

    function changeLike() {
        let xhttp;
        let url = "ajax/set-like.php?";

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                json = JSON.parse(this.responseText);
                if (json.isLiked)
                    imgLike.src = "img/icon/valentines-heart1.svg";
                else
                    imgLike.src = "img/icon/valentines-heart.svg";
                counter_likes.innerHTML = json.likes;
            };
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function getLikes() {
        let xhttp;
        let url = "ajax/get-likes.php?";

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                json = JSON.parse(this.responseText);
                if (json.isLiked)
                    imgLike.src = "img/icon/valentines-heart1.svg";
                else
                    imgLike.src = "img/icon/valentines-heart.svg";
                counter_likes.innerHTML = json.likes;
            };
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    window.addEventListener("load", getComments, false);
    window.addEventListener("load", getLikes, false);
    like.addEventListener("click", changeLike, false);
})();