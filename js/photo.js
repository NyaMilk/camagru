(function() {
    let text = document.getElementById("text");
    let btn = document.querySelector(".btn-blue");
    let modalID;

    function getComments() {
        let xhttp;
        let url = "ajax/comments.php?";

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector(".page-img__comments-list").innerHTML = this.responseText;
                document.querySelectorAll(".btn-confirm-del").forEach(item => item.addEventListener("click", function() {
                    let param = "commentID=" + this.id;
                    let xhttp;

                    xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "ajax/comments.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log("ok");
                            getComments();
                        }
                    };

                    xhttp.send(param);
                }, false));

                document.querySelectorAll(".modal-link").forEach(item => item.addEventListener("click", function() {
                    modalID = this.href.slice(this.href.search("openModal") + 9);
                    document.getElementById("openModal" + modalID).style.display = "block";
                }, false));
                document.querySelectorAll(".btn-close").forEach(item => item.addEventListener("click", function() {
                    document.getElementById("openModal" + modalID).style.display = "none";
                }, false));
            };
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    document.querySelector(".btn-save").addEventListener("click", function() {
        let new_text = text.value.trim();
        if (new_text.length == 0)
        {
            text.value = "";
            return;
        }
        let param = "comment=" + new_text;
        let xhttp;

        xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax/comments.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                getComments();
                text.value = "";
            }
        };

        xhttp.send(param);
    }, false);

    window.addEventListener("load", getComments, false);

    
    text.addEventListener("keyup", function(event) {
        if (this.value.length > 80) {
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
})();