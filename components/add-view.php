<style>
    .photo-edit {
        display: flex;
        padding: 15px 0;
    }

    .photo-upload {
        display: flex;
        justify-content: space-around;
    }

    .photo-upload label {
        cursor: pointer;
        width: 130px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .photo-upload label:hover {
        color: #999;
    }

    .photo-upload p {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .photo-edit input {
        display: none;
    }

    .photo-edit .custom-file-upload img {
        height: 40px;
    }

    .photo-edit__canvas {
        position: relative;
        width: 600px;
        height: 600px;
        margin: 25px auto;
    }

    .photo-edit #preview,
    #origin {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 100%;
        max-width: 100%;
    }

    #origin {
        visibility: hidden;
    }

    .photo-carousel {
        display: flex;
        flex-wrap: nowrap;
        overflow: auto;
    }

    .photo-carousel div {
        min-width: 200px;

        /* text-align: center; */
    }

    .photo-filters {
        margin: 15px 0;
    }

    .photo-filters .photo-carousel img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .photo-stickers .photo-carousel img {
        height: 180px;
    }

    /* .carousel-item {
        flex: 0 0 auto;
    } */

    .photo-filters ::-webkit-scrollbar,
    .photo-stickers ::-webkit-scrollbar {
        height: 25px;
    }

    .photo-filters ::-webkit-scrollbar-track,
    .photo-stickers ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .photo-filters ::-webkit-scrollbar-thumb,
    .photo-stickers ::-webkit-scrollbar-thumb {
        background: #888;
    }

    .photo-filters ::-webkit-scrollbar-thumb:hover,
    .photo-stickers ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    canvas,
    video {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 100%;
        max-width: 100%;
        display: none;
    }
</style>
<section class="photo-edit align_footer">
    <div class="container">
        <div class="photo-upload">
            <label class="custom-file-upload">
                <!-- <img src="img/computer.svg" alt="from computer"> -->
                <img src="img/icon/transfer.svg" alt="from computer">
                <p>Upload</p>
                <input id='file-upload' type="file">
            </label>

            <label class="custom-file-upload">
                <!-- <img src="img/camera.svg" alt="from camera"> -->
                <img src="img/icon/photo-camera.svg" alt="from camera">
                <p>Camera</p>
                <input id="startbutton" type="button">
            </label>
        </div>

        <div class="photo-edit__canvas">
            <img id="preview" src="img/preview.png" alt="preview">

            <img id="origin" src="img/preview.png">
            <video id="video"></video>
            <canvas id="canvas"></canvas>
            <form><input type='button' id='snapshot' value="snapshot"></form>
        </div>

        <div class="photo-upload">
            <label class="custom-file-upload">
                <img src="img/icon/shoot.svg" alt="from computer">
                <p>Shoot</p>
                <input id='shoot' type="button">
            </label>
            <label class="custom-file-upload">
                <img src="img/icon/save.svg" alt="from computer">
                <p>Save</p>
                <form method="post" enctype="multipart/form-data">
                    <input id="save" name="src" type="hidden" value="img/preview.png">
                    <input name="save" type="submit" value="Save">
                </form>
            </label>
            <label id="discard" class="custom-file-upload">
                <img src="img/icon/filter.svg" alt="from camera">
                <p>Clear</p>
                <!-- <input id="startbutton" type="button"> -->
            </label>
        </div>

        <div class="photo-filters">
            <div class="photo-carousel">
                <div id="0" class="filter">
                    <img class="carousel-item" src="img/filters/1.jpeg" alt="">
                </div>
                <div id="1" class="filter">
                    <img class="carousel-item" src="img/filters/2.png" alt="">
                </div>
                <div id="2" class="filter">
                    <img class="carousel-item" src="img/filters/3.png" alt="">
                </div>
                <div id="3" class="filter">
                    <img class="carousel-item" src="img/filters/4.png" alt="">
                </div>
                <div id="4" class="filter">
                    <img class="carousel-item" src="img/filters/5.jpeg" alt="">
                </div>
            </div>
        </div>

        <div class="photo-stickers">
            <div id="stick" class="photo-carousel">
                <div id="0" class="sticker">
                    <img class="carousel-item" src="img/stickers/1.png" alt="">
                </div>
                <div id="1" class="sticker">
                    <img class="carousel-item" src="img/stickers/2.png" alt="">
                </div>
                <div id="2" class="sticker">
                    <img class="carousel-item" src="img/stickers/3.png" alt="">
                </div>
                <div id="3" class="sticker">
                    <img class="carousel-item" src="img/stickers/4.png" alt="">
                </div>
                <div id="4" class="sticker">
                    <img class="carousel-item" src="img/stickers/5.png" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function() {
        var width = 900;
        var height = 0;

        var streaming = false;

        var photo = document.getElementById('origin');
        var preview = document.getElementById('preview');
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var startbutton = document.getElementById('startbutton');
        var shoot = document.getElementById('shoot');
        var discard = document.getElementById('discard');
        var save = document.getElementById('save');

        var filter = "none";
        var sticker = null;
        var sticker_width = 100;
        var sticker_height = 100;
        var x = 30;
        var y = 30;

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
            var context = canvas.getContext('2d');
            context.fillStyle = "#AAA";
            context.fillRect(0, 0, canvas.width, canvas.height);

            var data = canvas.toDataURL('image/png');
            photo.setAttribute('src', data);
        }

        function takepicture() {
            var context = canvas.getContext('2d');
            if (width && height) {
                canvas.width = width;
                canvas.height = height;
                context.drawImage(video, 0, 0, width, height);

                var data = canvas.toDataURL('image/png');
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

        var btn = document.getElementById('startbutton');
        var btn2 = document.getElementById('file-upload');
        btn.addEventListener('click', startup, false);
        btn2.addEventListener('click', vidOff, false);

        var filters = ['blur(5px)',
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
            var context = canvas.getContext('2d');
            context.canvas.width = photo.width;
            context.canvas.height = photo.height;

            context.filter = filter;
            context.drawImage(photo, 0, 0, photo.width, photo.height);
            context.filter = "none";
            if (sticker)
                context.drawImage(sticker, x, y, sticker_width, sticker_height);

            var data = canvas.toDataURL('image/png');
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
                var reader = new FileReader();

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
</script>