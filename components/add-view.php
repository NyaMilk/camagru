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

<script src="js/add.js"></script>