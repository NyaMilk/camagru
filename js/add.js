(function () {
    let width = 900;
    let height = 0;

    let streaming = false;

    let photo = document.getElementById('origin');
    let preview = document.getElementById('preview');
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let shoot = document.getElementById('shoot');
    let discard = document.getElementById('discard');
    let save = document.getElementById('save');
    let save_btn = document.getElementById('save_btn');

    let snapchat = {
        filter: "none",
        isClicked: false,
        stickers: []
    };
    let sticker_width = 100;
    let sticker_height = 100;
    let start_pos_x = 100;
    let start_pos_y = 100;

    function startup() {
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        })
            .then(function (stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function (err) {
                console.log("An error occurred: " + err);
            });

        video.addEventListener('canplay', function (ev) {
            if (!streaming) {
                height = video.videoHeight / (video.videoWidth / width);
                if (isNaN(height)) {
                    height = width / (4 / 3);
                }
                video.setAttribute('width', width);
                video.setAttribute('height', height);
                video.style.display = "block";
                canvas.setAttribute('width', width);
                canvas.setAttribute('height', height);
                preview.style.display = "none";
                streaming = true;
            }
        }, false);

        shoot.addEventListener('click', function (ev) {
            takepicture();
            ev.preventDefault();
        }, false);
        shoot.removeAttribute('disabled');
        clearphoto();
    }

    function clearphoto() {
        let context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);

        let data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
    }

    function takepicture() {
        let context = canvas.getContext('2d');
        if (width && height) {
            canvas.width = width;
            canvas.height = height;
            context.drawImage(video, 0, 0, width, height);

            let data = canvas.toDataURL('image/png');
            video.style.display = "none";
            photo.setAttribute('src', data);
            preview.style.display = "block";
            preview.setAttribute('src', data);
            streaming = false;
            save.value = preview.src;
            save_btn.removeAttribute("disabled");
            shoot.setAttribute('disabled', 'disabled');
        } else {
            clearphoto();
        }
    }

    function vidOff() {
        if (streaming) {
            const stream = video.srcObject;
            const tracks = stream.getTracks();

            tracks.forEach(function (track) {
                track.stop();
            });

            video.srcObject = null;
            streaming = false;
        }
    }

    let btn = document.getElementById('startbutton');
    let btn2 = document.getElementById('file-upload');
    btn.addEventListener('click', startup, false);
    btn2.addEventListener('click', vidOff, false);

    let filters = ['blur(5px)',
        'grayscale(100%)',
        'sepia(60%)',
        'invert(100%)',
        'brightness(200%)'
    ];

    document.querySelectorAll('.filter').forEach(item => {
        item.addEventListener('click', function () {
            snapchat['filter'] = filters[this.id];
            render();
        }, false);
    });

    document.querySelectorAll('.sticker').forEach(item => {
        item.addEventListener('click', function () {
            console.log(snapchat['stickers']);
            snapchat['stickers'].push({
                elem: document.getElementById('stick').getElementsByClassName('sticker')[this.id].getElementsByTagName('img')[0],
                x: start_pos_x,
                y: start_pos_y,
                isActive: false
            }
            );
            render();
        }, false);
    })

    function render() {
        let context = canvas.getContext('2d');
        context.canvas.width = photo.width;
        context.canvas.height = photo.height;

        context.filter = snapchat['filter'];
        context.drawImage(photo, 0, 0, photo.width, photo.height);
        context.filter = "none";
        if (snapchat['stickers']) {
            for (let el of snapchat['stickers']) {
                if (el.isActive)
                    context.fillRect(el.x, el.y, sticker_width, sticker_height);
                context.drawImage(el['elem'], el.x, el.y, sticker_width, sticker_height);
            }
        }
        let data = canvas.toDataURL('image/png');
        preview.setAttribute('src', data);
    }

    discard.addEventListener('click', function () {
        filter = "none";
        snapchat['stickers'] = [];
        render();
    }, false);

    function inArrayStickers(newX, newY) {
        for (let i = snapchat['stickers'].length - 1; i > -1; i--) {
            let el = snapchat['stickers'][i];
            if (newX >= el.x && newX <= el.x + sticker_width && newY >= el.y && newY <= el.y + sticker_height) {
                el.isActive = true;
                return true;
            }
        }
        return false;
    }

    document.getElementById('preview').addEventListener('click', function (e) {
        if (!snapchat['isClicked'] && inArrayStickers(e.offsetX, e.offsetY)) {
            snapchat['isClicked'] = true;
        }
        else {
            for (let el of snapchat['stickers']) {
                if (el.isActive) {
                    el.x = e.offsetX - sticker_width / 2;
                    el.y = e.offsetY - sticker_height / 2;
                    el.isActive = false;
                }
            }
            snapchat['isClicked'] = false;
        }
        render();
    }, false);

    document.getElementById('file-upload').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            if (!this.files[0].type.match("image*")) {
                alert("Wrong type file");
                return;
            }
            let reader = new FileReader();
            save_btn.removeAttribute("disabled");
            reader.onload = function (e) {
                filter = "none";
                sticker = null;
                document.getElementById('preview')
                    .setAttribute('src', e.target.result);
                document.getElementById('origin')
                    .setAttribute('src', e.target.result);
                save.value = preview.src;
            };
            reader.readAsDataURL(this.files[0]);
        }
    }, false);

    save.addEventListener("click", function () {
        let src = preview.src;
        const request = new XMLHttpRequest();
        const url = "add.php";
        const param = "src=" + src;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        request.addEventListener("readystatechange", () => {

            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
            }
        });
        request.send(param);
    }, false);
})();