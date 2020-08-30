(function() {
    let width = 900;
    let height = 0;

    let streaming = false;

    let photo = document.getElementById('origin');
    let preview = document.getElementById('preview');
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let start_button = document.getElementById('startbutton'); // where?
    let shoot = document.getElementById('shoot');
    let discard = document.getElementById('discard');
    let save = document.getElementById('save');

    let filter = "none";
    let sticker = null;
    let sticker_width = 100;
    let sticker_height = 100;
    let x = 30;
    let y = 30;

    function startup() {
        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });

        video.addEventListener('canplay', function(ev) {
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

        shoot.addEventListener('click', function(ev) {
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
            shoot.setAttribute('disabled', 'disabled');
        } else {
            clearphoto();
        }
    }

    function vidOff() {
        if (streaming) {
            const stream = video.srcObject;
            const tracks = stream.getTracks();

            tracks.forEach(function(track) {
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
        item.addEventListener('click', function() {
            filter = filters[this.id];
            render();
        }, false);
    });

    document.querySelectorAll('.sticker').forEach(item => {
        item.addEventListener('click', function() {
            sticker = document.getElementById('stick').getElementsByClassName('sticker')[this.id].getElementsByTagName('img')[0];
            render();
        }, false);
    })

    function render() {
        let context = canvas.getContext('2d');
        context.canvas.width = photo.width;
        context.canvas.height = photo.height;

        context.filter = filter;
        context.drawImage(photo, 0, 0, photo.width, photo.height);
        context.filter = "none";
        if (sticker)
            context.drawImage(sticker, x, y, sticker_width, sticker_height);

        let data = canvas.toDataURL('image/png');
        preview.setAttribute('src', data);
        save.value = preview.src;
    }

    function moveSticker(e) {
        x = e.offsetX - sticker_width / 2;
        y = e.offsetY - sticker_height / 2;
        render();
    }

    discard.addEventListener('click', function() {
        filter = "none";
        sticker = null;
        render();
    }, false);

    function readURL() {
        if (this.files && this.files[0]) {
            if (!this.files[0].type.match("image*")) {
                alert("Wrong type file");
                return;
            }
            let reader = new FileReader();

            reader.onload = function(e) {
                filter = "none";
                sticker = null;
                document.getElementById('preview')
                    .setAttribute('src', e.target.result);
                document.getElementById('origin')
                    .setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        }
    }

    document.getElementById('preview').addEventListener('click', moveSticker, false);
    document.getElementById('file-upload').addEventListener('change', readURL, false);
})();